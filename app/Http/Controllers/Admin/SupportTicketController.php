<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'driver', 'order'])->orderBy('id', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $rows = $query->paginate(15);
        return view('admin.support_tickets.index', compact('rows'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with([
            'user',
            'driver.profile.driverCar.brand',
            'driver.profile.driverCar.model',
            'order.service',
            'order.driver',
            'order.user',
        ])->findOrFail($id);

        $room = null;
        $chatMessages = collect();
        if ($ticket->order_id) {
            $room = \App\Models\room::with(['chat.sender', 'chat.receiver'])
                ->where('trip_id', $ticket->order_id)
                ->first();
            if ($room && $room->chat) {
                $chatMessages = $room->chat->sortBy('id');
            }
        }

        return view('admin.support_tickets.show', compact('ticket', 'room', 'chatMessages'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::with(['user', 'driver', 'order'])->findOrFail($id);
        $request->validate([
            'status' => 'required|in:open,in_review,resolved,closed',
            'admin_notes' => 'nullable|string',
            'notify_target' => 'nullable|in:none,user,driver,both',
        ]);

        $ticket->status = $request->status;
        if ($request->filled('admin_notes')) {
            $ticket->admin_notes = $request->admin_notes;
        }

        if ($request->status === 'resolved' || $request->status === 'closed') {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        $notifyTarget = $request->input('notify_target', 'none');
        $statusLabels = [
            'open' => 'مفتوحة (جديدة)',
            'in_review' => 'قيد المراجعة والتحقيق',
            'resolved' => 'تم الحل والتعويض',
            'closed' => 'مغلقة',
        ];
        $statusText = $statusLabels[$ticket->status] ?? $ticket->status;

        $title = "تحديث بشأن تذكرة الدعم #{$ticket->ticket_number}";
        $body = "تم تحديث حالة تذكرتك إلى: {$statusText}";
        if ($request->filled('admin_notes')) {
            $body .= "\nرد الدعم: " . $request->admin_notes;
        }

        $notificationSent = 0;
        if (in_array($notifyTarget, ['user', 'both']) && $ticket->user) {
            $ticket->user->sendPushNotification($title, $body, [
                'ticket_id' => $ticket->id,
                'order_id' => $ticket->order_id,
                'type' => 'support_ticket_reply',
            ]);
            $notificationSent++;
        }

        if (in_array($notifyTarget, ['driver', 'both']) && $ticket->driver) {
            $ticket->driver->sendPushNotification($title, $body, [
                'ticket_id' => $ticket->id,
                'order_id' => $ticket->order_id,
                'type' => 'support_ticket_reply',
            ]);
            $notificationSent++;
        }

        $msg = 'تم تحديث حالة تذكرة الدعم بنجاح';
        if ($notificationSent > 0) {
            $msg .= ' وتوجيه الإشعار الفوري للمستلم بنجاح (' . $notificationSent . ')';
        }

        return redirect()->back()->with('success', $msg);
    }
}
