<?php

namespace App\Courier;

use App\Courier\Dto\PickupResult;
use App\Courier\Dto\ShipmentData;
use App\Courier\Dto\ShipmentResult;

interface CourierInterface
{
    public function createShipment(ShipmentData $shipmentData): ShipmentResult;
    public function orderPickup(string $shipmentId): PickupResult;
}
