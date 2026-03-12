<?php

namespace App\Repositories\Eloquent;

use App\Models\Kader;
use App\Repositories\Interfaces\KaderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class KaderRepository extends BaseRepository implements KaderRepositoryInterface
{
    public function __construct(Kader $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['user', 'posyandu']);

        if (!empty($filters['search'])) {
            $query->where('nama_kader', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('no_telp', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }
}
