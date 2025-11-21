<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest
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
            'question_set_id' => 'required|exists:question_sets,id',
            'title'           => 'required|string|max:255',
            'max_players'     => 'nullable|integer|min:1|max:500',
        ];
    }
}
