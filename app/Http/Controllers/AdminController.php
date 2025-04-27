<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\CourseRequest;
use App\Http\Requests\Admin\TestRequest;
use App\Http\Resources\Admin\CourseResource;
use App\Models\Answer;
use App\Models\Course;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Inertia\Inertia;
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
            // foreach($elem['answers'] as $answer){
            //     if($elem['select']=='radio' || $elem['select']=='checkbox'){
            //         // array_push($answertArr, [$answer['answer'],$answer['status']]);
            //         $answer = Answer::create([
            //             'quest_id' => $quest->id,
            //             'answer_text' => $answer['answer'],
            //             'type' => $elem['select'],
            //             'status' => $answer['status'],
            //             'answer' => null
            //         ]);
            //     }
            //     else{
            //         // array_push($answertArr, [$answer['answer']]);
            //         $answer = Answer::create([
            //             'quest_id' => $quest->id,
            //             'answer_text' => $answer['answer'],
            //             'type' => $elem['select'],
            //             'status' => null,
            //             'answer' => $answer['answer'],
            //         ]);
            //         if($answer['answer'] == null || $answer['answer'] = ''){
            //             array_push($emptyAnswer, $elem['quest']);
            //         }
            //     }
            // }

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
            'test' => $testArr,
            'quest' => $answertArr,
            'all' => $data,
            'check?' => $flag
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
        return response()->json( [
            'course' => $course,
            'resource_course' => $resource,
            'auth' => auth()->check()
        ]);
    }

    public function course($id)
    {
        $name=Course::where("id", $id)->get();
        return Inertia::render("Admin/Course", [
            'course_id' => $id,
            'name' => $name[0]['course_name'],
            'id'=>$name[0]['id'],
        ]);
    }
}
