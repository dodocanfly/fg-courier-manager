<?php

namespace App\Courier\Dto;

class PickupResult
{
    public function __construct(
        private bool $success,
        private ?string $pickupId = null,
        private ?string $pickupDate = null,
        private ?string $errorMessage = null,
        private array $additionalData = []
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getPickupId(): ?string
    {
        return $this->pickupId;
    }

    public function getPickupDate(): ?string
    {
        return $this->pickupDate;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }

    public static function success(string $pickupId, string $pickupDate, array $additionalData = []): self
    {
        return new self(true, $pickupId, $pickupDate, null, $additionalData);
    }

    public static function failure(string $errorMessage, array $additionalData = []): self
    {
        return new self(false, null, null, $errorMessage, $additionalData);
    }
}
