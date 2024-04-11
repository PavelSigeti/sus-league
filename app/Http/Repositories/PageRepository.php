<?php

namespace App\Http\Repositories;

use App\Models\Page as Model;

class PageRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getById($id)
    {
        return $this->startConditions()->find($id);
    }

    public function getBySlug($slug)
    {
        return $this->startConditions()->where('slug', $slug)->first();
    }

    public function getAll()
    {
        $columns = [
            'id', 'title',
        ];

        $result = $this->startConditions()
            ->select($columns)
            ->get();

        return $result;
    }
}
