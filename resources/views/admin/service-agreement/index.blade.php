@extends('layouts.admin')

@section('title', 'Service Agreement – Admin')
@section('page-title', 'Service Agreement')

@section('content')

<p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
    Clients must digitally sign this before any project work begins. Saving below publishes a new version —
    it never edits text someone has already signed, so existing signatures stay tied to the wording they agreed to.
</p>

<details class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8" open>
    <summary class="flex items-center justify-between gap-4 cursor-pointer list-none px-6 py-4 [&::-webkit-details-marker]:hidden">
        <p class="font-semibold text-navy dark:text-white">
            Current Version
            @if ($activeTemplate)
                <span class="text-xs font-semibold uppercase tracking-wide text-gold-dark ml-2">v{{ $activeTemplate->version }}</span>
            @endif
        </p>
        <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </summary>

    <div class="border-t border-gray-200 dark:border-gray-700 p-6">
        @if ($activeTemplate?->isPdfBased())
            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Current version is an uploaded PDF.
                        <a href="{{ route('admin.service-agreement.templates.download', $activeTemplate) }}" class="text-gold-dark font-semibold hover:underline">Download</a>
                    </p>
                </div>
                <iframe src="{{ route('admin.service-agreement.templates.view', $activeTemplate) }}"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700"
                        style="height:750px;">
                </iframe>
            </div>
        @endif

        @php $source = old('source', $activeTemplate?->isPdfBased() ? 'pdf' : 'text'); @endphp

        <div class="flex items-center gap-1 mb-5 border-b border-gray-200 dark:border-gray-700" id="agreement-source-tabs">
            <button type="button" data-source-tab="text" onclick="setAgreementSource('text')"
                    class="px-4 py-2 text-sm font-semibold border-b-2 transition-colors {{ $source === 'text' ? 'border-gold text-navy dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-navy' }}">
                Paste Text
            </button>
            <button type="button" data-source-tab="pdf" onclick="setAgreementSource('pdf')"
                    class="px-4 py-2 text-sm font-semibold border-b-2 transition-colors {{ $source === 'pdf' ? 'border-gold text-navy dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-navy' }}">
                Upload PDF
            </button>
        </div>

        <form method="POST" action="{{ route('admin.service-agreement.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="source" id="agreement-source-input" value="{{ $source }}">
            <div class="mb-4">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title', $activeTemplate?->title) }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">
            </div>

            <div id="agreement-source-panel-text" class="mb-4" style="{{ $source === 'text' ? '' : 'display:none;' }}">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Agreement Text</label>
                <textarea name="body" rows="18"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-gold focus:border-gold dark:bg-navy-dark dark:text-white">{{ old('body', $activeTemplate?->isPdfBased() ? '' : $activeTemplate?->body) }}</textarea>
            </div>

            <div id="agreement-source-panel-pdf" class="mb-4" style="{{ $source === 'pdf' ? '' : 'display:none;' }}">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1.5">Agreement PDF</label>
                <input type="file" name="pdf" accept="application/pdf" id="pdf-upload-input"
                       onchange="previewPdf(this)"
                       class="w-full text-sm text-gray-600 dark:text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gold/15 file:text-navy dark:text-white file:font-semibold file:text-sm hover:file:bg-gold/25">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">PDF only, up to 25 MB. Clients will review/download this exact file, then sign with their typed name and drawn signature as usual.</p>

                <div id="pdf-preview-wrap" class="hidden mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">Preview</p>
                        <button type="button" onclick="clearPdfPreview()"
                            class="text-xs text-gray-400 hover:text-red-500 transition-colors">✕ Remove</button>
                    </div>
                    <iframe id="pdf-preview-frame" src="" class="w-full rounded-xl border border-gray-200 dark:border-gray-700" style="height:700px;"></iframe>
                </div>
            </div>

            <button type="submit" class="bg-navy hover:bg-navy-light text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                Publish New Version
            </button>
        </form>
    </div>
</details>

<script>
    function previewPdf(input) {
        const wrap = document.getElementById('pdf-preview-wrap');
        const frame = document.getElementById('pdf-preview-frame');
        if (input.files && input.files[0]) {
            const url = URL.createObjectURL(input.files[0]);
            frame.src = url;
            wrap.classList.remove('hidden');
        }
    }

    function clearPdfPreview() {
        const input = document.getElementById('pdf-upload-input');
        const frame = document.getElementById('pdf-preview-frame');
        input.value = '';
        frame.src = '';
        document.getElementById('pdf-preview-wrap').classList.add('hidden');
    }

    function setAgreementSource(value) {
        document.getElementById('agreement-source-input').value = value;
        document.getElementById('agreement-source-panel-text').style.display = value === 'text' ? '' : 'none';
        document.getElementById('agreement-source-panel-pdf').style.display = value === 'pdf' ? '' : 'none';
        document.querySelectorAll('#agreement-source-tabs [data-source-tab]').forEach((btn) => {
            const active = btn.dataset.sourceTab === value;
            btn.classList.toggle('border-gold', active);
            btn.classList.toggle('text-navy', active);
            btn.classList.toggle('dark:text-white', active);
            btn.classList.toggle('border-transparent', !active);
            btn.classList.toggle('text-gray-400', !active);
            btn.classList.toggle('dark:text-gray-500', !active);
        });
    }
</script>

<h3 class="font-semibold text-navy dark:text-white mb-3">Signed Agreements</h3>

@if ($signatures->isEmpty())
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center">
        <p class="text-gray-500 dark:text-gray-400">No clients have signed yet.</p>
    </div>
@else
    <div class="bg-white dark:bg-navy rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-navy-dark text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Client</th>
                    <th class="px-5 py-3">Project</th>
                    <th class="px-5 py-3">Version</th>
                    <th class="px-5 py-3">Signed</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($signatures as $signature)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-navy dark:text-white">{{ $signature->signer_name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $signature->user->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $signature->project->name }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">v{{ $signature->template->version }}</td>
                        <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $signature->signed_at->format('M j, Y') }}</td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('portal.agreement.download', $signature) }}" class="text-gold-dark font-semibold hover:underline">Download PDF</a>
                            <form method="POST" action="{{ route('admin.service-agreement.resend', $signature) }}" class="inline" onsubmit="return confirm('Resend the signed agreement email to {{ $signature->user->email }}?')">
                                @csrf
                                <button type="submit" class="text-navy dark:text-white font-semibold hover:underline ml-3">Resend Email</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
