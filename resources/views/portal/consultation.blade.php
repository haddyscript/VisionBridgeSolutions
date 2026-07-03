@extends('layouts.portal')

@section('title', 'Book a Consultation – Client Portal')
@section('page-title', 'Book a Consultation')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Pick a date and time that works for you, and we'll confirm your consultation within 24 hours.</p>

<div id="form-banner">
    @if (session('status') === 'consultation_sent')
        <div class="mb-6 text-sm text-teal-dark bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
            Thanks! Your consultation request has been received. We'll be in touch within 24 hours to confirm.
        </div>
    @endif
    @if (! $hasUploadedFile)
        <div class="mb-6 text-sm text-gold-dark bg-gold/10 border border-gold/30 rounded-lg px-4 py-3">
            Please upload at least one project file (image, video, logo, document, or marketing material) in
            <a href="{{ route('portal.category', 'image') }}" class="font-semibold underline">Project Files</a>
            before booking a consultation.
        </div>
    @endif
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <form method="POST" action="{{ route('portal.consultation.store') }}" id="consultation-form">
        @csrf
        <input type="hidden" name="preferred_at" id="preferred_at" value="{{ old('preferred_at') }}">
        <input type="hidden" name="timezone" id="timezone" value="{{ old('timezone') }}">

        <div id="consultation-form-grid" class="grid grid-cols-1 lg:grid-cols-2 gap-0">

            {{-- Calendar + time slots --}}
            <div class="px-6 sm:px-8 py-8 border-b lg:border-b-0 lg:border-r border-gray-100 dark:border-gray-700">

                <div class="flex items-center justify-between mb-4">
                    <button type="button" id="cal-prev" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <p id="cal-month-label" class="font-semibold text-navy dark:text-white text-sm"></p>
                    <button type="button" id="cal-next" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-navy dark:hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-1 mb-1 text-center">
                    @foreach (['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] as $day)
                        <span class="text-[0.65rem] font-semibold uppercase tracking-wide text-gray-400">{{ $day }}</span>
                    @endforeach
                </div>

                <div id="cal-grid" class="grid grid-cols-7 gap-1 mb-6"></div>

                <div id="slots-wrap" class="hidden">
                    <p id="slots-label" class="text-sm font-semibold text-navy dark:text-white mb-1"></p>
                    <p id="slots-timezone" class="text-xs text-gray-400 mb-3"></p>
                    <div id="slots-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-56 overflow-y-auto pr-1"></div>
                </div>

                <p id="slots-empty" class="hidden text-sm text-gray-500 text-center py-6">Select a date to see available times.</p>
            </div>

            {{-- Contact details --}}
            <div class="px-6 sm:px-8 py-8 space-y-5">

                <div id="selection-summary" class="hidden bg-gold/10 border border-gold/30 rounded-lg px-4 py-3 text-sm font-semibold text-gold-dark"></div>

                <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900 rounded-lg px-4 py-3">
                    <div class="w-9 h-9 rounded-full bg-gold/20 text-gold-dark flex items-center justify-center text-sm font-semibold shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-navy dark:text-white truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-bold text-navy dark:text-white mb-1.5">Phone</label>
                    <div class="flex gap-2">
                        <div class="relative shrink-0">
                            <button type="button" id="phone-country-trigger"
                                    class="w-[5.5rem] h-full rounded-lg border border-gray-300 dark:border-gray-600 px-2 py-2.5 text-sm flex items-center justify-between gap-1 bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                <span id="phone-country-display" class="truncate"></span>
                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div id="phone-country-list" class="hidden absolute z-20 mt-1 w-72 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
                                <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                    <input type="text" id="phone-country-search" placeholder="Search country..."
                                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-1.5 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                </div>
                                <div class="max-h-56 overflow-y-auto py-1">
                                    @foreach (config('dial_codes') as $country)
                                        <button type="button" class="phone-country-option w-full text-left px-3 py-2 text-sm hover:bg-gold/10 flex items-center gap-2.5"
                                                data-dial="{{ $country['dial'] }}" data-flag="{{ $country['flag'] }}" data-name="{{ strtolower($country['name']) }}" data-full-name="{{ $country['name'] }}">
                                            <span>{{ $country['flag'] }}</span>
                                            <span class="text-gray-400 w-12 shrink-0">{{ $country['dial'] }}</span>
                                            <span class="text-navy dark:text-white truncate">{{ $country['name'] }}</span>
                                        </button>
                                    @endforeach
                                    <p id="phone-country-empty" class="hidden text-sm text-gray-400 text-center py-4">No countries found.</p>
                                </div>
                            </div>
                        </div>
                        <input type="tel" id="phone_number" placeholder="Phone number" required
                               class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>
                    <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
                    <input type="hidden" name="country" id="country" value="{{ old('country') }}">
                </div>

                <div>
                    <label for="message" class="block text-sm font-bold text-navy dark:text-white mb-1.5">Message</label>
                    <textarea name="message" id="message" rows="3" required
                              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2.5 text-sm bg-white dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('message') }}</textarea>
                </div>

                <button type="submit" id="consultation-submit-btn" {{ $hasUploadedFile ? '' : 'disabled' }}
                        class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-sm py-3 rounded-lg transition-colors inline-flex items-center justify-center gap-2 disabled:opacity-40 disabled:cursor-not-allowed">
                    <span id="consultation-submit-label">Book Consultation</span>
                </button>
            </div>
        </div>
    </form>
