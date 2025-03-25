<?php

namespace App\Services\Api;
use App\Models\Order;

class OrdersService extends ApiService
{
    public function getOrders($dateFrom, $dateTo, $accountId)
    {
        $this->fetchData(
            '/api/orders',
            ['dateFrom' => $dateFrom, 'dateTo' => $dateTo],
            Order::class,
            $accountId
        );
    }
}
