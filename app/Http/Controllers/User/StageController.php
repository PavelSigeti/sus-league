<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Repositories\StageRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StageController extends Controller
{
    protected $stageRepository;

    public function __construct()
    {
        $this->stageRepository = app(StageRepository::class);
    }

    public function actual()
    {
        $user = Auth::user();
        return $this->stageRepository->getActual($user->id);
    }

    public function actualDashboard()
    {
        $user = Auth::user();
        return $this->stageRepository->getActualDashboard($user->id);
    }

    public function registeredStage()
    {
        $user = Auth::user();

        return $this->stageRepository->getUserStages($user->id);
    }

    public function ended()
    {
        $user = Auth::user();

        return $this->stageRepository->getEnded($user->id);
    }

    public function accept($id)
    {
        $stage = $this->stageRepository->getById($id);
        $now = Carbon::now()->format('Y-m-d\TH:i');
        $user = Auth::user();

        if($user->stages->where('id', $id)->count() !== 0) {
            return abort('400', 'Ошибка, обновите страницу');
        }
        if($stage->status === 'active' &&
            $stage->register_end > $now
            && $stage->register_start <= $now)
        {
            $user->stages()->attach($id, ['team_id' => $user->team_id, 'nickname' => $user->nickname]);
        } else {
            return abort('400', 'Ошибка, на регату не зарегестрироваться');
        }
    }

    public function cancel($id)
    {
        $stage = $this->stageRepository->getById($id);
        $now = Carbon::now()->format('Y-m-d\TH:i');
        $user = Auth::user();

        if($user->stages->where('id', $id)->count() === 0) {
            return abort('400', 'Ошибка, обновите страницу');
        }

        if( $stage->status === 'active' &&
            $stage->register_end > $now
            && $stage->register_start <= $now)
        {
            $user->stages()->detach($id);

        } else {
            return abort('400', 'Ошибка, гонка уже началась');
        }
    }


    public function showForUsers($id)
    {
        $user = Auth::user();

        if($user->stages->where('id', $id)->count() === 1) {
            return $this->stageRepository->getByIdWithUsersAdmin($id);
        }

        return $this->stageRepository->getByIdWithUsers($id);
    }

}
