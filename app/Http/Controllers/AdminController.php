<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CourseRequest;
use App\Http\Requests\Admin\TestRequest;
use App\Http\Requests\CheckUserTestRequest;
use App\Http\Requests\User\UserTestRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Question;
use App\Models\Student;
use App\Models\Test;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserTest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpParser\Builder\Use_;
use Storage;

class AdminController extends Controller
{
    public function createTest($id)
    {
        return Inertia::render('Admin/CreateTest', [
            'course_id' => $id
        ]);
    }

    public function createTestPost($id, TestRequest $request)
    {
        $data = $request->validated();
        $testArr = [];
        $answertArr = [];
        $emptyAnswer = [];
        $flag = false;

        $test = Test::create([
            'course_id' => $id,
            'test_name' => $data['test_name'],
            'check' => false
        ]);

        foreach($data['test'] as $elem){
            // array_push($testArr, [$elem['quest'], $elem['select']]);
            $quest = Question::create([
                'test_id' => $test->id,
                'quest_text' => $elem['quest'],
            ]);

            foreach($elem['answers'] as $answerItem){ // Переименуем переменную, чтобы не было конфликта
                if($elem['select'] == 'radio' || $elem['select'] == 'checkbox'){
                    Answer::create([
                        'quest_id' => $quest->id,
                        'answer_text' => $answerItem['answer'],
                        'type' => $elem['select'],
                        'status' => $answerItem['status'],
                        // 'answer' => null // Явно указываем null для этих типов
                    ]);
                }
                else{
                    Answer::create([
                        'quest_id' => $quest->id,
                        'answer_text' => $answerItem['answer'],
                        'type' => $elem['select'],
                        'status' => null, // Явно указываем null для статуса
                        // 'answer' => $answerItem['answer']
                    ]);
                    if(empty($answerItem['answer']) || $elem['select']=='textarea'){ // Правильная проверка на пустоту
                        array_push($emptyAnswer, $elem['quest']);
                    }
                }
            }
        }

        if(!empty($emptyAnswer)){ // Нужна (была), чтобы понимать нужно проверять тест в будущем или нет, при создании теста в начале стоит false, т.е. не надо
            $test->update([
                'check' => true
            ]);
        }

        return response()->json([
            'result' => true
        ]);
    }

    public function createCourse()
    {
        return Inertia::render('Admin/AddCourse');
    }

    public function createCoursePost(CourseRequest $request)
    {
        $data = $request->validated();
        $path = Storage::disk('public')->put('/img', $data['img']);
        Course::create([
            'user_id' => auth()->id(),
            'course_name' => $data['name'],
            'img' => $path,
            'color' => $data['color']
        ]);
        return response()->json([
            'status' => True
        ]);
    }

    public function main()
    {
        // $course = Course::where("user_id", auth()->id())->get();
        // $resource = CourseResource::collection($course);
        // return Inertia::render('Admin/Main', [
        //     'course' => $course,
        //     'resource_course' => $resource,
        // ]);
         return Inertia::render('Admin/Main');
    }
    public function mainPost()
    {
        $course = Course::where("user_id", auth()->id())->get();
        $resource = CourseResource::collection($course); 
        // $students = Student::with(['user', 'curs'])->get();
        return response()->json( [
            // 'students' => $students,
            'course' => $course,
            'resource_course' => $resource,
            'auth' => auth()->check()
        ]);
    }

    public function course($id)
    {
        // $name=Course::where("id", $id)->get();
        // $testsCourse = Test::where('course_id', $id)->get();
        return Inertia::render("Admin/Course", [
            'course_id' => $id,
            // 'tests' => $testsCourse,
            // 'name' => $name[0]['course_name'],
            // 'id'=>$name[0]['id'],
        ]);
    }

    public function courseData($id)
    {
        $course=Course::where("id", $id)->get();
        $users = User::all();

        // $studentsCourse = Student::where('course_id', $id)->with('courseStudent')->get();
        $students = Student::where('course_id', $id)->with('user')->get();
        $studentsCourse = $students->pluck('user');
        
        $testsCourse = Test::where('course_id', $id)->get();

        return response()->json([
            "tests" => $testsCourse,
            "users_for_add" => $users,
            "users_in_course" => $studentsCourse,
            "course_name" => $course[0]['course_name'],
            "course_id" => $course[0]['id']
        ]);
    }

    public function delete($id)
    {
        return Test::find($id)->delete();
    }

    public function addStudent($idStudent, $idCourse)
    {
        Student::create([
            'student_id'=> $idStudent,
            'course_id'=> $idCourse,
        ]);
    }

    public function deleteStudent($idStudent, $idCourse)
    {
        Student::where('student_id', $idStudent)->where('course_id', $idCourse)->delete();
    }

    public function progress()
    {
        return Inertia::render("Admin/Progress");
    }

    public function progressData()
    {
        $testForCheck = Test::where('check', true)->with('testStudent.userGet')->get();
        // $courseWithTests = Course::with('course.testStudent.userGet')->get();
        return response()->json([
            'course_for_check' => $testForCheck,
            // 'courseWithTests' => $courseWithTests
        ]);
    }

    public function checkTest($idTest, $idUser)
    {
        $userTest = UserTest::where('user_id', $idUser)->where('test_id', $idTest)->with('answerGet')->get();
        return Inertia::render("Admin/CheckTest",[
            'id_test' => $idTest,
            'user_id' => $idUser,
            'user_test' => $userTest,
        ]);
    }

    public function checkTestPost(CheckUserTestRequest $request)
    {
        $data = $request->validated();
        $arr=[];

        $userTest = UserTest::where('user_id', $data['user_id'])->where('test_id', $data['test_id'])->first();
        $userTest->update([
            'score_student' => $data['score'],
            'is_checked' => true,
        ]);
        foreach($data['answer_user'] as $answer){
            $userAnswer = UserAnswer::where('test_id', $userTest->id)->where('answer_id', $answer['id'])->update(['status'=> $answer['status']]);
        }
        return response()->json([
            "res" => true,
        ]);
    }
}
