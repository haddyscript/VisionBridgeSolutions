{{--
    $id: element id for this preview card (must be unique on the page)
    $plan: the CarePlan being previewed, or null for the "Add new plan" placeholder
--}}
<div id="{{ $id }}" class="care-plan-preview rounded-2xl overflow-hidden border-2 {{ ($plan?->is_available ?? true) ? 'border-gold' : 'border-gray-100' }} sticky top-4">
    <div data-preview="badge-wrap" class="bg-gold text-navy px-5 py-3 text-center {{ $plan?->badge ? '' : 'hidden' }}">
        <span data-preview="badge" class="text-[0.65rem] font-bold tracking-widest uppercase">{{ $plan?->badge }}</span>
    </div>
    <div class="bg-white p-6 text-center">
        <h4 data-preview="name" class="font-bold text-navy text-lg mb-1">{{ $plan?->name ?? 'Plan Name' }}</h4>
        <p data-preview="tagline" class="text-xs text-gray-400 mb-3">{{ $plan?->tagline }}</p>
        <div class="my-4">
            <div data-preview="price-wrap" class="{{ $plan?->formattedPrice() ? '' : 'hidden' }}">
                <span data-preview="price" class="text-3xl font-bold text-navy">{{ $plan?->formattedPrice() }}</span>
                <span class="text-gray-400 text-xs">/mo</span>
            </div>
            <span data-preview="coming-soon" class="text-xl font-bold text-gray-300 {{ $plan?->formattedPrice() ? 'hidden' : '' }}">Coming Soon</span>
        </div>
        <p data-preview="description" class="text-xs text-gray-500 mb-4">{{ $plan?->description }}</p>
        <ul data-preview="features" class="text-left space-y-2 mb-6">
            @foreach ($plan?->features ?? [] as $item)
                <li class="flex items-start gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>
                        <span class="font-semibold text-navy block">{{ $item['title'] ?? $item }}</span>
                        @if (!empty($item['description']))
                            <span class="text-xs text-gray-400 block">{{ $item['description'] }}</span>
                        @endif
                    </span>
                </li>
            @endforeach
        </ul>
        <span data-preview="cta" class="block w-full text-center font-semibold px-4 py-2.5 rounded-lg text-sm {{ ($plan?->is_available ?? true) ? 'bg-gold text-navy-dark' : 'bg-gray-100 text-gray-400' }}">{{ $plan?->cta_label ?? 'Get Started' }}</span>
        <p class="text-xs text-gray-400 mt-3">Response Time: <span data-preview="response-time">{{ $plan?->response_time }}</span></p>
    </div>
</div>
