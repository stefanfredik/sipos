<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function all(array $filters = []): LengthAwarePaginator|Collection;
    
    public function findById(string $id): ?Model;
    
    public function create(array $data): Model;
    
    public function update(string $id, array $data): Model;
    
    public function delete(string $id): bool;
}
