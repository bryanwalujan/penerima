<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HasDosenHelpers;
use App\Models\Dosen;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Haki;
use App\Models\Paten;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminDosenController extends Controller
{
    use HasDosenHelpers;

    protected RecommendationService $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    // =========================================================================
    // CRUD DOSEN
    // =========================================================================

    public function index()
    {
        $this->checkAdmin();

        $dosens = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->get();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        $this->checkAdmin();

        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate(array_merge([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email',
            'nidn'  => 'required|string|max:30|unique:dosens,nidn',
            'nip'   => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ], $this->relationValidationRules()));

        $data = $request->only(['nama', 'email', 'nidn', 'nip', 'nuptk']);

        if ($foto = $this->handleFotoUpload($request)) {
            $data['foto'] = $foto;
        }

        $dosen = Dosen::create($data);

        $this->syncRelations($dosen, $request->all());

        $this->auditLog(
            'create_dosen',
            "Admin created dosen: {$data['nama']} (NIDN: {$data['nidn']})",
            Dosen::class,
            $dosen->id
        );

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $this->checkAdmin();

        $dosen = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->findOrFail($id);

        return view('admin.dosen.show', compact('dosen'));
    }

    public function edit($id)
    {
        $this->checkAdmin();

        $dosen = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->findOrFail($id);

        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $dosen = Dosen::findOrFail($id);

        $request->validate(array_merge([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'nidn'  => 'required|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'nip'   => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:20',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ], $this->relationValidationRules()));

        $data = $request->only(['nama', 'email', 'nidn', 'nip', 'nuptk']);

        if ($foto = $this->handleFotoUpload($request, $dosen)) {
            $data['foto'] = $foto;
        }

        $dosen->update($data);

        $this->syncRelations($dosen, $request->all(), deleteFirst: true);

        $this->auditLog(
            'update_dosen',
            "Admin updated dosen: {$data['nama']} (NIDN: {$data['nidn']})",
            Dosen::class,
            $dosen->id
        );

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $dosen = Dosen::findOrFail($id);

        if ($dosen->foto) {
            Storage::disk('public')->delete($dosen->foto);
        }

        $dosen->penelitians()->delete();
        $dosen->pengabdians()->delete();
        $dosen->hakis()->delete();
        $dosen->patens()->delete();
        $dosen->delete();

        $this->auditLog(
            'delete_dosen',
            "Admin deleted dosen: {$dosen->nama} (NIDN: {$dosen->nidn})",
            Dosen::class,
            $id
        );

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }

    // =========================================================================
    // DESTROY PER-RELASI
    // =========================================================================

    public function destroyPenelitian($id)
    {
        $this->checkAdmin();

        $penelitian = Penelitian::findOrFail($id);
        $penelitian->delete();

        $this->auditLog('delete_penelitian', "Admin deleted penelitian: {$penelitian->judul_penelitian}", Penelitian::class, $id);

        return redirect()->route('admin.dosen.index')->with('success', 'Penelitian berhasil dihapus.');
    }

    public function destroyPengabdian($id)
    {
        $this->checkAdmin();

        $pengabdian = Pengabdian::findOrFail($id);
        $pengabdian->delete();

        $this->auditLog('delete_pengabdian', "Admin deleted pengabdian: {$pengabdian->judul_pengabdian}", Pengabdian::class, $id);

        return redirect()->route('admin.dosen.index')->with('success', 'Pengabdian berhasil dihapus.');
    }

    public function destroyHaki($id)
    {
        $this->checkAdmin();

        $haki = Haki::findOrFail($id);
        $haki->delete();

        $this->auditLog('delete_haki', "Admin deleted haki: {$haki->judul_haki}", Haki::class, $id);

        return redirect()->route('admin.dosen.index')->with('success', 'HAKI berhasil dihapus.');
    }

    public function destroyPaten($id)
    {
        $this->checkAdmin();

        $paten = Paten::findOrFail($id);
        $paten->delete();

        $this->auditLog('delete_paten', "Admin deleted paten: {$paten->judul_paten}", Paten::class, $id);

        return redirect()->route('admin.dosen.index')->with('success', 'Paten berhasil dihapus.');
    }

    // =========================================================================
    // REKOMENDASI KOLABORASI
    // =========================================================================

    public function recommend($id)
    {
        $this->checkAdmin();

        $dosen = Dosen::findOrFail($id);
        $recommendations = $this->recommendationService->getCollaborationRecommendations($id);

        $this->auditLog(
            'recommend_collaboration',
            "Admin requested collaboration recommendations for dosen ID: {$id}",
            Dosen::class,
            $id
        );

        return response()->json($recommendations);
    }

    // =========================================================================
    // PRIVATE HELPER
    // =========================================================================

    private function checkAdmin(): void
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang diizinkan.');
        }
    }
}