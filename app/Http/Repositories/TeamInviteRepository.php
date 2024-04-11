<?php

namespace App\Http\Repositories;

use App\Models\TeamInvite as Model;

class TeamInviteRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getById($id)
    {
        return $this->startConditions()->find($id);
    }

    public function getByUserId($id)
    {
        $columns = [
            'name', 'user_id', 'team_invites.id',
        ];


        $result = $this->startConditions()
            ->join('teams', 'team_invites.team_id', '=', 'teams.id')
            ->select($columns)
            ->where('user_id', $id)
            ->get();

        return $result;
    }

    public function getByTeamId($id)
    {
        $columns = [
          'name', 'surname', 'nickname',
          'user_id', 'team_invites.id',
        ];

        $result = $this->startConditions()
            ->join('users', 'team_invites.user_id', '=', 'users.id')
            ->select($columns)
            ->where('team_invites.team_id', $id)
            ->get();

        return $result;
    }
    public function deleteUsersInvites($id) {
        return $this->startConditions()
            ->where('user_id', $id)
            ->delete();
    }
    public function countUsersInvites($id) {
        return $this->startConditions()
            ->where('user_id', $id)
            ->count();
    }

}
