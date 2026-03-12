<?php

namespace App\Repositories\Eloquent;

use App\Models\IbuHamil;
use App\Repositories\Interfaces\IbuHamilRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class IbuHamilRepository extends BaseRepository implements IbuHamilRepositoryInterface
{
    public function __construct(IbuHamil $model)
    {
        parent::__construct($model);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('nik', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findByNik(string $nik): ?IbuHamil
    {
        return $this->model->where('nik', $nik)->first();
    }
}
