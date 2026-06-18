@extends('layouts.admin')

@section('title', 'Contact Messages – Admin')
@section('page-title', 'Contact Messages')

@section('content')

@if ($messages->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
        <p class="text-gray-500">No messages from the "Get in Touch" form yet.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($messages as $message)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                    <div>
                        <p class="font-semibold text-navy">{{ $message->first_name }} {{ $message->last_name }}</p>
                        <a href="mailto:{{ $message->email }}" class="text-sm text-gold-dark hover:underline">{{ $message->email }}</a>
                        @if ($message->organization)
                            <span class="text-sm text-gray-400"> &middot; {{ $message->organization }}</span>
                        @endif
                    </div>
                    <div class="text-right shrink-0">
                        @if ($message->service)
                            <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full bg-gold/15 text-gold-dark mb-1">
                                {{ $message->service }}
                            </span>
                        @endif
                        <p class="text-xs text-gray-400">{{ $message->created_at->format('M j, Y \a\t g:ia') }}</p>
                    </div>
                </div>

                @if ($message->message)
                    <p class="text-sm text-gray-700 whitespace-pre-line border-t border-gray-100 pt-3">{{ $message->message }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

@endsection
