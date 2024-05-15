<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateRaceAction;
use App\Http\Controllers\Controller;
use App\Http\Repositories\RaceRepository;
use App\Http\Requests\CreateRaceRequest;
use App\Http\Requests\StoreResultsRequest;

class RaceController extends Controller
{
    protected $raceRepository;

    public function __construct() {
        $this->raceRepository = app(RaceRepository::class);
    }

    public function getStageRaces($stageId, $status, $groupId)
    {
        return $this->raceRepository->getStageRaces($stageId, $status, $groupId);
    }

    public function getRaceUsers($id)
    {
        return $this->raceRepository->getRaceUsers($id);
    }

    public function getRaceTeams($id)
    {
        return $this->raceRepository->getRaceTeams($id);
    }

    public function getRacePlace($id)
    {

        $result = $this->raceRepository->getRacePlace($id);

        $places = $result
            ->mapWithKeys(function ($item) {
                return [$item['team_id'] => $item['place']];
            });

        $notes = $result
            ->mapWithKeys(function ($item) {
                return [$item['team_id'] => $item['note']];
            });

        return ['places' => $places, 'notes' => $notes];
    }

    public function storeResults(StoreResultsRequest $request, $id) {

        $race = $this->raceRepository->getById($id);
        $results = collect($request->result);
        $notes = collect($request->notes);
        $count = $results->count();


        $results = $results->mapWithKeys(function ($item, $key) use ($count) {
            return [$key => ['place' => $item === null ? $count+1 : $item]];
        });

        $notes = $notes->mapWithKeys(function ($item, $key) {
            return [$key => ['note' => $item ]];
        });

        $race->teams()->sync($results);
        $race->teams()->sync($notes);

        return true;
    }

    public function createRace(CreateRaceRequest $request, CreateRaceAction $action)
    {
        $teams = $this->raceRepository->getRaceTeams($request->lastRaceId)->keyBy('team_id');

        $result = $action($request, $teams);

        return $result;
    }

    public function destroy($id)
    {
        $race = $this->raceRepository->getById($id);
        $minRaceId = $this->raceRepository->getFirstRaceInGroup($race->stage_id, $race->group_id, $race->status);

        if($race->id !== $minRaceId) {
            $race->delete();
        }


        return true;
    }

}
