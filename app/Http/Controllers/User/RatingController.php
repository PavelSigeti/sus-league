<?php

namespace App\Http\Controllers\User;

use App\Http\Repositories\StageResultRepository;
use App\Http\Controllers\Controller;


class RatingController extends Controller
{
    protected $stageResultRepository;

    public function __construct()
    {
        $this->stageResultRepository = app(StageResultRepository::class);
        $this->year = date('Y');
    }

    public function userPersonalRating($id)
    {
        $ratings = $this->stageResultRepository->getUserPersonalRatingData($this->year);
    
        $rankedUsers = $ratings->map(function ($item, $index) use ($id) {
            return [
                'name' => ($index + 1) . '. ' . $item->name . ' ' . $item->surname,
                'score' => $item->total,
                'user_id' => $item->id,
                'is_user' => $item->id == $id
            ];
        });
    
        $userIndex = $rankedUsers->search(fn($item) => $item['user_id'] == $id);
    
        if ($userIndex !== false) {
            if ($userIndex < 3) {
                $result = $rankedUsers->slice(0, 4)->values();
            } else {
                $result = $rankedUsers->slice(0, 2)->values();
                $result->push(['name' => '', 'score' => '', 'is_user' => false]);
                $result->push($rankedUsers[$userIndex]);
            }
        } else {
            $result = $rankedUsers->slice(0, 3)->values();
        }
    
        return response()->json($result->map(fn($item) => [
            'name' => $item['name'],
            'score' => $item['score'],
            'is_user' => $item['is_user']
        ]));
    }

    public function userTeamRating($id)
    {        
        $userTeam = $this->stageResultRepository->getUserTeamData($id, $this->year);
        
        if (!$userTeam) {
            return response()->json([]);
        }
    
        $teamRatings = $this->stageResultRepository->getTeamRatings($this->year);
    
        $rankedTeams = $teamRatings->map(function ($item, $index) use ($userTeam) {
            return [
                'name' => ($index + 1) . '. ' . $item->name,
                'score' => $item->total,
                'team_id' => $item->id,
                'is_user' => $item->id == $userTeam->id
            ];
        });
    
        $teamIndex = $rankedTeams->search(fn($item) => $item['team_id'] == $userTeam->id);
    
        if ($teamIndex !== false) {
            if ($teamIndex < 3) {
                $result = $rankedTeams->slice(0, 4)->values();
            } else {
                $result = $rankedTeams->slice(0, 2)->values();
                $result->push(['name' => '', 'score' => '', 'is_user' => false]);
                $result->push($rankedTeams[$teamIndex]);
            }
        } else {
            $result = $rankedTeams->slice(0, 3)->values();
        }
    
        return response()->json($result->map(fn($item) => [
            'name' => $item['name'],
            'score' => $item['score'],
            'is_user' => $item['is_user']
        ]));
    }

    public function userUniversityRating($id)
    {
        $userUniversityId = $this->stageResultRepository->getUserUniversityId($id);
        
        if (!$userUniversityId) {
            return response()->json([]);
        }
    
        $universityRatings = $this->stageResultRepository->getUniversityRatings($this->year);
    
        $rankedUniversities = $universityRatings->map(function ($item, $index) use ($userUniversityId) {
            return [
                'name' => ($index + 1) . '. ' . $item->name,
                'score' => $item->total,
                'university_id' => $item->id,
                'is_user' => $item->id == $userUniversityId
            ];
        });
    
        $universityIndex = $rankedUniversities->search(fn($item) => $item['university_id'] == $userUniversityId);
    
        if ($universityIndex !== false) {
            if ($universityIndex < 3) {
                $result = $rankedUniversities->slice(0, 4)->values();
            } else {
                $result = $rankedUniversities->slice(0, 2)->values();
                $result->push(['name' => '', 'score' => '', 'is_user' => false]);
                $result->push($rankedUniversities[$universityIndex]);
            }
        } else {
            $result = $rankedUniversities->slice(0, 3)->values();
        }
    
        return response()->json($result->map(fn($item) => [
            'name' => $item['name'],
            'score' => $item['score'],
            'is_user' => $item['is_user']
        ]));
    }

}
