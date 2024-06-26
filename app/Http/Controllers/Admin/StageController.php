<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CalcTotalAction;
use App\Actions\Admin\CreateFleetsAction;
use App\Actions\Admin\FinishStage;
use App\Actions\Admin\SortGroupResultAction;
use App\Actions\Admin\StageStartAction;
use App\Http\Controllers\Controller;
use App\Http\Repositories\RaceRepository;
use App\Http\Repositories\StageRepository;
use App\Http\Repositories\TeamRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\StageGroupCreateRequest;
use App\Http\Requests\StageStoreRequest;
use App\Http\Requests\StageUpdateRequest;
use App\Http\Requests\StoreTeamResultRequest;
use App\Models\Race;
use App\Models\RaceTeam;
use App\Models\Stage;
use App\Models\StageResult;
use App\Models\StageTeam;
use App\Models\StageUser;

class StageController extends Controller
{
    protected $stageRepository;
    protected $raceRepository;
    protected $teamRepository;
    protected $userRepository;

    public function __construct() {
        $this->stageRepository = app(StageRepository::class);
        $this->raceRepository = app(RaceRepository::class);
        $this->userRepository = app(UserRepository::class);
        $this->teamRepository = app(TeamRepository::class);
    }

    public function store(StageStoreRequest $request) {
        $stage = Stage::query()->create($request->only([
            'tournament_id', 'register_start', 'register_end',
            'title', 'excerpt',
            'description',
        ]));

        return $stage;
    }

    public function tournament($id)
    {
        return $this->stageRepository->getByTournamentId($id);
    }

    public function edit($id)
    {
        return $this->stageRepository->getByIdWithUsersAdmin($id);
    }

    public function getStageStatusGroup($id)
    {
        return $this->raceRepository->getStageStatusGroup($id);
    }

    public function finish($id, SortGroupResultAction $sortAction, FinishStage $finishStage)
    {
        $stage = $this->stageRepository->getById($id);
        $status = $stage->status;

        if($status === 'fleet' || $status === 'default') {
            $groups = $this->raceRepository->getStageStatusGroup($id)[$status];
            $drops = $this->stageRepository->getStageDrops($id, $status);
            $groupsResult = [];

            foreach($groups as $groupId) {
                $result = $this->userRepository->getGroupData($id, $groupId, $status);
                $groupsResult = array_merge($groupsResult, $sortAction($result, $drops)->toArray());
            }

            return $finishStage($stage, $groupsResult);
        }

        return abort(400, 'Этап уже закончился, обновите страницу');
    }

    public function finishGroup($id)
    {
        $stage = $this->stageRepository->getById($id);
        $status = $stage->status;

        if($status !== 'group') {
            abort(400, 'Групповой этап уже закончился, обновите страницу');
        }

        $stage->update(['status'=>'finished']);

        return ['result'=> true];
    }

    public function getTotal($stageId, $groupId, $status, CalcTotalAction $action)
    {
        $result = $this->teamRepository->getGroupData($stageId, $groupId, $status);
        $drops = $this->stageRepository->getStageDrops($stageId, $status);

        return $action($result, $drops);
    }

    public function update(StageUpdateRequest $request, $id)
    {
        $stage = $this->stageRepository->getById($id);

        $stage->update($request->only([
            'register_start', 'register_end',
            'title', 'excerpt', 'description',
            'race_amount_drop', 'race_amount_group_drop', 'race_amount_fleet_drop',
            'participant_text',
        ]));

        return true;
    }

    public function group(StageGroupCreateRequest $request, $id)
    {
        $stage = $this->stageRepository->getById($id);
        $uniqueGroups = array_unique($request->groups);
        $races = array();
        foreach($uniqueGroups as $groupId) {
            $races[$groupId] = Race::query()->create([
                'stage_id' => $id,
                'group_id' => $groupId,
                'status' => 'group',
            ]);
        }
        foreach ($request->groups as $teamId => $groupId) {
            RaceTeam::query()->create([
                'race_id' => $races[$groupId]['id'],
                'team_id' => $teamId,
            ]);
            StageTeam::query()->create([
               'stage_id' => $id,
               'team_id' => $teamId,
            ]);
        }

        $stage->update(['status' => 'group']);

        return ['result' => true];
    }

    public function removeTeamFromStage($teamId, $stageId)
    {
        $team = $this->teamRepository->getById($teamId);
        $users = $team->users;

        $stage = $this->stageRepository->getById($stageId);
        if( $stage->status === 'active')
        {
            foreach ($users as $user) {
                $user->stages()->detach($stageId);
            }
            return ['result' => true];
        } else {
            return ['result'=> false, 'msg' => 'Ошибка, этап находиться не в статусе распределения групп'];
        }
    }

    public function getStageResults($id)
    {
//        return StageTeam::query()
//            ->join('races', 'races.stage_id', '=', 'stage_team.stage_id')
//            ->where(['stage_team.stage_id' => $id])
//            ->get()
//            ->groupBy('team_id');
        return StageTeam::query()
            ->addSelect([
            'group_id' => Race::select('group_id')
                ->join('race_team', 'race_team.race_id', '=', 'races.id')
                ->whereColumn('races.stage_id', 'stage_team.stage_id')
                ->whereColumn('race_team.team_id', 'stage_team.team_id')
                ->limit(1)
        ])
            ->where(['stage_team.stage_id' => $id])
            ->get();
    }

    public function storeTeamResult(StoreTeamResultRequest $request)
    {
        foreach ($request->results as $team_id => $place) {
            StageTeam::query()
                ->where('stage_id', $request->stage_id)
                ->where('team_id', $team_id)
                ->update([
                    'result' => $place,
                ]);
        }

        return ['result' => true];
    }

    public function storeUserResult(StoreTeamResultRequest $request)
    {
        foreach ($request->results as $team_id => $result) {
            $teamUsers = StageUser::query()
                ->where('stage_id', $request->stage_id)
                ->where('team_id', $team_id)
                ->get();
            foreach ($teamUsers as $user) {
                StageResult::query()->updateOrCreate([
                    'stage_id' => $request->stage_id,
                    'user_id' => $user->user_id,
                ],
                [
                    'result' => $result,
                ]);
            }
        }


        return ['result' => true];


        return ['result' => true];
    }

}
