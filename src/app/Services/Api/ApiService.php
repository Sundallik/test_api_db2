<?php

namespace App\Services\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\RequestException;

abstract class ApiService
{
    private $client;
    private $key;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('api.base_uri'),
        ]);
        $this->key = config('api.api_key');
    }

    protected function fetchData(
        string $endpoint,
        array  $queryParams,
        string $model,
        int    $account_id,
        int    $maxRetries = 10,
        int    $initialDelay = 2
    )
    {
        $page = 1;
        $retryCount = 0;
        $currentDelay = $initialDelay;

        do {
            if ($retryCount >= $maxRetries) throw new Exception('Max retry limit reached');

            try {
                $response = $this->client->get($endpoint, [
                    'query' => array_merge($queryParams, [
                        'key' => $this->key,
                        'page' => $page,
                    ]),
                ]);

                $retryCount = 0;
                $currentDelay = $initialDelay;

                $data = json_decode($response->getBody()->getContents(), true);

                $data['data'] = array_map(function($item) use ($account_id) {
                    $item['account_id'] = $account_id;
                    return $item;
                }, $data['data']);

                if (!empty($data['data'])) {
                    $model::insertOrIgnore($data['data']);
                }

                $name = class_basename($model);
                $pageCount = $data['meta']['last_page'];
                print_r("$name: page $page of $pageCount imported successfully" . PHP_EOL);

                $page++;
            } catch (GuzzleException $e) {
                if ($e->getCode() === 429) {
                    sleep($currentDelay);
                    $retryCount++;
                    print_r("Too many requests ({$e->getCode()}), retry $retryCount: $currentDelay sec" . PHP_EOL);
                    $currentDelay *= 2;
                } else {
                    $this->fetchErrors($e);
                }
            }
        } while (isset($data['links']['next']));
    }

    private function fetchErrors(GuzzleException $e)
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
}


