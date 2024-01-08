<?php

namespace App\Services\Api\SocialAssistance;

use App\Models\SocialAssistance;

class SocialAssistanceService
{
    public function getData($request)
    {
        $query = SocialAssistance::query();
    }
}
