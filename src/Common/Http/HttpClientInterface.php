<?php

namespace App\Common\Http;

interface HttpClientInterface
{
    public function get(string $url, array $headers = []): HttpResponse;
    public function post(string $url, array $data, array $headers = []): HttpResponse;
}
