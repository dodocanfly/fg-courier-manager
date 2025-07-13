<?php

namespace App\Courier\Dto;

readonly class ShipmentResult
{
    public function __construct(
        private bool $success,
        private ?string $shipmentId = null,
        private ?string $trackingNumber = null,
        private ?string $errorMessage = null,
        private array $additionalData = [],
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getShipmentId(): ?string
    {
        return $this->shipmentId;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    public static function success(string $shipmentId, string $trackingNumber, array $additionalData = []): self
    {
        return new self(true, $shipmentId, $trackingNumber, null, $additionalData);
    }

    public static function failure(string $errorMessage, array $additionalData = []): self
    {
        return new self(false, null, null, $errorMessage, $additionalData);
    }
}
