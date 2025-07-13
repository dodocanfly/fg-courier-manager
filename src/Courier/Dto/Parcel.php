<?php

namespace App\Courier\Dto;

readonly class Parcel
{
    public function __construct(
        public string $courier,
        public string $service,
        public float $length,
        public float $width,
        public float $height,
        public float $weight
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            courier: $data['courier'],
            service: $data['service'],
            length: $data['length'],
            width: $data['width'],
            height: $data['height'],
            weight: $data['weight']
        );
    }

    public function toArray(): array
    {
        return [
            'courier' => $this->courier,
            'service' => $this->service,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'weight' => $this->weight,
        ];
    }
}
