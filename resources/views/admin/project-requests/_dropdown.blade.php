{{--
    Reusable custom-styled dropdown — replaces a native <select> (unstyleable
    browser option-list chrome) with a themed button + panel, same pattern as
    portal/account.blade.php's Organization Type field. Used 5x on this page,
    hence pulled into a partial instead of duplicated per field.

    Props:
      $name        form field name for the hidden input that actually submits
      $domId       unique id prefix (must be unique per @include on the page)
      $options     array of ['value' => ..., 'label' => ..., 'dot' => optional tailwind bg-* class]
      $selected    current value (nullable)
      $placeholder optional first "no selection" entry, e.g. "Unassigned" / "None"
      $autoSubmit  bool — submit the enclosing form immediately on selection (default false)
      $formId      optional id of a <form> elsewhere in the DOM to submit with
                    (HTML5 form="" attribute) — for fields rendered outside
                    their owning form, e.g. a sidebar field for a form that
                    visually wraps a different column
--}}
@php
    $autoSubmit = $autoSubmit ?? false;
    $placeholder = $placeholder ?? null;
    $formId = $formId ?? null;
    $hasMatch = collect($options)->contains(fn ($o) => (string) $o['value'] === (string) $selected);
    // No placeholder and nothing matches (e.g. status defaults before a first
    // save) → fall back to the first option, mirroring how a native <select>
    // auto-selects its first <option> when none is marked selected.
    $effectiveSelected = $hasMatch ? $selected : ($placeholder !== null ? '' : ($options[0]['value'] ?? ''));
    $selectedOption = collect($options)->first(fn ($o) => (string) $o['value'] === (string) $effectiveSelected);
@endphp
<div class="relative" data-custom-select id="{{ $domId }}-wrap" data-auto-submit="{{ $autoSubmit ? '1' : '0' }}">
    <input type="hidden" name="{{ $name }}" id="{{ $domId }}-input" value="{{ $effectiveSelected }}" @if ($formId) form="{{ $formId }}" @endif>

    <button type="button" id="{{ $domId }}-toggle" aria-haspopup="listbox" aria-expanded="false"
            class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
        <span id="{{ $domId }}-label" class="flex items-center gap-2 min-w-0 truncate {{ $selectedOption ? 'text-navy dark:text-white' : 'text-gray-400' }}">
            @if ($selectedOption && ($selectedOption['dot'] ?? null))
                <span class="w-2 h-2 rounded-full shrink-0 {{ $selectedOption['dot'] }}"></span>
            @endif
            <span id="{{ $domId }}-label-text">{{ $selectedOption['label'] ?? ($placeholder ?? 'Select one…') }}</span>
        </span>
        <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div id="{{ $domId }}-menu" class="hidden absolute z-20 left-0 right-0 mt-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1 max-h-64 overflow-y-auto" role="listbox">
        @if ($placeholder !== null)
            <button type="button" data-select-option="" data-select-label="{{ $placeholder }}" role="option" aria-selected="{{ $effectiveSelected === '' ? 'true' : 'false' }}"
                    class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ $effectiveSelected === '' ? 'text-gold-dark font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                <span>{{ $placeholder }}</span>
                <svg class="w-4 h-4 text-gold-dark shrink-0 {{ $effectiveSelected === '' ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </button>
        @endif
        @foreach ($options as $option)
            <button type="button" data-select-option="{{ $option['value'] }}" data-select-label="{{ $option['label'] }}" data-select-dot="{{ $option['dot'] ?? '' }}" role="option" aria-selected="{{ (string) $effectiveSelected === (string) $option['value'] ? 'true' : 'false' }}"
                    class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm text-left hover:bg-gold/10 transition-colors {{ (string) $effectiveSelected === (string) $option['value'] ? 'text-gold-dark font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                <span class="flex items-center gap-2 min-w-0 truncate">
                    @if ($option['dot'] ?? null)
                        <span class="w-2 h-2 rounded-full shrink-0 {{ $option['dot'] }}"></span>
                    @endif
                    {{ $option['label'] }}
                </span>
                <svg class="w-4 h-4 text-gold-dark shrink-0 {{ (string) $effectiveSelected === (string) $option['value'] ? '' : 'invisible' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </button>
        @endforeach
    </div>
</div>
