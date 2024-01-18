<?php

namespace App\Services\Api\Notification;

use App\Actions\Utility\CursorPaginateCollection;
use App\Models\Notification;

class NotificationService
{
    public function getData($request)
    {
        $query = Notification::query();

        $query->where('resident_id', auth('api')->user()->id);

        $query->orderBy('created_at', 'desc');

        $per_page = $request->per_page ?? 15;

        $data = $query->cursorPaginate($per_page);

        return $data;
    }

    public function read($request, $id)
    {
        $query = Notification::find($id);

        if (!$query) {
            throw new \Exception('Notifikasi tidak ditemukan.', 404);
        }

        if ($query->resident_id !== auth('api')->user()->id) {
            throw new \Exception('Notifikasi tidak ditemukan.', 404);
        }

        $query->update([
            'is_read' => true
        ]);

        return $query;
    }
}
