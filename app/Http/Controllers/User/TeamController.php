<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TeamInviteRepository;
use App\Http\Requests\TeamStoreRequest;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{
    protected $teamInviteRepository;

    public function __construct()
    {
        $this->teamInviteRepository = app(TeamInviteRepository::class);
    }


    public function edit()
    {
        $user = Auth::user();
        $owner = ($user->team_id !== null) ? ($user->id === $user->team->owner_id) ? true : false : false;
        $invites = $this->teamInviteRepository->getByUserId($user->id);

        $teamInvites = null;
        $teammates = null;
        if($user->team_id !== null) {
            $teammates = $user->team->users;
        }
        if($owner) {
            $teamInvites = $this->teamInviteRepository->getByTeamId($user->team_id);
        }

        return [
            'team' => $user->team,
            'invites' => $invites,
            'owner' => $owner,
            'teammates' => $teammates,
            'teamInvites' => $teamInvites,
        ];
    }

    public function store(TeamStoreRequest $request)
    {
        $user = Auth::user();
        $team = Team::query()->create([
            'name' => $request->name,
            'owner_id' => $user->id,
        ]);

        $user->update([
            'team_id' => $team->id,
        ]);

        $this->teamInviteRepository->deleteUsersInvites($user->id);

        return $team;
    }

    public function destroy()
    {
        $user = Auth::user();
        $team = Team::find($user->team_id);

        if($user->id === $team->owner_id) {
            TeamInvite::query()->where('team_id', $team->id)->delete();
            User::query()->where('team_id', $team->id)->update([
               'team_id' => null,
            ]);
            $team->update([
                'owner_id' => null,
            ]);

            return true;
        }
        return abort(400, 'Ошибка! Вы не можете удалить команду!');
    }
}
