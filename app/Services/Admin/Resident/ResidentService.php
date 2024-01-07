<?php

namespace App\Services\Admin\Resident;

use App\Actions\Utility\PaginateCollection;
use App\Models\Resident;
use App\Models\SocialAssistanceRecipient;

class ResidentService
{
    public function getData($request)
    {
        $search = $request->search;
        $village_id = $request->village_id;
        $status = $request->status;
        $eligibility_status = $request->eligibility_status;
        $sort_by = $request->sort_by;
        $social_assistance_id = $request->social_assistance_id;

        $query = Resident::query()->with(['village', 'familyCard', 'identityCard']);

        $query->when(request('village_id', false), function ($q) use ($village_id) {
            $q->where('village_id', $village_id);
        });

        $query->when(request('status', false), function ($q) use ($status) {
            $q->where('status', $status);
        });

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('head_of_family_name', 'like', '%' . $search . '%')->orWhere('head_of_family_nik', 'like', '%' . $search . '%')->orWhere('family_card_number', 'like', '%' . $search . '%');
        });

        $per_page = $request->per_page ?? 10;

        $paginateCollection = new PaginateCollection();

        $records = $query->get();

        $filteredRecords = $records;

        if ($eligibility_status === 'eligible' || $eligibility_status === 'not_eligible') {
            $filteredRecords = $records->filter(function ($resident) use ($eligibility_status) {
                $result = $resident->calculateEligibilityStatus() ? 'eligible' : 'not_eligible';
                return $result === $eligibility_status;
            });
        }

        if ($sort_by === 'selected') {
            $selectedResidentIds = SocialAssistanceRecipient::where('social_assistance_id', $social_assistance_id)->pluck('resident_id')->toArray();

            $selected = $filteredRecords->whereIn('id', $selectedResidentIds);
            $notSelected = $filteredRecords->whereNotIn('id', $selectedResidentIds);

            $filteredRecords = $selected->merge($notSelected);
        }

        $data = $paginateCollection->handle($filteredRecords, $per_page);

        return $data;
    }

    public function getDetailData($request, $id)
    {
        $query = Resident::query()->with(['village', 'familyCard', 'identityCard']);

        $data = $query->find($id);

        if (!$data) {
            throw new \Exception('Data tidak ditemukan');
        }

        return $data;
    }

    public function store($request)
    {
        $data = $request->validated();

        $data = Resident::create([
            'family_card_number' => $data['family_card_number'],
            'head_of_family_nik' => $data['head_of_family_nik'],
            'head_of_family_name' => $data['head_of_family_name'],
            'address' => $data['address'],
            'rt' => $data['rt'],
            'rw' => $data['rw'],
            'district' => $data['district'],
            'city' => $data['city'],
            'village_id' => $data['village_id'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'monthly_income' => $data['monthly_income'],
            'dependent_count' => $data['dependent_count'],
            'house_ownership' => $data['house_ownership'],
            'house_type' => $data['house_type'],
            'status' => 'inactive',
        ]);

        return $data;
    }

    public function update($request, $id)
    {
        $data = $request->validated();

        $query = Resident::findOrFail($id);

        if ($request->has('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        $query->update([
            'family_card_number' => $data['family_card_number'],
            'head_of_family_nik' => $data['head_of_family_nik'],
            'head_of_family_name' => $data['head_of_family_name'],
            'address' => $data['address'],
            'rt' => $data['rt'],
            'rw' => $data['rw'],
            'district' => $data['district'],
            'city' => $data['city'],
            'village_id' => $data['village_id'],
            'province' => $data['province'],
            'postal_code' => $data['postal_code'],
            'monthly_income' => $data['monthly_income'],
            'dependent_count' => $data['dependent_count'],
            'house_ownership' => $data['house_ownership'],
            'house_type' => $data['house_type'],
            'status' => 'inactive',
            'password' => $data['password'] ?? $query->password,
        ]);

        return $data;
    }

    public function destroy($id)
    {
        $data = Resident::findOrFail($id);

        $data->delete();

        return $data;
    }

    public function destroyMany($request)
    {
        $ids = $request->ids;

        $residents = Resident::whereIn('id', $ids)->get();

        foreach ($residents as $resident) {
            $resident->delete();
        }

        return $residents;
    }

    public function activate($request, $id)
    {
        $data = Resident::findOrFail($id);

        $data->update([
            'status' => 'active',
        ]);

        return $data;
    }
}
