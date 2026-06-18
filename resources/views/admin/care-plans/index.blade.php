@extends('layouts.admin')

@section('title', 'Care Plan Pricing – Admin')
@section('page-title', 'Care Plan Pricing')

@section('content')

<p class="text-sm text-gray-500 mb-6">These cards control the "Website Maintenance Plans" pricing section on the public homepage.</p>

<div class="space-y-6 mb-8">
    @foreach ($plans as $plan)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-navy">{{ $plan->name }}</h3>
                <form method="POST" action="{{ route('admin.care-plans.destroy', $plan) }}" onsubmit="return confirm('Remove this plan card?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-7 h-7 rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('admin.care-plans.update', $plan) }}">
                @csrf
                @method('PATCH')

                <div class="grid sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Plan Name</label>
                        <input type="text" name="name" value="{{ $plan->name }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Price / month (USD, blank = no price shown)</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ $plan->price !== null ? $plan->price / 100 : '' }}"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Badge (e.g. "Most Popular", "Coming Soon")</label>
                        <input type="text" name="badge" value="{{ $plan->badge }}"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Display Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ $plan->sort_order }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Button Label</label>
                        <input type="text" name="cta_label" value="{{ $plan->cta_label }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Button Link</label>
                        <input type="text" name="cta_url" value="{{ $plan->cta_url }}" required
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Features (one per line)</label>
                    <textarea name="features" rows="5"
                              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ implode("\n", $plan->features ?? []) }}</textarea>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="is_available" value="1" {{ $plan->is_available ? 'checked' : '' }}
                               class="rounded border-gray-300 text-gold focus:ring-gold">
                        Available (unchecked shows as "Coming Soon" / disabled button)
                    </label>
                    <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    @endforeach
</div>

{{-- Add new plan --}}
<div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-6">
    <h3 class="font-semibold text-navy mb-4">Add a New Plan Card</h3>
    <form method="POST" action="{{ route('admin.care-plans.store') }}">
        @csrf

        <div class="grid sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Plan Name</label>
                <input type="text" name="name" placeholder="e.g. Growth Care Plan" required
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Price / month (USD, blank = no price shown)</label>
                <input type="number" name="price" step="0.01" min="0" placeholder="99.00"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Badge</label>
                <input type="text" name="badge" placeholder="e.g. Most Popular"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Display Order</label>
                <input type="number" name="sort_order" min="0" value="{{ $plans->count() + 1 }}" required
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Button Label</label>
                <input type="text" name="cta_label" value="Get Started" required
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Button Link</label>
                <input type="text" name="cta_url" value="#contact" required
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Features (one per line)</label>
            <textarea name="features" rows="5" placeholder="Website Updates&#10;Security Monitoring"
                      class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold"></textarea>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="is_available" value="1" checked
                       class="rounded border-gray-300 text-gold focus:ring-gold">
                Available
            </label>
            <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Add Plan
            </button>
        </div>
    </form>
</div>

@endsection
