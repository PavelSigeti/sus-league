<?php

namespace App\Http\Repositories;

use App\Http\Repositories\CoreRepository;
use App\Models\Team as Model;
class TeamRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getTeamsAmount($id)
    {
        return $this->startConditions()->where('user_id', $id)->count();
    }

    public function getById($id) {
        return $this->startConditions()->find($id);
    }
}
