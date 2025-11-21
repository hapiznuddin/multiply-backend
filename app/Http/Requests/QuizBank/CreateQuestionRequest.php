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
            'options'        => 'sometimes|required_if:type,multiple_choice|array',
            'options.*.option_text' => 'required_if:type,multiple_choice|string',
            'options.*.is_correct'  => 'required_if:type,multiple_choice|boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->input('type') === 'multiple_choice') {

                $options = $this->input('options', []);

                // cek minimal 1 correct answer
                $hasCorrect = collect($options)->contains(function ($opt) {
                    return isset($opt['is_correct']) && $opt['is_correct'] === true;
                });

                if (! $hasCorrect) {
                    $validator->errors()->add('options', 'Minimal satu opsi harus benar.');
                }
            }
        });
    }
}
