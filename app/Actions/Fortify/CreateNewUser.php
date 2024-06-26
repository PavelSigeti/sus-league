<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:32', 'min:2'],
            'surname' => ['required', 'string', 'max:32', 'min:2'],
            'patronymic' => ['required', 'string', 'max:32', 'min:2'],
            'birth' => ['required', 'date', 'before:2008-01-01'],
            'university_id' => ['nullable', 'integer'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();
        
        $university_id = $input['university_id'] ? $input['university_id'] : null;

        return User::query()->create([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'patronymic' => $input['patronymic'],
            'birth' => $input['birth'],
            'university_id' => $university_id,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
