<?php

namespace App\Repositories\Interfaces;

use App\Models\IbuHamil;
use Illuminate\Pagination\LengthAwarePaginator;

interface IbuHamilRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findByNik(string $nik): ?IbuHamil;
}
