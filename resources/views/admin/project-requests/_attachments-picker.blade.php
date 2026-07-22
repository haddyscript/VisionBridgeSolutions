{{-- Multi-file "Supporting Documents" picker — chip list, mirrors resources/views/portal/partials/text-submission-section.blade.php's attach-files pattern. Include inside an existing multipart/form-data <form>. --}}
<div class="upload-attach">
    <div class="flex flex-wrap items-center gap-2">
        <label class="inline-flex items-center gap-2 cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-navy-dark hover:border-gold hover:bg-gold/5 px-3.5 py-2 text-sm font-medium text-navy dark:text-white transition-colors">
            <input type="file" name="attachments[]" multiple class="attach-input sr-only">
            <svg class="w-4 h-4 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 10-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
            <span>Attach supporting documents</span>
        </label>
        <span class="attach-filelist flex flex-wrap items-center gap-2"></span>
    </div>
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Optional — specs, contracts, reference files, anything beyond the formal proposal document (up to 25MB each).</p>
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.upload-attach').forEach(function (wrap) {
                const attachInput = wrap.querySelector('.attach-input');
                if (!attachInput || attachInput.dataset.bound) return;
                attachInput.dataset.bound = '1';

                const listEl = wrap.querySelector('.attach-filelist');
                let selectedFiles = [];

                function syncInputFiles() {
                    const dt = new DataTransfer();
                    selectedFiles.forEach(function (file) { dt.items.add(file); });
                    attachInput.files = dt.files;
                }

                function renderFileList() {
                    listEl.innerHTML = '';
                    selectedFiles.forEach(function (file, index) {
                        const chip = document.createElement('span');
                        chip.className = 'inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 rounded-lg bg-gray-50 dark:bg-navy-dark/60 border border-gray-200 dark:border-gray-700 px-2.5 py-1.5';
                        chip.innerHTML =
                            '<svg class="w-3.5 h-3.5 text-gold-dark shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                            '<span class="truncate max-w-[160px]"></span>' +
                            '<button type="button" class="attach-remove text-gray-400 hover:text-red-500 transition-colors" title="Remove">' +
                                '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                            '</button>';
                        chip.querySelector('span.truncate').textContent = file.name;
                        chip.querySelector('.attach-remove').addEventListener('click', function () {
                            selectedFiles.splice(index, 1);
                            syncInputFiles();
                            renderFileList();
                        });
                        listEl.appendChild(chip);
                    });
                }

                attachInput.addEventListener('change', function () {
                    selectedFiles = selectedFiles.concat(Array.from(attachInput.files));
                    syncInputFiles();
                    renderFileList();
                });
            });
        });
    </script>
@endonce
