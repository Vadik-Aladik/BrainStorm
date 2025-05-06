<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function quest()
    {
        return $this->hasMany(Question::class, 'test_id', 'id');
    }

    public function testStudent()
    {
        return $this->hasMany(UserTest::class, 'test_id', 'id');
    }

    // public function testStudent()
    // {
    //     return $this->hasMany(UserTest::class, 'test_id', 'id');
    // }
}
