<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\RemoveTeammateRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
    }

    public function search(UserSearchRequest $request)
    {
        $select = [
          'id', 'nickname', 'name', 'surname',
        ];
        $result = User::query()
            ->select($select)
            ->whereNull('team_id')
            ->where(function($query) use ($request) {
                $query->where('nickname', 'LIKE', "%{$request->user}%");
                $query->orWhere('id', 'LIKE', "%{$request->user}%");
            })
            ->get();

        return $result;
    }

    public function removeTeammate(RemoveTeammateRequest $request)
    {
        $auth = Auth::user();
        if($request->id === null) {
            $user = $auth;
        } else {
            $user = User::find($request->id);
        }
        $team = Team::find($user->team_id);

        if( ($auth->id === $user->id || $auth->id === $team->owner_id) && $user->id !== $team->owner_id) {
            $user->update([
                'team_id' => null,
            ]);
            return true;
        } else {
            return abort(400, 'Ошибка! Перезагрузите страницу');
        }
    }

    public function settings()
    {
        $user = Auth::user();

        return $this->userRepository->getSettings($user->id);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = Auth::user();
        if($user->university_id !== null) {
            $user->update([
                'nickname' => $request->nickname,
            ]);
        } else {
            $user->update([
                'nickname' => $request->nickname,
                'university_id' => $request->university_id,
            ]);
        }
    }
}
