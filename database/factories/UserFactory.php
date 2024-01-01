<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;


    private static $usedVillageIds = [];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $villages = \App\Models\Village::all()->except(static::$usedVillageIds);

        if ($villages->isEmpty()) {
            static::$usedVillageIds = [];
            $villages = \App\Models\Village::all();
        }

        $village = $villages->random();
        static::$usedVillageIds[] = $village->id;

        return [
            'name' => 'Admin ' . $village->name,
            'email' => Str::slug($village->name) . '@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia123'),
            'village_id' => $village->id,
        ];
    }
}
