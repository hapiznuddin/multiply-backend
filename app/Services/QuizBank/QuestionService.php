<?php

namespace App\Services\QuizBank;

use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class QuestionService
{
    public function __construct(
        protected QuestionRepositoryInterface $questions
    ) {}

    // public function create(array $data): Question
    // {
    //     return $this->questions->create($data);
    // }

    public function create(array $data): Question
    {
        return DB::transaction(function () use ($data) {

            // 1. create question first
            $question = $this->questions->create([
                'material_id'    => $data['material_id'],
                'question_text'  => $data['question_text'],
                'type'           => $data['type'],
                'correct_answer' => $data['type'] === 'input'
                    ? $data['correct_answer']
                    : null,
            ]);

            // 2. if multiple-choice, insert options
            if ($data['type'] === 'multiple_choice') {
                foreach ($data['options'] as $option) {
                    $question->options()->create([
                        'option_text' => $option['option_text'],
                        'is_correct'  => $option['is_correct'],
                    ]);
                }
            }

            return $question->load('options');
        });
    }

    // public function update(Question $question, array $data): Question
    // {
    //     return $this->questions->update($question, $data);
    // }

    public function update(Question $question, array $data): Question
    {
        return DB::transaction(function () use ($question, $data) {

            $question->update([
                'question_text'  => $data['question_text'],
                'type'           => $data['type'],
                'correct_answer' => $data['type'] === 'input'
                    ? $data['correct_answer']
                    : null,
            ]);

            if ($data['type'] === 'multiple_choice') {
                $question->options()->delete();

                foreach ($data['options'] as $option) {
                    $question->options()->create([
                        'option_text' => $option['option_text'],
                        'is_correct'  => $option['is_correct'],
                    ]);
                }
            }

            return $question->load('options');
        });
    }

    public function delete(Question $question): void
    {
        $this->questions->delete($question);
    }

    public function find(int $id): ?Question
    {
        return $this->questions->findById($id);
    }

    public function getByMaterial(int $materialId)
    {
        return $this->questions->getByMaterial($materialId);
    }
}
