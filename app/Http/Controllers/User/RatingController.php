<?php

namespace App\Http\Controllers\User;

use App\Http\Repositories\StageResultRepository;
use App\Models\StageResult;
use App\Models\StageTeam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\RemoveTeammateRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class RatingController extends Controller
{
    protected $stageResultRepository;

    public function __construct()
    {
        $this->stageResultRepository = app(StageResultRepository::class);
    }

    public function userPersonalRating($id)
    {
        return $this->stageResultRepository->getUserPersonalRating($id);
    }
    
    public function userTeamRating($id)
    {
        return $this->stageResultRepository->getUserTeamRating($id);
    }
    
    public function userUniversityRating($id)
    {
        return $this->stageResultRepository->getUserUniversityRating($id);
    }

}
