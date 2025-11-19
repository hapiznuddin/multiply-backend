<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

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
            'participant_id' => 'required|integer|exists:room_participants,id',
            'question_id' => 'required|integer|exists:questions,id',
            'answer' => 'required|string',
        ];
    }
}
