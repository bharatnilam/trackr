<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1800|max:' . (date('Y') + 5),
            'pg_rating' => 'nullable|string|max:20',
            'runtime' => 'nullable|integer|min:1',
            'director' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'actors' => 'nullable|string',
            'synopsis' => 'nullable|string',
            'poster_image_url' => 'nullable|url|max:2048',
            'external_id' => 'nullable|string|max:255|unique:movies',
            'available_platforms' => 'nullable|string'
        ];
    }
}
