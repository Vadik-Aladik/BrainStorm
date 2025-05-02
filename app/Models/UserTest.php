<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function testGet()
    {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }

    public function courseGet()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
}
