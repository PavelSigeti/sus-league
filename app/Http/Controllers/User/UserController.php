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
          'id', 'name', 'surname',
        ];
        $result = User::query()
            ->select($select)
            ->where(function($query) use ($request) {
                $query->where('surname', 'LIKE', "%{$request->user}%");
                $query->orWhere('email', 'LIKE', "%{$request->user}%");
                $query->orWhere('id', 'LIKE', "%{$request->user}%");
            })
            ->get();

        return $result;
    }

    public function getProfile($id)
    {
        return $this->userRepository->getProfile($id);
    }

    public function getStatistics($id)
    {
        return $this->userRepository->getStatistics($id);
    }

    public function settings()
    {
        $user = Auth::user();

        return $this->userRepository->getSettings($user->id);
    }

    public function update(UserUpdateRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'university_id' => $request->university_id,
        ]);

        return true;
    }
}
