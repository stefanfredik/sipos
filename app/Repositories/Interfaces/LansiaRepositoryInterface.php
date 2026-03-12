<?php

namespace App\Repositories\Interfaces;

use App\Models\Lansia;
use Illuminate\Pagination\LengthAwarePaginator;

interface LansiaRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function findByNik(string $nik): ?Lansia;
}
