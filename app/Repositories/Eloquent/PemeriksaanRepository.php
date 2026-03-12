<?php

namespace App\Repositories\Eloquent;

use App\DTOs\Pemeriksaan\CreatePemeriksaanDTO;
use App\Models\Pemeriksaan;
use App\Repositories\Interfaces\PemeriksaanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PemeriksaanRepository implements PemeriksaanRepositoryInterface
{
    public function findById(string $id): ?Pemeriksaan
    {
        return Pemeriksaan::with(['jadwalPosyandu', 'kader', 'bidan', 'peserta'])->find($id);
    }

    public function findByPeserta(string $type, string $id, array $filters = []): LengthAwarePaginator
    {
        $query = Pemeriksaan::where('peserta_type', $type)
            ->where('peserta_id', $id)
            ->with(['jadwalPosyandu', 'kader', 'bidan'])
            ->orderBy('tgl_pemeriksaan', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function findByJadwal(string $jadwalId): Collection
    {
        return Pemeriksaan::where('jadwal_posyandu_id', $jadwalId)
            ->with(['peserta', 'kader', 'bidan'])
            ->get();
    }

    public function getStatistik(string $type, string $pesertaId): Collection
    {
        return Pemeriksaan::where('peserta_type', $type)
            ->where('peserta_id', $pesertaId)
            ->orderBy('tgl_pemeriksaan', 'asc')
            ->get();
    }

    public function create(CreatePemeriksaanDTO $dto): Pemeriksaan
    {
        return Pemeriksaan::create($dto->toArray());
    }

    public function update(string $id, array $data): Pemeriksaan
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update($data);
        return $pemeriksaan;
    }

    public function delete(string $id): bool
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        return $pemeriksaan->delete();
    }
}
