<?php

namespace App\Traits;

trait CrudOperations
{
    public function findByUuid($uuid, $columns = ['*'])
    {
        return $this->model()->where('uuid', $uuid)->select($columns)->firstOrFail();
    }

    public function updateByUuid($uuid, $data)
    {
        $model = $this->model()->where('uuid', $uuid)->firstOrFail();
        $model->update($data);
        return $model;
    }

    public function deleteByUuid($uuid)
    {
        $model = $this->model()->where('uuid', $uuid)->firstOrFail();
        $model->delete();
        return $model;
    }

    protected abstract function model();
}
