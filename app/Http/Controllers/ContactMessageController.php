<?php

namespace App\Http\Controllers;

use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        $contactMessage = ContactMessage::create($validated);

        Mail::to(config('mail.contact_address'))->send(new NewContactMessageMail($contactMessage));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Thanks for reaching out! We've received your message and will get back to you within 24 hours.",
            ]);
        }

        return redirect(route('home').'#contact')->with('status', 'contact_sent');
    }
}
