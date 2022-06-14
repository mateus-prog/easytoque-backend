<?php

namespace App\Repositories\Elouquent;

use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    protected function resolveModel()
    {
        return app($this->model);
    }

    public function all(int $limit = 0, int $offset = 0): object
    {
        try {
            return $this->model->when($limit, function ($query, $limit) {
                return $query->limit($limit);
            })
                ->when($offset && $limit, function ($query, $offset) {
                    return $query->offset($offset);
                })
                ->get()
                ->map->format();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function store(array $request): Model
    {
        try {
            return $this->model->create($request);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function findById(int $id): object
    {
        try {
            return $this->model->findOrFail($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function findByFieldWhereReturnArray(string $field, string $operator, string $value, string $getField): array
    {
        try {
            if(substr_count($getField, ',') > 0) {
                $fields = explode(',', $getField);
                $fields = array_map('trim', $fields);
            }else{
                $fields = [$getField];
            }

            return $this->model->where($field, $operator, $value)
                ->select($fields)
                ->get()
                ->toArray();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function findByFieldWhereReturnObject(string $field, string $operator, string $value): object
    {
        try {
            return $this->model->where($field, $operator, $value)
                ->get()
                ->map->format();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function findByFieldWhereIn(string $field, array $value): object
    {
        try {
            return $this->model->whereIn($field, $value)
                ->get()
                ->map->format();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update(int $id, array $set): void
    {
        try {
            $obj = $this->model->findOrFail($id);

            $obj->update($set);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            //$this->model->update($id, ["deleted_at" => Carbon::now()]);

            $this->model->findOrFail($id)->delete();

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
