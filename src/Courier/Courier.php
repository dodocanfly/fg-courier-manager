<?php

namespace App\Courier;

use App\Common\Http\HttpClientInterface;
use App\Common\Logger\LoggerInterface;

abstract class Courier implements CourierInterface
{
    protected const string URL_PRODUCTION = '';
    protected const string URL_SANDBOX = '';

    public function __construct(
        protected array $config,
        protected HttpClientInterface $httpClient,
        protected LoggerInterface $logger,
    ) {
        $this->validate();
    }

    abstract public function validate(): void;

    abstract public function getHeaders(): array;

    protected function getApiUrl(): string
    {
        return $this->config['sandbox'] ?? false ? static::URL_SANDBOX : static::URL_PRODUCTION;
    }
}
