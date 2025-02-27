<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Course;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
    
        if ($user->role == 'student') {
            $courses = Course::withCount('modules')->get(); // Lấy tất cả khóa học cùng số chương
            return view('courses.index', compact('courses'));
        } else {
            $courses = Course::where('mentor_id', $user->id)->get();
            return view('mentor.index', compact('courses'));
        }
    }
    
    public function createCourse(){
        return view('mentor.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);
        $user = Auth::user();
        Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'mentor_id' => $user->id, // Lấy ID của mentor đang đăng nhập
        ]);
    
        return redirect()->route('index')->with('success', 'Khóa học đã được tạo!');
    }
    public function edit($id){
        $course = Course::find($id);
        return view('mentor.edit', compact('course'));
    }
    public function update(Request $request, $id){
        Course::find($id)->update($request->all());
        return redirect()->route('index');
    }
    public function destroy($id){
        Course::find($id)->delete();
        return redirect()->route('index');
    }
    public function show($id){
        $course = Course::find($id);
        return view('mentor.show',compact('course'));
    }
}
