<?php

namespace App\Services;

use App\DTOs\Pemeriksaan\CreatePemeriksaanDTO;
use App\Models\Pemeriksaan;
use App\Repositories\Interfaces\PemeriksaanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PemeriksaanService
{
    public function __construct(
        private readonly PemeriksaanRepositoryInterface $pemeriksaanRepository
    ) {}

    public function getRiwayatPeserta(string $type, string $id, array $filters = []): LengthAwarePaginator
    {
        return $this->pemeriksaanRepository::findByPeserta($type, $id, $filters);
    }

    public function getStatistikPertumbuhan(string $type, string $pesertaId): Collection
    {
        return $this->pemeriksaanRepository->getStatistik($type, $pesertaId);
    }

    public function recordExamination(CreatePemeriksaanDTO $dto): Pemeriksaan
    {
        return $this->pemeriksaanRepository->create($dto);
    }

    public function updateExamination(string $id, array $data): Pemeriksaan
    {
        return $this->pemeriksaanRepository->update($id, $data);
    }

    public function deleteExamination(string $id): bool
    {
        return $this->pemeriksaanRepository->delete($id);
    }
}
