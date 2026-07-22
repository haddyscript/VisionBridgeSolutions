{{--
    Reusable custom-styled dropdown — replaces a native <select> (unstyleable
    browser option-list chrome) with a themed button + panel. Shared across
    admin pages (originally built for Project Requests, also used on
    Developers) — the driver script below is wrapped in @once so including
    this partial multiple times per page only wires it up a single time.

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
            class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm text-left focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
        <span id="{{ $domId }}-label" class="flex items-center gap-2 min-w-0 truncate {{ $selectedOption ? 'text-navy dark:text-white' : 'text-gray-400' }}">
            @if ($selectedOption && ($selectedOption['dot'] ?? null))
                <span class="w-2 h-2 rounded-full shrink-0 {{ $selectedOption['dot'] }}"></span>
            @endif
            <span id="{{ $domId }}-label-text">{{ $selectedOption['label'] ?? ($placeholder ?? 'Select one...') }}</span>
        </span>
        <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- position: fixed + JS-computed coordinates (see the driver script below), not
         CSS absolute — this dropdown is used inside scrollable containers (e.g. the
         Developers page's Unassigned list), and an absolutely-positioned menu gets
         clipped by an ancestor's overflow:auto instead of floating above everything. --}}
    <div id="{{ $domId }}-menu" class="hidden fixed z-40 bg-white dark:bg-navy border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-1 max-h-64 overflow-y-auto" role="listbox">
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

@once
<script>
(function () {
    function wireUp(wrap) {
        const domId = wrap.id.replace(/-wrap$/, '');
        const toggle = document.getElementById(domId + '-toggle');
        const menu = document.getElementById(domId + '-menu');
        const chevron = toggle?.querySelector('svg');
        const hiddenInput = document.getElementById(domId + '-input');
        const label = document.getElementById(domId + '-label');
        const labelText = document.getElementById(domId + '-label-text');
        const autoSubmit = wrap.dataset.autoSubmit === '1';
        if (!toggle || !menu || !hiddenInput || !label || !labelText) return;
        if (wrap.dataset.customSelectWired) return;
        wrap.dataset.customSelectWired = '1';

        function closeMenu() {
            menu.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = '';
        }

        function openMenu() {
            document.querySelectorAll('[data-custom-select]').forEach(function (otherWrap) {
                if (otherWrap === wrap) return;
                document.getElementById(otherWrap.id.replace(/-wrap$/, '') + '-menu')?.classList.add('hidden');
            });

            // Fixed positioning is computed from the toggle button's viewport
            // coordinates, not CSS, so the menu escapes any scrollable/clipped
            // ancestor. Flips above the toggle if there isn't room below.
            const rect = toggle.getBoundingClientRect();
            menu.style.left = rect.left + 'px';
            menu.style.width = rect.width + 'px';
            menu.classList.remove('hidden');

            const menuHeight = menu.offsetHeight;
            const spaceBelow = window.innerHeight - rect.bottom;
            if (spaceBelow < menuHeight + 12 && rect.top > menuHeight + 12) {
                menu.style.top = (rect.top - menuHeight - 6) + 'px';
            } else {
                menu.style.top = (rect.bottom + 6) + 'px';
            }

            toggle.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.contains('hidden') ? openMenu() : closeMenu();
        });

        menu.querySelectorAll('[data-select-option]').forEach(function (option) {
            option.addEventListener('click', function () {
                const value = option.dataset.selectOption;
                const optLabel = option.dataset.selectLabel;
                const dot = option.dataset.selectDot || '';

                hiddenInput.value = value;
                labelText.textContent = optLabel;
                label.classList.remove('text-gray-400');
                label.classList.add('text-navy', 'dark:text-white');

                let dotEl = label.querySelector('span.w-2');
                if (dot) {
                    if (!dotEl) {
                        dotEl = document.createElement('span');
                        label.insertBefore(dotEl, labelText);
                    }
                    dotEl.className = 'w-2 h-2 rounded-full shrink-0 ' + dot;
                } else if (dotEl) {
                    dotEl.remove();
                }

                menu.querySelectorAll('[data-select-option]').forEach(function (opt) {
                    const isSelected = opt === option;
                    opt.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                    opt.classList.toggle('text-gold-dark', isSelected);
                    opt.classList.toggle('font-semibold', isSelected);
                    opt.classList.toggle('text-gray-700', !isSelected);
                    opt.classList.toggle('dark:text-gray-300', !isSelected);
                    const check = opt.querySelector('svg');
                    if (check) check.classList.toggle('invisible', !isSelected);
                });

                closeMenu();

                // Fire a real 'change' event on the hidden input so any
                // page-specific JS listening for it (e.g. a client-side
                // filter) works exactly as it would with a native <select>.
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

                if (autoSubmit && hiddenInput.form) {
                    hiddenInput.form.requestSubmit();
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) closeMenu();
        });

        // Fixed-position coordinates are computed once, on open — closing on
        // scroll (capture: true, since scrolling inside a nested scrollable
        // container like the Unassigned list doesn't bubble a 'scroll' event
        // to document otherwise) avoids the menu staying frozen in place
        // while the button it's anchored to moves out from under it.
        document.addEventListener('scroll', function () {
            if (!menu.classList.contains('hidden')) closeMenu();
        }, true);
    }

    // Blade's @@once only emits this <script> once, at the position of the
    // first custom-dropdown partial included on the page — any dropdowns that
    // appear later in the HTML (e.g. inside a loop further down the page)
    // haven't been parsed into the DOM yet at that point. Deferring to
    // DOMContentLoaded guarantees every instance exists before we query for
    // them, regardless of where in the page the first one happened to sit.
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-custom-select]').forEach(wireUp);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id$="-menu"]').forEach(function (m) { m.classList.add('hidden'); });
        }
    });
})();
</script>
@endonce
