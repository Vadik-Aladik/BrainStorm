<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserTestRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Index\TestResource;
use App\Http\Resources\Index\UserProgressResource;
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
        
        // $resultUser = UserTest::where('user_id', auth()->id())->with(['testGet', 'courseGet'])->limit(2)->get();
        $resultUser = UserTest::where('user_id', auth()->id())->where('is_checked', 1)->with(['testGet', 'courseGet'])->orderBy('created_at', 'desc')->take(6)->get();
        $resourceResult = UserResultResource::collection($resultUser);

        $checkUser = UserTest::where('user_id', auth()->id())->where('is_checked', 0)->with(['testGet', 'courseGet'])->orderBy('created_at', 'desc')->get();
        $resourceCheck = UserResultResource::collection($checkUser);

        // $progress = Student::where('student_id', auth()->id())->with(['curs.progress_user','curs.completeTest'])->get();

        return Inertia::render('Index/App', [
            'courses' => $resourceCourse,
            'result' => $resourceResult,
            'check' => $resourceCheck,
            'res' => $res
        ]);
    }

    public function course($id)
    {
        if(Course::where('id', $id)->count() <= 0 || Student::where('student_id', auth()->id())->where('course_id', $id)->count() <= 0){
            return redirect()->route('student.index');
        }
        else{
            $completeTest = UserTest::where('user_id', auth()->id())->pluck('test_id');
            $course = Course::where('id', $id)->with(['completeTest', 'allTest'])->get();
            return Inertia::render('Index/Course', [
                'course_admin' => $course,
                'complete_test' => $completeTest, // delete?
            ]);
        }
    }

    public function test($id, $idTest)
    {
        // $testCourse = Test::where('id', $idTest)->with('quest.answer')->get();
        $completeTest = UserTest::where('user_id', auth()->id())->where('test_id', $idTest)->exists();

        if($completeTest){
            return redirect()->route('student.course', ['id'=>$id]);
        }
        else if(Test::where('id', $idTest)->count() <= 0){
            return redirect()->route('student.index');
        }
        else{
            return Inertia::render('Index/Test', [
                'id_course' => $id,
                'id_test' => $idTest,
            ]);
        }
        
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

        $test=Test::where('id', $data['test_id'])->get();

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
                if($answer['user_answers'] == null || $answer['user_answers']==""){
                    $textareaFlag=true;
                    UserAnswer::create([
                        "test_id" => $userTest->id,
                        "answer_id" => $answer['answers_id'],
                        "answer" => $answer['user_answers'],
                        "status" => $answer['status'],
                    ]);
                }
                else{
                    UserAnswer::create([
                        "test_id" => $userTest->id,
                        "answer_id" => $answer['answers_id'],
                        "answer" => $answer['user_answers'],
                        "status" => $answer['status'],
                    ]);
                }
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

        if($textareaFlag || $test[0]->check){
            $userTest->update([
                "is_checked" => false,
            ]);
        }


        return response()->json([
            'test_user'=>$data,
            'status' => true,
        ]);
    }

    public function viewTest($idTest)
    {
        if(UserTest::where('user_id', auth()->id())->where('test_id', $idTest)->where('is_checked', true)->count() <= 0){
            return redirect()->route('student.index');
        }
        else{
            $userTest = UserTest::where('user_id', auth()->id())->where('test_id', $idTest)->with('answerGet')->get();
            return Inertia::render("Index/ViewTest", [
                'id_test' => $idTest,
                'user_test' => $userTest,
            ]);
        }
    }

    public function progress()
    {
        return Inertia::render("Index/Progress");
    }

    public function progressPost()
    {
        //Ресурс для прогресса
        $progressUser = Student::where('student_id', auth()->id())->with(['curs.progress_user','curs.completeTest'])->get();
        $progress = UserProgressResource::collection($progressUser->pluck('curs'));

        return response()->json([
            'user_progress' => $progress
        ]);
    }
}
