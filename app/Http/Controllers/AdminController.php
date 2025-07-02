<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CourseRequest;
use App\Http\Requests\Admin\TestRequest;
use App\Http\Requests\CheckUserTestRequest;
use App\Http\Requests\User\UserTestRequest;
use App\Http\Resources\Admin\AdminProgressResource;
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
    public function main()
    {
        return Inertia::render('Admin/Main');
    }
    public function postMain()
    {
        $course = Course::where("user_id", auth()->id())->paginate(2);
        $resource = CourseResource::collection($course); 
        return response()->json( [
            'course' => $course,
            'resource_course' => $resource,
            'auth' => auth()->check()
        ]);
    }

    public function createCourse()
    {
        return Inertia::render('Admin/AddCourse');
    }

    public function postCreateCourse(CourseRequest $request)
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

    public function createTest($id)
    {
        return Inertia::render('Admin/CreateTest', [
            'course_id' => $id
        ]);
    }

    public function postCreateTest($id, TestRequest $request)
    {
        $data = $request->validated();
        $emptyAnswer = [];

        $test = Test::create([
            'course_id' => $id,
            'test_name' => $data['test_name'],
            'check' => false
        ]);

        foreach($data['test'] as $elem){
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
                    ]);
                }
                else{
                    Answer::create([
                        'quest_id' => $quest->id,
                        'answer_text' => $answerItem['answer'],
                        'type' => $elem['select'],
                        'status' => null, // Явно указываем null для статуса
                    ]);
                    if(empty($answerItem['answer']) || $elem['select']=='textarea'){ // Правильная проверка на пустоту
                        array_push($emptyAnswer, $elem['quest']);
                    }
                }
            }
        }

        if(!empty($emptyAnswer)){ // Нужна, чтобы понимать нужно проверять тест в будущем или нет, при создании теста в начале стоит false, т.е. не надо
            $test->update([
                'check' => true
            ]);
        }

        return response()->json([
            'result' => true
        ]);
    }

    public function course($id)
    {
        return Inertia::render("Admin/Course", [
            'course_id' => $id,
        ]);
    }

    public function courseData($id)
    {
        $course=Course::where("id", $id)->firstOrFail();
        $users = User::all();

        $students = Student::where('course_id', $id)->with('user')->get();
        $studentsCourse = $students->pluck('user');
        
        $testsCourse = Test::where('course_id', $id)->paginate(6);

        return response()->json([
            "tests" => $testsCourse,
            "users_for_add" => $users,
            "users_in_course" => $studentsCourse,
            "course_name" => $course->course_name,
            "course_id" => $course->id
        ]);
    }
    public function courseDataStudents($id)
    {
        $users = User::whereDoesntHave('students', function($query) use ($id) {
            $query->where('course_id', $id);
        })->paginate(3);

        return response()->json([
            "users_for_add" => $users,
        ]);
    } 
    public function courseDataStudentsCourse($id)
    {
        $students = Student::where('course_id', $id)->with('user')->paginate(3);
        $studentsCourse = $students->pluck('user');

        return response()->json([
            "users_in_course" => $studentsCourse,
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
        $courseTest = Course::with(['course.testStudent.userGet', 'countStudentCourse'])->withCount(['countStudentCourse'])->paginate(2);
        $courseWithTests = AdminProgressResource::collection($courseTest);
        return response()->json([
            'courseTestInfo' => $courseTest,
            'courseWithTests' => $courseWithTests
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

    public function postCheckTest(CheckUserTestRequest $request)
    {
        $data = $request->validated();

        $userTest = UserTest::where('user_id', $data['user_id'])->where('test_id', $data['test_id'])->first();
        $userTest->update([
            'score_student' => $data['score'],
            'is_checked' => true,
        ]);
        foreach($data['answer_user'] as $answer){
            $userAnswer = UserAnswer::where('test_id', $userTest->id)->where('answer_id', $answer['id'])->update(['status'=> $answer['status']]); // Замена статустов ответов пользователя
        }
        return response()->json([
            "res" => true,
        ]);
    }
}
