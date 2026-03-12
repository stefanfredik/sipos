<?php

namespace App\Repositories\Eloquent;

use App\Models\Posyandu;
use App\Repositories\Interfaces\PosyanduRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PosyanduRepository extends BaseRepository implements PosyanduRepositoryInterface
{
    public function __construct(Posyandu $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_posyandu', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('lokasi', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->paginate($perPage);
    }
}
