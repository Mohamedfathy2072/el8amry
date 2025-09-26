<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleStatusFactory extends Factory
{
    public static $statuses = [
        'new',
        'used',
        'vip',
        'electric',
        'certified pre-owned',
        'salvage',
        'damaged',
        'for parts',
        'rebuilt',
        'refurbished',
        'leased',
        'export only'
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(self::$statuses),
        ];
    }
}
