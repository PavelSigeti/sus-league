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
use App\Http\Repositories\UserRepository;
use App\Http\Requests\StageStoreRequest;
use App\Http\Requests\StageUpdateRequest;
use App\Models\Stage;

class StageController extends Controller
{
    protected $stageRepository;
    protected $raceRepository;
    protected $userRepository;

    public function __construct() {
        $this->stageRepository = app(StageRepository::class);
        $this->raceRepository = app(RaceRepository::class);
        $this->userRepository = app(UserRepository::class);
    }

    public function store(StageStoreRequest $request) {
        $stage = Stage::query()->create($request->only([
            'tournament_id', 'register_start', 'register_end',
            'race_start', 'title', 'excerpt',
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

    public function startStage($id, StageStartAction $action)
    {
        $stage = $this->stageRepository->getByIdWithRaces($id);
        if($stage->status !== 'active') {
            abort(400, 'Этап уже начался, обновите страницу');
        }
        return $action($stage);
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

    public function finishGroup($id, SortGroupResultAction $sortAction, CreateFleetsAction $createFleetsAction)
    {
        $stage = $this->stageRepository->getById($id);
        $status = $stage->status;

        if($status !== 'group') {
            abort(400, 'Групповой этап уже закончился, обновите страницу');
        }

        $groups = $this->raceRepository->getStageStatusGroup($id)[$status];

        $drops = $this->stageRepository->getStageDrops($id, $status);

        $groupsResult = [];
        foreach($groups as $groupId) {
            $result = $this->userRepository->getGroupData($id, $groupId, $status);
            $groupsResult[$groupId] = $sortAction($result, $drops);
        }

        return $createFleetsAction($groupsResult, $stage);
    }

    public function getTotal($stageId, $groupId, $status, CalcTotalAction $action)
    {
        $result = $this->userRepository->getGroupData($stageId, $groupId, $status);
        $drops = $this->stageRepository->getStageDrops($stageId, $status);

        return $action($result, $drops);
    }

    public function update(StageUpdateRequest $request, $id)
    {
        $stage = $this->stageRepository->getById($id);

        $stage->update($request->only([
            'register_start', 'register_end', 'race_start',
            'title', 'excerpt', 'description',
            'race_amount_drop', 'race_amount_group_drop', 'race_amount_fleet_drop',
            'participant_text',
        ]));

        return true;
    }
}
