<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UniversityRepository;
use App\Http\Requests\EmailRequest;
use App\Models\User;

class RegistrationController extends Controller
{
    protected $universityRepository;

    public function __construct()
    {
        $this->universityRepository = app(UniversityRepository::class);
    }

    public function universities()
    {
        $universities = $this->universityRepository->getAllForReg();

        return $universities;
    }

    public function email(EmailRequest $request)
    {
        return User::query()->where(['email' => $request->email])->count();
    }
}
