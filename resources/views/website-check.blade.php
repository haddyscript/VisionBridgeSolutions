@extends('layouts.app')

@section('title', 'Free Website Check – VisionBridge Solutions')
@section('description', 'Is your website secure, fast, mobile-friendly, and easy to find on Google? Get a free, plain-language website check in seconds from VisionBridge Solutions.')

@section('content')

<style>
    #website-check {
        background: #0A0A0A;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding-top: clamp(120px, 14vw, 160px);
        padding-bottom: 90px;
    }
    #website-check::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(ellipse 70% 55% at 50% 0%, rgba(201,168,76,.10), transparent 65%);
    }
    .wc-panel {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.10);
        border-radius: 18px;
    }
    .wc-input {
        width: 100%;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 12px;
        color: #fff;
        padding: 14px 16px;
        font-size: 16px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .wc-input::placeholder { color: rgba(255,255,255,.38); }
    .wc-input:focus { border-color: #C9A84C; box-shadow: 0 0 0 3px rgba(201,168,76,.22); }
    .wc-btn {
        background: #C9A84C;
        color: #111D33;
        font-weight: 700;
        border-radius: 12px;
        padding: 14px 26px;
        white-space: nowrap;
        transition: background .2s, opacity .2s;
    }
    .wc-btn:hover { background: #DFC06A; }
    .wc-btn:disabled { opacity: .6; cursor: wait; }
    .wc-ring { transform: rotate(-90deg); }
    .wc-ring circle { transition: stroke-dashoffset 1.2s ease; }
    .wc-status { width: 26px; height: 26px; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; flex-shrink: 0; }
    .wc-status.pass { background: rgba(42,157,143,.16); color: #3DBFB0; }
    .wc-status.warn { background: rgba(201,168,76,.16); color: #DFC06A; }
    .wc-status.fail { background: rgba(220,38,38,.16); color: #F87171; }
    .wc-spinner { width: 18px; height: 18px; border: 2px solid rgba(17,29,51,.3); border-top-color: #111D33; border-radius: 999px; animation: wc-spin .8s linear infinite; display: inline-block; vertical-align: -3px; }
    @keyframes wc-spin { to { transform: rotate(360deg); } }
    .wc-step { color: rgba(255,255,255,.45); transition: color .3s; }
    .wc-step.active { color: #DFC06A; }
    .wc-step.done { color: #3DBFB0; }
    @media (prefers-reduced-motion: reduce) {
        .wc-ring circle { transition: none; }
        .wc-spinner { animation-duration: 2s; }
    }
</style>

<section id="website-check">
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6" style="z-index:1;">

        <div class="text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-[0.18em] px-4 py-2 rounded-full" style="color:#DFC06A; background:rgba(201,168,76,.10); border:1px solid rgba(201,168,76,.25);">Free Website Check</span>
            <h1 class="font-display mt-6 text-4xl sm:text-5xl font-extrabold leading-tight text-white">
                How Healthy Is <span style="color:#C9A84C;">Your Website?</span>
            </h1>
            <p class="mt-5 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto" style="color:rgba(255,255,255,.62);">
                Enter your website address and we'll check its security, speed, phone-friendliness, and how it shows up on Google — explained in plain language, in about 15 seconds.
            </p>
        </div>

        {{-- Step 1: enter URL --}}
        <form id="wc-form" class="wc-panel mt-10 p-4 sm:p-5 flex flex-col sm:flex-row gap-3" novalidate>
            <label for="wc-url" class="sr-only">Your website address</label>
            <input id="wc-url" name="url" type="text" inputmode="url" autocomplete="url" class="wc-input" placeholder="yourchurch.org" required>
            <button id="wc-run" type="submit" class="wc-btn">Check My Website</button>
        </form>
        <p id="wc-error" class="hidden mt-3 text-sm text-center" style="color:#F87171;" role="alert"></p>

        {{-- Progress --}}
        <div id="wc-progress" class="hidden wc-panel mt-6 p-6 text-sm space-y-2.5" aria-live="polite">
            <p class="wc-step" data-step="0">● Connecting to your website…</p>
            <p class="wc-step" data-step="1">● Checking security and certificate…</p>
            <p class="wc-step" data-step="2">● Measuring speed…</p>
            <p class="wc-step" data-step="3">● Checking phones and Google visibility…</p>
        </div>

        {{-- Step 2: score + teaser + unlock --}}
        <div id="wc-result" class="hidden mt-8 space-y-6">
            <div class="wc-panel p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
                <div class="relative shrink-0" style="width:140px;height:140px;">
                    <svg class="wc-ring" width="140" height="140" viewBox="0 0 140 140" aria-hidden="true">
                        <circle cx="70" cy="70" r="60" fill="none" stroke="rgba(255,255,255,.08)" stroke-width="12"/>
                        <circle id="wc-ring-fill" cx="70" cy="70" r="60" fill="none" stroke="#C9A84C" stroke-width="12" stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="377"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span id="wc-score" class="text-4xl font-extrabold text-white">0</span>
                        <span class="text-xs" style="color:rgba(255,255,255,.45);">out of 100</span>
                    </div>
                </div>
                <div class="text-center sm:text-left">
                    <p class="text-sm" style="color:rgba(255,255,255,.55);">Results for <span id="wc-host" class="font-semibold text-white"></span></p>
                    <p id="wc-grade" class="text-2xl font-bold mt-1"></p>
                    <p id="wc-summary" class="text-sm mt-2" style="color:rgba(255,255,255,.62);"></p>
                </div>
            </div>

            <div id="wc-top" class="wc-panel p-6">
                <h2 class="text-base font-bold text-white mb-4">Top issues we found</h2>
                <div id="wc-top-list" class="space-y-4"></div>
            </div>

            <form id="wc-unlock" class="wc-panel p-6 sm:p-8" novalidate>
                <h2 class="text-lg font-bold text-white">Get your full report — free</h2>
                <p class="text-sm mt-1 mb-5" style="color:rgba(255,255,255,.58);">See every check with what it means and why it matters, and we'll email you a copy to share with your team or board.</p>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label for="wc-name" class="sr-only">Your name</label>
                        <input id="wc-name" name="name" type="text" autocomplete="name" class="wc-input" placeholder="Your name *" required>
                    </div>
                    <div>
                        <label for="wc-email" class="sr-only">Email</label>
                        <input id="wc-email" name="email" type="email" autocomplete="email" class="wc-input" placeholder="Email *" required>
                    </div>
                    <div>
                        <label for="wc-org" class="sr-only">Organization</label>
                        <input id="wc-org" name="organization" type="text" autocomplete="organization" class="wc-input" placeholder="Church / organization (optional)">
                    </div>
                    <div>
                        <label for="wc-phone" class="sr-only">Phone</label>
                        <input id="wc-phone" name="phone" type="tel" autocomplete="tel" class="wc-input" placeholder="Phone (optional)">
                    </div>
                </div>
                <p id="wc-unlock-error" class="hidden mt-3 text-sm" style="color:#F87171;" role="alert"></p>
                <button id="wc-unlock-btn" type="submit" class="wc-btn w-full mt-5">Show My Full Report</button>
                <p class="text-xs mt-3 text-center" style="color:rgba(255,255,255,.38);">No spam. We'll only use this to send your report and follow up about it.</p>
            </form>

            {{-- Step 3: full report --}}
            <div id="wc-full" class="hidden space-y-6"></div>

            <div id="wc-cta" class="hidden wc-panel p-6 sm:p-8 text-center">
                <p class="text-sm font-semibold" style="color:#3DBFB0;">✓ Your report is on its way to your inbox.</p>
                <h2 class="text-xl font-bold text-white mt-3">Want help fixing these?</h2>
                <p class="text-sm mt-2 max-w-lg mx-auto" style="color:rgba(255,255,255,.6);">We fix, redesign, and look after websites for churches, ministries, nonprofits, and businesses. Let's walk through your report together.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center mt-6">
                    <a href="{{ route('consultation.create') }}" class="wc-btn inline-block">Book A Consultation</a>
                    <a href="{{ route('website-redesign') }}" class="inline-block rounded-xl px-6 py-3.5 font-semibold text-white" style="border:1px solid rgba(255,255,255,.25);">About Website Redesigns</a>
                </div>
                <button type="button" id="wc-again" class="mt-5 text-sm underline" style="color:rgba(255,255,255,.5);">Check another website</button>
            </div>
        </div>

    </div>
</section>

@endsection

@section('scripts')
<script>
(function () {
    const csrf = @json(csrf_token());
    const runUrl = @json(route('website-check.run'));
    const unlockUrl = @json(route('website-check.unlock', ['websiteCheck' => '__TOKEN__']));
    const $ = (id) => document.getElementById(id);
    const icons = { pass: '✓', warn: '!', fail: '✕' };
    let token = null;
    let stepTimer = null;

    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s ?? '';
        return d.innerHTML;
    }

    async function post(url, body) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify(body),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const firstError = data.errors ? Object.values(data.errors)[0][0] : null;
            throw new Error(res.status === 429
                ? "You've run a lot of checks — please wait a minute and try again."
                : (firstError || data.message || 'Something went wrong. Please try again.'));
        }
        return data;
    }

    function showProgress(on) {
        const steps = document.querySelectorAll('.wc-step');
        clearInterval(stepTimer);
        $('wc-progress').classList.toggle('hidden', !on);
        steps.forEach(s => s.className = 'wc-step');
        if (!on) return;
        let i = 0;
        steps[0].classList.add('active');
        stepTimer = setInterval(() => {
            if (i >= steps.length - 1) return;
            steps[i].className = 'wc-step done';
            i++;
            steps[i].classList.add('active');
        }, 3500);
    }

    function issueRow(c) {
        return `<div class="flex gap-3">
            <span class="wc-status ${c.status}" aria-label="${c.status}">${icons[c.status]}</span>
            <div><p class="text-sm font-semibold text-white">${esc(c.label)}</p>
            <p class="text-sm mt-0.5" style="color:rgba(255,255,255,.62);">${esc(c.detail)}</p>
            ${c.why && c.status !== 'pass' ? `<p class="text-xs mt-1" style="color:rgba(255,255,255,.42);">Why it matters: ${esc(c.why)}</p>` : ''}</div>
        </div>`;
    }

    $('wc-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const url = $('wc-url').value.trim();
        $('wc-error').classList.add('hidden');
        if (!url) {
            $('wc-error').textContent = 'Please enter your website address.';
            $('wc-error').classList.remove('hidden');
            return;
        }

        $('wc-run').disabled = true;
        $('wc-run').innerHTML = '<span class="wc-spinner"></span> Checking…';
        $('wc-result').classList.add('hidden');
        showProgress(true);

        try {
            const data = await post(runUrl, { url });
            token = data.token;

            $('wc-host').textContent = data.host;
            $('wc-grade').textContent = data.grade.label;
            $('wc-grade').style.color = data.grade.color;
            $('wc-summary').textContent = data.issue_count === 0
                ? `All ${data.check_count} checks passed. Nice work!`
                : `${data.issue_count} of ${data.check_count} checks need attention.`;
            $('wc-top-list').innerHTML = data.top_issues.length
                ? data.top_issues.map(issueRow).join('')
                : '<p class="text-sm" style="color:rgba(255,255,255,.62);">No issues found on the basics — unlock the full report to see every check.</p>';

            $('wc-full').classList.add('hidden');
            $('wc-cta').classList.add('hidden');
            $('wc-unlock').classList.remove('hidden');
            $('wc-top').classList.remove('hidden');
            $('wc-result').classList.remove('hidden');

            const ring = $('wc-ring-fill');
            ring.style.stroke = data.grade.color;
            ring.style.strokeDashoffset = 377;
            requestAnimationFrame(() => requestAnimationFrame(() => {
                ring.style.strokeDashoffset = 377 - (377 * data.score / 100);
            }));
            let n = 0;
            const counter = setInterval(() => {
                n = Math.min(data.score, n + Math.max(1, Math.round(data.score / 30)));
                $('wc-score').textContent = n;
                if (n >= data.score) clearInterval(counter);
            }, 30);

            $('wc-result').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (err) {
            $('wc-error').textContent = err.message;
            $('wc-error').classList.remove('hidden');
        } finally {
            showProgress(false);
            $('wc-run').disabled = false;
            $('wc-run').textContent = 'Check My Website';
        }
    });

    $('wc-unlock').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        $('wc-unlock-error').classList.add('hidden');
        // Read fields by id — form.name is the <form>'s own name attribute, not the input.
        const fields = { name: $('wc-name').value.trim(), email: $('wc-email').value.trim(), organization: $('wc-org').value.trim(), phone: $('wc-phone').value.trim() };
        if (!fields.name || !fields.email) {
            $('wc-unlock-error').textContent = 'Please enter your name and email.';
            $('wc-unlock-error').classList.remove('hidden');
            return;
        }

        const btn = $('wc-unlock-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="wc-spinner"></span> Preparing your report…';

        try {
            const data = await post(unlockUrl.replace('__TOKEN__', token), fields);

            $('wc-full').innerHTML = Object.entries(data.categories).map(([key, label]) => {
                const items = data.checks.filter(c => c.category === key);
                if (!items.length) return '';
                return `<div class="wc-panel p-6"><h2 class="text-base font-bold text-white mb-4">${esc(label)}</h2>
                    <div class="space-y-4">${items.map(issueRow).join('')}</div></div>`;
            }).join('');

            form.classList.add('hidden');
            $('wc-top').classList.add('hidden');
            $('wc-full').classList.remove('hidden');
            $('wc-cta').classList.remove('hidden');
            $('wc-full').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (err) {
            $('wc-unlock-error').textContent = err.message;
            $('wc-unlock-error').classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Show My Full Report';
        }
    });

    $('wc-again').addEventListener('click', () => {
        $('wc-result').classList.add('hidden');
        $('wc-url').value = '';
        $('wc-url').focus();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();
</script>
@endsection
