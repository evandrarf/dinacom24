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

    public function update($request, $id)
    {
        $data = $request->validated();

        $village = Village::findOrFail($id);

        $village->update($data);

        return $village;
    }

    public function destroy($request, $id)
    {
        $village = Village::findOrFail($id);

        $village->delete();

        return $village;
    }

    public function destroyMany($request)
    {
        $ids = $request->ids;

        $villages = Village::whereIn('id', $ids)->get();

        foreach ($villages as $village) {
            $village->delete();
        }

        return $villages;
    }
}
