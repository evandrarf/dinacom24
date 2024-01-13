<?php

namespace App\Services\Api\Report;

use App\Models\Report;

class ReportService
{
    public function show($id)
    {
        $query = Report::with('social_assistance', 'ticket', 'resident')->find($id);

        if (!$query) {
            throw new \Exception('Laporan tidak ditemukan.', 404);
        }

        if ($query->resident_id !== auth('api')->user()->id) {
            throw new \Exception('Laporan tidak ditemukan.', 404);
        }

        return $query;
    }
}
