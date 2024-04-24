<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TeamInviteRepository;
use App\Http\Repositories\TeamRepository;
use App\Http\Requests\TeamInviteStoreRequest;
use App\Models\TeamInvite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamInviteController extends Controller
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
        $teamAmount = $this->teamRepository->getTeamsAmount($user['id']);

        if ($teamAmount >= 3) $this->teamInviteRepository->deleteUsersInvites($user['id']);

        $invites = $this->teamInviteRepository->getByUserId($user['id']);

        return ['invites' => $invites];
    }

    public function store(TeamInviteStoreRequest $request)
    {
        $user = Auth::user();
        $team = $this->teamRepository->getById($request->team_id);

        $userRequest = User::query()->find($request->user_id);
        $check = TeamInvite::query()->where('user_id', $request->user_id)->where('team_id', $request->team_id)->count();
        if($check > 0) {
            return abort(400, 'Вы уже пригласили этого пользователя');
        }
        $countInvites = $this->teamInviteRepository->countUsersInvites($request->user_id);
        if($countInvites >= 5) {
            return abort(400, 'Пользователя пригласили уже более пяти команд. Попросите его отклонить приглашения');
        }
        if($user->id === $team->user_id && count($team->users) < 4 && count($userRequest->teams) < 3){
            $teamInvite = TeamInvite::query()->create($request->only(['user_id', 'team_id']));
            $response = $userRequest->only(['name', 'surname']);
            $response['user_id'] = $request->user_id;
            $response['id'] = $teamInvite->id;

            return $response;
        }
        return abort(400, 'Ошибка! В команде уже 4 пользователя');
    }

    public function destroy($id) {
        $user = Auth::user();
        $teamInvite = $this->teamInviteRepository->getById($id);
        $team = $this->teamRepository->getById($teamInvite->team_id);
        if($user->id === $teamInvite->user_id || $team->user_id === $user->id) {
            $teamInvite->delete();
            return true;
        }
        return abort(400, 'Ошибка при отмене приглашения');
    }

    public function accept($id)
    {
        $user = Auth::user();
        $teamInvite = $this->teamInviteRepository->getById($id);
        $teamAmount = $this->teamRepository->getTeamsAmount($user['id']);

        if($teamInvite->user_id === $user->id && $teamAmount < 3) {
            $team = $this->teamRepository->getById($teamInvite->team_id);
            $team->users()->attach($user['id']);
            TeamInvite::query()->where('user_id', $user->id)->delete();

            return true;
        }

        return abort(400, 'Ошибка при принятии приглашения. Возможно вы уже в команде');
    }
}
