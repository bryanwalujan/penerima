<?php
// app/Http/Controllers/Admin/DosenSyncLogController.php
// ── Di REPODOSEN ──────────────────────────────────────────────────────────────

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenSyncLogController extends Controller
{
    /**
     * Daftar semua dosen yang sudah masuk via sync.
     * GET /admin/dosen-sync
     */
    public function index(Request $request)
    {
        $query = Dosen::query()->latest('updated_at');

        // Filter pencarian
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip',  'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        $dosens = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => Dosen::count(),
            'dengan_nip'   => Dosen::whereNotNull('nip')->count(),
            'dengan_nidn'  => Dosen::whereNotNull('nidn')->count(),
            'tanpa_nip'    => Dosen::whereNull('nip')->count(),
        ];

        return view('admin.dosen-sync.index', compact('dosens', 'stats'));
    }

    /**
     * Detail satu dosen.
     * GET /admin/dosen-sync/{dosen}
     */
    public function show(Dosen $dosen)
    {
        return view('admin.dosen-sync.show', compact('dosen'));
    }
}