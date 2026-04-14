document.addEventListener('DOMContentLoaded', function () {
    // File input handling
    const fileInput = document.getElementById('fileInput');
    const fileNameDisplay = document.querySelector('.file-name');
    const importForm = document.getElementById('import-dosen-form');

    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.textContent = '';
                fileNameDisplay.classList.add('hidden');
            }
        });
    }

    // Form submission with SweetAlert2
    if (importForm) {
        importForm.addEventListener('submit', function (event) {
            event.preventDefault();
            if (!fileInput.files.length) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pilih File',
                    text: 'Silakan pilih file Excel (.xlsx atau .xls) untuk diimpor.',
                });
                return;
            }
            Swal.fire({
                title: 'Mengimpor Data',
                text: 'Harap tunggu, data sedang diimpor...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            importForm.submit();
        });
    }

    // Export dropdown handling
    const exportBtn = document.getElementById('export-btn');
    const exportDropdown = document.getElementById('export-dropdown');

    if (exportBtn && exportDropdown) {
        exportBtn.addEventListener('click', function (event) {
            event.stopPropagation();
            exportDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function (event) {
            if (!exportBtn.contains(event.target) && !exportDropdown.contains(event.target)) {
                exportDropdown.classList.remove('show');
            }
        });

        // Close dropdown with ESC key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                exportDropdown.classList.remove('show');
            }
        });
    }
});