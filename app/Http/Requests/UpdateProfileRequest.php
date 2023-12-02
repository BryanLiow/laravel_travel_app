<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator; // Add this line
use Illuminate\Http\Exceptions\HttpResponseException; // Add this line

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        Log::info('authorize function');

        // Adjust the logic here if necessary
        return Auth::check();
    }

    public function rules()
    {
        Log::info('rules function');

        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'headline' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ];
    }

    // Override the failedValidation method
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
