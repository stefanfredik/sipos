<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\Interfaces\BidanRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

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
        // Create user first
        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'nama_user' => $data['nama_bidan'],
            'role' => UserRole::Bidan,
        ]);

        // Add user_id to data and remove username/password
        $data['user_id'] = $user->id;
        unset($data['username'], $data['password']);

        if (isset($data['foto_bidan'])) {
            $data['foto_bidan'] = $this->fileUploadService->upload($data['foto_bidan'], 'bidan');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        $bidan = $this->repository->findById($id);

        // Update username if provided
        if (isset($data['username'])) {
            $bidan->user->update(['username' => $data['username']]);
            unset($data['username']);
        }

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
