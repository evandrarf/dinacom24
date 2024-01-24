<?php

namespace App\Services\Admin\Absence;

use App\Models\Notification;
use App\Models\Report;
use App\Models\Ticket;

class AbsenceService
{
    public function getDetailRecipient($request)
    {
        $data = $request->validated();

        $ticket = Ticket::where('ticket_number', $data['ticket_number'])->with('resident', 'social_assistance')->first();

        if (!$ticket) {
            throw new \Exception('Ticket dengan nomor ' . $data['ticket_number'] . ' tidak ditemukan', 404);
        }

        if ($ticket->social_assistance->status == 'draft') {
            throw new \Exception('Ticket dengan nomor ' . $data['ticket_number'] . ' tidak ditemukan', 404);
        }

        if ($ticket->social_assistance->status == 'finished') {
            throw new \Exception('Bantuan sosial sudah selesai', 400);
        }

        if ($ticket->social_assistance->start_date > now()->format('Y-m-d')) {
            throw new \Exception('Bantuan sosial belum bisa diambil', 400);
        }

        if ($ticket->report) {
            throw new \Exception('Bantuan sosial sudah pernah diambil', 400);
        }

        return $ticket;
    }

    public function confirm($request)
    {
        $data = $request->validated();

        $ticket = Ticket::where('ticket_number', $data['ticket_number'])->with('resident', 'social_assistance')->first();

        if (!$ticket) {
            throw new \Exception('Ticket dengan nomor ' . $data['ticket_number'] . ' tidak ditemukan', 404);
        }

        if ($ticket->social_assistance->status == 'draft') {
            throw new \Exception('Ticket dengan nomor ' . $data['ticket_number'] . ' tidak ditemukan', 404);
        }

        if ($ticket->social_assistance->status == 'finished') {
            throw new \Exception('Bantuan sosial sudah selesai', 400);
        }

        if ($ticket->social_assistance->start_date > now()->format('Y-m-d')) {
            throw new \Exception('Bantuan sosial belum bisa diambil', 400);
        }

        if ($ticket->report) {
            throw new \Exception('Bantuan sosial sudah pernah diambil', 400);
        }

        $report = Report::create([
            'ticket_id' => $ticket->id,
            'resident_id' => $ticket->resident->id,
            'social_assistance_id' => $ticket->social_assistance->id,
            'taken_at' => now(),
        ]);

        $this->generateReportNotification($report->id);

        return $report;
    }

    public function generateReportNotification($report_id)
    {
        $existing = Notification::where('data_type', 'report')->where('data_id', $report_id)->first();

        if ($existing) {
            return $existing;
        }

        $report = Report::find($report_id);

        Notification::create([
            'data_type' => 'report',
            'data_id' => $report_id,
            'title' => 'Bantuan Sosial Sudah Diambil',
            'body' => 'Bantuan sosial sudah diambil. Lihat laporan untuk detailnya.',
            'social_assistance_id' => $report->social_assistance_id,
            'resident_id' => $report->resident_id,
            'social_assistance_recipient_id' => $report->ticket->social_assistance_recipient_id,
            'type' => 'info',
            'created_time' => now()->timestamp,
        ]);

        return true;
    }
}
