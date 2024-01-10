<?php

namespace App\Services\Admin\SocialAssistance;

use App\Actions\Utility\GenerateQrCode;
use App\Actions\Utility\PaginateCollection;
use App\Models\Resident;
use App\Models\SocialAssistance;
use App\Models\SocialAssistanceRecipient;
use App\Models\Ticket;
use App\Services\FileService;
use chillerlan\QRCode\QRCode;
use Illuminate\Http\Testing\File;

class SocialAssistanceService
{
    public function getData($request)
    {
        $search = $request->search;
        $status = $request->status == 'all' ? false : $request->status;

        $query = SocialAssistance::query()->with('recipients');

        $query->when($status != false || $status != null, function ($q) use ($status) {
            $q->where('status', $status);
        });

        $query->when(request('search', false), function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });

        $per_page = $request->per_page ?? 10;

        $paginateCollection = new PaginateCollection();

        $data = $paginateCollection->handle($query->orderBy('start_date')->get(), $per_page);

        return $data;
    }

    public function store($request)
    {
        $data = $request->validated();

        $start_date = date('Y-m-d', strtotime($data['start_datetime']));
        $start_time = date('H:i:s', strtotime($data['start_datetime']));
        $end_date = date('Y-m-d', strtotime($data['end_datetime']));
        $end_time = date('H:i:s', strtotime($data['end_datetime']));

        $query = SocialAssistance::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'start_date' => $start_date,
            'start_time' => $start_time,
            'end_date' => $end_date,
            'end_time' => $end_time,
            'status' => $data['status'],
            'amount_per_kk' => $data['amount_per_kk'],
        ]);


        $this->generateSocialAssistanceRecipient($query->id, $data['resident_ids']);
        $this->generateTicket($query->id);

        return $query;
    }

    public function getDetailData($id)
    {
        $data = SocialAssistance::with('recipients')->find($id);

        if (!$data) {
            throw new \Exception('Data tidak ditemukan');
        }

        return $data;
    }

    public function getResidentData($id)
    {
        $residentIds = SocialAssistanceRecipient::where('social_assistance_id', $id)->pluck('resident_id')->toArray();

        $data = Resident::whereIn('id', $residentIds)->get();

        $paginateCollection = new PaginateCollection();

        $data = $paginateCollection->handle($data, 10);

        return $data;
    }

    public function generateSocialAssistanceRecipient($socialAssistanceId, $residentIds)
    {
        $data = [];
        foreach ($residentIds as $residentId) {
            $data[] = [
                'social_assistance_id' => $socialAssistanceId,
                'resident_id' => $residentId,
            ];
        }

        SocialAssistanceRecipient::where('social_assistance_id', $socialAssistanceId)->whereNotIn('resident_id', $residentIds)->delete();

        $existing = SocialAssistanceRecipient::where('social_assistance_id', $socialAssistanceId)->whereIn('resident_id', $residentIds)->select('resident_id', 'social_assistance_id')->get()->toArray();


        $data = array_filter($data, function ($item) use ($existing) {
            return !in_array($item, $existing);
        });

        SocialAssistanceRecipient::insert($data);

        return true;
    }

    public function update($request, $id)
    {
        $data = $request->validated();

        $start_date = date('Y-m-d', strtotime($data['start_datetime']));
        $start_time = date('H:i:s', strtotime($data['start_datetime']));
        $end_date = date('Y-m-d', strtotime($data['end_datetime']));
        $end_time = date('H:i:s', strtotime($data['end_datetime']));

        $query = SocialAssistance::findOrFail($id);

        $query->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'start_date' => $start_date,
            'start_time' => $start_time,
            'end_date' => $end_date,
            'end_time' => $end_time,
            'status' => $data['status'],
            'amount_per_kk' => $data['amount_per_kk'],
        ]);

        $this->generateSocialAssistanceRecipient($query->id, $data['resident_ids']);
        $this->generateTicket($query->id);

        return $query;
    }

    public function generateTicket($socialAssistanceId)
    {
        $socialAssistanceRecipientIds = SocialAssistanceRecipient::where('social_assistance_id', $socialAssistanceId)->pluck('id')->toArray();

        $existing = Ticket::whereIn('social_assistance_recipient_id', $socialAssistanceRecipientIds)->get()->pluck('social_assistance_recipient_id')->toArray();

        $socialAssistanceRecipientIds = array_filter($socialAssistanceRecipientIds, function ($item) use ($existing) {
            return !in_array($item, $existing);
        });

        $data = [];

        $socialAssisatnceRecipients = SocialAssistanceRecipient::whereIn('id', $socialAssistanceRecipientIds)->get();

        foreach ($socialAssistanceRecipientIds as $socialAssistanceRecipientId) {
            $ticketNumber = time() . rand(1000000, 9999999);

            $generateQrCode = new GenerateQrCode();

            $qrCode = $generateQrCode->handle($ticketNumber);

            $data[] = [
                'social_assistance_recipient_id' => $socialAssistanceRecipientId,
                'ticket_number' => $ticketNumber,
                'qr_code_file_id' => $qrCode->id,
                'social_assistance_id' => $socialAssistanceId,
                'resident_id' => $socialAssisatnceRecipients->where('id', $socialAssistanceRecipientId)->first()->resident_id,
            ];
        }

        Ticket::insert($data);

        return true;
    }

    public function destroy($id)
    {
        $query = SocialAssistance::find($id);

        if (!$query) {
            throw new \Exception('Data tidak ditemukan');
        }

        $query->delete();

        return $query;
    }

    public function destroyMany($request)
    {
        $ids = $request->ids;

        $query = SocialAssistance::whereIn('id', $ids)->delete();

        return $query;
    }
}
