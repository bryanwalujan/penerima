<div class="bg-white rounded-xl card-shadow overflow-hidden">
    <div class="border-b">
        <ul class="flex flex-wrap" role="tablist">
            <li class="flex-1 min-w-[150px]">
                <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50 active" data-tab="dosen" role="tab" aria-selected="true" aria-controls="dosen">
                    <i class="fas fa-user-tie mr-2"></i>Data Dosen
                    <span class="count-badge">{{ isset($dosens) ? $dosens->count() : 0 }}</span>
                </a>
            </li>
            <li class="flex-1 min-w-[150px]">
                <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="penelitian" role="tab" aria-selected="false" aria-controls="penelitian">
                    <i class="fas fa-flask mr-2"></i>Penelitian
                    <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->penelitians->count(); }) : 0 }}</span>
                </a>
            </li>
            <li class="flex-1 min-w-[150px]">
                <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="pengabdian" role="tab" aria-selected="false" aria-controls="pengabdian">
                    <i class="fas fa-hands-helping mr-2"></i>Pengabdian
                    <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->pengabdians->count(); }) : 0 }}</span>
                </a>
            </li>
            <li class="flex-1 min-w-[150px]">
                <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="haki" role="tab" aria-selected="false" aria-controls="haki">
                    <i class="fas fa-copyright mr-2"></i>HAKI
                    <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->hakis->count(); }) : 0 }}</span>
                </a>
            </li>
            <li class="flex-1 min-w-[150px]">
                <a class="tab-link py-4 px-6 block text-center hover:bg-gray-50" data-tab="paten" role="tab" aria-selected="false" aria-controls="paten">
                    <i class="fas fa-certificate mr-2"></i>Paten
                    <span class="count-badge">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->patens->count(); }) : 0 }}</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="p-4 overflow-x-auto">
        @include('admin.dosen.partials.tab-dosen')
        @include('admin.dosen.partials.tab-penelitian')
        @include('admin.dosen.partials.tab-pengabdian')
        @include('admin.dosen.partials.tab-haki')
        @include('admin.dosen.partials.tab-paten')
    </div>
</div>