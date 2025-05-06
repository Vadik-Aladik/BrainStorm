<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserTestRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Index\TestResource;
use App\Http\Resources\Index\UserResultResource;
use App\Models\Course;
use App\Models\Student;
use App\Models\Test;
use App\Models\UserAnswer;
use App\Models\UserTest;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function main()
    {
        // DB::table("user_tests")->delete();
        // DB::table("user_answers")->delete();
        // $courseStudent = Course::all();
        $res = Student::where('student_id', auth()->id())->with('curs')->get();
        $courseStudent = $res->pluck('curs')->sort();
        $resourceCourse = CourseResource::collection($courseStudent); 
        $resultUser = UserTest::where('user_id', auth()->id())->with(['testGet', 'courseGet'])->get();
        $resourceResult = UserResultResource::collection($resultUser);

        return Inertia::render('Index/App', [
            'courses' => $resourceCourse,
            'result' => $resourceResult,
        ]);
    }

    public function course($id)
    {
        $course = Course::where('id', $id)->with('course')->get();
        return Inertia::render('Index/Course', [
            'course_admin' => $course
        ]);
    }

    public function test($id, $idTest)
    {
        // $testCourse = Test::where('id', $idTest)->with('quest.answer')->get();
        return Inertia::render('Index/Test', [
            'id_course' => $id,
            'id_test' => $idTest,
        ]);
    }

    public function testGet($idTest)
    {
        $data = Test::where('id', $idTest)->with('quest.answer')->get();
        $test = TestResource::collection($data);
        return response()->json([
            'test' => $test
        ]);
    }

    public function userTest(UserTestRequest $request)
    {
        $data=$request->validated();
        $textareaFlag = false;

        // foreach($data['answer_user'] as $answer){
        //     if($answer['type'] == 'radio'){
        //         // array_push($radio, ['quest_id'=>$answer['quest_id'], 'test_id'=>$data['test_id'], "answer_id"=>$answer['answers_id']]);
        //         UserAnswer::create([
        //             "test_id"=> $data['test_id'],
        //             "quest_id"=> $answer['quest_id'],
        //             "answer_id"=> $answer['answers_id'],
        //             "answer" => null,
        //             "status" => $answer['status'],
        //         ]);
        //     }
        //     if($answer['type'] == 'checkbox'){
        //         foreach($answer['answers_id'] as $check_answer){
        //             // array_push($checkbox, ['quest_id'=>$answer['quest_id'], 'test_id'=>$data['test_id'], "answer_id"=>$check_answer]);
        //             UserAnswer::create([
        //                 "test_id"=> $data['test_id'],
        //                 "quest_id"=> $answer['quest_id'],
        //                 "answer_id"=> $check_answer,
        //                 "answer" => null,
        //                 "status" => $answer['status'],
        //             ]);
        //         }
        //     }
        //     if($answer['type'] == 'input'){
        //         // array_push($input, ['quest_id'=>$answer['quest_id'], 'test_id'=>$data['test_id'], "answer_id"=>$answer['answers_id'], "answer" => $answer['user_answers']]);
        //         UserAnswer::create([
        //             "test_id"=> $data['test_id'],
        //             "quest_id"=> $answer['quest_id'],
        //             "answer_id"=> $answer['answers_id'],
        //             "answer" => $answer['user_answers'],
        //             "status" => $answer['status'],
        //         ]);
        //     }
        //     if($answer['type'] == 'textarea'){
        //         // array_push($textarea, ['quest_id'=>$answer['quest_id'], 'test_id'=>$data['test_id'], "answer_id"=>$answer['answers_id'], "answer" => $answer['user_answers']]);
        //         $textareaFlag = true;
        //         UserAnswer::create([
        //             "test_id"=> $data['test_id'],
        //             "quest_id"=> $answer['quest_id'],
        //             "answer_id"=> $answer['answers_id'],
        //             "answer" => $answer['user_answers'],
        //             "status" => $answer['status'],
        //         ]);
        //     }
        // }

        // if($textareaFlag){
        //     UserTest::create([
        //         "user_id" => auth()->id(),
        //         "course_id" => $data['course_id'],
        //         "test_id" => $data['test_id'],
        //         "score_student" => $data['score'],
        //         "is_checked" => false,
        //     ]);
        // }
        // else{
        //     UserTest::create([
        //         "user_id" => auth()->id(),
        //         "course_id" => $data['course_id'],
        //         "test_id" => $data['test_id'],
        //         "score_student" => (string) $data['score'],
        //         "is_checked" => true,
        //     ]);
        // }

        $userTest = UserTest::create([
            "user_id" => auth()->id(),
            "course_id" => $data['course_id'],
            "test_id" => $data['test_id'],
            "score_student" => (string) $data['score'],
            "is_checked" => true
        ]);

        foreach($data['answer_user'] as $answer){
            if($answer['type'] == 'radio'){
                UserAnswer::create([
                    "test_id" => $userTest->id,
                    "answer_id" => $answer['answers_id'],
                    "answer" => null,
                    "status" => $answer['status'],
                ]);
            }
            if($answer['type'] == 'checkbox'){
                foreach($answer['answers_id'] as $check_answer){
                    UserAnswer::create([
                        "test_id" => $userTest->id,
                        "answer_id" => $check_answer,
                        "answer" => null,
                        "status" => $answer['status'],
                    ]);
                }
            }
            if($answer['type'] == 'input'){
                UserAnswer::create([
                    "test_id" => $userTest->id,
                    "answer_id" => $answer['answers_id'],
                    "answer" => $answer['user_answers'],
                    "status" => $answer['status'],
                ]);
            }
            if($answer['type'] == 'textarea'){
                $textareaFlag=true;
                UserAnswer::create([
                    "test_id" => $userTest->id,
                    "answer_id" => $answer['answers_id'],
                    "answer" => $answer['user_answers'],
                    "status" => $answer['status'],
                ]);
            }
        }

        if($textareaFlag){
            $userTest->update([
                "is_checked" => false,
            ]);
        }


        return response()->json([
            'test_user'=>$data,
            'status' => true
        ]);
    }

    public function viewTest($idCourse, $idTest)
    {
        $userTest = UserTest::where('user_id', auth()->id())->where('test_id', $idTest)->with('answerGet')->get();
        return Inertia::render("Index/ViewTest", [
            'id_course' => $idCourse,
            'id_test' => $idTest,
            'user_test' => $userTest,
        ]);
    }
}
