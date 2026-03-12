<?php

namespace App\Services;

use App\Models\Posyandu;
use App\Repositories\Interfaces\PosyanduRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PosyanduService extends BaseService
{
    public function __construct(
        PosyanduRepositoryInterface $repository
    ) {
        parent::__construct($repository);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data): Posyandu
    {
        return $this->repository->create($data);
    }

    public function update(string $id, array $data): Posyandu
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function findById(string $id): Posyandu
    {
        return $this->repository->findById($id);
    }
}
