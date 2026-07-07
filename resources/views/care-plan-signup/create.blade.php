@extends('layouts.app')

@section('title', $plan->name.' Signup – VisionBridge Solutions')

@section('content')

<section class="bg-white min-h-screen pt-36 pb-28 px-4">
    <div class="max-w-xl mx-auto">

        <div class="text-center mb-8">
            <p class="text-sm font-bold uppercase tracking-widest text-gold-dark mb-3">Website Care Plan Signup</p>
            <h1 class="font-display text-3xl md:text-4xl font-extrabold text-navy mb-3">{{ $plan->name }}</h1>
            <p class="text-gray-700 text-lg font-medium">
                {{ $plan->formattedPrice() }}/{{ $plan->interval }} &mdash; tell us a bit about your organization and
                you'll be redirected to our secure checkout to complete your subscription.
            </p>
        </div>

        @if (request('checkout') === 'cancel')
            <div class="mb-6 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                Checkout was canceled. No charge was made — you can try again below whenever you're ready.
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <form method="POST" action="{{ route('care-plan-signup.store', $plan) }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Organization / Business Name *</label>
                    <input type="text" name="organization" value="{{ old('organization') }}" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-base font-bold text-navy mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        <p id="email-exists-warning" class="hidden mt-1.5 text-sm font-medium text-red-600">
                            An account already exists with this email. Please <a href="{{ route('login') }}" class="underline">log in</a> instead.
                        </p>
                        <p id="email-typo-warning" class="hidden mt-1.5 text-sm font-medium text-red-600">
                            Did you mean <button type="button" id="email-typo-fix" class="underline"></button>?
                        </p>
                    </div>
                    <div>
                        <label class="block text-base font-bold text-navy mb-1">Phone *</label>
                        <div class="flex gap-2">
                            <div class="relative shrink-0">
                                <button type="button" id="phone-country-trigger"
                                        class="w-[5.5rem] h-full rounded-lg border border-gray-300 px-2 py-2.5 text-base flex items-center justify-between gap-1 bg-white focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
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
                            <input type="tel" id="phone_number" placeholder="Phone number" required
                                   class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                        </div>
                        <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Website Domain</label>
                    <input type="text" name="domain" value="{{ old('domain') }}" placeholder="e.g. yourorganization.org"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">
                </div>

                <div>
                    <label class="block text-base font-bold text-navy mb-1">Anything else we should know?</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" id="submit-button" class="w-full bg-gold hover:bg-gold-dark text-navy font-bold text-lg py-4 rounded-xl transition-colors shadow disabled:opacity-50 disabled:cursor-not-allowed">
                    Continue to Secure Checkout
                </button>

                <p class="text-center text-sm font-medium text-gray-600">
                    You'll be redirected to Stripe to enter payment details and authorize monthly billing.
                    No long-term contract — cancel anytime.
                </p>
            </form>
        </div>
    </div>
</section>

