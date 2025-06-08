<?php

namespace App\Http\Resources\Index;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'course_id' => $this->id,
            'course_name' => $this->course_name,
            'tests' => $this->formatCompletedTests()
        ];

        // return [
        //     'course_id' => $this->id,
        //     'course_name' => $this->course_name,
        //     'image' => $this->img,
        //     'color' => $this->color,
        //     'tests' => $this->completeTest->map(function ($test) {
        //         // Находим соответствующий прогресс пользователя для этого теста
        //         $progress = $this->progress_user->firstWhere('test_id', $test->id);
                
        //         return [
        //             'test_id' => $test->id,
        //             'test_name' => $test->test_name,
        //             'score' => $progress ? $progress->score_student : null,
        //             'is_checked' => $progress ? (bool)$progress->is_checked : false,
        //         ];
        //     }),
        // ];
    }

    protected function formatCompletedTests(): array
    {
        $completedTests = [];
        
        // Группируем progress_user по test_id для обработки
        $progressByTestId = [];
        foreach ($this->progress_user as $progress) {
            if ($progress->is_checked) {
                $progressByTestId[$progress->test_id] = $progress;
            }
        }

        // Сопоставляем с complete_test
        foreach ($this->completeTest as $test) {
            if (isset($progressByTestId[$test->id])) {
                $progress = $progressByTestId[$test->id];
                
                $completedTests[] = [
                    'id' => $test->id,
                    'test_name' => $test->test_name,
                    'score' => $progress->score_student,
                ];
            }
        }

        return $completedTests;
    }
}
