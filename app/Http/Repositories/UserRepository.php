<?php

namespace App\Http\Repositories;

use App\Models\User as Model;
use Illuminate\Support\Facades\DB;


class UserRepository extends CoreRepository
{

    protected function getModelClass()
    {
        return Model::class;
    }

    public function getStageData($id)
    {
        $result = $this->startConditions()
            ->join('race_user', 'users.id', '=', 'race_user.user_id')
            ->join('stage_user', 'users.id', '=', 'stage_user.user_id')
            ->join('races', 'race_user.race_id', '=', 'races.id')
            ->select('race_user.race_id', 'users.id', 'race_user.place',
                'races.group_id', 'users.name', 'races.stage_id',
                'races.status',
            )
            ->where('races.stage_id', $id)
            ->orderBy('race_user.race_id')
            ->get()
            ->groupBy(['status', 'group_id', 'id']);

        return $result;
    }

    public function getGroupData($stageId, $groupId, $status)
    {
        $result = $this->startConditions()
            ->join('race_user', 'users.id', '=', 'race_user.user_id')
            ->join('races', 'race_user.race_id', '=', 'races.id')
            ->join('stage_user', function($join) {
                $join->on('users.id', '=', 'stage_user.user_id');
                $join->on('races.stage_id', '=', 'stage_user.stage_id');
            })
            ->select('race_user.race_id', 'users.id', 'race_user.place',
                'races.group_id', 'races.stage_id',
                'races.status', 'users.name', 'users.surname'
            )
            ->where('races.stage_id', $stageId)
            ->where('races.group_id', $groupId)
            ->where('races.status', $status)
            ->orderBy('race_user.race_id')
            ->get()
            ->groupBy(['id']);

        return $result;
    }

    public function getSettings($id)
    {
        $result = $this->startConditions()
            ->select(['email', 'university_id', 'universities.name as university'])
            ->leftJoin('universities', 'users.university_id', '=', 'universities.id')
            ->find($id);

        return $result;
    }

    public function getProfile($id)
    {
        $result = $this->startConditions()
            ->select([
                'users.id',
                'users.name',
                'users.surname',
                'users.patronymic',
                DB::raw("DATE_FORMAT(users.created_at, '%d.%m.%Y') as registration_date"),
                DB::raw("TIMESTAMPDIFF(YEAR, users.birth, CURDATE()) as age"),
                'universities.name as university',
            ])
            ->leftJoin('universities', 'users.university_id', '=', 'universities.id')
            ->find($id);

        return $result;
    }


    public function getStatistics($id)
    {
        $result = $this->startConditions()
            ->leftJoin('stage_user', 'users.id', '=', 'stage_user.user_id')
            ->leftJoin('races', 'stage_user.stage_id', '=', 'races.stage_id')
            ->leftJoin('race_team', function ($join) {
                $join->on('races.id', '=', 'race_team.race_id')
                     ->on('stage_user.team_id', '=', 'race_team.team_id');
            })
            ->leftJoin('stage_team', function ($join) {
                $join->on('stage_user.stage_id', '=', 'stage_team.stage_id')
                     ->on('stage_user.team_id', '=', 'stage_team.team_id');
            })
            ->leftJoin('stage_results', function ($join) {
                $join->on('stage_user.user_id', '=', 'stage_results.user_id')
                     ->on('stage_user.user_id', '=', 'stage_results.user_id');
            })
            ->select([
                'users.id',
                DB::raw('COUNT(DISTINCT stage_user.stage_id) as stages_count'),

                DB::raw('COUNT(DISTINCT race_team.id) as races_count'),

                DB::raw('ROUND(COALESCE(AVG(
                    CASE
                        WHEN race_team.place = 0 THEN CAST(REGEXP_SUBSTR(race_team.note, "[0-9]+") AS SIGNED)
                        ELSE race_team.place
                    END
                ), 0), 1) as avg_place'),

                DB::raw('COUNT(DISTINCT CASE
                            WHEN stage_team.result = (SELECT MAX(st2.result)
                                                      FROM stage_team st2
                                                      WHERE st2.stage_id = stage_team.stage_id)
                            THEN stage_user.stage_id
                            END) as wins_stages'),

                DB::raw('CONCAT(ROUND(
                                (COUNT(DISTINCT CASE
                                    WHEN (race_team.place = 1 OR (race_team.place = 0 AND CAST(REGEXP_SUBSTR(race_team.note, "[0-9]+") AS SIGNED) = 1))
                                    THEN race_team.id
                                END) * 100.0)
                                / NULLIF(COUNT(DISTINCT race_team.id), 0), 1), "%") as win_rate'),

                DB::raw('COUNT(DISTINCT CASE
                            WHEN (race_team.place IN (1, 2, 3) OR
                                  (race_team.place = 0 AND CAST(REGEXP_SUBSTR(race_team.note, "[0-9]+") AS SIGNED) IN (1, 2, 3)))
                            THEN race_team.id
                            END) as top3_count'),
            ])
            ->where('users.id', $id)
            ->groupBy('users.id')
            ->first();

        return $result;
    }

    public function getRating($id)
    {
        $currentYear = date('Y');

        $result = $this->startConditions()
            ->leftJoin('stage_user', 'users.id', '=', 'stage_user.user_id')
            ->leftJoin('races', 'stage_user.stage_id', '=', 'races.stage_id')
            ->leftJoin('race_team', function ($join) {
                $join->on('races.id', '=', 'race_team.race_id')
                     ->on('stage_user.team_id', '=', 'race_team.team_id');
            })
            ->leftJoin('stage_team', function ($join) {
                $join->on('stage_user.stage_id', '=', 'stage_team.stage_id')
                     ->on('stage_user.team_id', '=', 'stage_team.team_id');
            })
            ->select([
                DB::raw('COUNT(DISTINCT race_team.id) as total_races'),

                DB::raw('ROUND(COALESCE(AVG(
                    CASE
                        WHEN race_team.place = 0 THEN CAST(REGEXP_SUBSTR(race_team.note, "[0-9]+") AS SIGNED)
                        ELSE race_team.place
                    END
                ), 0), 1) as avg_place'),

                DB::raw('CONCAT(ROUND(
                    (COUNT(DISTINCT CASE
                        WHEN (race_team.place = 1 OR (race_team.place = 0 AND CAST(REGEXP_SUBSTR(race_team.note, "[0-9]+") AS SIGNED) = 1))
                        THEN race_team.id
                    END) * 100.0)
                    / NULLIF(COUNT(DISTINCT race_team.id), 0), 1), "%") as win_rate'),

                DB::raw('COUNT(DISTINCT CASE
                            WHEN stage_team.result = (SELECT MAX(st2.result)
                                                      FROM stage_team st2
                                                      WHERE st2.stage_id = stage_team.stage_id)
                            THEN stage_user.stage_id
                            END) as wins_stages'),
            ])
            ->where('users.id', $id)
            ->whereYear('races.created_at', $currentYear)
            ->groupBy('users.id')
            ->first();

        //TODO:: Внизу в контроллер

        $data = [
            ['name' => 'Всего гонок', 'score' => $result->total_races ?? 0],
            ['name' => 'Среднее место в гонке', 'score' => $result->avg_place ?? 0],
            ['name' => 'Процент побед в гоноках', 'score' => $result->win_rate ?? "0%"],
            ['name' => 'Победы в этапах', 'score' => $result->wins_stages ?? 0]
        ];

        return $data;
    }
}
