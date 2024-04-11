<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tournament_id' => 'required|integer',
            'register_start' => 'required|date',
            'register_end' => 'required|date',
            'race_start' => 'required|date',
            'title' => 'required|string',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
}
