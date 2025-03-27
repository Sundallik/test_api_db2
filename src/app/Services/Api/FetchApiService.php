<?php

namespace App\Services\Api;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\ApiToken;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
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

        $model::where('account_id', $account->id)->delete();

        do {
            self::checkRetries($retryCount);
            $client = self::createClient($apiService);

            try {
                $queryParams['page'] = $page;
                $response = $client->get($endpoint, ['query' => $queryParams]);

                $retryCount = 0;
                $currentDelay = self::initialDelay;

                $data = self::fetchResponse($response, $account);

                if (!empty($data['data'])) {
                    $model::insert($data['data']);
//                    $model::upsert(
//                        $data['data'],
//                        $model::getUniqueColumns(),
//                        $model::getUpdatableColumns()
//                    );
                }

                self::printLog($model, $data, $page);

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

    private static function printLog(string $model, array $data, int $page)
    {
        $name = class_basename($model);
        $pageCount = $data['meta']['last_page'];
        print_r("$name: page $page of $pageCount imported successfully" . PHP_EOL);
    }
}


