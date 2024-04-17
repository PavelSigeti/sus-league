<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TeamInviteRepository;
use App\Http\Repositories\TeamRepository;
use App\Http\Requests\RemoveTeammateRequest;
use App\Http\Requests\TeamStoreRequest;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{
    protected $teamInviteRepository;
    protected $teamRepository;

    public function __construct()
    {
        $this->teamInviteRepository = app(TeamInviteRepository::class);
        $this->teamRepository = app(TeamRepository::class);
    }

    public function show()
    {
        $user = Auth::user();
        return $user->teams;
    }

    public function edit($id)
    {
        $team = $this->teamRepository->getById($id);

        return $team->users->map->only(['id', 'name', 'surname', 'patronymic']);
    }

    public function store(TeamStoreRequest $request)
    {
        $user = Auth::user();
        $teamAmount = $this->teamRepository->getTeamsAmount($user['id']);
        if ($teamAmount >= 3) return abort(422, 'Вы уже состоите в 3-x командах');

        $data = [
            'name' => $request->name,
            'user_id' => $user['id'],
        ];

//        dd($data);

        $team = Team::query()->create($data);
        $team->users()->sync($user['id']);
        $teamAmount++;

        if ($teamAmount >= 3) $this->teamInviteRepository->deleteUsersInvites($user['id']);

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

    public function removeTeammate(RemoveTeammateRequest $request)
    {
        $team = $this->teamRepository->getById($request->team_id);
        try {
            if($team->user_id === $request->user_id) {
                TeamUser::query()->where('team_id', $team->id)->delete();
                $team->update(['user_id' => null]);
            } else {
                TeamUser::query()->where('team_id', $team->id)->where('user_id', $request->user_id)->delete();
            }
            return ['result' => true];
        }
        catch (\Exception $e) {
            return abort(400, 'Ошибка! Перезагрузите страницу');
        }
    }
}
