<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TeamInviteRepository;
use App\Http\Requests\TeamInviteStoreRequest;
use App\Models\TeamInvite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamInviteController extends Controller
{
    protected $teamInviteRepository;

    public function __construct()
    {
        $this->teamInviteRepository = app(TeamInviteRepository::class);
    }


    public function store(TeamInviteStoreRequest $request)
    {
        $user = Auth::user();
        $team = $user->team;

        $userRequest = User::query()->find($request->user_id);
        $check = TeamInvite::query()->where('user_id', $request->user_id)->where('team_id', $request->team_id)->count();
        if($check > 0) {
            return abort(400, 'Вы уже пригласили этого пользователя');
        }
        $countInvites = $this->teamInviteRepository->countUsersInvites($request->user_id);
        if($countInvites >= 5) {
            return abort(400, 'Пользователя пригласили уже более пяти команд. Попросите его откланить приглашения');
        }
        if($user->id === $team->owner_id && count($team->users) < 3 && $userRequest->team_id === null){
            $teamInvite = TeamInvite::query()->create($request->only(['user_id', 'team_id']));
            $response = $userRequest->only(['name', 'surname', 'nickname']);
            $response['user_id'] = $request->user_id;
            $response['id'] = $teamInvite->id;

            return $response;
        }
        return abort(400, 'Ошибка! В команде уже 3 пользователя.');
    }

    public function destroy($id) {
        $user = Auth::user();
        $teamInvite = $this->teamInviteRepository->getById($id);
        if($user->team_id === $teamInvite->team_id || $user->id === $teamInvite->user_id) {
            $teamInvite->delete();
            return true;
        }
        return abort(400, 'Ошибка при отмене приглашения');
    }

    public function accept($id)
    {
        $user = Auth::user();
        $teamInvite = $this->teamInviteRepository->getById($id);

        if($user->team_id === null && $teamInvite->user_id === $user->id) {
            $user->update([
                'team_id' => $teamInvite->team_id,
            ]);
            TeamInvite::query()->where('user_id', $user->id)->delete();

            return true;
        }

        return abort(400, 'Ошибка при принятии приглашения. Возможно вы уже в команде');
    }
}
