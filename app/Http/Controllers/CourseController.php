<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');

        $courses = Course::withCount('modules')
            ->withAvg('reviews', 'rating') // Lấy điểm trung bình từ bảng reviews
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%$search%");
            })
            ->orderByDesc(DB::raw('COALESCE(reviews_avg_rating, 0)')) // Sắp xếp theo điểm trung bình
            ->get();

        return view('courses.index', compact('courses'));
    }

    public function review($courseId) {
        $course = Course::with('reviews.user')->findOrFail($courseId);
        return view('courses.review', compact('course'));
    }
    
    public function submitReview(Request $request, $courseId) {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string'
        ]);
    
        Review::create([
            'user_id' => Auth::id(),
            'course_id' => $courseId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
    
        return redirect()->route('courses.index')->with('status', 'Cảm ơn bạn đã đánh giá khóa học!');
    }    
    public function show($id) {
        $course = Course::with(['reviews.user', 'mentor', 'modules'])->findOrFail($id);
        return view('courses.show', compact('course'));
    }
    
}
