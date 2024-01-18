<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    public function twoDigitNumber($max = 99)
    {
        $number = $this->faker->numberBetween(1, $max);
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $districs = [
            "Banyumanik",
            "Candisari",
            "Gajahmungkur",
            "Gayamsari",
            "Genuk",
            "Gunungpati",
            "Mijen",
            "Ngaliyan",
            "Pedurungan",
            "Semarang Barat",
            "Semarang Selatan",
            "Semarang Tengah",
            "Semarang Timur",
            "Semarang Utara",
            "Tembalang",
            "Tugu"
        ];

        $income = $this->faker->numberBetween(100000, 15000000);

        return [
            'family_card_number' => $this->faker->unique()->numerify('################'),
            'head_of_family_nik' => $this->faker->unique()->nik(),
            'head_of_family_name' => $this->faker->name(),
            'address' => $this->faker->streetAddress(),
            'rt' => $this->twoDigitNumber(9),
            'rw' => $this->twoDigitNumber(),
            'district' => $this->faker->randomElement($districs),
            'city' => "Semarang",
            'province' => "Jawa Tengah",
            'postal_code' => $this->faker->numerify('#####'),
            'monthly_income' => $income,
            'dependent_count' => $this->faker->numberBetween(1, 8),
            'house_ownership' => $income > 6000000 ? 'owned' : $this->faker->randomElement(['owned', 'rented', 'join']),
            'house_type' => $income > 2000000 ? 'permanent' : $this->faker->randomElement(['permanent', 'semi_permanent']),
            'status' => 'inactive',
        ];
    }
}
