<?php

namespace App\Services\Api;

use App\Models\Account;
use App\Models\ApiService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class FetchApiService
{
    const maxRetries = 10;
    const initialDelay = 10;

    public static function fetchData(
        ApiService $apiService,
        Account $account,
        string $endpoint,
        array  $queryParams,
        string $model
    )
    {
        $page = 1;
        $retryCount = 0;
        $currentDelay = self::initialDelay;
        $uniqueColumn = $model::getUniqueColumn();

        do {
            self::checkRetries($retryCount);
            $client = self::createClient($apiService);

            try {
                $queryParams['page'] = $page;
                $response = $client->get($endpoint, ['query' => $queryParams]);

                $retryCount = 0;
                $currentDelay = self::initialDelay;

                $data = self::fetchResponse($response, $account);

                $groupedLatest = collect($data['data'])
                    ->groupBy($uniqueColumn)
                    ->map(function ($group) {
                        return $group->map(function ($item) {
                            $item['date'] = Carbon::parse($item['date']);
                            return $item;
                        })
                        ->sortBy('date')->last();
                    });

                $existingRecords = $model::whereIn($uniqueColumn, $groupedLatest->pluck($uniqueColumn))
                    ->where('account_id', $account->id)->get()->keyBy($uniqueColumn);

                $recordsToUpsert = $groupedLatest->filter(function ($item) use ($existingRecords, $uniqueColumn) {
                    $key = $item[$uniqueColumn];
                    if (!$existingRecords->has($key)) return true;
                    return $item['date']->gte($existingRecords->get($key)->date);
                })->toArray();

                if (!empty($recordsToUpsert)) {
                    $model::upsert(
                        $recordsToUpsert,
                        [$uniqueColumn, 'account_id'],
                        $model::getUpdatableColumns()
                    );
                };

                self::printLog($model, $data, $page, $account, $apiService);
                $page++;
            } catch (GuzzleException $e) {
                if ($e->getCode() === 429) {
                    sleep($currentDelay);
                    $retryCount++;
                    print_r("Too many requests (429), retry $retryCount: $currentDelay sec" . PHP_EOL);
                    $currentDelay *= 2;
                } else {
                    self::fetchErrors($e);
                }
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
        print_r("Error {$e->getCode()}" . PHP_EOL . $errorString . PHP_EOL);
    }

    private static function checkRetries(int $retryCount)
    {
        if ($retryCount >= self::maxRetries) throw new Exception('Max retry limit reached');
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

    private static function printLog(string $model, array $data, int $page, Account $account, ApiService $apiService)
    {
        $name = class_basename($model);
        $pageCount = $data['meta']['last_page'];
        print_r("$name: page $page of $pageCount imported successfully ($account->name, $apiService->name)" . PHP_EOL);
    }
}


