<?php

namespace App\Services;

use App\DTOs\IbuHamil\CreateIbuHamilDTO;
use App\Models\IbuHamil;
use App\Repositories\Interfaces\IbuHamilRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class IbuHamilService extends BaseService
{
    public function __construct(
        IbuHamilRepositoryInterface $repository
    ) {
        parent::__construct($repository);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function create(CreateIbuHamilDTO $dto): IbuHamil
    {
        $data = $dto->toArray();

        if ($dto->foto) {
            $data['foto'] = $dto->foto->store('ibu_hamil', 'public');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data, $foto = null): IbuHamil
    {
        $ibuHamil = $this->repository->findById($id);

        if ($foto) {
            if ($ibuHamil->foto) {
                Storage::disk('public')->delete($ibuHamil->foto);
            }
            $data['foto'] = $foto->store('ibu_hamil', 'public');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function findById(string $id): IbuHamil
    {
        return $this->repository->findById($id);
    }
}
