<?php

namespace App\Courier\Dto;

class ShipmentData
{
    public function __construct(
        public Address $sender,
        public Address $receiver,
        public Parcel $parcel,
    ) {
    }
}
