<?php

namespace App\Repositories\Eloquent;

use App\Models\JadwalPosyandu;
use App\Repositories\Interfaces\JadwalPosyanduRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalPosyanduRepository extends BaseRepository implements JadwalPosyanduRepositoryInterface
{
    public function __construct(JadwalPosyandu $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['posyandu', 'kader', 'bidan']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('posyandu', fn ($p) => $p->where('nama_posyandu', 'like', "%{$search}%"))
                  ->orWhereHas('kader', fn ($k) => $k->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('bidan', fn ($b) => $b->where('nama', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['posyandu_id'])) {
            $query->where('posyandu_id', $filters['posyandu_id']);
        }

        return $query->orderBy('tanggal', 'desc')->paginate($perPage);
    }

    public function getUpcoming(int $days = 7): Collection
    {
        return $this->model->newQuery()
            ->with(['posyandu', 'kader', 'bidan'])
            ->upcoming()
            ->where('tanggal', '<=', now()->addDays($days)->toDateString())
            ->get();
    }

    public function getByMonth(int $year, int $month): Collection
    {
        return $this->model->newQuery()
            ->with(['posyandu', 'kader', 'bidan'])
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('tanggal', 'asc')
            ->get();
    }

    public function getByPosyandu(string $posyanduId): Collection
    {
        return $this->model->newQuery()
            ->with(['posyandu', 'kader', 'bidan'])
            ->where('posyandu_id', $posyanduId)
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
