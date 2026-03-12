<?php

namespace App\Repositories\Eloquent;

use App\Models\Bidan;
use App\Repositories\Interfaces\BidanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BidanRepository extends BaseRepository implements BidanRepositoryInterface
{
    public function __construct(Bidan $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('user');

        if (!empty($filters['search'])) {
            $query->where('nama_bidan', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('no_telp', 'like', '%' . $filters['search'] . '%');
        }

        return $query->latest()->paginate($perPage);
    }
}
