<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TeamInviteRepository;
use App\Http\Repositories\TeamRepository;
use App\Http\Requests\RemoveTeammateRequest;
use App\Http\Requests\TeamStoreRequest;
use App\Models\StageUser;
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
        $teamInvite = $this->teamInviteRepository->getByTeamId($id);

        return [
            'users' => $team->users->map->only(['id', 'name', 'surname', 'patronymic']),
            'invites' => $teamInvite,
        ];
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
        $user = Auth::user();

        $activeStage = StageUser::query()
            ->where('stage_user.user_id', $user['id'])
            ->where('stage_user.team_id', $team->id)
            ->join('stages', 'stage_user.stage_id', '=', 'stages.id')
            ->whereNot('stages.status', 'finished')
            ->count();

        if($activeStage !== 0) {
            return abort(400, 'Команда зарегистрирована на регату!');
        }

        try {
            if($team->user_id === $user->id) {
                TeamUser::query()->where('team_id', $team->id)->where('user_id', $request->user_id)->delete();
                if($request->user_id === $user->id) {
                    $team->update(['user_id' => null]);
                }
            } else {
                TeamUser::query()->where('team_id', $team->id)->where('user_id', $user->id)->delete();
            }
            return ['result' => true];
        }
        catch (\Exception $e) {
            return abort(400, 'Ошибка! Перезагрузите страницу');
        }
    }

    public function capitanTeams()
    {
        $user = Auth::user();

        $teams = Team::query()->where('user_id', $user['id'])->whereHas('users', function ($query) {
            $query->havingRaw('COUNT(*) = 4');
        }, '=', 4)->with('users')->get();

        return [
            'result' => true,
            'teams' => $teams,
        ];
    }
}
