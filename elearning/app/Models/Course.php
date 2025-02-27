<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    public function reviews() {
        return $this->hasMany(Review::class);
    }    
    
    protected $fillable = ['title', 'description', 'price', 'mentor_id']; 

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id'); // 'mentor_id' liên kết đến bảng users
    }
    // Quan hệ với bảng Module
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id');
    }
    public function test()
{
    return $this->hasOne(Test::class);
}

    // Quan hệ với học viên đã đăng ký
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')->withTimestamps();
    }

    // Kiểm tra học viên đã đăng ký chưa
    public function isRegistered($userId)
    {
        return $this->students()->where('user_id', $userId)->exists();
    }

    public function getProgress($userId)
    {
        $totalModules = $this->modules()->count();
    
        if ($totalModules == 0) {
            return 0; // Tránh lỗi chia cho 0
        }
    
        $completedModules = DB::table('completed_modules')
            ->where('user_id', $userId)
            ->where('course_id', $this->id)
            ->count();
    
        return round(($completedModules / $totalModules) * 100, 2);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')->withPivot('paid');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Kiểm tra nếu học viên đã hoàn thành khóa học
    public function isCompleted($userId)
    {
        return $this->getProgress($userId) == 100;
    }
    
}
