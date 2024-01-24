<?php

namespace App\Services\Api\Ticket;

use App\Models\Ticket;

class TicketService
{
    public function show($request, $id)
    {
        $ticket = Ticket::with('social_assistance_recipient', 'qr_code_file', 'social_assistance', 'resident')->find($id);


        if (!$ticket) {
            throw new \Exception('Tidak dapat menemukan data tiket', 404);
        }

        if ($ticket->resident_id !== auth('api')->user()->id) {
            throw new \Exception('Tidak dapat menemukan data tiket', 404);
        }

        if ($ticket->social_assistance->status === 'draft') {
            throw new \Exception('Tidak dapat menemukan data tiket', 404);
        }

        return $ticket;
    }
}
