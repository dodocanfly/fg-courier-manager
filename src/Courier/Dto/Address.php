<?php

namespace App\Courier\Dto;

readonly class Address
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $companyName,
        public string $email,
        public string $phone,
        public string $street,
        public string $buildingNumber,
        public string $apartmentNumber,
        public string $city,
        public string $postalCode,
        public string $state,
        public string $countryCode,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            companyName: $data['companyName'] ?? '',
            email: $data['email'],
            phone: $data['phone'],
            street: $data['street'],
            buildingNumber: $data['buildingNumber'],
            apartmentNumber: $data['apartmentNumber'] ?? '',
            city: $data['city'],
            postalCode: $data['postalCode'],
            state: $data['state'] ?? '',
            countryCode: $data['countryCode']
        );
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'companyName' => $this->companyName,
            'email' => $this->email,
            'phone' => $this->phone,
            'street' => $this->street,
            'buildingNumber' => $this->buildingNumber,
            'apartmentNumber' => $this->apartmentNumber,
            'city' => $this->city,
            'postalCode' => $this->postalCode,
            'state' => $this->state,
            'countryCode' => $this->countryCode,
        ];
    }
}
