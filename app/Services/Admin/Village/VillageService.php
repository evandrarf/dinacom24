<?php

namespace App\Services\Admin\Village;

use App\Actions\Utility\PaginateCollection;
use App\Models\Village;

class VillageService
{
    public function getData($request)
    {
        $query = Village::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $per_page = $request->per_page ?? 10;

        $paginateCollection = new PaginateCollection();

        $data = $paginateCollection->handle($query->get(), $per_page);

        return $data;
    }

    public function store($request)
    {
        $data = $request->validated();

        $village = Village::create($data);

        return $village;
    }
}
