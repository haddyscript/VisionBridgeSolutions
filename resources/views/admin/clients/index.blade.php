@extends('layouts.admin')

@section('title', 'Clients – Admin')
@section('page-title', 'Clients')

@section('content')

@php
    $statusColors = [
        'onboarding'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'in_progress' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300',
        'in_review'   => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
        'launched'    => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
        'maintenance' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300',
        'paused'      => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
    ];
    $statusLabels = [
        'onboarding'  => 'Onboarding',
        'in_progress' => 'In Progress',
        'in_review'   => 'In Review',
        'launched'    => 'Launched',
        'maintenance' => 'Care',
        'paused'      => 'Paused',
    ];

    $total     = $clients->count();
    $online    = $clients->filter(fn ($u) => $u->isOnline())->count();
    $verified  = $clients->where('email_verified_at', '!=', null)->count();
    $noProject = $clients->filter(fn ($u) => $u->projects->isEmpty())->count();
@endphp

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-4 flex flex-col justify-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Total Clients</p>
        <p class="text-2xl font-bold leading-tight text-navy dark:text-white">{{ $total }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-4 flex flex-col justify-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Online Now</p>
        <p class="text-2xl font-bold leading-tight text-green-600 dark:text-green-400">{{ $online }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-4 flex flex-col justify-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Verified</p>
        <p class="text-2xl font-bold leading-tight text-navy dark:text-white">{{ $verified }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 px-5 py-4 flex flex-col justify-center">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1.5">No Project Yet</p>
        <p class="text-2xl font-bold leading-tight text-gold">{{ $noProject }}</p>
    </div>
</div>

{{-- Search + Add Client --}}
<div class="mb-4">
    <div class="flex flex-col sm:flex-row gap-2">
        <form method="GET" action="{{ route('admin.clients.index') }}" class="flex flex-1 gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email…"
                class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-800 dark:text-white">
            <button type="submit"
                class="px-4 py-2 bg-navy text-white text-sm font-semibold rounded-lg hover:bg-navy-light transition-colors">
                Search
            </button>
            @if ($search)
                <a href="{{ route('admin.clients.index') }}"
                    class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Clear
                </a>
            @endif
        </form>
        <button type="button" onclick="openAddClientModal()"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gold hover:bg-gold-dark text-navy text-sm font-semibold rounded-lg transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Client
        </button>
    </div>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    @if ($clients->isEmpty())
        <div class="px-6 py-16 text-center">
            <p class="text-gray-400 dark:text-gray-500 text-sm">
                {{ $search ? 'No clients match your search.' : 'No clients yet.' }}
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-widest text-gray-400">Client</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-widest text-gray-400 hidden sm:table-cell">Phone</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-widest text-gray-400 hidden md:table-cell">Project</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-widest text-gray-400 hidden lg:table-cell">Joined</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($clients as $client)
                        @php $project = $client->projects->first(); @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">

                            {{-- Client identity --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="relative shrink-0">
                                        <div class="w-9 h-9 rounded-full bg-navy/10 dark:bg-white/10 text-navy dark:text-white flex items-center justify-center text-sm font-bold">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>
                                        @if ($client->isOnline())
                                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-navy dark:text-white truncate">{{ $client->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $client->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Phone --}}
                            <td class="px-5 py-4 hidden sm:table-cell">
                                <span class="text-gray-500 dark:text-gray-400">{{ $client->phone ?: '—' }}</span>
                            </td>

                            {{-- Project --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                @if ($project)
                                    <p class="font-medium text-navy dark:text-white truncate max-w-[180px]">{{ $project->name }}</p>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">No project</span>
                                @endif
                            </td>

                            {{-- Joined --}}
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <span class="text-gray-500 dark:text-gray-400">{{ $client->created_at->format('M j, Y') }}</span>
                            </td>

                            {{-- Status badges --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @if (! $client->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                            Unverified
                                        </span>
                                    @endif
                                    @if ($project)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($project)
                                        <a href="{{ route('admin.projects.show', $project) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-navy text-white text-xs font-semibold hover:bg-navy-light transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View Project
                                        </a>
                                    @endif
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.outside="open = false"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" x-transition
                                            class="fixed w-52 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg z-50 py-1">
                                            <button type="button"
                                                onclick="openEditModal({{ $client->id }}, '{{ addslashes($client->name) }}', '{{ addslashes($client->email) }}', '{{ addslashes($client->phone ?? '') }}')"
                                                class="w-full text-left px-3 py-2 text-xs text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                Edit Client Info
                                            </button>
                                            <p class="px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-400 border-t border-gray-100 dark:border-gray-700 mt-1">Account Info</p>
                                            <div class="px-3 py-2 border-t border-gray-100 dark:border-gray-700 space-y-1">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    <span class="font-medium text-navy dark:text-white">Verified:</span>
                                                    {{ $client->email_verified_at ? $client->email_verified_at->format('M j, Y') : 'Not yet' }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    <span class="font-medium text-navy dark:text-white">Last seen:</span>
                                                    {{ $client->last_seen_at ? $client->last_seen_at->diffForHumans() : 'Never' }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    <span class="font-medium text-navy dark:text-white">Phone:</span>
                                                    {{ $client->phone ?: 'Not provided' }}
                                                </p>
                                                @if ($client->referredBy)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        <span class="font-medium text-navy dark:text-white">Referred by:</span>
                                                        {{ $client->referredBy->name }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    <span class="font-medium text-navy dark:text-white">Referrals:</span>
                                                    {{ $client->referrals_count ?? $client->referrals()->count() }}
                                                </p>
                                            </div>
                                            <div class="border-t border-gray-100 dark:border-gray-700 pt-1 mt-1">
                                                <form method="POST" action="{{ route('admin.clients.impersonate', $client) }}"
                                                    onsubmit="return confirm('Log in as {{ addslashes($client->name) }}? You\'ll see the portal exactly as they do until you click \'Return to Admin\'. This is logged.')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full text-left px-3 py-2 text-xs text-navy dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                        Log In as Client
                                                    </button>
                                                </form>
                                            </div>
                                            @if ($project)
                                                <div class="border-t border-gray-100 dark:border-gray-700 pt-1 mt-1">
                                                    <form method="POST" action="{{ route('admin.projects.reset-client-password', $project) }}"
                                                        onsubmit="return confirm('Send a password reset email to {{ addslashes($client->name) }}?')">
                                                        @csrf
                                                        <button type="submit"
                                                            class="w-full text-left px-3 py-2 text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                            Send Password Reset
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                            <div class="border-t border-gray-100 dark:border-gray-700 pt-1 mt-1">
                                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                                                    onsubmit="return confirm('Permanently delete {{ addslashes($client->name) }}\'s account? This removes the account, project, payments, subscriptions (canceling any active Stripe plan), files, and everything else tied to it. This cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full text-left px-3 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                        Delete Client Account
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Edit Client Modal --}}
<div id="edit-client-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 hidden">
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <div class="px-6 pt-6 pb-5" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <button type="button" onclick="closeEditModal()" class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Admin</p>
            <h2 class="font-display text-xl font-bold text-white">Edit Client Information</h2>
        </div>

        <form id="edit-client-form" method="POST" class="px-6 py-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Full Name</label>
                <input type="text" name="name" id="edit-name" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email Address</label>
                <input type="email" name="email" id="edit-email" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Phone Number <span class="text-gray-400">(optional)</span></label>
                <input type="tel" name="phone" id="edit-phone"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white"
                    placeholder="(000) 000-0000">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-gold hover:bg-gold-dark text-navy text-sm font-semibold transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Add Client Modal --}}
<div id="add-client-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 hidden">
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <div class="px-6 pt-6 pb-5" style="background:linear-gradient(135deg,#111D33,#1B2A4A);">
            <button type="button" onclick="closeAddClientModal()" class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <p class="text-xs font-semibold uppercase tracking-widest text-gold mb-1">Admin</p>
            <h2 class="font-display text-xl font-bold text-white">Add Client</h2>
        </div>

        <form id="add-client-form" method="POST" action="{{ route('admin.clients.store') }}" class="px-6 py-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Full Name</label>
                <input type="text" name="name" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Email Address</label>
                <input type="email" name="email" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Phone Number <span class="text-gray-400">(optional)</span></label>
                <input type="tel" name="phone"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-gray-900 dark:text-white"
                    placeholder="(000) 000-0000">
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Creates the account right away and emails them a password-setup link. No project is attached yet — add one from their row afterward.
            </p>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeAddClientModal()"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-gold hover:bg-gold-dark text-navy text-sm font-semibold transition-colors">
                    Create Client
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddClientModal() {
        document.getElementById('add-client-modal').classList.remove('hidden');
    }

    function closeAddClientModal() {
        document.getElementById('add-client-modal').classList.add('hidden');
    }

    document.getElementById('add-client-modal').addEventListener('click', function (e) {
        if (e.target === this) closeAddClientModal();
    });

    const baseUrl = '{{ url('/admin/clients') }}';

    function openEditModal(id, name, email, phone) {
        document.getElementById('edit-name').value  = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-client-form').action = baseUrl + '/' + id;
        document.getElementById('edit-client-modal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('edit-client-modal').classList.add('hidden');
    }

    document.getElementById('edit-client-modal').addEventListener('click', function (e) {
        if (e.target === this) closeEditModal();
    });

    // Lightweight Alpine-style toggle without requiring Alpine.js.
    // The menu uses `position: fixed`, positioned here via getBoundingClientRect()
    // rather than CSS `absolute` — the table wrapper has overflow-hidden/
    // overflow-x-auto for its rounded corners and horizontal scroll, which
    // clips anything `absolute`-positioned once a row is near the bottom of
    // that box. `fixed` positioning is computed against the viewport, so it
    // escapes that clipping regardless of which row opened it.
    const clientMenus = [];

    function closeAllClientMenus(except) {
        clientMenus.forEach(function (m) {
            if (m !== except) m.close();
        });
    }

    document.querySelectorAll('[x-data]').forEach(function (el) {
        const btn = el.querySelector('button');
        const menu = el.querySelector('[x-show]');

        if (!btn || !menu) return;
        menu.style.display = 'none';

        const entry = { open: false };

        function positionMenu() {
            const rect = btn.getBoundingClientRect();
            const menuHeight = menu.offsetHeight;
            const spaceBelow = window.innerHeight - rect.bottom;

            // Flip the menu above the button when there isn't room below it
            // (e.g. the last row), so it never gets cut off by the viewport.
            if (spaceBelow < menuHeight + 8 && rect.top > menuHeight + 8) {
                menu.style.top = (rect.top - menuHeight - 4) + 'px';
            } else {
                menu.style.top = (rect.bottom + 4) + 'px';
            }
            menu.style.right = (window.innerWidth - rect.right) + 'px';
        }

        entry.close = function () {
            entry.open = false;
            menu.style.display = 'none';
        };

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (entry.open) {
                entry.close();
                return;
            }
            // Only one menu open at a time — close any other first.
            closeAllClientMenus(entry);
            entry.open = true;
            // Show first so positionMenu() can measure the menu's height.
            menu.style.display = 'block';
            positionMenu();
        });

        window.addEventListener('scroll', function () {
            if (entry.open) positionMenu();
        }, true);

        window.addEventListener('resize', function () {
            if (entry.open) positionMenu();
        });

        clientMenus.push(entry);
    });

    document.addEventListener('click', function () {
        closeAllClientMenus();
    });
</script>

@endsection
