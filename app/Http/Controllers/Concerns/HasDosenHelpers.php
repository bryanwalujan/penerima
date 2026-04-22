<?php

namespace App\Http\Controllers\Concerns;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Trait HasDosenHelpers
 *
 * Logic yang dipakai bersama oleh AdminDosenController
 * dan DosenProfileController agar tidak duplikasi kode.
 */
trait HasDosenHelpers
{
    /**
     * Simpan relasi penelitian, pengabdian, haki, paten ke dosen.
     * Jika $deleteFirst = true, hapus data lama sebelum insert ulang.
     */
    protected function syncRelations($dosen, array $request, bool $deleteFirst = false): void
    {
        $relations = [
            'penelitians'  => ['key' => 'judul_penelitian',  'relation' => 'penelitians'],
            'pengabdians'  => ['key' => 'judul_pengabdian',  'relation' => 'pengabdians'],
            'hakis'        => ['key' => 'judul_haki',         'relation' => 'hakis'],
            'patens'       => ['key' => 'judul_paten',        'relation' => 'patens'],
        ];

        foreach ($relations as $field => $config) {
            if ($deleteFirst) {
                $dosen->{$config['relation']}()->delete();
            }

            if (!empty($request[$field])) {
                foreach ($request[$field] as $item) {
                    if (!empty($item[$config['key']])) {
                        $dosen->{$config['relation']}()->create($item);
                    }
                }
            }
        }
    }

    /**
     * Handle upload foto — hapus foto lama jika ada.
     */
    protected function handleFotoUpload($request, $dosen = null): ?string
    {
        if (!$request->hasFile('foto')) {
            return null;
        }

        if ($dosen && $dosen->foto) {
            Storage::disk('public')->delete($dosen->foto);
        }

        return $request->file('foto')->store('dosen', 'public');
    }

    /**
     * Catat audit log.
     */
    protected function auditLog(string $action, string $description, ?string $modelType = null, ?int $modelId = null, ?int $userId = null): void
    {
        AuditLog::create([
            'user_id'    => $userId ?? Auth::guard('web')->id(),
            'action'     => $action,
            'description'=> $description,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'changes'    => null,
        ]);
    }

    /**
     * Aturan validasi relasi yang dipakai store dan update.
     */
    protected function relationValidationRules(): array
    {
        return [
            'penelitians.*.skema'            => 'nullable|string',
            'penelitians.*.posisi'           => 'nullable|string',
            'penelitians.*.judul_penelitian' => 'nullable|string',
            'penelitians.*.sumber_dana'      => 'nullable|string',
            'penelitians.*.status'           => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'penelitians.*.tahun'            => 'nullable|integer',
            'penelitians.*.link_luaran'      => 'nullable|url',
            'pengabdians.*.skema'            => 'nullable|string',
            'pengabdians.*.posisi'           => 'nullable|string',
            'pengabdians.*.judul_pengabdian' => 'nullable|string',
            'pengabdians.*.sumber_dana'      => 'nullable|string',
            'pengabdians.*.status'           => 'nullable|string|in:Selesai,Berjalan,Diajukan',
            'pengabdians.*.tahun'            => 'nullable|integer',
            'pengabdians.*.link_luaran'      => 'nullable|url',
            'hakis.*.judul_haki'             => 'nullable|string',
            'hakis.*.expired'                => 'nullable|date',
            'hakis.*.link'                   => 'nullable|url',
            'patens.*.judul_paten'           => 'nullable|string',
            'patens.*.jenis_paten'           => 'nullable|string',
            'patens.*.expired'               => 'nullable|date',
            'patens.*.link'                  => 'nullable|url',
        ];
    }
}