<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UniversityRepository;
use App\Http\Requests\UniversityStoreRequest;
use App\Models\University;
use App\Models\User;

class UniversityController extends Controller
{
    protected $universityRepository;

    public function __construct()
    {
        $this->universityRepository = app(UniversityRepository::class);
    }

    public function index()
    {
        return $this->universityRepository->getWithUserAmount();
    }

    public function store(UniversityStoreRequest $request)
    {
        return University::query()->create([
            'name' => $request->name,
        ]);
    }

    public function destroy($id)
    {
        $university = $this->universityRepository->getById($id);
        User::query()->where('university_id', $id)->update([
           'university_id' => null,
        ]);
        $university->delete();

        return true;
    }
}

