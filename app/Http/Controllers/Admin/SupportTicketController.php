<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SupportTicketReplyMail;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class SupportTicketController extends Controller
{
    public function index()
    {
        return view('admin.support-tickets.index', [
            'tickets' => SupportTicket::with('user', 'project')->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('user', 'project', 'replies.user');

        return view('admin.support-tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $reply = $ticket->replies()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        dispatch(function () use ($reply) {
            Mail::to($reply->ticket->user->email)->send(new SupportTicketReplyMail($reply, route('portal.support-tickets.show', $reply->ticket)));
        })->afterResponse();

        return back()->with('status', 'Reply sent.');
    }

    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(SupportTicket::STATUSES))],
        ]);

        $ticket->update($validated);

        return back()->with('status', 'Ticket status updated.');
    }
}
