<?php

namespace App\Http\Controllers\Api\Ticket;

use App\Http\Controllers\ApiBaseController;
use App\Http\Resources\Api\Ticket\DetailTicketResource;
use App\Services\Api\Ticket\TicketService;
use Illuminate\Http\Request;

class TicketController extends ApiBaseController
{
    private $ticketService;
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function show(Request $request, $id)
    {
        try {
            $data = $this->ticketService->show($request, $id);

            $res = new DetailTicketResource($data, 'Sukses mendapatkan data tiket.');

            return $this->respond($res);
        } catch (\Exception $e) {
            return $this->exceptionError($e->getMessage(), $e->getCode());
        }
    }
}
