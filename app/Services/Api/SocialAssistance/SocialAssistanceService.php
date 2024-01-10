<?php

namespace App\Services\Api\SocialAssistance;

use App\Models\SocialAssistance;
use Carbon\Carbon;

class SocialAssistanceService
{
    public function getData($request)
    {
        $status = $request->status;

        $query = SocialAssistance::query();

        $query->when(request('status'), function ($q) use ($status) {
            $status = strtolower($status);
            if ($status === 'ongoing') {
                $q->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())->orWhereNot('status', 'finished');
            } elseif ($status === 'finished') {
                $q->where('end_date', '<', now())->orWhere('status', 'finished');
            }
        });

        $query->orderBy('start_date', 'asc');

        $data = $query->get()->except('recipients')->groupBy(function ($item) {
            return Carbon::parse($item->start_date)->format('Y-m');
        });

        return $data;
    }
}
