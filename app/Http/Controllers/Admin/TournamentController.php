<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TournamentRepository;
use App\Http\Requests\TournamentStoreRequest;
use App\Models\Tournament;

class TournamentController extends Controller
{
    protected $tournamentRepository;

    public function __construct() {
        $this->tournamentRepository = app(TournamentRepository::class);
    }

    public function index()
    {
        return $this->tournamentRepository->getAll();
    }

    public function store(TournamentStoreRequest $request)
    {
        $tournament = Tournament::query()->create($request->only([
            'title', 'yacht', 'description',
        ]));

        return ['id' => $tournament->id];
    }

    public function edit($id) {
        return $this->tournamentRepository->getById($id);
    }


    public function update(TournamentStoreRequest $request, $id) {
        $tournament = $this->tournamentRepository->getById($id);
        $tournament->update($request->only([
            'title', 'yacht', 'description',
        ]));

        return true;
    }
}