<script>
    (function () {
        const emailInput = document.getElementById('email');
        const existsWarning = document.getElementById('email-exists-warning');
        const typoWarning = document.getElementById('email-typo-warning');
        const typoFixButton = document.getElementById('email-typo-fix');
        const submitButton = document.getElementById('submit-button');

        const KNOWN_DOMAINS = [
            'gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com',
            'icloud.com', 'aol.com', 'live.com', 'msn.com',
        ];

        // Used for the general trailing-digit TLD check below, so it also
        // catches typos on domains that aren't a known free provider, e.g.
        // "johnnyglobalmissions.org12".
        const COMMON_TLDS = [
            'com', 'org', 'net', 'edu', 'gov', 'mil', 'info', 'biz', 'co', 'io', 'us',
        ];

        function levenshtein(a, b) {
            const rows = a.length + 1;
            const cols = b.length + 1;
            const d = Array.from({ length: rows }, () => new Array(cols).fill(0));

            for (let i = 0; i < rows; i++) d[i][0] = i;
            for (let j = 0; j < cols; j++) d[0][j] = j;

            for (let i = 1; i < rows; i++) {
                for (let j = 1; j < cols; j++) {
                    const cost = a[i - 1] === b[j - 1] ? 0 : 1;
                    d[i][j] = Math.min(
                        d[i - 1][j] + 1,
                        d[i][j - 1] + 1,
                        d[i - 1][j - 1] + cost
                    );
                }
            }

            return d[rows - 1][cols - 1];
        }

        function suggestedDomain(domain) {
            if (KNOWN_DOMAINS.includes(domain)) {
                return null;
            }

            for (const known of KNOWN_DOMAINS) {
                // Catches stray digits typed around the provider name, e.g.
                // "123gmail.com" or "gmail123.com".
                const dotIndex = known.lastIndexOf('.');
                const label = known.slice(0, dotIndex);
                const tld = known.slice(dotIndex + 1);
                const pattern = new RegExp(`^[0-9]{1,4}${label}\\.${tld}$|^${label}[0-9]{1,4}\\.${tld}$`);

                if (pattern.test(domain)) {
                    return known;
                }
            }

            // General check, independent of the known-provider list: stray
            // digits stuck onto an otherwise valid TLD, e.g.
            // "johnnyglobalmissions.org12" -> "johnnyglobalmissions.org".
            const lastDot = domain.lastIndexOf('.');
            if (lastDot !== -1) {
                const namePart = domain.slice(0, lastDot);
                const tldMatch = domain.slice(lastDot + 1).match(/^([a-z]+)([0-9]{1,3})$/);

                if (tldMatch && COMMON_TLDS.includes(tldMatch[1])) {
                    return `${namePart}.${tldMatch[1]}`;
                }
            }

            let best = null;
            let bestDistance = Infinity;

            for (const known of KNOWN_DOMAINS) {
                const distance = levenshtein(domain, known);
                if (distance <= 2 && distance < bestDistance) {
                    best = known;
                    bestDistance = distance;
                }
            }

            return best;
        }

        function updateSubmitState() {
            const blocked = !existsWarning.classList.contains('hidden')
                || !typoWarning.classList.contains('hidden');
            submitButton.disabled = blocked;
        }

        function checkTypo() {
            const email = emailInput.value.trim();
            const atIndex = email.lastIndexOf('@');

            if (atIndex === -1 || atIndex === email.length - 1) {
                typoWarning.classList.add('hidden');
                return false;
            }

            const domain = email.slice(atIndex + 1).toLowerCase();
            const suggestion = suggestedDomain(domain);

            if (suggestion) {
                typoFixButton.textContent = email.slice(0, atIndex + 1) + suggestion;
                typoFixButton.dataset.suggestion = email.slice(0, atIndex + 1) + suggestion;
                typoWarning.classList.remove('hidden');
                return true;
            }

            typoWarning.classList.add('hidden');
            return false;
        }

        function checkExists() {
            const email = emailInput.value.trim();

            if (!email || !emailInput.checkValidity()) {
                existsWarning.classList.add('hidden');
                updateSubmitState();
                return;
            }

            fetch(`{{ route('care-plan-signup.check-email') }}?email=${encodeURIComponent(email)}`, {
                headers: { 'Accept': 'application/json' },
            })
                .then(response => response.json())
                .then(data => {
                    existsWarning.classList.toggle('hidden', !data.exists);
                    updateSubmitState();
                })
                .catch(() => {
                    existsWarning.classList.add('hidden');
                    updateSubmitState();
                });
        }

        emailInput.addEventListener('blur', function () {
            const email = emailInput.value.trim();

            if (!email || !emailInput.checkValidity()) {
                typoWarning.classList.add('hidden');
                existsWarning.classList.add('hidden');
                updateSubmitState();
                return;
            }

            const hasTypo = checkTypo();
            updateSubmitState();

            // Skip the exists-check while a likely typo is showing — the
            // domain is probably wrong anyway, no point hitting the network.
            if (!hasTypo) {
                checkExists();
            }
        });

        emailInput.addEventListener('input', function () {
            existsWarning.classList.add('hidden');
            typoWarning.classList.add('hidden');
            updateSubmitState();
        });

        typoFixButton.addEventListener('click', function () {
            emailInput.value = typoFixButton.dataset.suggestion || emailInput.value;
            typoWarning.classList.add('hidden');
            updateSubmitState();
            checkExists();
            emailInput.focus();
        });
    })();

    (function () {
        const phoneNumber    = document.getElementById('phone_number');
        const phoneHidden    = document.getElementById('phone');
        const countryTrigger = document.getElementById('phone-country-trigger');
        const countryDisplay = document.getElementById('phone-country-display');
        const countryList    = document.getElementById('phone-country-list');
        const countryOptions = document.querySelectorAll('.phone-country-option');
        const countrySearch  = document.getElementById('phone-country-search');
        const countryEmpty   = document.getElementById('phone-country-empty');

        let selectedDial = '+1';
        let selectedFlag = '🇺🇸';

        function renderCountryDisplay() {
            countryDisplay.textContent = selectedFlag + ' ' + selectedDial;
        }

        function selectCountry(dial, flag) {
            selectedDial = dial;
            selectedFlag = flag;
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
            opt.addEventListener('click', () => selectCountry(opt.dataset.dial, opt.dataset.flag));
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

        function syncPhone() {
            const num = phoneNumber.value.trim();
            phoneHidden.value = num ? (selectedDial + ' ' + num) : '';
        }

        const oldPhone = @js(old('phone'));
        if (oldPhone) {
            phoneNumber.value = oldPhone;
        }

        phoneNumber.addEventListener('input', syncPhone);
        renderCountryDisplay();
        syncPhone();
    })();
</script>

@endsection
