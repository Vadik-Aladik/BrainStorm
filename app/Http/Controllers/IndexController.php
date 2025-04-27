<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Index\TestResource;
use App\Models\Course;
use App\Models\Test;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function main()
    {
        $courseStudent = Course::all();
        $resourceCourse = CourseResource::collection($courseStudent); 
        return Inertia::render('Index/App', [
            'courses' => $resourceCourse
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
        $test = Test::where('id', $idTest)->with('quest.answer')->get();
        $res = TestResource::collection($test);
        return response()->json([
            'test' => $res
        ]);
    }
}
