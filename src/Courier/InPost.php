<?php

namespace App\Courier;

use App\Courier\Dto\PickupResult;
use App\Courier\Dto\ShipmentData;
use App\Courier\Dto\ShipmentResult;

class InPost extends Courier
{
    protected const string URL_PRODUCTION = 'https://api-shipx-pl.easypack24.net/v1';
    protected const string URL_SANDBOX = 'https://sandbox-api-shipx-pl.easypack24.net/v1';

    public function validate(): void
    {
        if (empty($this->config['api_token'])) {
            throw new \InvalidArgumentException('API token is required for InPost');
        }

        if (empty($this->config['organization_id'])) {
            throw new \InvalidArgumentException('Organization ID is required for InPost');
        }
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->config['api_token'],
            'Content-Type' => 'application/json'
        ];
    }

    public function createShipment(ShipmentData $shipmentData): ShipmentResult
    {
        $url = $this->getApiUrl() . '/organizations/' . $this->config['organization_id'] . '/shipments';
        $payload = $this->prepareShipmentPayload($shipmentData);

        $this->logger->info('Wysyłanie żądania utworzenia przesyłki InPost', [
            'url' => $url,
            'payload' => $payload
        ]);

        $response = $this->httpClient->post($url, $payload, $this->getHeaders());
        $responseData = $response->getBodyAsArray();

        if ($response->isSuccess()) {
            $this->logger->info('Przesyłka InPost utworzona pomyślnie', $responseData);

            return ShipmentResult::success(
                $responseData['id'],
                $responseData['tracking_number'],
                $responseData,
            );
        }

        $errorMessage = $responseData['message'] ?? 'Nieznany błąd przy tworzeniu przesyłki';
        $this->logger->error('Błąd przy tworzeniu przesyłki InPost', [
            'status_code' => $response->getStatusCode(),
            'error' => $errorMessage,
            'response' => $responseData
        ]);

        return ShipmentResult::failure($errorMessage, $responseData);
    }

    public function orderPickup(string $shipmentId): PickupResult
    {
        $url = $this->getApiUrl() . '/organizations/' . $this->config['organization_id'] . '/dispatch_orders';
        $payload = $this->preparePickupPayload($shipmentId);

        $this->logger->info('Wysyłanie żądania zamówienia kuriera InPost', [
            'url' => $url,
            'payload' => $payload
        ]);

        $response = $this->httpClient->post($url, $payload, $this->getHeaders());
        $responseData = $response->getBodyAsArray();

        if ($response->isSuccess()) {
            $this->logger->info('Kurier InPost zamówiony pomyślnie', $responseData);

            return PickupResult::success(
                $responseData['id'],
                $responseData['pickup_date'],
                $responseData,
            );
        }

        $errorMessage = $responseData['message'] ?? 'Nieznany błąd przy zamawianiu kuriera';
        $this->logger->error('Błąd przy zamawianiu kuriera InPost', [
            'status_code' => $response->getStatusCode(),
            'error' => $errorMessage,
            'response' => $responseData,
        ]);

        return PickupResult::failure($errorMessage, $responseData);
    }

    private function prepareShipmentPayload(ShipmentData $shipmentData): array
    {
        return [
            'receiver' => $shipmentData->receiver->toArray(),
            'sender' => $shipmentData->sender->toArray(),
            'parcels' => [
                [
                    'dimensions' => [
                        'height' => $shipmentData->parcel->height,
                        'length' => $shipmentData->parcel->length,
                        'width' => $shipmentData->parcel->width,
                    ],
                    'weight' => [
                        'amount' => $shipmentData->parcel->weight,
                    ],
                ]
            ],
            'service' => $shipmentData->parcel->service,
        ];
    }

    private function preparePickupPayload(string $shipmentId): array
    {
        // pickup_date i pickup_time_* zapisywałbym w konfiguracji konta klienta i pobierał z $this->>config
        return [
            'shipments' => [$shipmentId],
            'pickup_date' => date('Y-m-d', strtotime('+1 day')),
            'pickup_time_from' => '09:00',
            'pickup_time_to' => '17:00',
        ];
    }
}
