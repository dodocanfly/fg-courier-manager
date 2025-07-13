<?php

namespace App\Courier;

use App\Common\Http\HttpClientInterface;
use App\Common\Logger\LoggerInterface;

readonly class CourierFactory
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
    ) {
    }

    public function createCourier(string $courierType, array $config): CourierInterface
    {
        return match ($courierType) {
            'inpost' => new InPost($config, $this->httpClient, $this->logger),

            default => throw new \InvalidArgumentException("Nieobsługiwany typ kuriera: {$courierType}"),
        };
    }
}
