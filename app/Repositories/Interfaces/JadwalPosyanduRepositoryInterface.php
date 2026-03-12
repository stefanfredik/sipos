<?php

namespace App\Repositories\Interfaces;

use App\Models\JadwalPosyandu;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface JadwalPosyanduRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getUpcoming(int $days = 7): Collection;

    public function getByMonth(int $year, int $month): Collection;

    public function getByPosyandu(string $posyanduId): Collection;
}
