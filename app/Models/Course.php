<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function course()
    {
        return $this->hasMany(Test::class, 'course_id', 'id');
    }

    public function completeTest()
    {
        return $this->hasMany(Test::class, 'course_id', 'id')->whereHas('testStudent', function($q) {
            $q->where('user_id', auth()->id());
        });
    }

    public function allTest()
    {
        return $this->hasMany(Test::class, 'course_id', 'id')->whereDoesntHave('testStudent', function($q) {
            $q->where('user_id', auth()->id());
        });
    }

    public function progress_user()
    {
        return $this->hasMany(UserTest::class, 'course_id', 'id')->where('user_id', auth()->id());
    }

    public function countStudentCourse()
    {
        return $this->hasMany(Student::class, 'course_id', 'id');
    }

    // public function userCourse()
    // {
    //     return $this->hasMany(Student::class, 'id', 'course');
    // }
}
