<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                <i class="fas fa-user-tie text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Dosen</p>
                <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->count() : 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                <i class="fas fa-flask text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Penelitian</p>
                <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->penelitians->count(); }) : 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                <i class="fas fa-hands-helping text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pengabdian</p>
                <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->pengabdians->count(); }) : 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                <i class="fas fa-certificate text-white text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total HAKI & Paten</p>
                <p class="text-2xl font-semibold text-gray-900">{{ isset($dosens) ? $dosens->sum(function($d) { return $d->hakis->count() + $d->patens->count(); }) : 0 }}</p>
            </div>
        </div>
    </div>
</div>