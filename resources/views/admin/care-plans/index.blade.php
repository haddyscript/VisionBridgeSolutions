@extends('layouts.admin')

@section('title', 'Care Plan Pricing – Admin')
@section('page-title', 'Care Plan Pricing')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">These cards control the "Website Maintenance Plans" pricing section on the public homepage.</p>

<div class="space-y-4 mb-8">
    @foreach ($plans as $plan)
        <details class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden" open>
            <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-6 py-4 [&::-webkit-details-marker]:hidden">
                <div class="flex items-center gap-3">
                    <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 transition-transform duration-200 group-open:rotate-90 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <div>
                        <p class="font-semibold text-navy dark:text-white">{{ $plan->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $plan->formattedPrice() ? $plan->formattedPrice().'/mo' : 'No price shown' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $plan->is_available ? 'bg-teal/15 text-teal-dark' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                        {{ $plan->is_available ? 'Available' : 'Coming Soon' }}
                    </span>
                    <form method="POST" action="{{ route('admin.care-plans.destroy', $plan) }}" onsubmit="return confirm('Remove this plan card?')" onclick="event.stopPropagation()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-full text-gray-400 dark:text-gray-500 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </form>
                </div>
            </summary>

            <div class="border-t border-gray-200 dark:border-gray-700 p-6 grid lg:grid-cols-[1fr_300px] gap-6">
                <form method="POST" action="{{ route('admin.care-plans.update', $plan) }}" class="care-plan-form" data-preview-target="preview-{{ $plan->id }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Plan Name</label>
                            <input type="text" name="name" value="{{ $plan->name }}" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Price / month (USD, blank = no price shown)</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ $plan->price !== null ? $plan->price / 100 : '' }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Badge (e.g. "Most Popular", "Coming Soon")</label>
                            <input type="text" name="badge" value="{{ $plan->badge }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Display Order</label>
                            <input type="number" name="sort_order" min="0" value="{{ $plan->sort_order }}" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Button Label</label>
                            <input type="text" name="cta_label" value="{{ $plan->cta_label }}" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Button Link</label>
                            <input type="text" name="cta_url" value="{{ $plan->cta_url }}" required
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Features (one per line)</label>
                        <textarea name="features" rows="5"
                                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">{{ implode("\n", $plan->features ?? []) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <input type="checkbox" name="is_available" value="1" {{ $plan->is_available ? 'checked' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                            Available (unchecked shows as "Coming Soon" / disabled button)
                        </label>
                        <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-2">Live Preview</p>
                    @include('admin.care-plans._preview', ['id' => 'preview-'.$plan->id, 'plan' => $plan])
                </div>
            </div>
        </details>
    @endforeach
</div>

{{-- Add new plan --}}
<details class="bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 overflow-hidden">
    <summary class="flex items-center gap-3 cursor-pointer list-none px-6 py-4 [&::-webkit-details-marker]:hidden">
        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 transition-transform duration-200 group-open:rotate-90 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <h3 class="font-semibold text-navy dark:text-white">Add a New Plan Card</h3>
    </summary>

    <div class="border-t border-gray-200 dark:border-gray-700 p-6 grid lg:grid-cols-[1fr_300px] gap-6">
        <form method="POST" action="{{ route('admin.care-plans.store') }}" class="care-plan-form" data-preview-target="preview-new">
            @csrf

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Plan Name</label>
                    <input type="text" name="name" placeholder="e.g. Growth Care Plan" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Price / month (USD, blank = no price shown)</label>
                    <input type="number" name="price" step="0.01" min="0" placeholder="99.00"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Badge</label>
                    <input type="text" name="badge" placeholder="e.g. Most Popular"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Display Order</label>
                    <input type="number" name="sort_order" min="0" value="{{ $plans->count() + 1 }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Button Label</label>
                    <input type="text" name="cta_label" value="Get Started" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Button Link</label>
                    <input type="text" name="cta_url" value="#contact" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-1.5">Features (one per line)</label>
                <textarea name="features" rows="5" placeholder="Website Updates&#10;Security Monitoring"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white dark:placeholder-gray-500"></textarea>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <input type="checkbox" name="is_available" value="1" checked
                           class="rounded border-gray-300 dark:border-gray-600 text-gold focus:ring-gold">
                    Available
                </label>
                <button type="submit" class="bg-gold hover:bg-gold-dark text-navy-dark text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Add Plan
                </button>
            </div>
        </form>

        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500 mb-2">Live Preview</p>
            @include('admin.care-plans._preview', ['id' => 'preview-new', 'plan' => null])
        </div>
    </div>
</details>

<script>
    function updateCarePlanPreview(form) {
        const preview = document.getElementById(form.dataset.previewTarget);
        if (!preview) return;

        const name = form.querySelector('[name="name"]').value || 'Plan Name';
        const priceRaw = form.querySelector('[name="price"]').value;
        const badge = form.querySelector('[name="badge"]').value;
        const ctaLabel = form.querySelector('[name="cta_label"]').value || 'Get Started';
        const features = form.querySelector('[name="features"]').value
            .split('\n').map(function (s) { return s.trim(); }).filter(Boolean);
        const isAvailable = form.querySelector('[name="is_available"]').checked;

        preview.querySelector('[data-preview="name"]').textContent = name;

        const priceWrap = preview.querySelector('[data-preview="price-wrap"]');
        const comingSoon = preview.querySelector('[data-preview="coming-soon"]');
        if (priceRaw) {
            preview.querySelector('[data-preview="price"]').textContent = '$' + parseFloat(priceRaw).toFixed(0);
            priceWrap.classList.remove('hidden');
            comingSoon.classList.add('hidden');
        } else {
            priceWrap.classList.add('hidden');
            comingSoon.classList.remove('hidden');
        }

        const badgeWrap = preview.querySelector('[data-preview="badge-wrap"]');
        if (badge) {
            preview.querySelector('[data-preview="badge"]').textContent = badge;
            badgeWrap.classList.remove('hidden');
        } else {
            badgeWrap.classList.add('hidden');
        }

        const list = preview.querySelector('[data-preview="features"]');
        list.innerHTML = '';
        features.forEach(function (item) {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2 text-sm text-gray-600';
            li.innerHTML = '<svg class="w-4 h-4 text-teal shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span></span>';
            li.querySelector('span').textContent = item;
            list.appendChild(li);
        });

        const ctaEl = preview.querySelector('[data-preview="cta"]');
        ctaEl.textContent = ctaLabel;
        ctaEl.classList.toggle('bg-gold', isAvailable);
        ctaEl.classList.toggle('text-navy-dark', isAvailable);
        ctaEl.classList.toggle('bg-gray-100', !isAvailable);
        ctaEl.classList.toggle('text-gray-400', !isAvailable);

        preview.classList.toggle('border-gold', isAvailable);
        preview.classList.toggle('border-gray-100', !isAvailable);
    }

    document.querySelectorAll('.care-plan-form').forEach(function (form) {
        updateCarePlanPreview(form);
        form.addEventListener('input', function () { updateCarePlanPreview(form); });
    });
</script>

@endsection
