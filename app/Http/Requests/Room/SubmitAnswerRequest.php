<?php

namespace App\Http\Requests\Room;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() { return true; }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guest_token' => 'required|string',
            'room_participant_id' => 'required|integer|exists:room_participants,id',
            'question_id' => 'required|integer|exists:questions,id',
            'time_taken' => 'nullable|integer',

            // answer dynamic: MCQ = integer option_id but stored as string, input = string
            'answer' => ['required', function ($attribute, $value, $fail) {

                $question = Question::find($this->question_id);
                if (! $question) {
                    return $fail("Invalid question.");
                }

                // MCQ
                if ($question->type === 'multiple_choice') {
                    if (!is_numeric($value)) {
                        return $fail("Answer must be a valid option id.");
                    }

                    // Check option owner
                    if (!QuestionOption::where('question_id', $question->id)
                        ->where('id', $value)
                        ->exists()) {
                        return $fail("Invalid option for this question.");
                    }
                }

                // INPUT
                if ($question->type === 'input') {
                    if (!is_string($value)) {
                        return $fail("Answer must be text.");
                    }
                }
            }]
        ];
    }
}
