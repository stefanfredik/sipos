<?php

namespace App\Repositories\Interfaces;

use App\DTOs\Pemeriksaan\CreatePemeriksaanDTO;
use App\Models\Pemeriksaan;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PemeriksaanRepositoryInterface
{
    public function findById(string $id): ?Pemeriksaan;

    public function findByPeserta(string $type, string $id, array $filters = []): LengthAwarePaginator;

    public function findByJadwal(string $jadwalId): Collection;

    public function getStatistik(string $type, string $pesertaId): Collection;

    public function create(CreatePemeriksaanDTO $dto): Pemeriksaan;

    public function update(string $id, array $data): Pemeriksaan;

    public function delete(string $id): bool;
}
