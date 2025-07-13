<?php

use App\Common\Http\GuzzleHttpClient;
use App\Common\Logger\FileLogger;
use App\Courier\CourierFactory;
use App\Courier\Dto\Address;
use App\Courier\Dto\Parcel;
use App\Courier\Dto\ShipmentData;

require_once 'vendor/autoload.php';

// Dane przykładowego konta InPost - aby umożliwić wysyłanie przesyłek typu inpost_courier_standard, należy podać dane
// konta (api_token, organization_id), które obsługuje takie przesyłki. W przeciwnym razie otrzymamy komunikat o błędzie.
$inpostConfig = [
    'api_token' => 'inpost-api-token',
    'organization_id' => 123,
    'sandbox' => true,
];


// Przykładowe dane nadawcy, odbiorcy i paczki, np. pobrane z bazy danych
$senderData = [
    'firstName' => 'Jan',
    'lastName' => 'Kowalski',
    'companyName' => 'Firma Testowa',
    'email' => 'kontakt@testowa.pl',
    'phone' => '+48123456789',
    'street' => 'ul. Testowa',
    'buildingNumber' => '1',
    'city' => 'Warszawa',
    'postalCode' => '00-001',
    'countryCode' => 'PL',
];

$receiverData = [
    'firstName' => 'Anna',
    'lastName' => 'Nowak',
    'email' => 'anna@nowak.pl',
    'phone' => '+48987654321',
    'street' => 'ul. Przykładowa',
    'buildingNumber' => '5',
    'apartmentNumber' => '10',
    'city' => 'Kraków',
    'postalCode' => '30-001',
    'countryCode' => 'PL',
];

$parcelData = [
    'courier' => 'inpost',
    'service' => 'inpost_courier_standard',
    'length' => 30.0,
    'width' => 25.0,
    'height' => 15.0,
    'weight' => 2.5,
];

$shipmentData = new ShipmentData(
    Address::fromArray($senderData),
    Address::fromArray($receiverData),
    Parcel::fromArray($parcelData),
);


$httpClient = new GuzzleHttpClient(); // Można użyć dowolnego klienta HTTP implementującego interfejs the HttpClientInterface
$logger = new FileLogger('logs/shipment.log'); // Aby wyświetlić komunikaty w konsoli należy użyć ConsoleLogger

// Tworzymy fabrykę kurierów, która będzie odpowiedzialna za tworzenie instancji kurierów na podstawie typu kuriera
$courierFactory = new CourierFactory($httpClient, $logger);

// Tworzymy instancję kuriera na podstawie typu kuriera podanego w danych paczki oraz konfiguracji użytkownika z bazy
// W prosty sposób możemy rozszerzyć tę fabrykę o kolejne kurierów, np. DPD, DHL, FedEx itp.
$courier = $courierFactory->createCourier($parcelData['courier'], $inpostConfig);

// Tworzenie przesyłki
$shipmentResult = $courier->createShipment($shipmentData);

// Jeśli przesyłka została utworzona pomyślnie, zamawiamy kuriera
if ($shipmentResult->isSuccess()) {
    $pickupResult = $courier->orderPickup($shipmentResult->getShipmentId());
}
