<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\Order;
use Illuminate\Http\Request;

class SupportTicketApiController extends Controller
{
    public function createTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'order_id' => 'nullable|integer',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $user = auth()->user();
        $ticketNumber = 'TK-' . strtoupper(substr(uniqid(), -6));

        $driverId = null;
        if ($request->order_id) {
            $order = Order::find($request->order_id);
            if ($order) {
                $driverId = $order->driver_id;
            }
        }

        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'user_id' => $user->id,
            'driver_id' => $driverId,
            'order_id' => $request->order_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'status' => 'open',
        ]);

        return Resp($ticket, 'تم إرسال تذكرة الدعم بنجاح، وسيقوم فريق الدعم بمراجعتها والتواصل معك.');
    }

    public function myTickets()
    {
        $user = auth()->user();
        $tickets = SupportTicket::with('order')
            ->where('user_id', $user->id)
            ->orWhere('driver_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return Resp($tickets, 'Tickets fetched successfully');
    }
}
