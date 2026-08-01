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
        $ticket = SupportTicket::with(['user', 'driver', 'order.service'])->findOrFail($id);
        return view('admin.support_tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $request->validate([
            'status' => 'required|in:open,in_review,resolved,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $ticket->status = $request->status;
        if ($request->filled('admin_notes')) {
            $ticket->admin_notes = $request->admin_notes;
        }

        if ($request->status === 'resolved' || $request->status === 'closed') {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        return redirect()->back()->with('success', 'تم تحديث حالة تذكرة الدعم بنجاح');
    }
}
