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

    // Pastikan DOM sudah siap sebelum fungsi didefinisikan
    document.addEventListener('DOMContentLoaded', function() {
        // Tidak perlu dipindah ke sini karena fungsi onclick dipanggil global
    });

    // Fungsi harus berada di scope global (window)
    let editStates = {};
    
    window.toggleEditMode = function(id) {
        const form = document.getElementById(`form-${id}`);
        const inputs = form.querySelectorAll('[data-editable="true"]');
        const editButtons = document.getElementById(`edit-buttons-${id}`);
        const editBtn = document.getElementById(`edit-btn-${id}`);
        
        const isEditMode = editStates[id] || false;
        
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                if (!isEditMode) {
                    input.disabled = false;
                    input.classList.remove('bg-gray-100');
                    input.classList.add('bg-white');
                } else {
                    input.disabled = true;
                    input.classList.remove('bg-white');
                    input.classList.add('bg-gray-100');
                }
            } else {
                if (!isEditMode) {
                    input.removeAttribute('readonly');
                    input.classList.remove('bg-gray-100');
                    input.classList.add('bg-white');
                } else {
                    input.setAttribute('readonly', true);
                    input.classList.remove('bg-white');
                    input.classList.add('bg-gray-100');
                }
            }
        });
        
        if (editButtons) {
            if (!isEditMode) {
                editButtons.classList.remove('hidden');
                editBtn.innerHTML = '<i class="fas fa-times"></i> Batal Edit';
                editBtn.classList.remove('text-blue-500', 'hover:text-blue-700');
                editBtn.classList.add('text-orange-500', 'hover:text-orange-700');
                editStates[id] = true;
            } else {
                editButtons.classList.add('hidden');
                editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
                editBtn.classList.remove('text-orange-500', 'hover:text-orange-700');
                editBtn.classList.add('text-blue-500', 'hover:text-blue-700');
                editStates[id] = false;
            }
        }
    };
    
    window.cancelEdit = function(id) {
        location.reload();
    };
    
    window.confirmDelete = function(url, title) {
        document.getElementById('deleteMessage').innerHTML = 
            `Apakah Anda yakin ingin menghapus data penelitian "<strong>${title}</strong>"?`;
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteModal').classList.remove('hidden');
    };
    
    window.closeDeleteModal = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };
    
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeDeleteModal();
        }
    };