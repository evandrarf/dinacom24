<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialAssistance>
 */
class SocialAssistanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = $this->faker->dateTimeBetween('+1 day', '+2 month');
        $end_date = clone $start_date;
        $end_date->add(new \DateInterval('P1M'));

        $start_time = "08:00:00";
        $end_time = Carbon::parse($start_time);
        $end_time = $end_time->addHours(8)->format('H:i:s');

        $amount_per_kk = [200000, 250000, 300000, 350000, 400000, 450000, 500000, 550000, 600000, 650000, 700000, 750000, 800000, 850000, 900000, 950000, 1000000];

        $names = [
            'Bantuan Sosial Tunai',
            'Bantuan Pangan',
            'Bantuan Listrik',
            'Bantuan Kesehatan',
            'Bantuan Pendidikan',
            'Bantuan Usaha Mikro',
            'Bantuan Bencana Alam',
            'Bantuan Anak Yatim',
            'Bantuan Pekerja Informal',
            'Bantuan Sembako'
        ];

        return [
            'name' => $this->faker->randomElement($names),
            'description' => $this->faker->text,
            'amount_per_kk' => $this->faker->randomElement($amount_per_kk),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ];
    }
}
