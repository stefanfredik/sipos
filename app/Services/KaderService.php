<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\Interfaces\KaderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

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
        // Create user first
        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'nama_user' => $data['nama_kader'],
            'role' => UserRole::Kader,
        ]);

        // Add user_id to data and remove username/password
        $data['user_id'] = $user->id;
        unset($data['username'], $data['password']);

        if (isset($data['foto_kader'])) {
            $data['foto_kader'] = $this->fileUploadService->upload($data['foto_kader'], 'kader');
        }

        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        $kader = $this->repository->findById($id);

        // Update username if provided
        if (isset($data['username'])) {
            $kader->user->update(['username' => $data['username']]);
            unset($data['username']);
        }

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
