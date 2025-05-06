<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        // return $this->hasOne(User::class, 'student_id', 'id');
        // return $this->belongsTo(User::class, 'id', 'student_id');
        // return $this->hasMany(User::class, 'id', 'student_id');
        return $this->belongsTo(User::class, 'student_id', 'id');
    }

    public function curs()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
