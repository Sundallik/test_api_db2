<?php

namespace App\Services;

use App\Models\Account;
use App\Models\ApiService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ResponseInterface;

class FetchApiService
{
    public static function fetchData(
        ApiService $apiService,
        Account $account,
        string $endpoint,
        array  $queryParams,
        string $model,
        int $maxRetries = 10,
        int $initialDelay = 10,
    )
    {
        $page = 1;
        $retryCount = 0;
        $currentDelay = $initialDelay;
        $model = app()->make($model);
        $uniqueColumns = $model::getUniqueColumns();
        $client = self::createClient($apiService);

        do {
            self::checkRetries($retryCount, $maxRetries);

            try {
                $queryParams['page'] = $page;
                $response = $client->get($endpoint, [
                    'query' => $queryParams,
                    'http_errors' => true,
                    'headers' => ['Accept' => 'application/json'],
                ]);

                $retryCount = 0;
                $currentDelay = $initialDelay;

                $data = self::fetchResponse($response, $account);
                $response = null;

                $latestRecords = collect($data['data'])
                    ->map(function ($item) {
                        $item['date'] = Carbon::parse($item['date'])->toDateTimeString();
                        return $item;
                    })
                    ->sortByDesc('date')
                    ->unique(function ($item) use ($uniqueColumns) {
                        $result = '';
                        foreach ($uniqueColumns as $column) {
                            $result .= $item[$column] ?? 'null';
                        }
                        return $result;
                    })
                    ->values()
                    ->all();

                if (!empty($latestRecords)) {
                    $model::upsert(
                        $latestRecords,
                        $uniqueColumns,
                        $model::getUpdatableColumns()
                    );
                }

                self::printLog($model, $data, $page, $account, $apiService);
                $page++;
                gc_collect_cycles();
            } catch (GuzzleException $e) {
                switch($e->getCode()) {
                    case 400:
                        print_r("Invalid parameters" . PHP_EOL);
                        return;
                    case 429:
                        $retryCount++;
                        print_r("Too many requests (429), retry $retryCount: $currentDelay sec" . PHP_EOL);
                        sleep($currentDelay);
                        if ($currentDelay <= 1000) $currentDelay *= 2;
                        break;
                    default:
                        self::fetchErrors($e);
                }
            }  finally {
                if (isset($response)) {
                    $response->getBody()->close();
                    unset($response);
                }
                unset($data['data'], $latestRecords);
                gc_collect_cycles();
            }
        } while (isset($data['links']['next']));
    }

    private static function createClient(ApiService $apiService): Client
    {
        return new Client([
            'base_uri' => $apiService->base_url,
        ]);
    }

    private static function fetchErrors(GuzzleException $e)
    {
        $response = $e->getResponse();

        $data = json_decode($response->getBody(), true);

        $errorMessages = [];
        foreach ($data['errors'] ?? $data as $field => $messages) {
            $errorMessages[] = "$field: " . (is_array($messages)
                    ? implode(', ', $messages)
                    : $messages);
        }

        $errorString = implode(PHP_EOL, $errorMessages);
        print_r("Error: {$e->getCode()}" . PHP_EOL . $errorString . PHP_EOL);
    }

    private static function checkRetries(int $retryCount, int $maxRetries)
    {
        if ($retryCount >= $maxRetries) throw new Exception('Max retry limit reached');
    }

    private static function fetchResponse(ResponseInterface $response, Account $account): array
    {
        $data = json_decode($response->getBody()->getContents(), true);

        $data['data'] = array_map(function($item) use ($account) {
            $item['account_id'] = $account->id;
            return $item;
        }, $data['data']);

        return $data;
    }

    private static function printLog(Model $model, array $data, int $page, Account $account, ApiService $apiService)
    {
        $name = class_basename($model);
        $pageCount = $data['meta']['last_page'];

        print_r("$name: page $page of $pageCount imported successfully (account: $account->name, api: $apiService->name)" . " " . round(memory_get_usage() / 1024 / 1024, 2) . ' MB' . PHP_EOL);
    }
}


