<?php

namespace App\Services\Api\SocialAssistance;

use App\Models\SocialAssistance;
use Carbon\Carbon;

class SocialAssistanceService
{
    public function getData($request)
    {
        $status = $request->status;

        $query = SocialAssistance::query()->whereNot('status', 'draft');

        $query->when(request('status'), function ($q) use ($status) {
            $status = strtolower($status);
            if ($status === 'ongoing') {
                $q->where('end_date', '>=', now())->whereNot('status', 'finished');
                $q->orderBy('start_date', 'asc');
            } elseif ($status === 'finished') {
                $q->where('end_date', '<', now())->orWhere('status', 'finished');
                $q->orderBy('start_date', 'desc');
            }
        });

        $data = $query->get()->except('recipients')->groupBy(function ($item) {
            return Carbon::parse($item->start_date)->format('Y-m');
        });

        return $data;
    }
}
