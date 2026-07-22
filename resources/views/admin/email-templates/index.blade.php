@extends('layouts.admin')

@section('title', 'Email Templates – Admin')
@section('page-title', 'Email Templates')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1 bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm font-semibold text-navy dark:text-white">{{ count($templates) }} Templates</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Read-only — rendered with sample placeholder data, not real client info.</p>
            </div>
            <nav class="max-h-[70vh] overflow-y-auto py-2">
                @foreach ($templates as $template)
                    <a href="{{ route('admin.email-templates.index', ['template' => $template]) }}"
                       class="block px-4 py-2.5 text-sm border-l-2 {{ $selected === $template ? 'border-gold bg-gold/10 text-navy dark:text-white font-semibold' : 'border-transparent text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        {{ ucwords(str_replace('-', ' ', $template)) }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="lg:col-span-3 bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between gap-3">
                <p class="text-sm font-semibold text-navy dark:text-white">{{ ucwords(str_replace('-', ' ', $selected)) }}</p>
                <span class="text-xs text-gray-400 font-mono">emails/{{ $selected }}.blade.php</span>
            </div>
            <iframe src="{{ route('admin.email-templates.preview', $selected) }}" class="w-full bg-white" style="height:75vh; border:0;"></iframe>
        </div>
    </div>
@endsection
