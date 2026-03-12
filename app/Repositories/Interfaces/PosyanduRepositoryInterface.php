<?php

namespace App\Repositories\Interfaces;

use App\Models\Posyandu;
use Illuminate\Pagination\LengthAwarePaginator;

interface PosyanduRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
