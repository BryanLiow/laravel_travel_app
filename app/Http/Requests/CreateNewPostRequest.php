<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator; // Add this line
use Illuminate\Http\Exceptions\HttpResponseException; // Add this line

class CreateNewPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        Log::info('authorize function');

        // Adjust the logic here if necessary
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        Log::info('rules function');

        return [
            'title' => 'required|string',
            'caption' => 'required|string',
            'postPhoto' => 'required|array',
            'postPhoto.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'sometimes|string',
        ];
    }

    // Override the failedValidation method
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