</div>

@php
    $consultationStatusLabels = [
        'new' => 'Pending Confirmation',
        'confirmed' => 'Confirmed',
        'rescheduled' => 'Rescheduled',
        'cancelled' => 'Cancelled',
    ];
    $consultationStatusColors = [
        'new' => 'bg-gold/15 text-gold-dark',
        'confirmed' => 'bg-emerald-100 text-emerald-700',
        'rescheduled' => 'bg-teal/15 text-teal-dark',
        'cancelled' => 'bg-red-100 text-red-600',
    ];
@endphp

<div class="mt-8">
    <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-4">Upcoming Consultations</h2>

    @if ($upcomingConsultations->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 text-sm text-gray-500 dark:text-gray-400">
            You don't have any upcoming consultations booked.
        </div>
    @else
        <div class="space-y-3">
            @foreach ($upcomingConsultations as $consultation)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-navy dark:text-white">
                            {{ $consultation->preferred_at?->format('F j, Y \a\t g:i A') ?? 'Time to be confirmed' }}
                        </p>
                        @if ($consultation->message)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $consultation->message }}</p>
                        @endif
                        @if ($consultation->meeting_link)
                            <a href="{{ $consultation->meeting_link }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-gold-dark hover:underline mt-2">
                                Join Meeting Link
                            </a>
                        @endif
                    </div>
                    <span class="shrink-0 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $consultationStatusColors[$consultation->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $consultationStatusLabels[$consultation->status] ?? $consultation->status }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="mt-8">
    <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-4">Consultation History</h2>

    @if ($pastConsultations->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 text-sm text-gray-500 dark:text-gray-400">
            No past or canceled consultations yet.
        </div>
    @else
        <div class="space-y-3">
            @foreach ($pastConsultations as $consultation)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 flex items-start justify-between gap-4 opacity-80">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-navy dark:text-white">
                            {{ $consultation->preferred_at?->format('F j, Y \a\t g:i A') ?? 'No time selected' }}
                        </p>
                        @if ($consultation->message)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $consultation->message }}</p>
                        @endif
                    </div>
                    <span class="shrink-0 text-xs font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full {{ $consultationStatusColors[$consultation->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $consultationStatusLabels[$consultation->status] ?? $consultation->status }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Notice modal, used instead of the native browser alert() --}}
