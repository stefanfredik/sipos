<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = []): LengthAwarePaginator|Collection
    {
        $query = $this->model->newQuery();
        
        $perPage = $filters['per_page'] ?? config('sipos.per_page', 15);
        
        if (isset($filters['paginate']) && $filters['paginate'] === false) {
            return $query->get();
        }
        
        return $query->paginate($perPage);
    }

    public function findById(string $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): Model
    {
        $record = $this->findById($id);
        
        if (!$record) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Record with ID {$id} not found.");
        }
        
        $record->update($data);
        
        return $record;
    }

    public function delete(string $id): bool
    {
        $record = $this->findById($id);
        
        if (!$record) {
            return false;
        }
        
        return $record->delete();
    }
}
