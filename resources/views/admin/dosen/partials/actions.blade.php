<div class="flex flex-col sm:flex-row gap-4 mb-6">
    <!-- Tambah Dosen Button -->
    <a href="{{ route('admin.dosen.create') }}" class="action-button bg-primary text-white">
        <i class="fas fa-plus-circle"></i> Tambah Dosen
    </a>
    
    <!-- Download Template Button -->
    <a href="{{ route('admin.dosen.exportTemplate') }}" class="action-button bg-primary text-white">
        <i class="fas fa-file-download"></i> Download Template
    </a>
    
    <div class="flex flex-col sm:flex-row gap-4 w-full">
        <!-- Import Form -->
        <form id="import-dosen-form" action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data" class="flex w-full sm:flex-row flex-col gap-4">
            @csrf
            <div class="file-input-wrapper flex items-center bg-primary rounded-l-lg sm:rounded">
                <button type="button" class="action-button bg-transparent text-white">
                    <i class="fas fa-file-excel"></i> Pilih File
                </button>
                <span class="file-name hidden text-white text-sm ml-2 truncate max-w-[150px]"></span>
                <input type="file" name="file" accept=".xlsx,.xls" id="fileInput">
            </div>
            <button type="submit" class="action-button bg-success text-white rounded-r-lg sm:rounded">
                <i class="fas fa-upload"></i> Impor
            </button>
        </form>
        
        <!-- Export Button -->
        <div class="relative flex-1 sm:flex-none">
            <button id="export-btn" class="action-button bg-primary text-white w-full sm:w-auto">
                <i class="fas fa-download"></i> Ekspor
            </button>
            <div id="export-dropdown" class="export-dropdown">
                <a href="{{ route('admin.dosen.export', ['format' => 'excel']) }}" class="dropdown-item">Excel</a>
                <a href="{{ route('admin.dosen.export', ['format' => 'ris']) }}" class="dropdown-item">RIS</a>
                <a href="{{ route('admin.dosen.export', ['format' => 'bib']) }}" class="dropdown-item">BibTeX</a>
                <a href="{{ route('admin.dosen.export', ['format' => 'csv']) }}" class="dropdown-item border-b-0">CSV</a>
            </div>
        </div>
    </div>
</div>