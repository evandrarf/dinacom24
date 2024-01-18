<?php

namespace App\Actions\Options;


class GetDistrictOptions
{
    public function handle()
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

        return $districs;
    }
}
