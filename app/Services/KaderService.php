<?php

namespace App\Services;

use App\Repositories\Interfaces\KaderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class KaderService extends BaseService
{
    private FileUploadService $fileUploadService;

    public function __construct(KaderRepositoryInterface $repository, FileUploadService $fileUploadService)
    {
        parent::__construct($repository);
        $this->fileUploadService = $fileUploadService;
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function create(array $data)
    {
        if (isset($data['foto_kader'])) {
            $data['foto_kader'] = $this->fileUploadService->upload($data['foto_kader'], 'kader');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        $kader = $this->repository->findById($id);

        if (isset($data['foto_kader'])) {
            if ($kader->foto_kader) {
                $this->fileUploadService->delete($kader->foto_kader);
            }
            $data['foto_kader'] = $this->fileUploadService->upload($data['foto_kader'], 'kader');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        $kader = $this->repository->findById($id);
        if ($kader->foto_kader) {
            $this->fileUploadService->delete($kader->foto_kader);
        }

        return $this->repository->delete($id);
    }
}
