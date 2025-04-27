<?php

namespace App\Http\Resources\Index;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'test_name' => $this->test_name,
        //     'check' => $this->check,
        //     'quest' => [
        //         'id' => $this->quest->id,
        //         'quest_text' => $this->quest->quest_text,
        //         'answer' => [
        //             'id' => $this->quest->answer->id,
        //             'answer_text' => $this->answer_text->answer_text,
        //             'type' => $this->type->type,
        //             'status' => $this->status->status,
        //         ]
        //     ]
        // ];

        return [
            'id' => $this->id,
            'test_name' => $this->test_name,
            'check' => $this->check,
            'quest' => $this->quest->map(function($quest) {
                return [
                    'id' => $quest->id,
                    'quest_text' => $quest->quest_text,
                    'answer' => $quest->answer->map(function($answer) {
                        return [
                            'id' => $answer->id,
                            'answer_text' => $answer->answer_text,
                            'type' => $answer->type,
                            'status' => $answer->status,
                        ];
                    })
                ];
            })
        ];
    }
}
