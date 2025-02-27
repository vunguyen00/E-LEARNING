<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class ModulesController extends Controller
{
    // Hiển thị form tạo module
    public function create(Course $course)
    {
        return view('mentor.createModule', compact('course'));
    }

    // Lưu module vào database
    public function store(Request $request, Course $course)
    {
        // Kiểm tra dữ liệu nhập vào
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_url' => 'required|file|mimes:mp4,mov,avi|max:20480', // 20MB max
        ]);

        // Lấy chap cao nhất trong khóa học này
        $maxChap = $course->modules()->max('chap');
        $newChap = $maxChap ? $maxChap + 1 : 1; // Nếu chưa có module nào thì bắt đầu từ 1

        // Lưu file video vào thư mục cụ thể
        $videoFile = $request->file('video_url');
        $videoName = time() . '_' . $videoFile->getClientOriginalName(); // Tạo tên file duy nhất
        $videoPath = 'D:\code thue\daln\elearning\public\videos\\' . $videoName;
        $videoFile->move('D:\code thue\daln\elearning\public\videos', $videoName);

        // Lưu đường dẫn URL vào database
        $videoUrl = asset('videos/' . $videoName);

        // Thêm module vào database
        Module::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'chap' => $newChap, // Tự động tăng
            'video_url' => $videoUrl, // Lưu đường dẫn URL thay vì đường dẫn vật lý
        ]);

        return redirect()->route('mentor.show', ['course' => $course])->with('success', 'Module đã được thêm!');
    }

    // Hiển thị danh sách module của khóa học
    public function show(Course $course)
    {
        $modules = $course->modules()->orderBy('chap', 'asc')->get();
        return view('mentor.show', compact('course', 'modules'));
    }

    public function edit(Course $course, Module $module)
    {
        return view('modules.edit', compact('module', 'course'));
    }

    public function destroy(Course $course, Module $module)
    {
        Module::find($module->id)->delete();
        return redirect()->route('mentor.show', ['course' => $course->id]);
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'chap' => 'required|numeric|min:1',
            'video_url' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB max
        ]);

        // Nếu có file mới, lưu file và cập nhật đường dẫn
        if ($request->hasFile('video_url')) {
            $videoFile = $request->file('video_url');
            $videoName = time() . '_' . $videoFile->getClientOriginalName();
            $videoPath = 'D:\code thue\daln\elearning\public\videos\\' . $videoName;
            $videoFile->move('D:\code thue\daln\elearning\public\videos', $videoName);
            $module->video_url = asset('videos/' . $videoName);
        }

        // Cập nhật thông tin module
        $module->title = $request->title;
        $module->description = $request->description;
        $module->chap = $request->chap;
        $module->save();

        return redirect()->route('mentor.show', $module->course_id)->with('success', 'Module đã được cập nhật!');
    }
}
