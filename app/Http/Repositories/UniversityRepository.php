<?php

namespace App\Http\Repositories;

use App\Models\University as Model;

class UniversityRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAll()
    {
        return $this->startConditions()->get();
    }

    public function getAllForReg()
    {
        $result = $this->startConditions()
            ->select(['id as code', 'name as label'])
            ->orderBy('code')
            ->get();
        return $result;
    }

    public function getById($id)
    {
        return $this->startConditions()->find($id);
    }

    public function getWithUserAmount()
    {
        $columns = [
            'id', 'name',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->withCount(['users'])
            ->orderBy('id')
            ->get();
            ;

        return $result;
    }
}
