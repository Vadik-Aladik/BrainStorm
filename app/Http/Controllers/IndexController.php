<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserTestRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Index\TestResource;
use App\Http\Resources\Index\UserResultResource;
use App\Models\Course;
use App\Models\Test;
use App\Models\UserTest;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function main()
    {
        // DB::table("user_tests")->delete();
        $courseStudent = Course::all();
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
        // $radio=[];
        // $checkbox=[];
        // $input=[];
        $textarea=[];

        foreach($data['answer_user'] as $answer){
        //     if($answer['type'] == 'radio'){
        //         array_push($radio, [$answer['quest_id'], $answer['type'], $answer['answers_id']]);
        //     }
        //     if($answer['type'] == 'checkbox'){
        //         foreach($answer['answers_id'] as $check_answer){
        //             array_push($checkbox, [$answer['quest_id'], $answer['type'], $check_answer]);
        //         }
        //     }
        //     if($answer['type'] == 'input'){
        //         array_push($input, [$answer['quest_id'], $answer['type'], $answer['user_answers']]);
        //     }
            if($answer['type'] == 'textarea'){
                array_push($textarea, [$answer['quest_id'], $answer['type'], $answer['user_answers']]);
            }
        }

        if(!empty($textarea)){
            UserTest::create([
                "user_id" => auth()->id(),
                "course_id" => $data['course_id'],
                "test_id" => $data['test_id'],
                "score_student" => $data['score'],
                "is_checked" => false,
            ]);
        }
        else{
            UserTest::create([
                "user_id" => auth()->id(),
                "course_id" => $data['course_id'],
                "test_id" => $data['test_id'],
                "score_student" => (string) $data['score'],
                "is_checked" => true,
            ]);
        }

        return response()->json([
            'test_user'=>$data,
            'status' => true
        ]);
    }
}
