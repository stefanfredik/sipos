<?php

namespace App\Services;

use App\Models\Lansia;
use App\Repositories\Interfaces\LansiaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class LansiaService extends BaseService
{
    public function __construct(
        LansiaRepositoryInterface $repository
    ) {
        parent::__construct($repository);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(array $data, $foto = null): Lansia
    {
        if ($foto) {
            $data['foto'] = $foto->store('lansia', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data, $foto = null): Lansia
    {
        $lansia = $this->repository->findById($id);

        if ($foto) {
            if ($lansia->foto) {
                Storage::disk('public')->delete($lansia->foto);
            }
            $data['foto'] = $foto->store('lansia', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function findById(string $id): Lansia
    {
        return $this->repository->findById($id);
    }
}
