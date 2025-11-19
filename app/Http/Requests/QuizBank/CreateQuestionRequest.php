<?php

namespace App\Http\Requests\QuizBank;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateQuestionRequest extends FormRequest
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
            'material_id'    => 'required|integer|exists:materials,id',
            'question_text'  => 'required|string',
            'type'           => ['required', Rule::in(['multiple_choice','input'])],
            'correct_answer' => 'required_if:type,input|nullable|string',
            'options'        => 'required_if:type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct'  => 'boolean',
        ];
    }
}
