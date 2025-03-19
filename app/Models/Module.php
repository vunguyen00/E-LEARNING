<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Module extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'description', 'chap', 'video_url'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function completions()
    {
        return $this->hasMany(ModuleCompletion::class, 'module_id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'completed_modules', 'module_id', 'user_id')
                    ->withPivot('course_id')
                    ->withTimestamps();
    }
}
