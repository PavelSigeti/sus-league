<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StageUpdateRequest extends FormRequest
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
            'register_start' => 'required|date',
            'register_end' => 'required|date',
            'race_start' => 'required|date',
            'title' => 'required|string',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'participant_text' => 'nullable|string',
            'race_amount_drop' => 'required|integer',
            'race_amount_group_drop' => 'required|integer',
            'race_amount_fleet_drop' => 'required|integer',
        ];
    }
}
