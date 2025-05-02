<?php

namespace App\Http\Resources\Index;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResultResource extends JsonResource
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
            "id" => $this->id,
            'name_test' => $this->testGet->test_name,
            'name_course' => $this->courseGet->course_name,
            'score' => $this->score_student,
            'status' => $this->is_checked,
        ];
    }
}
