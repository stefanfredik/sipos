<?php

namespace App\Services;

use App\Models\Balita;
use App\Repositories\Interfaces\BalitaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class BalitaService extends BaseService
{
    public function __construct(
        BalitaRepositoryInterface $repository
    ) {
        parent::__construct($repository);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data, $foto = null): Balita
    {
        if ($foto) {
            $data['foto'] = $foto->store('balita', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data, $foto = null): Balita
    {
        $balita = $this->repository->findById($id);

        if ($foto) {
            if ($balita->foto) {
                Storage::disk('public')->delete($balita->foto);
            }
            $data['foto'] = $foto->store('balita', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function findById(string $id): Balita
    {
        return $this->repository->findById($id);
    }
}
