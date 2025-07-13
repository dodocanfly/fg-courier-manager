<?php

namespace App\Common\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

class GuzzleHttpClient implements HttpClientInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function get(string $url, array $headers = []): HttpResponse
    {
        try {
            $response = $this->client->get($url, [
                RequestOptions::HEADERS => $headers
            ]);

            return new HttpResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $response->getBody()->getContents()
            );
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    public function post(string $url, array $data, array $headers = []): HttpResponse
    {
        try {
            $response = $this->client->post($url, [
                RequestOptions::HEADERS => $headers,
                RequestOptions::JSON => $data
            ]);

            return new HttpResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $response->getBody()->getContents()
            );
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    private function handleException(RequestException $e): HttpResponse
    {
        $response = $e->getResponse();

        if ($response) {
            return new HttpResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $response->getBody()->getContents()
            );
        }

        return new HttpResponse(
            500,
            [],
            json_encode(['error' => $e->getMessage()])
        );
    }
}
