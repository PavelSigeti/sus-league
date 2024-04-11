<?php

namespace App\Http\Repositories;

use App\Models\Tournament as Model;

class TournamentRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAll()
    {
        $columns = [
            'id', 'title', 'yacht',
        ];
        $result = $this->startConditions()
            ->select($columns)
            ->orderBy('id', 'desc')
            ->get();

        return $result;
    }

    public function getById($id)
    {
        $columns = [
            'id', 'title', 'yacht', 'description'
        ];
        $result = $this->startConditions()
            ->select($columns)
            ->find($id);

        return $result;
    }
}
