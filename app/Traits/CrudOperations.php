<?php

namespace App\Traits;

trait CrudOperations
{
    public function findByUuid($uuid, $columns = ['*'], $relationsWithColumns = [])
    {
        $query = $this->getModel()->where('uuid', $uuid)->select($columns);

        foreach ($relationsWithColumns as $relation => $relationColumns) {
            $query->with([$relation => function ($query) use ($relationColumns) {
                $query->select($relationColumns);
            }]);
        }

        return $query->firstOrFail();
    }

    public function updateByUuid($uuid, $data)
    {
        $model = $this->getModel()->where('uuid', $uuid)->firstOrFail();
        $model->update($data);
        return $model;
    }

    public function deleteByUuid($uuid)
    {
        $model = $this->getModel()->where('uuid', $uuid)->firstOrFail();
        $model->delete();
        return $model;
    }

    protected abstract function getModel();
}
