<?php

namespace App\Actions\Options;

use App\Models\Village;

class GetVillageOptions
{
    public function handle()
    {
        $village = Village::get()->pluck('name', 'id');

        return $village;
    }
}