<div id="notice-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="notice-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div id="notice-modal-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6">
            <div class="w-11 h-11 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1 1 0 003 19.5h18a1 1 0 00.86-1.46L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
            </div>
            <h2 class="font-display text-lg font-bold text-navy dark:text-white mb-2">One more thing</h2>
            <p id="notice-modal-message" class="text-base font-medium text-gray-700 dark:text-gray-300 mb-6"></p>
            <div class="flex justify-end">
                <button type="button" id="notice-modal-ok" class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-gold hover:bg-gold-dark text-navy-dark transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const noticeModal   = document.getElementById('notice-modal');
    const noticeBackdrop = document.getElementById('notice-modal-backdrop');
    const noticePanel   = document.getElementById('notice-modal-panel');
    const noticeMessage = document.getElementById('notice-modal-message');
    const noticeOk      = document.getElementById('notice-modal-ok');

    window.showNotice = function (message) {
        noticeMessage.textContent = message;
        noticeModal.classList.remove('hidden');
        noticeModal.classList.add('flex');
        requestAnimationFrame(() => {
            noticeBackdrop.classList.remove('opacity-0');
            noticePanel.classList.remove('scale-95', 'opacity-0');
        });
    };

    function closeNotice() {
        noticeBackdrop.classList.add('opacity-0');
        noticePanel.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            noticeModal.classList.add('hidden');
            noticeModal.classList.remove('flex');
        }, 200);
    }

    noticeOk.addEventListener('click', closeNotice);
    noticeBackdrop.addEventListener('click', closeNotice);

    const phoneNumber      = document.getElementById('phone_number');
    const phoneHidden      = document.getElementById('phone');
    const countryHidden    = document.getElementById('country');
    const countryTrigger   = document.getElementById('phone-country-trigger');
    const countryDisplay   = document.getElementById('phone-country-display');
    const countryList      = document.getElementById('phone-country-list');
    const countryOptions   = document.querySelectorAll('.phone-country-option');
    const countrySearch    = document.getElementById('phone-country-search');
    const countryEmpty     = document.getElementById('phone-country-empty');

    let selectedDial = '+1';
    let selectedFlag = '🇺🇸';
    let selectedName = 'United States';

    function renderCountryDisplay() {
        countryDisplay.textContent = selectedFlag + ' ' + selectedDial;
        countryHidden.value = selectedName;
    }

    function selectCountry(dial, flag, name) {
        selectedDial = dial;
        selectedFlag = flag;
        selectedName = name;
        renderCountryDisplay();
        closeCountryList();
        syncPhone();
    }

    function filterCountries() {
        const query = countrySearch.value.trim().toLowerCase();
        let visibleCount = 0;

        countryOptions.forEach((opt) => {
            const matches = opt.dataset.name.includes(query) || opt.dataset.dial.includes(query);
            opt.classList.toggle('hidden', !matches);
            if (matches) visibleCount++;
        });

        countryEmpty.classList.toggle('hidden', visibleCount > 0);
    }

    function openCountryList() {
        countryList.classList.remove('hidden');
        countrySearch.value = '';
        filterCountries();
        countrySearch.focus();
    }

    function closeCountryList() {
        countryList.classList.add('hidden');
    }

    countryOptions.forEach((opt) => {
        opt.addEventListener('click', () => selectCountry(opt.dataset.dial, opt.dataset.flag, opt.dataset.fullName));
    });

    countrySearch.addEventListener('input', filterCountries);
    countrySearch.addEventListener('click', (e) => e.stopPropagation());

    countryTrigger.addEventListener('click', () => {
        countryList.classList.contains('hidden') ? openCountryList() : closeCountryList();
    });

    document.addEventListener('click', (e) => {
        if (!countryTrigger.contains(e.target) && !countryList.contains(e.target)) {
            closeCountryList();
        }
    });

    const oldPhone = @js(old('phone'));
    if (oldPhone) {
        phoneNumber.value = oldPhone;
    }

    function syncPhone() {
        const num = phoneNumber.value.trim();
        phoneHidden.value = num ? (selectedDial + ' ' + num) : '';
    }

    phoneNumber.addEventListener('input', syncPhone);
    renderCountryDisplay();
    syncPhone();

    const visitorTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.getElementById('timezone').value = visitorTimezone;

    const bookedSlots = new Set(@json($bookedSlots));

    const monthLabel   = document.getElementById('cal-month-label');
    const calGrid      = document.getElementById('cal-grid');
    const prevBtn       = document.getElementById('cal-prev');
    const nextBtn       = document.getElementById('cal-next');
    const slotsWrap     = document.getElementById('slots-wrap');
    const slotsLabel    = document.getElementById('slots-label');
    const slotsTimezone = document.getElementById('slots-timezone');
    const slotsGrid     = document.getElementById('slots-grid');
    const slotsEmpty    = document.getElementById('slots-empty');
    const preferredAt   = document.getElementById('preferred_at');
    const summary       = document.getElementById('selection-summary');

    slotsTimezone.textContent = 'Times shown in your local timezone (' + visitorTimezone + ')';

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let viewYear  = today.getFullYear();
    let viewMonth = today.getMonth();
    let selectedDate = null; // Date at midnight
    let selectedTime = null; // 'HH:MM'

    const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function isPast(date) {
        return date < today;
    }

    function isWeekend(date) {
        const d = date.getDay();
        return d === 0 || d === 6;
    }

    function sameDate(a, b) {
        return a && b && a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
    }

    function renderCalendar() {
        monthLabel.textContent = monthNames[viewMonth] + ' ' + viewYear;
        calGrid.innerHTML = '';

        const firstOfMonth = new Date(viewYear, viewMonth, 1);
        const startOffset = firstOfMonth.getDay();
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

        for (let i = 0; i < startOffset; i++) {
            calGrid.appendChild(document.createElement('div'));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(viewYear, viewMonth, day);
            const disabled = isPast(date) || isWeekend(date);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = day;
            btn.className = 'w-full aspect-square rounded-lg text-sm font-medium transition-colors flex items-center justify-center';

            if (disabled) {
                btn.disabled = true;
                btn.className += ' text-gray-300 cursor-not-allowed';
            } else if (sameDate(date, selectedDate)) {
                btn.className += ' bg-gold text-navy font-bold';
            } else if (sameDate(date, today)) {
                btn.className += ' border border-gold text-gold-dark hover:bg-gold/10';
            } else {
                btn.className += ' text-navy dark:text-white hover:bg-gold/10';
            }

            if (!disabled) {
                btn.addEventListener('click', () => selectDate(date));
            }

            calGrid.appendChild(btn);
        }

        const prevMonthEnd = new Date(viewYear, viewMonth, 0);
        prevBtn.disabled = prevMonthEnd < today && prevMonthEnd.getMonth() !== today.getMonth();
        prevBtn.classList.toggle('opacity-30', prevBtn.disabled);
        prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);
    }

    function formatTime(hour, minute) {
        const period = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 === 0 ? 12 : hour % 12;
        return displayHour + ':' + String(minute).padStart(2, '0') + ' ' + period;
    }

    function slotKey(date) {
        const pad = (n) => String(n).padStart(2, '0');
        return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
    }

    function renderSlots() {
        slotsGrid.innerHTML = '';

        if (!selectedDate) {
            slotsWrap.classList.add('hidden');
            slotsEmpty.classList.remove('hidden');
            return;
        }

        slotsWrap.classList.remove('hidden');
        slotsEmpty.classList.add('hidden');
        slotsLabel.textContent = 'Available times — ' + selectedDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });

        const isToday = sameDate(selectedDate, today);
        const now = new Date();
        let hasSlot = false;

        for (let hour = 9; hour < 17; hour++) {
            for (let minute of [0, 30]) {
                const slotDate = new Date(selectedDate);
                slotDate.setHours(hour, minute, 0, 0);

                if (isToday && slotDate <= now) continue;

                hasSlot = true;
                const value = hour + ':' + String(minute).padStart(2, '0');
                const isBooked = bookedSlots.has(slotKey(slotDate));
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = formatTime(hour, minute) + (isBooked ? ' · Booked' : '');

                if (isBooked) {
                    btn.disabled = true;
                    btn.className = 'text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-700 px-3 py-2 text-gray-300 cursor-not-allowed';
                } else {
                    btn.className = 'text-sm font-medium rounded-lg border px-3 py-2 transition-colors ' +
                        (selectedTime === value
                            ? 'bg-gold border-gold text-navy font-bold'
                            : 'border-gray-300 dark:border-gray-600 text-navy dark:text-white hover:border-gold hover:bg-gold/10');
                    btn.addEventListener('click', () => selectTime(value));
                }

                slotsGrid.appendChild(btn);
            }
        }

        if (!hasSlot) {
            const p = document.createElement('p');
            p.className = 'text-sm text-gray-400 col-span-full text-center py-4';
            p.textContent = 'No times left today. Please pick another date.';
            slotsGrid.appendChild(p);
        }
    }

    function updateHiddenField() {
        if (selectedDate && selectedTime) {
            const [hour, minute] = selectedTime.split(':').map(Number);
            const dt = new Date(selectedDate);
            dt.setHours(hour, minute, 0, 0);

            const pad = (n) => String(n).padStart(2, '0');
            const isoLocal = dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + 'T' + pad(hour) + ':' + pad(minute);
            preferredAt.value = isoLocal;

            summary.classList.remove('hidden');
            summary.textContent = 'Selected: ' + dt.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }) + ' at ' + formatTime(hour, minute);
        } else {
            preferredAt.value = '';
            summary.classList.add('hidden');
        }
    }

    function selectDate(date) {
        selectedDate = date;
        selectedTime = null;
        renderCalendar();
        renderSlots();
        updateHiddenField();
    }

    function selectTime(value) {
        selectedTime = value;
        renderSlots();
        updateHiddenField();
    }

    prevBtn.addEventListener('click', () => {
        viewMonth -= 1;
        if (viewMonth < 0) { viewMonth = 11; viewYear -= 1; }
        renderCalendar();
    });

    nextBtn.addEventListener('click', () => {
        viewMonth += 1;
        if (viewMonth > 11) { viewMonth = 0; viewYear += 1; }
        renderCalendar();
    });

    const consultationForm = document.getElementById('consultation-form');
    const submitBtn        = document.getElementById('consultation-submit-btn');
    const submitLabel      = document.getElementById('consultation-submit-label');
    const formBanner       = document.getElementById('form-banner');
    const formGrid         = document.getElementById('consultation-form-grid');

    function setBanner(type, messages) {
        const styles = type === 'success'
            ? 'text-teal-dark bg-teal/10 border border-teal/30'
            : 'text-red-600 bg-red-50 border border-red-200';

        formBanner.innerHTML = '<div class="mb-6 text-sm ' + styles + ' rounded-lg px-4 py-3">' +
            messages.map((m) => '<p>' + m + '</p>').join('') +
            '</div>';
    }

    consultationForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!selectedDate || !selectedTime) {
            window.showNotice('Please select a date and time for your consultation.');
            return;
        }

        submitBtn.disabled = true;
        submitLabel.textContent = 'Booking...';
        submitBtn.insertAdjacentHTML('afterbegin',
            '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">' +
                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                '<path class="opacity-75" fill="currentColor" d="M12 2a10 10 0 0110 10h-4a6 6 0 00-6-6V2z"></path>' +
            '</svg>');

        fetch(consultationForm.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: new FormData(consultationForm),
        })
            .then((response) => response.json().then((data) => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (ok) {
                    setBanner('success', [data.message]);
                    formGrid.classList.add('hidden');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    const messages = data.errors
                        ? Object.values(data.errors).flat()
                        : [data.message || 'Something went wrong. Please try again.'];
                    setBanner('error', messages);
                }
            })
            .catch(() => {
                setBanner('error', ['Something went wrong. Please try again.']);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitLabel.textContent = 'Book Consultation';
                const spinner = submitBtn.querySelector('svg');
                if (spinner) spinner.remove();
            });
    });

    renderCalendar();
    renderSlots();
})();
</script>

@endsection
