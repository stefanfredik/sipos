<?php

namespace App\Repositories\Eloquent;

use App\Models\Balita;
use App\Repositories\Interfaces\BalitaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BalitaRepository extends BaseRepository implements BalitaRepositoryInterface
{
    public function __construct(Balita $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nik', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nama_orang_tua', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findByNik(string $nik): ?Balita
    {
        return $this->model->where('nik', $nik)->first();
    }
}
