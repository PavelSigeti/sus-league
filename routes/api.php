<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/settings', [\App\Http\Controllers\User\DashboardController::class, 'invites']);
    Route::post('/team/store', [\App\Http\Controllers\User\TeamController::class, 'store']);
    Route::get('/team/edit/{id}', [\App\Http\Controllers\User\TeamController::class, 'edit']);
    Route::get('/team/show', [\App\Http\Controllers\User\TeamController::class, 'show']);
    Route::post('/team/remove-teammate', [\App\Http\Controllers\User\TeamController::class, 'removeTeammate']);

    Route::get('/team/capitan', [\App\Http\Controllers\User\TeamController::class, 'capitanTeams']);

    Route::post('/user/search', [\App\Http\Controllers\User\UserController::class, 'search']);
    Route::get('/team-invite/show', [\App\Http\Controllers\User\TeamInviteController::class, 'show']);
    Route::post('/team-invite', [\App\Http\Controllers\User\TeamInviteController::class, 'store']);
    Route::delete('/team-invite/{id}/delete', [\App\Http\Controllers\User\TeamInviteController::class, 'destroy']);
    Route::post('/team-invite/{id}/accept', [\App\Http\Controllers\User\TeamInviteController::class, 'accept']);

    Route::get('/user-settings', [\App\Http\Controllers\User\UserController::class, 'settings']);
    Route::patch('/user/update', [\App\Http\Controllers\User\UserController::class, 'update']);

    Route::get('/stage/actual', [\App\Http\Controllers\User\StageController::class, 'actual']);
    Route::get('/stage/actual/dashboard', [\App\Http\Controllers\User\StageController::class, 'actualDashboard']);
    Route::get('/stage/registered-stage', [\App\Http\Controllers\User\StageController::class, 'registeredStage']);
    Route::get('/stage/ended', [\App\Http\Controllers\User\StageController::class, 'ended']);
    Route::post('/stage/accept', [\App\Http\Controllers\User\StageController::class, 'accept']);
    Route::post('/stage/cancel', [\App\Http\Controllers\User\StageController::class, 'cancel']);
    Route::get('/stage/{id}/reg-info', [\App\Http\Controllers\User\StageController::class, 'regInfo']);

    Route::post('/feedback', [\App\Http\Controllers\User\FeedbackController::class, 'store']);

    Route::get('/stage/{id}/show-users', [\App\Http\Controllers\User\StageController::class, 'showForUsers']);

    Route::post('/user/file', [\App\Http\Controllers\User\UserFileController::class, 'upload']);
    Route::get('/user/docs', [\App\Http\Controllers\User\UserFileController::class, 'docs']);
    Route::delete('/user/file/{type}', [\App\Http\Controllers\User\UserFileController::class, 'destroy']);

    Route::get('/stage/{id}/files', [\App\Http\Controllers\Admin\StageFileController::class, 'getStageFiles']);

    Route::get('/user/{id}/rating/personal', [\App\Http\Controllers\User\RatingController::class, 'userPersonalRating']);
    Route::get('/user/{id}/rating/team', [\App\Http\Controllers\User\RatingController::class, 'userTeamRating']);
    Route::get('/user/{id}/rating/university', [\App\Http\Controllers\User\RatingController::class, 'userUniversityRating']);

    Route::get('/user/{id}', [\App\Http\Controllers\User\UserController::class, 'getProfile']);
    Route::get('/user/{id}/statistics', [\App\Http\Controllers\User\UserController::class, 'getStatistics']);
    Route::get('/user/{id}/rating', [\App\Http\Controllers\User\UserController::class, 'getRating']);

});

