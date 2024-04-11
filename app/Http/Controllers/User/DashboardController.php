<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TeamInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function invites()
    {
        $user = Auth::user();
        $invites = null;
        if(!$user->team) {
            $invites = TeamInvite::query()->where('user_id', $user->id);
        }

        return [
            'user' => $user,
            'team' => $user->team,
            'invites' => $invites,
        ];
    }
}
