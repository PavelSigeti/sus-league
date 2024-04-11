<?php

namespace App\Http\Controllers\Guest;

use App\Actions\User\CalcStageGroupTable;
use App\Http\Controllers\Controller;
use App\Http\Repositories\RaceRepository;
use App\Http\Repositories\StageRepository;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

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

    public function getResult($stageId, CalcStageGroupTable $action)
    {
        $groupStatus = $this->raceRepository->getGroupIdsById($stageId);

        $response = [];

        foreach ($groupStatus as $status => $group) {
            foreach ($group as $groupId) {
                $result = $this->userRepository->getGroupData($stageId, $groupId, $status);
                $drops = $this->stageRepository->getStageDrops($stageId, $status);
                $response[$status][$groupId] = $action($result, $drops);
            }
        }

        return $response;

    }

    public function getEnded()
    {
        return $this->stageRepository->getAllEnded();
    }

    public function getActual()
    {
        return $this->stageRepository->getAllActual();
    }

    public function show($id)
    {
        return $this->stageRepository->getByIdWithUsers($id);
    }
}
