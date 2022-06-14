<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RolesRepositoryInterface
{
    public function all(int $limit = 0, int $offset = 0): object;
    public function store(array $request): Model;
    public function findById(int $id): object;
    public function findByFieldWhereReturnArray(string $field, string $operator, string $value, string $getField): array;
    public function findByFieldWhereReturnObject(string $field, string $operator, string $value): object;
    public function findByFieldWhereIn(string $field, array $value): object;
    public function update(int $id, array $set): void;
    public function delete(int $id): bool;
}
