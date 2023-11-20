<?php

namespace App\Services;

class GetData
{

    public function __construct(public string $url)
    {
    }

    public function __invoke(string $securityType): array
    {
        return [
            [
                'symbol' => 'APPL',
                'price' => 188.97,
                'last_price_datetime' => '2023-10-30T17:31:18-04:00'
            ],
            [
                'symbol' => 'TSLA',
                'price' => 244.42,
                'last_price_datetime' => '2023-10-30T17:32:11-04:00'
            ]
        ];
    }
}
