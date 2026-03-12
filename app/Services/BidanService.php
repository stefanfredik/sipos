<?php

namespace App\Services;

use App\Repositories\Interfaces\BidanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class BidanService extends BaseService
{
    private FileUploadService $fileUploadService;

    public function __construct(BidanRepositoryInterface $repository, FileUploadService $fileUploadService)
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
        if (isset($data['foto_bidan'])) {
            $data['foto_bidan'] = $this->fileUploadService->upload($data['foto_bidan'], 'bidan');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        $bidan = $this->repository->findById($id);

        if (isset($data['foto_bidan'])) {
            if ($bidan->foto_bidan) {
                $this->fileUploadService->delete($bidan->foto_bidan);
            }
            $data['foto_bidan'] = $this->fileUploadService->upload($data['foto_bidan'], 'bidan');
        }

        return $this->repository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        $bidan = $this->repository->findById($id);
        if ($bidan->foto_bidan) {
            $this->fileUploadService->delete($bidan->foto_bidan);
        }

        return $this->repository->delete($id);
    }
}
