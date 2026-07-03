<?php

namespace App\DTOs;

use App\Custom\Cast;
use App\Custom\Mask;

final class AddressDTO
{
    public function __construct(
        public int $city,
        public string $city_desc,
        public ?string $complement,
        public string $line1,
        public ?string $line2,
        public ?string $number,
        public ?string $zip_code,
    ) {}

    public static function fromArray(?array $data): ?self {
        if (empty($data['city_desc']) && empty($data['line1']) && empty($data['zip_code'])) return null;
        return new self(
            city: $data['city'] ?? null,
            city_desc: $data['city_desc'] ?? null,
            complement: $data['complement'] ?? null,
            line1: $data['line1'] ?? null,
            line2: $data['line2'] ?? null,
            number: $data['number'] ?? null,
            zip_code: $data['zip_code'] ?? null,
        );
    }

    public function toForm(): ?array {
        return array_filter(array_merge((array) $this, [
            'zip_code' => Mask::zipCode($this->zip_code),
        ])) ?: null;
    }

    public function toDb(): ?array {
        return array_filter([
            'city' => Cast::integer($this->city),
            'city_desc' => Cast::textLine($this->city_desc),
            'complement' => Cast::textLine($this->complement),
            'line1' => Cast::textLine($this->line1),
            'line2' => Cast::textLine($this->line2),
            'number' => Cast::textLine($this->number),
            'zip_code' => Cast::stripNonNumber($this->zip_code),
        ]) ?: null;
    }
}
