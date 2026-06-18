@extends('layouts.admin')

@section('title', $project->name.' – Admin')
@section('page-title', $project->name)

@section('content')

@php
    $categories = \App\Http\Controllers\Portal\CategoryController::CATEGORIES;

    $statusLabels = [
        'onboarding'  => 'Onboarding',
        'in_progress' => 'In Progress',
        'review'      => 'In Review',
        'launched'    => 'Launched',
        'maintenance' => 'Maintenance',
    ];
    $milestoneStatuses = ['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
    $empty = collect();
@endphp

<a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-navy mb-6">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    All Projects
</a>

{{-- Client + project header --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Client</p>
            <p class="font-semibold text-navy">{{ $project->user->name }}</p>
            <p class="text-sm text-gray-500">{{ $project->user->email }}</p>
        </div>

        <form method="POST" action="{{ route('admin.projects.update', $project) }}" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status</label>
            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                @foreach ($statusLabels as $value => $label)
                    <option value="{{ $value }}" {{ $project->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
        <span>Project Progress</span>
        <span class="font-semibold text-navy">{{ $project->progressPercent() }}%</span>
    </div>
    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
        <div class="h-full bg-gold rounded-full" style="width: {{ $project->progressPercent() }}%"></div>
    </div>
</div>

@php
    $pendingPaymentCount = $project->payments->where('status', 'pending')->count();
@endphp

{{-- Tabs --}}
<div class="flex items-center gap-1 border-b border-gray-200 mb-6">
    <button type="button" data-tab-button="overview" onclick="showProjectTab('overview')"
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-gold text-navy">
        Overview
    </button>
    <button type="button" data-tab-button="billing" onclick="showProjectTab('billing')"
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-navy transition-colors">
        Billing
        @if ($pendingPaymentCount > 0)
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gold/15 text-gold-dark">{{ $pendingPaymentCount }}</span>
        @endif
    </button>
    <button type="button" data-tab-button="files" onclick="showProjectTab('files')"
            class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-navy transition-colors">
        Files &amp; Content
    </button>
</div>

<div data-tab-panel="overview">

{{-- Milestones --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h3 class="font-semibold text-navy mb-4">Milestones</h3>

    <div class="space-y-2 mb-5">
        @foreach ($project->milestones as $milestone)
            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 px-4 py-2.5">
                <span class="text-sm text-navy">{{ $milestone->title }}</span>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.milestones.update', $milestone) }}">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                class="rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                            @foreach ($milestoneStatuses as $value => $label)
                                <option value="{{ $value }}" {{ $milestone->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                    <form method="POST" action="{{ route('admin.milestones.destroy', $milestone) }}" onsubmit="return confirm('Remove this milestone?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
        @if ($project->milestones->isEmpty())
            <p class="text-sm text-gray-400">No milestones yet.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.milestones.store', $project) }}" class="flex items-center gap-3">
        @csrf
        <input type="text" name="title" placeholder="Add a milestone..." required
               class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            Add
        </button>
    </form>
</div>

</div>

<div data-tab-panel="billing" class="hidden">

{{-- Payments --}}
@php
    $paymentStatusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'paid' => 'bg-teal/15 text-teal-dark',
        'failed' => 'bg-red-50 text-red-500',
        'canceled' => 'bg-gray-100 text-gray-500',
    ];
@endphp
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h3 class="font-semibold text-navy mb-4">Payments</h3>

    <div class="space-y-2 mb-5">
        @foreach ($project->payments as $payment)
            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 px-4 py-2.5">
                <div>
                    <span class="text-sm text-navy">{{ $payment->description }}</span>
                    <span class="text-sm text-gray-400 ml-2">{{ $payment->formattedAmount() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $paymentStatusColors[$payment->status] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                    @if ($payment->isPending())
                        <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Remove this payment request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-7 h-7 rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        @if ($project->payments->isEmpty())
            <p class="text-sm text-gray-400">No payment requests yet.</p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.payments.store', $project) }}" class="flex items-center gap-3">
        @csrf
        <input type="text" name="description" placeholder="What's this payment for..." required
               class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        <input type="number" name="amount" step="0.01" min="1" placeholder="Amount (USD)" required
               class="w-40 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
        <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            Request
        </button>
    </form>
</div>

{{-- Maintenance Plan --}}
@php
    $subscriptionStatusColors = [
        'pending' => 'bg-gold/15 text-gold-dark',
        'active' => 'bg-teal/15 text-teal-dark',
        'past_due' => 'bg-red-50 text-red-500',
        'canceled' => 'bg-gray-100 text-gray-500',
    ];
    $subscriptionStatusLabels = [
        'pending' => 'Pending',
        'active' => 'Active',
        'past_due' => 'Past Due',
        'canceled' => 'Canceled',
    ];
    $currentSubscription = $project->subscription;
@endphp
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <h3 class="font-semibold text-navy mb-4">Maintenance Plan</h3>

    @if ($currentSubscription && ! $currentSubscription->isCanceled())
        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 px-4 py-2.5">
            <div>
                <span class="text-sm text-navy">{{ $currentSubscription->description }}</span>
                <span class="text-sm text-gray-400 ml-2">{{ $currentSubscription->formattedAmount() }}</span>
                @if ($currentSubscription->current_period_end)
                    <span class="text-xs text-gray-400 ml-2">renews {{ $currentSubscription->current_period_end->format('M j, Y') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-block text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $subscriptionStatusColors[$currentSubscription->status] ?? 'bg-gray-100 text-gray-500' }}">
                    {{ $subscriptionStatusLabels[$currentSubscription->status] ?? $currentSubscription->status }}
                </span>
                <form method="POST" action="{{ route('admin.subscriptions.destroy', $currentSubscription) }}" onsubmit="return confirm('Cancel this maintenance plan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-7 h-7 rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-400 mb-4">No active maintenance plan.</p>
        <form method="POST" action="{{ route('admin.subscriptions.store', $project) }}" class="flex items-center gap-3">
            @csrf
            <input type="text" name="description" placeholder="e.g. Monthly Website Maintenance" required
                   class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <input type="number" name="amount" step="0.01" min="1" placeholder="Amount / month (USD)" required
                   class="w-48 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            <button type="submit" class="shrink-0 bg-navy hover:bg-navy-light text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                Request
            </button>
        </form>
    @endif
</div>

</div>

<div data-tab-panel="files" class="hidden">

{{-- Project files & content --}}
@foreach ($categories as $cat => $meta)
    @php $items = $uploadsByCategory->get($cat, $empty); @endphp

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-navy">
                {{ $meta['label'] }}
                @if ($cat === 'revision' && $items->isNotEmpty())
                    <span class="ml-2 inline-block text-xs font-semibold uppercase tracking-wide px-2 py-0.5 rounded-full bg-red-50 text-red-500">{{ $items->count() }} open</span>
                @endif
            </h3>
            <span class="text-xs text-gray-400">{{ $items->count() }} item{{ $items->count() === 1 ? '' : 's' }}</span>
        </div>

        @if ($items->isEmpty())
            <p class="text-sm text-gray-400">Nothing here yet.</p>
        @elseif ($meta['type'] === 'file')
            <div class="space-y-2.5">
                @foreach ($items as $item)
                    <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3">
                        <a href="{{ $item->url() }}" target="_blank" class="flex items-center gap-3 min-w-0 group">
                            <span class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center shrink-0 overflow-hidden">
                                @if (in_array($cat, ['image', 'logo']))
                                    <img src="{{ $item->url() }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[0.6rem] font-bold uppercase text-gray-500">{{ $item->extension() ?: 'FILE' }}</span>
                                @endif
                            </span>
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-navy group-hover:text-gold-dark truncate">{{ $item->original_name }}</span>
                                <span class="block text-xs text-gray-400">
                                    {{ $item->created_at->format('M j, Y') }}
                                    @if ($item->formattedSize()) &middot; {{ $item->formattedSize() }} @endif
                                    &middot; from {{ $item->user->name }}
                                </span>
                            </span>
                        </a>
                        <form method="POST" action="{{ route('admin.uploads.approve', $item) }}" class="shrink-0">
                            @csrf
                            @method('PATCH')
                            @if ($item->isApproved())
                                <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-dark bg-teal/10 border border-teal/30 px-3 py-1.5 rounded-full hover:bg-teal/15 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Approved
                                </button>
                            @else
                                <button type="submit" class="text-xs font-semibold text-navy bg-gray-100 hover:bg-gold/15 hover:text-gold-dark border border-gray-200 px-3 py-1.5 rounded-full transition-colors">
                                    Approve
                                </button>
                            @endif
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="space-y-3">
                @foreach ($items as $item)
                    <div class="rounded-lg border border-gray-200 px-4 py-3.5">
                        <p class="text-xs text-gray-400 mb-1.5">{{ $item->created_at->format('M j, Y \a\t g:ia') }} &middot; from {{ $item->user->name }}</p>
                        @if ($item->body)
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $item->body }}</p>
                        @endif
                        @if ($item->path)
                            <a href="{{ $item->url() }}" target="_blank" class="mt-2 inline-flex items-center gap-2 text-sm text-gold-dark hover:underline">
                                {{ $item->original_name }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endforeach

</div>

<script>
    function showProjectTab(tab) {
        document.querySelectorAll('[data-tab-panel]').forEach((el) => {
            el.classList.toggle('hidden', el.dataset.tabPanel !== tab);
        });
        document.querySelectorAll('[data-tab-button]').forEach((el) => {
            const active = el.dataset.tabButton === tab;
            el.classList.toggle('border-gold', active);
            el.classList.toggle('text-navy', active);
            el.classList.toggle('border-transparent', !active);
            el.classList.toggle('text-gray-400', !active);
        });
    }
</script>

@endsection
