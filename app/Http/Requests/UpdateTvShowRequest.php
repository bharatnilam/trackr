<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTvShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'release_year' => 'sometimes|required|integer|min:1800|max:' . (date('Y') + 5),
            'pg_rating' => 'nullable|string|max:20',
            'runtime' => 'nullable|integer|min:1',
            'director' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'synopsis' => 'nullable|string',
            'number_of_seasons' => 'nullable|integer|min:1',
            'number_of_episodes' => 'nullable|integer|min:1',
            'status' => 'nullable|string|max:255',
            'poster_image_url' => 'nullable|url|max:2048',
            'external_id' => 'nullable|string|max:255|unique:tv_shows',
            'available_platforms' => 'nullable|string'
        ];
    }
}
