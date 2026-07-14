<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Mail\NewSupportTicketMail;
use App\Mail\SupportTicketClientReplyMail;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->user()->projects()->first();

        return view('portal.support-tickets.index', [
            'tickets' => $project ? $project->supportTickets : collect(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $project = $request->user()->projects()->first();
        abort_unless($project, 422, 'No project found for this account.');

        $ticket = $project->supportTickets()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        dispatch(function () use ($ticket) {
            Mail::to(config('mail.support_address'))->send(new NewSupportTicketMail($ticket));
        })->afterResponse();

        return redirect()->route('portal.support-tickets.show', $ticket)->with('status', 'Your support ticket has been submitted — we\'ll be in touch soon.');
    }

    public function show(Request $request, SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $ticket->load('replies.user');

        return view('portal.support-tickets.show', [
            'ticket' => $ticket,
        ]);
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $reply = $ticket->replies()->create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        // A client following up on a ticket we'd already marked resolved
        // means it isn't actually resolved — reopen it automatically rather
        // than leaving it stuck showing "Resolved" while still active.
        if ($ticket->status === 'resolved') {
            $ticket->update(['status' => 'open']);
        }

        dispatch(function () use ($reply) {
            Mail::to(config('mail.support_address'))->send(new SupportTicketClientReplyMail($reply));
        })->afterResponse();

        return back()->with('status', 'Reply sent.');
    }
}