Route::group(['middleware' => ['auth:sanctum',  'admin' ]], function () {
    Route::get('/admin', \App\Http\Controllers\Admin\DashboardController::class);

    Route::get('/admin/tournament', [\App\Http\Controllers\Admin\TournamentController::class, 'index']);
    Route::post('/admin/tournament/store', [\App\Http\Controllers\Admin\TournamentController::class, 'store']);
    Route::get('/admin/tournament/{id}', [\App\Http\Controllers\Admin\TournamentController::class, 'edit']);
    Route::patch('/admin/tournament/{id}/update', [\App\Http\Controllers\Admin\TournamentController::class, 'update']);

    Route::post('/admin/stage/store', [\App\Http\Controllers\Admin\StageController::class, 'store']);
    Route::get('/admin/stage/{id}', [\App\Http\Controllers\Admin\StageController::class, 'tournament']);
    Route::get('/admin/stage/{id}/edit', [\App\Http\Controllers\Admin\StageController::class, 'edit']);
    Route::patch('/admin/stage/{id}/update', [\App\Http\Controllers\Admin\StageController::class, 'update']);

    Route::post('/admin/remove-team/{teamId}/stage/{stageId}', [\App\Http\Controllers\Admin\StageController::class, 'removeTeamFromStage']);
    Route::post('/admin/stage/{id}/group', [\App\Http\Controllers\Admin\StageController::class, 'group']);

    Route::post('/admin/stage/{id}/finish', [\App\Http\Controllers\Admin\StageController::class, 'finish']);
    Route::post('/admin/stage/{id}/finish-group', [\App\Http\Controllers\Admin\StageController::class, 'finishGroup']);

    Route::get('/admin/stage/{stageId}/races', [\App\Http\Controllers\Admin\RaceController::class, 'getStageRaces']);
    Route::get('/admin/stage/{id}/meta', [\App\Http\Controllers\Admin\StageController::class, 'getStageStatusGroup']);

    Route::get('/admin/stage/{id}/results', [\App\Http\Controllers\Admin\StageController::class, 'getStageResults']);
    Route::post('/admin/stage/team/result', [\App\Http\Controllers\Admin\StageController::class, 'storeTeamResult']);
    Route::post('/admin/stage/user/result', [\App\Http\Controllers\Admin\StageController::class, 'storeUserResult']);

    Route::post('/admin/race/{id}/results', [\App\Http\Controllers\Admin\RaceController::class, 'storeResults']);
    Route::get('/admin/stage/{stageId}/races/{status}/group/{groupId}', [\App\Http\Controllers\Admin\RaceController::class, 'getStageRaces']);
    Route::get('/admin/race/{id}', [\App\Http\Controllers\Admin\RaceController::class, 'getRacePlace']);
    Route::get('/admin/race/{id}/users', [\App\Http\Controllers\Admin\RaceController::class, 'getRaceUsers']);
    Route::get('/admin/race/{id}/teams', [\App\Http\Controllers\Admin\RaceController::class, 'getRaceTeams']);

    Route::post('/admin/race/create', [\App\Http\Controllers\Admin\RaceController::class, 'createRace']);
    Route::post('/admin/race/{id}/remove', [\App\Http\Controllers\Admin\RaceController::class, 'destroy']);

    Route::get('/admin/stage/{stageId}/{groupId}/{status}/total', [\App\Http\Controllers\Admin\StageController::class, 'getTotal']);
    Route::get('/admin/stage/{stageId}/{groupId}/{status}/total-detail', [\App\Http\Controllers\Admin\StageController::class, 'getTotalDetail']);

    Route::get('/admin/universities', [\App\Http\Controllers\Admin\UniversityController::class, 'index']);
    Route::post('/admin/universities/store', [\App\Http\Controllers\Admin\UniversityController::class, 'store']);
    Route::delete('/admin/universities/{id}/delete', [\App\Http\Controllers\Admin\UniversityController::class, 'destroy']);

    Route::get('/admin/pages', [\App\Http\Controllers\Admin\PageController::class, 'index']);
    Route::post('/admin/page/store', [\App\Http\Controllers\Admin\PageController::class, 'store']);
    Route::get('/admin/page/{id}', [\App\Http\Controllers\Admin\PageController::class, 'edit']);
    Route::patch('/admin/page/{id}/update', [\App\Http\Controllers\Admin\PageController::class, 'update']);

    Route::get('/admin/docs/{status}', [\App\Http\Controllers\Admin\FileController::class, 'show']);
    Route::post('/admin/docs/{id}/approve', [\App\Http\Controllers\Admin\FileController::class, 'approve']);
    Route::post('/admin/docs/{id}/cancel', [\App\Http\Controllers\Admin\FileController::class, 'cancel']);
    Route::delete('/admin/docs/{id}/delete', [\App\Http\Controllers\Admin\FileController::class, 'destroy']);

    Route::get('/admin/stage/{id}/files', [\App\Http\Controllers\Admin\StageFileController::class, 'getStageFiles']);
    Route::post('/admin/stage/file', [\App\Http\Controllers\Admin\StageFileController::class, 'store']);
    Route::delete('/admin/stage/file/{id}/delete', [\App\Http\Controllers\Admin\StageFileController::class, 'destroy']);

    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::get('/admin/user/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
    Route::post('/admin/user/{id}/file', [\App\Http\Controllers\Admin\UserController::class, 'fileUpload']);
    Route::get('/admin/user/{id}/docs', [\App\Http\Controllers\Admin\UserController::class, 'fileDocs']);
    Route::delete('/admin/user/{id}/file/{type}', [\App\Http\Controllers\Admin\UserController::class, 'fileDestroy']);
});


Route::group([], function () {
    Route::get('/stage/{id}', [\App\Http\Controllers\Guest\StageController::class, 'getResult']);
    Route::get('/universities', [\App\Http\Controllers\Guest\RegistrationController::class, 'universities']);
    Route::post('/email', [\App\Http\Controllers\Guest\RegistrationController::class, 'email']);
    Route::get('/rating/users/{year?}', [\App\Http\Controllers\Guest\RatingController::class, 'usersRating']);
    Route::get('/rating/university/{year?}', [\App\Http\Controllers\Guest\RatingController::class, 'universityRating']);
    Route::get('/rating/team/{year?}', [\App\Http\Controllers\Guest\RatingController::class, 'teamRating']);

    Route::get('/user/{id}/stages', [\App\Http\Controllers\User\StageController::class, 'getStages']);

    Route::get('/home/stage/ended',[\App\Http\Controllers\Guest\StageController::class, 'getEnded']);
    Route::get('/home/stage/actual',[\App\Http\Controllers\Guest\StageController::class, 'getActual']);

    Route::get('/page/{slug}', [\App\Http\Controllers\User\PageController::class, 'show']);

    Route::get('/stage/{id}/show', [\App\Http\Controllers\Guest\StageController::class, 'show']);

    Route::get('/team/users/{stageId}', [\App\Http\Controllers\User\TeamController::class, 'getTeamWithUsers']);

});

