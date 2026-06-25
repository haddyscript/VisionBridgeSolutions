@extends('layouts.app')

@section('title', 'Book A Consultation – VisionBridge Solutions')

@section('content')

<section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-32" style="background:#080F1C;">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden">

        <div class="px-8 sm:px-10 pt-8 sm:pt-10">
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-navy mb-2">Book A Consultation</h1>
            <p class="text-gray-500 text-sm mb-6">Pick a date and time that works for you, and we'll confirm your consultation within 24 hours.</p>

            @if (session('status') === 'consultation_sent')
                <div class="mb-6 text-sm text-teal-dark bg-teal/10 border border-teal/30 rounded-lg px-4 py-3">
                    Thanks! Your consultation request has been received. We'll be in touch within 24 hours to confirm.
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('consultation.store') }}" id="consultation-form">
            @csrf
            <input type="hidden" name="preferred_at" id="preferred_at" value="{{ old('preferred_at') }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 border-t border-gray-100">

                {{-- Calendar + time slots --}}
                <div class="px-8 sm:px-10 py-8 border-b lg:border-b-0 lg:border-r border-gray-100">

                    <div class="flex items-center justify-between mb-4">
                        <button type="button" id="cal-prev" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-navy transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <p id="cal-month-label" class="font-semibold text-navy text-sm"></p>
                        <button type="button" id="cal-next" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-navy transition-colors">
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
                        <p id="slots-label" class="text-sm font-semibold text-navy mb-3"></p>
                        <div id="slots-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-56 overflow-y-auto pr-1"></div>
                    </div>

                    <p id="slots-empty" class="hidden text-sm text-gray-400 text-center py-6">Select a date to see available times.</p>
                </div>

                {{-- Contact details --}}
                <div class="px-8 sm:px-10 py-8 space-y-5">

                    <div id="selection-summary" class="hidden bg-gold/10 border border-gold/30 rounded-lg px-4 py-3 text-sm font-semibold text-gold-dark"></div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-navy mb-1.5">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-navy mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-semibold text-navy mb-1.5">Phone</label>
                        <div class="flex gap-2">
                            <div class="relative shrink-0">
                                <button type="button" id="phone-country-trigger"
                                        class="w-[5.5rem] h-full rounded-lg border border-gray-300 px-2 py-2.5 text-sm flex items-center justify-between gap-1 bg-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                    <span id="phone-country-display" class="truncate"></span>
                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div id="phone-country-list" class="hidden absolute z-20 mt-1 w-72 bg-white border border-gray-200 rounded-lg shadow-lg">
                                    <div class="p-2 border-b border-gray-100">
                                        <input type="text" id="phone-country-search" placeholder="Search country..."
                                               class="w-full rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                                    </div>
                                    <div class="max-h-56 overflow-y-auto py-1">
                                        @foreach (config('dial_codes') as $country)
                                            <button type="button" class="phone-country-option w-full text-left px-3 py-2 text-sm hover:bg-gold/10 flex items-center gap-2.5"
                                                    data-dial="{{ $country['dial'] }}" data-flag="{{ $country['flag'] }}" data-name="{{ strtolower($country['name']) }}" data-full-name="{{ $country['name'] }}">
                                                <span>{{ $country['flag'] }}</span>
                                                <span class="text-gray-400 w-12 shrink-0">{{ $country['dial'] }}</span>
                                                <span class="text-navy truncate">{{ $country['name'] }}</span>
                                            </button>
                                        @endforeach
                                        <p id="phone-country-empty" class="hidden text-sm text-gray-400 text-center py-4">No countries found.</p>
                                    </div>
                                </div>
                            </div>
                            <input type="tel" id="phone_number" placeholder="Phone number"
                                   class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
                        <input type="hidden" name="country" id="country" value="{{ old('country') }}">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-navy mb-1.5">Message</label>
                        <textarea name="message" id="message" rows="3"
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-gold w-full text-center">
                        Book Consultation
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Notice modal, used instead of the native browser alert() --}}
<div id="notice-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div id="notice-modal-backdrop" class="absolute inset-0 bg-navy-dark/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div id="notice-modal-panel" class="relative w-full max-w-sm transform scale-95 opacity-0 transition-all duration-200">
        <div class="bg-white rounded-2xl shadow-2xl p-6">
            <div class="w-11 h-11 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1 1 0 003 19.5h18a1 1 0 00.86-1.46L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
            </div>
            <h2 class="font-display text-lg font-bold text-navy mb-2">One more thing</h2>
            <p id="notice-modal-message" class="text-sm text-gray-500 mb-6"></p>
            <div class="flex justify-end">
                <button type="button" id="notice-modal-ok" class="px-4 py-2.5 rounded-lg text-sm font-semibold bg-gold hover:bg-gold-dark text-navy-dark transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
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

    const monthLabel   = document.getElementById('cal-month-label');
    const calGrid      = document.getElementById('cal-grid');
    const prevBtn       = document.getElementById('cal-prev');
    const nextBtn       = document.getElementById('cal-next');
    const slotsWrap     = document.getElementById('slots-wrap');
    const slotsLabel    = document.getElementById('slots-label');
    const slotsGrid     = document.getElementById('slots-grid');
    const slotsEmpty    = document.getElementById('slots-empty');
    const preferredAt   = document.getElementById('preferred_at');
    const summary       = document.getElementById('selection-summary');

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
                btn.className += ' text-navy hover:bg-gold/10';
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
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = formatTime(hour, minute);
                btn.className = 'text-sm font-medium rounded-lg border px-3 py-2 transition-colors ' +
                    (selectedTime === value
                        ? 'bg-gold border-gold text-navy font-bold'
                        : 'border-gray-300 text-navy hover:border-gold hover:bg-gold/10');

                btn.addEventListener('click', () => selectTime(value));
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

    document.getElementById('consultation-form').addEventListener('submit', (e) => {
        if (!selectedDate || !selectedTime) {
            e.preventDefault();
            window.showNotice('Please select a date and time for your consultation.');
        }
    });

    renderCalendar();
    renderSlots();
})();
</script>
@endsection
