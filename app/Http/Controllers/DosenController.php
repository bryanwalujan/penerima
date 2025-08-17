<?php
namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Imports\DosenImport;
use App\Exports\DosenImportTemplateExport;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Haki;
use App\Models\Paten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Services\RecommendationService;
use App\Exports\RepositoryExport;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\AuditLog;

class DosenController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    private function checkAdmin()
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Hanya admin yang diizinkan.');
        }
        return null;
    }

    public function create()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email',
            'nidn' => 'required|string|max:20|unique:dosens,nidn',
            'nip' => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'penelitians.*.skema' => 'nullable|string',
            'penelitians.*.posisi' => 'nullable|string',
            'penelitians.*.judul_penelitian' => 'nullable|string',
            'penelitians.*.sumber_dana' => 'nullable|string',
            'penelitians.*.status' => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'penelitians.*.tahun' => 'nullable|integer',
            'penelitians.*.link_luaran' => 'nullable|url',
            'pengabdians.*.skema' => 'nullable|string',
            'pengabdians.*.posisi' => 'nullable|string',
            'pengabdians.*.judul_pengabdian' => 'nullable|string',
            'pengabdians.*.sumber_dana' => 'nullable|string',
            'pengabdians.*.status' => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'pengabdians.*.tahun' => 'nullable|integer',
            'pengabdians.*.link_luaran' => 'nullable|url',
            'hakis.*.judul_haki' => 'nullable|string',
            'hakis.*.expired' => 'nullable|date',
            'hakis.*.link' => 'nullable|url',
            'patens.*.judul_paten' => 'nullable|string',
            'patens.*.jenis_paten' => 'nullable|string',
            'patens.*.expired' => 'nullable|date',
            'patens.*.link' => 'nullable|url',
        ]);

        $data = $request->only(['nama', 'email', 'nidn', 'nip', 'nuptk']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('dosen', 'public');
            $data['foto'] = $path;
        }

        $dosen = Dosen::create($data);

        if ($request->has('penelitians')) {
            foreach ($request->penelitians as $penelitian) {
                if (!empty($penelitian['judul_penelitian'])) {
                    $dosen->penelitians()->create($penelitian);
                }
            }
        }

        if ($request->has('pengabdians')) {
            foreach ($request->pengabdians as $pengabdian) {
                if (!empty($pengabdian['judul_pengabdian'])) {
                    $dosen->pengabdians()->create($pengabdian);
                }
            }
        }

        if ($request->has('hakis')) {
            foreach ($request->hakis as $haki) {
                if (!empty($haki['judul_haki'])) {
                    $dosen->hakis()->create($haki);
                }
            }
        }

        if ($request->has('patens')) {
            foreach ($request->patens as $paten) {
                if (!empty($paten['judul_paten'])) {
                    $dosen->patens()->create($paten);
                }
            }
        }

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'create_dosen',
            'description' => "Admin created dosen: {$data['nama']} (NIDN: {$data['nidn']})",
            'model_type' => Dosen::class,
            'model_id' => $dosen->id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function index()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $dosens = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->get();
        return view('admin.dosen.index', compact('dosens'));
    }

    public function show($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $dosen = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->findOrFail($id);
        return view('admin.dosen.show', compact('dosen'));
    }

    public function edit($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $dosen = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->findOrFail($id);
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'nidn' => 'required|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'nip' => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
            'penelitians.*.skema' => 'nullable|string',
            'penelitians.*.posisi' => 'nullable|string',
            'penelitians.*.judul_penelitian' => 'nullable|string',
            'penelitians.*.sumber_dana' => 'nullable|string',
            'penelitians.*.status' => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'penelitians.*.tahun' => 'nullable|integer',
            'penelitians.*.link_luaran' => 'nullable|url',
            'pengabdians.*.skema' => 'nullable|string',
            'pengabdians.*.posisi' => 'nullable|string',
            'pengabdians.*.judul_pengabdian' => 'nullable|string',
            'pengabdians.*.sumber_dana' => 'nullable|string',
            'pengabdians.*.status' => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'pengabdians.*.tahun' => 'nullable|integer',
            'pengabdians.*.link_luaran' => 'nullable|url',
            'hakis.*.judul_haki' => 'nullable|string',
            'hakis.*.expired' => 'nullable|date',
            'hakis.*.link' => 'nullable|url',
            'patens.*.judul_paten' => 'nullable|string',
            'patens.*.jenis_paten' => 'nullable|string',
            'patens.*.expired' => 'nullable|date',
            'patens.*.link' => 'nullable|url',
        ]);

        $data = $request->only(['nama', 'email', 'nidn', 'nip', 'nuptk']);

        if ($request->hasFile('foto')) {
            if ($dosen->foto) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $path = $request->file('foto')->store('dosen', 'public');
            $data['foto'] = $path;
        }

        $dosen->update($data);

        $dosen->penelitians()->delete();
        if ($request->has('penelitians')) {
            foreach ($request->penelitians as $penelitian) {
                if (!empty($penelitian['judul_penelitian'])) {
                    $dosen->penelitians()->create($penelitian);
                }
            }
        }

        $dosen->pengabdians()->delete();
        if ($request->has('pengabdians')) {
            foreach ($request->pengabdians as $pengabdian) {
                if (!empty($pengabdian['judul_pengabdian'])) {
                    $dosen->pengabdians()->create($pengabdian);
                }
            }
        }

        $dosen->hakis()->delete();
        if ($request->has('hakis')) {
            foreach ($request->hakis as $haki) {
                if (!empty($haki['judul_haki'])) {
                    $dosen->hakis()->create($haki);
                }
            }
        }

        $dosen->patens()->delete();
        if ($request->has('patens')) {
            foreach ($request->patens as $paten) {
                if (!empty($paten['judul_paten'])) {
                    $dosen->patens()->create($paten);
                }
            }
        }

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'update_dosen',
            'description' => "Admin updated dosen: {$data['nama']} (NIDN: {$data['nidn']})",
            'model_type' => Dosen::class,
            'model_id' => $dosen->id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $dosen = Dosen::findOrFail($id);

        if ($dosen->foto) {
            Storage::disk('public')->delete($dosen->foto);
        }

        $dosen->penelitians()->delete();
        $dosen->pengabdians()->delete();
        $dosen->hakis()->delete();
        $dosen->patens()->delete();
        $dosen->delete();

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'delete_dosen',
            'description' => "Admin deleted dosen: {$dosen->nama} (NIDN: {$dosen->nidn})",
            'model_type' => Dosen::class,
            'model_id' => $id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }

    public function destroyPenelitian($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $penelitian = Penelitian::findOrFail($id);
        $penelitian->delete();

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'delete_penelitian',
            'description' => "Admin deleted penelitian: {$penelitian->judul_penelitian}",
            'model_type' => Penelitian::class,
            'model_id' => $id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Penelitian berhasil dihapus.');
    }

    public function destroyPengabdian($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $pengabdian = Pengabdian::findOrFail($id);
        $pengabdian->delete();

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'delete_pengabdian',
            'description' => "Admin deleted pengabdian: {$pengabdian->judul_pengabdian}",
            'model_type' => Pengabdian::class,
            'model_id' => $id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Pengabdian berhasil dihapus.');
    }

    public function destroyHaki($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $haki = Haki::findOrFail($id);
        $haki->delete();

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'delete_haki',
            'description' => "Admin deleted haki: {$haki->judul_haki}",
            'model_type' => Haki::class,
            'model_id' => $id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'HAKI berhasil dihapus.');
    }

    public function destroyPaten($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $paten = Paten::findOrFail($id);
        $paten->delete();

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'delete_paten',
            'description' => "Admin deleted paten: {$paten->judul_paten}",
            'model_type' => Paten::class,
            'model_id' => $id,
            'changes' => null,
        ]);

        return redirect()->route('admin.dosen.index')->with('success', 'Paten berhasil dihapus.');
    }

    public function import(Request $request)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file'));

            AuditLog::create([
                'user_id' => Auth::guard('web')->id(),
                'action' => 'import_dosen',
                'description' => 'Admin imported dosen data via Excel',
                'model_type' => null,
                'model_id' => null,
                'changes' => null,
            ]);

            return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $format = $request->query('format', 'excel');
        $filename = 'unima_repository_' . Carbon::now()->format('Y-m-d') . '.' . ($format === 'excel' ? 'xlsx' : $format);

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'export_data',
            'description' => "Admin exported repository data to {$format}",
            'model_type' => null,
            'model_id' => null,
            'changes' => null,
        ]);

        if ($format === 'excel') {
            return Excel::download(new RepositoryExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
        }

        $dosens = Dosen::with(['penelitians', 'pengabdians', 'hakis', 'patens'])->get();
        $content = '';

        if ($format === 'ris') {
            foreach ($dosens as $dosen) {
                foreach ($dosen->penelitians as $penelitian) {
                    $content .= "TY  - MISC\n";
                    $content .= "TI  - {$penelitian->judul_penelitian}\n";
                    $content .= "AU  - {$dosen->nama}\n";
                    $content .= "PY  - {$penelitian->tahun}\n";
                    if ($penelitian->keywords) {
                        $keywords = is_array($penelitian->keywords) ? implode(', ', $penelitian->keywords) : $penelitian->keywords;
                        $content .= "KW  - {$keywords}\n";
                    }
                    if ($penelitian->skema) {
                        $content .= "KW  - {$penelitian->skema}\n";
                    }
                    if ($penelitian->link_luaran) {
                        $content .= "UR  - {$penelitian->link_luaran}\n";
                    }
                    $content .= "ER  -\n\n";
                }
                foreach ($dosen->pengabdians as $pengabdian) {
                    $content .= "TY  - MISC\n";
                    $content .= "TI  - {$pengabdian->judul_pengabdian}\n";
                    $content .= "AU  - {$dosen->nama}\n";
                    $content .= "PY  - {$pengabdian->tahun}\n";
                    if ($pengabdian->skema) {
                        $content .= "KW  - {$pengabdian->skema}\n";
                    }
                    if ($pengabdian->link_luaran) {
                        $content .= "UR  - {$pengabdian->link_luaran}\n";
                    }
                    $content .= "ER  -\n\n";
                }
                foreach ($dosen->hakis as $haki) {
                    $content .= "TY  - MISC\n";
                    $content .= "TI  - {$haki->judul_haki}\n";
                    $content .= "AU  - {$dosen->nama}\n";
                    if ($haki->expired) {
                        $content .= "DA  - {$haki->expired}\n";
                    }
                    if ($haki->link) {
                        $content .= "UR  - {$haki->link}\n";
                    }
                    $content .= "ER  -\n\n";
                }
                foreach ($dosen->patens as $paten) {
                    $content .= "TY  - MISC\n";
                    $content .= "TI  - {$paten->judul_paten}\n";
                    $content .= "AU  - {$dosen->nama}\n";
                    if ($paten->expired) {
                        $content .= "DA  - {$paten->expired}\n";
                    }
                    if ($paten->jenis_paten) {
                        $content .= "KW  - {$paten->jenis_paten}\n";
                    }
                    if ($paten->link) {
                        $content .= "UR  - {$paten->link}\n";
                    }
                    $content .= "ER  -\n\n";
                }
            }
            return Response::streamDownload(function () use ($content) {
                echo $content;
            }, $filename, ['Content-Type' => 'text/plain']);
        } elseif ($format === 'bib') {
            foreach ($dosens as $dosen) {
                foreach ($dosen->penelitians as $penelitian) {
                    $content .= "@misc{penelitian_{$penelitian->id},\n";
                    $content .= "  title = {\"{$penelitian->judul_penelitian}\"},\n";
                    $content .= "  author = {\"{$dosen->nama}\"},\n";
                    $content .= "  year = {\"{$penelitian->tahun}\"},\n";
                    $keywords = $penelitian->keywords ? (is_array($penelitian->keywords) ? implode(', ', $penelitian->keywords) : $penelitian->keywords) : '';
                    $keywords .= $penelitian->skema ? ($keywords ? ', ' : '') . $penelitian->skema : '';
                    $content .= "  keywords = {\"{$keywords}\"},\n";
                    if ($penelitian->link_luaran) {
                        $content .= "  url = {\"{$penelitian->link_luaran}\"}\n";
                    }
                    $content .= "}\n\n";
                }
                foreach ($dosen->pengabdians as $pengabdian) {
                    $content .= "@misc{pengabdian_{$pengabdian->id},\n";
                    $content .= "  title = {\"{$pengabdian->judul_pengabdian}\"},\n";
                    $content .= "  author = {\"{$dosen->nama}\"},\n";
                    $content .= "  year = {\"{$pengabdian->tahun}\"},\n";
                    if ($pengabdian->skema) {
                        $content .= "  keywords = {\"{$pengabdian->skema}\"},\n";
                    }
                    if ($pengabdian->link_luaran) {
                        $content .= "  url = {\"{$pengabdian->link_luaran}\"}\n";
                    }
                    $content .= "}\n\n";
                }
                foreach ($dosen->hakis as $haki) {
                    $content .= "@misc{haki_{$haki->id},\n";
                    $content .= "  title = {\"{$haki->judul_haki}\"},\n";
                    $content .= "  author = {\"{$dosen->nama}\"},\n";
                    if ($haki->expired) {
                        $content .= "  year = {\"" . Carbon::parse($haki->expired)->year . "\"},\n";
                    }
                    if ($haki->link) {
                        $content .= "  url = {\"{$haki->link}\"}\n";
                    }
                    $content .= "}\n\n";
                }
                foreach ($dosen->patens as $paten) {
                    $content .= "@misc{paten_{$paten->id},\n";
                    $content .= "  title = {\"{$paten->judul_paten}\"},\n";
                    $content .= "  author = {\"{$dosen->nama}\"},\n";
                    if ($paten->expired) {
                        $content .= "  year = {\"" . Carbon::parse($paten->expired)->year . "\"},\n";
                    }
                    if ($paten->jenis_paten) {
                        $content .= "  keywords = {\"{$paten->jenis_paten}\"},\n";
                    }
                    if ($paten->link) {
                        $content .= "  url = {\"{$paten->link}\"}\n";
                    }
                    $content .= "}\n\n";
                }
            }
            return Response::streamDownload(function () use ($content) {
                echo $content;
            }, $filename, ['Content-Type' => 'text/plain']);
        } elseif ($format === 'csv') {
            $content .= "type,dosen_nama,judul,tahun,keywords,skema,posisi,sumber_dana,status,link,expired,jenis_paten\n";
            foreach ($dosens as $dosen) {
                foreach ($dosen->penelitians as $penelitian) {
                    $keywords = $penelitian->keywords ? (is_array($penelitian->keywords) ? implode(',', $penelitian->keywords) : $penelitian->keywords) : '';
                    $content .= "\"Penelitian\",\"{$dosen->nama}\",\"{$penelitian->judul_penelitian}\",\"{$penelitian->tahun}\",\"{$keywords}\",\"{$penelitian->skema}\",\"{$penelitian->posisi}\",\"{$penelitian->sumber_dana}\",\"{$penelitian->status}\",\"{$penelitian->link_luaran}\",\"\",\"\"\n";
                }
                foreach ($dosen->pengabdians as $pengabdian) {
                    $content .= "\"Pengabdian\",\"{$dosen->nama}\",\"{$pengabdian->judul_pengabdian}\",\"{$pengabdian->tahun}\",\"\",\"{$pengabdian->skema}\",\"{$pengabdian->posisi}\",\"{$pengabdian->sumber_dana}\",\"{$pengabdian->status}\",\"{$pengabdian->link_luaran}\",\"\",\"\"\n";
                }
                foreach ($dosen->hakis as $haki) {
                    $content .= "\"Haki\",\"{$dosen->nama}\",\"{$haki->judul_haki}\",\"\",\"\",\"\",\"\",\"\",\"\",\"{$haki->link}\",\"{$haki->expired}\",\"\"\n";
                }
                foreach ($dosen->patens as $paten) {
                    $content .= "\"Paten\",\"{$dosen->nama}\",\"{$paten->judul_paten}\",\"\",\"\",\"\",\"\",\"\",\"\",\"{$paten->link}\",\"{$paten->expired}\",\"{$paten->jenis_paten}\"\n";
                }
            }
            return Response::streamDownload(function () use ($content) {
                echo $content;
            }, $filename, ['Content-Type' => 'text/csv']);
        }

        return redirect()->back()->with('error', 'Format ekspor tidak didukung.');
    }

    public function exportTemplate()
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $filename = 'dosen_import_template_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'export_template',
            'description' => 'Admin exported dosen import template',
            'model_type' => null,
            'model_id' => null,
            'changes' => null,
        ]);

        return Excel::download(new DosenImportTemplateExport, $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function editProfile()
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }
        return view('dosen.edit', compact('dosen'));
    }

    public function updateProfile(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
            'nidn' => 'required|string|max:20|unique:dosens,nidn,' . $dosen->id,
            'nip' => 'nullable|string|max:20',
            'nuptk' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:10000',
        ]);

        $data = $request->only(['nama', 'email', 'nidn', 'nip', 'nuptk']);

        if ($request->hasFile('foto')) {
            if ($dosen->foto) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $path = $request->file('foto')->store('dosen', 'public');
            $data['foto'] = $path;
        }

        $dosen->update($data);

        AuditLog::create([
            'user_id' => $dosen->id,
            'action' => 'update_profile',
            'description' => "Dosen updated profile: {$data['nama']} (NIDN: {$data['nidn']})",
            'model_type' => Dosen::class,
            'model_id' => $dosen->id,
            'changes' => null,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Profil berhasil diperbarui.');
    }

    public function editPenelitian()
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }
        $penelitians = $dosen->penelitians;
        return view('dosen.edit-penelitian', compact('dosen', 'penelitians'));
    }

    public function updatePenelitian(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'penelitians.*.skema' => 'nullable|string',
            'penelitians.*.posisi' => 'nullable|string',
            'penelitians.*.judul_penelitian' => 'nullable|string',
            'penelitians.*.sumber_dana' => 'nullable|string',
            'penelitians.*.status' => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'penelitians.*.tahun' => 'nullable|integer',
            'penelitians.*.link_luaran' => 'nullable|url',
        ]);

        $dosen->penelitians()->delete();
        if ($request->has('penelitians')) {
            foreach ($request->penelitians as $penelitian) {
                if (!empty($penelitian['judul_penelitian'])) {
                    $dosen->penelitians()->create($penelitian);
                }
            }
        }

        AuditLog::create([
            'user_id' => $dosen->id,
            'action' => 'update_penelitian',
            'description' => "Dosen updated penelitian data",
            'model_type' => Penelitian::class,
            'model_id' => null,
            'changes' => null,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Penelitian berhasil diperbarui.');
    }

    public function editPengabdian()
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }
        $pengabdians = $dosen->pengabdians;
        return view('dosen.edit-pengabdian', compact('dosen', 'pengabdians'));
    }

    public function updatePengabdian(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'pengabdians.*.skema' => 'nullable|string',
            'pengabdians.*.posisi' => 'nullable|string',
            'pengabdians.*.judul_pengabdian' => 'nullable|string',
            'pengabdians.*.sumber_dana' => 'nullable|string',
            'pengabdians.*.status' => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'pengabdians.*.tahun' => 'nullable|integer',
            'pengabdians.*.link_luaran' => 'nullable|url',
        ]);

        $dosen->pengabdians()->delete();
        if ($request->has('pengabdians')) {
            foreach ($request->pengabdians as $pengabdian) {
                if (!empty($pengabdian['judul_pengabdian'])) {
                    $dosen->pengabdians()->create($pengabdian);
                }
            }
        }

        AuditLog::create([
            'user_id' => $dosen->id,
            'action' => 'update_pengabdian',
            'description' => "Dosen updated pengabdian data",
            'model_type' => Pengabdian::class,
            'model_id' => null,
            'changes' => null,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Pengabdian berhasil diperbarui.');
    }

    public function editHaki()
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }
        $hakis = $dosen->hakis;
        return view('dosen.edit-haki', compact('dosen', 'hakis'));
    }

    public function updateHaki(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'hakis.*.judul_haki' => 'nullable|string',
            'hakis.*.expired' => 'nullable|date',
            'hakis.*.link' => 'nullable|url',
        ]);

        $dosen->hakis()->delete();
        if ($request->has('hakis')) {
            foreach ($request->hakis as $haki) {
                if (!empty($haki['judul_haki'])) {
                    $dosen->hakis()->create($haki);
                }
            }
        }

        AuditLog::create([
            'user_id' => $dosen->id,
            'action' => 'update_haki',
            'description' => "Dosen updated haki data",
            'model_type' => Haki::class,
            'model_id' => null,
            'changes' => null,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'HAKI berhasil diperbarui.');
    }

    public function editPaten()
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }
        $patens = $dosen->patens;
        return view('dosen.edit-paten', compact('dosen', 'patens'));
    }

    public function updatePaten(Request $request)
    {
        $dosen = Auth::guard('dosen')->user();
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'patens.*.judul_paten' => 'nullable|string',
            'patens.*.jenis_paten' => 'nullable|string',
            'patens.*.expired' => 'nullable|date',
            'patens.*.link' => 'nullable|url',
        ]);

        $dosen->patens()->delete();
        if ($request->has('patens')) {
            foreach ($request->patens as $paten) {
                if (!empty($paten['judul_paten'])) {
                    $dosen->patens()->create($paten);
                }
            }
        }

        AuditLog::create([
            'user_id' => $dosen->id,
            'action' => 'update_paten',
            'description' => "Dosen updated paten data",
            'model_type' => Paten::class,
            'model_id' => null,
            'changes' => null,
        ]);

        return redirect()->route('dosen.dashboard')->with('success', 'Paten berhasil diperbarui.');
    }

    public function recommend($id)
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $dosen = Dosen::findOrFail($id);
        $recommendations = $this->recommendationService->getCollaborationRecommendations($id);

        AuditLog::create([
            'user_id' => Auth::guard('web')->id(),
            'action' => 'recommend_collaboration',
            'description' => "Admin requested collaboration recommendations for dosen ID: {$id}",
            'model_type' => Dosen::class,
            'model_id' => $id,
            'changes' => null,
        ]);

        return response()->json($recommendations);
    }
}