<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Test;
use App\Models\TestResult;  
use Illuminate\Support\Facades\Auth;
class TestController extends Controller
{
    public function show($courseId, $testId)
    {
        $course = Course::findOrFail($courseId);
        $test = Test::with('questions.answers')->findOrFail($testId);
    
        return view('courses.test', compact('course', 'test'));
    }
    
    

    public function submit(Request $request, $courseId, $testId)
    {
        $test = Test::with('questions.answers')->findOrFail($testId);
        $userAnswers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $test->questions->count();
        $passScore = 70; // Điểm tối thiểu để đỗ
    
        foreach ($test->questions as $question) {
            if (isset($userAnswers[$question->id])) {
                $selectedAnswerId = $userAnswers[$question->id];
                $correctAnswer = $question->answers->where('is_correct', true)->first();
    
                if ($correctAnswer && $correctAnswer->id == $selectedAnswerId) {
                    $correctCount++;
                }
            }
        }
    
        $score = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;
        $passed = $score >= $passScore;
    
        // Lưu kết quả bài kiểm tra
        TestResult::updateOrCreate(
            ['user_id' => Auth::id(), 'test_id' => $testId],
            ['score' => $score, 'passed' => $passed]
        );
    
        if ($passed) {
            return redirect()->route('courses.index')->with('success', "🎉 Chúc mừng! Bạn đã đỗ với số điểm: $score");
        } else {
            return redirect()->back()->with('error', "❌ Bạn chưa đạt yêu cầu (Điểm: $score). Vui lòng thử lại!");
        }
    }
    
    
    
    public function create(Course $course)
    {
        return view('tests.create', compact('course'));
    }

    public function store(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
    
        // Tạo bài kiểm tra
        $test = Test::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'pass_score' => $request->pass_score ?? 50, // Điểm đậu mặc định 50
        ]);
    
        // Thêm câu hỏi và câu trả lời
        foreach ($request->questions as $qData) {
            $question = $test->questions()->create([
                'question' => $qData['question'],
            ]);
    
            foreach ($qData['answers'] as $index => $answer) {
                $question->answers()->create([
                    'answer' => $answer,
                    'is_correct' => ($index == $qData['correct_answer']) ? 1 : 0, // Đáp án đúng
                ]);
            }
        }
    
        return redirect()->route('mentor.show', $course->id)->with('success', 'Bài kiểm tra đã được tạo!');
    }
    public function submitTest(Request $request, $testId)
    {
        $test = Test::with('questions.answers')->findOrFail($testId);
        $score = 0;
        $totalQuestions = $test->questions->count();

        // Kiểm tra nếu không có câu hỏi
        if ($totalQuestions == 0) {
            return redirect()->back()->with('error', 'Không có câu hỏi nào để chấm điểm.');
        }

        // Chấm điểm
        foreach ($test->questions as $question) { 
            $correctAnswer = $question->answers->where('is_correct', true)->first();

            if ($correctAnswer && isset($request->answers[$question->id]) && $request->answers[$question->id] == $correctAnswer->id) {
                $score++; // Cộng điểm nếu chọn đúng đáp án
            }
        }

        // Tính phần trăm điểm
        $percentage = ($score / $totalQuestions) * 100;
        $passed = $percentage >= $test->pass_score; // Kiểm tra đạt hay không

        // Lưu kết quả vào bảng test_results
        TestResult::updateOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $test->id],
            ['score' => $percentage, 'passed' => $passed]
        );

        return redirect()->route('courses.show', $test->course->id)->with('success', 'Bạn đã hoàn thành bài kiểm tra! Điểm số: ' . $percentage . '%');
    }

    public function edit($id)
    {
        $test = Test::with('questions.answers')->findOrFail($id);
        return view('tests.edit', compact('test'));
    }


    public function update(Request $request, $test_id)
    {
        $test = Test::findOrFail($test_id);
        $test->update(['title' => $request->title]);

        foreach ($request->questions as $qData) {
            if (isset($qData['id'])) {
                // Cập nhật câu hỏi đã có
                $question = Question::findOrFail($qData['id']);
                $question->update(['question' => $qData['question']]);
            } else {
                // Tạo câu hỏi mới
                $question = $test->questions()->create(['question' => $qData['question']]);
            }

            foreach ($qData['answers'] as $aData) {
                if (isset($aData['id'])) {
                    // Cập nhật đáp án đã có
                    $answer = Answer::findOrFail($aData['id']);
                    $answer->update([
                        'answer' => $aData['text'],
                        'is_correct' => ($aData['id'] == $qData['correct_answer']) ? 1 : 0,
                    ]);
                } else {
                    // Tạo đáp án mới
                    $question->answers()->create([
                        'answer' => $aData['text'],
                        'is_correct' => 0, // Đặt mặc định sai
                    ]);
                }
            }

            // Đánh dấu đáp án đúng
            if (isset($qData['correct_answer'])) {
                Answer::where('question_id', $question->id)->update(['is_correct' => 0]); // Đặt tất cả về sai
                Answer::where('id', $qData['correct_answer'])->update(['is_correct' => 1]); // Đánh dấu đúng
            }
        }

        return redirect()->route('tests.edit', $test->id)->with('success', 'Bài kiểm tra đã được cập nhật!');
    }

}
