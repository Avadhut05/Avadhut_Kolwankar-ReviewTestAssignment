<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'level',
        'image',
        ];
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
