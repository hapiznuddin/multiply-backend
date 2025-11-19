<?php

namespace App\Http\Requests\QuizBank;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionSetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() { return $this->user() !== null; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*' => 'integer|exists:materials,id',
        ];
    }
}
