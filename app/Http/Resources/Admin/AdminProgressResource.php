<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        // return[
        //     'course_id' => $this->id,
        //     'course_name' => $this->course_name,
        //     'course_tests' => $this->course->map(function($test){
        //         return[
        //             'test_id' => $test->id,
        //             'test_name' => $test->test_name,
        //             'check' => $test->check,
        //             'test_student' => $test->test_student->map(function($student){
        //                 return $student->user_get->map(function($user){
        //                     return[
        //                         'user_id' => $user->id,
        //                         'user_name' => $user->name,
        //                         'user_email' => $user->email,
        //                         'is_checked' => $student->is_checked,
        //                         'user_score' => $student->score_student,
        //                     ];
        //                 })
        //             })
        //         ];
        //     })
        // ];


        // return [
        //     'course_id' => $this->id,
        //     'course_name' => $this->course_name,
        //     'count_student' => $this->count_student_course_count,
        //     'course_tests' => $this->course->map(function($test){
        //         return [
        //             'test_id' => $test->id,
        //             'test_name' => $test->test_name,
        //             'check' => $test->check,
        //             'test_students' => $test->testStudent->map(function($student){
        //                 return [
        //                     'student_id' => $student->user_id,
        //                     'student_name' => $student->userGet->name,
        //                     'student_email' => $student->userGet->email,
        //                     'student_check' => $student->is_checked,
        //                     'student_score' => $student->score_student,
        //                 ];
        //             }),
        //         ];
        //     })
        // ];

        return [
            'course_id' => $this->id,
            'course_name' => $this->course_name,
            'count_student' => $this->count_student_course_count,
            'course_tests' => $this->course->map(function($test){
                $idStudents = $this->countStudentCourse->pluck("student_id");
                $idStudentsAvailable = $test->testStudent->filter(function($item) use ($idStudents){
                    return $idStudents->contains($item->user_id);
                });

                return [
                    'test_id' => $test->id,
                    'test_name' => $test->test_name,
                    'check' => $test->check,
                    // 'test_students' => $test->testStudent->map(function($student){
                    'test_students' => $idStudentsAvailable->map(function($student){
                        return [
                            'student_id' => $student->user_id,
                            'student_name' => $student->userGet->name,
                            'student_email' => $student->userGet->email,
                            'student_check' => $student->is_checked,
                            'student_score' => $student->score_student,
                        ];
                    }),
                ];
            })
        ];
    }
}
