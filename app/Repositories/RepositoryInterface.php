<?php

namespace App\Repositories;

/**
 * Repository Interface
 */
interface RepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete(int $id);

    public function show(int $id);
}
