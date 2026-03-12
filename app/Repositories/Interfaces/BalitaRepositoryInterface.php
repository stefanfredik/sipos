<?php

namespace App\Repositories\Interfaces;

use App\Models\Balita;
use Illuminate\Pagination\LengthAwarePaginator;

interface BalitaRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findByNik(string $nik): ?Balita;
}
