<?php

namespace App\Repositories\Interfaces;

interface KaderRepositoryInterface extends BaseRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
}
