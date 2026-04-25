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
                didOpen: () => { Swal.showLoading(); }
            });
            importForm.submit();
        });
    }

    // Export dropdown
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

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                exportDropdown.classList.remove('show');
            }
        });
    }

    // ✅ Tutup modal saat klik background — pakai addEventListener, BUKAN window.onclick
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('deleteModal');
        if (modal && event.target === modal) {
            window.closeDeleteModal();
        }
    });
});

// ============================================
// Fungsi global untuk onclick di HTML
// Harus di luar DOMContentLoaded agar bisa
// dipanggil langsung dari atribut onclick=""
// ============================================

var editStates = {};

window.toggleEditMode = function (id) {
    var form = document.getElementById('form-' + id);
    var inputs = form.querySelectorAll('[data-editable="true"]');
    var editButtons = document.getElementById('edit-buttons-' + id);
    var editBtn = document.getElementById('edit-btn-' + id);

    var isEditMode = editStates[id] || false;

    inputs.forEach(function (input) {
        if (input.tagName === 'SELECT') {
            input.disabled = isEditMode;
        } else {
            if (isEditMode) {
                input.setAttribute('readonly', true);
            } else {
                input.removeAttribute('readonly');
            }
        }
        if (isEditMode) {
            input.classList.remove('bg-white');
            input.classList.add('bg-gray-100');
        } else {
            input.classList.remove('bg-gray-100');
            input.classList.add('bg-white');
        }
    });

    if (editButtons) {
        if (isEditMode) {
            editButtons.classList.add('hidden');
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
            editBtn.classList.remove('text-orange-500', 'hover:text-orange-700');
            editBtn.classList.add('text-blue-500', 'hover:text-blue-700');
        } else {
            editButtons.classList.remove('hidden');
            editBtn.innerHTML = '<i class="fas fa-times"></i> Batal Edit';
            editBtn.classList.remove('text-blue-500', 'hover:text-blue-700');
            editBtn.classList.add('text-orange-500', 'hover:text-orange-700');
        }
        editStates[id] = !isEditMode;
    }
};

window.cancelEdit = function (id) {
    location.reload();
};

window.confirmDelete = function (url, title) {
    document.getElementById('deleteMessage').innerHTML =
        'Apakah Anda yakin ingin menghapus data penelitian "<strong>' + title + '</strong>"?';
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.remove('hidden');
};

window.closeDeleteModal = function () {
    document.getElementById('deleteModal').classList.add('hidden');
};