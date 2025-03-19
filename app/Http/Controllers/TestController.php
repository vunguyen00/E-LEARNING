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
    
        if ($totalQuestions == 0) {
            return redirect()->back()->with('error', 'Bài kiểm tra chưa có câu hỏi.');
        }
    
        foreach ($test->questions as $question) {
            $selectedAnswerId = $userAnswers[$question->id] ?? null;
            $correctAnswer = $question->answers->where('is_correct', 1)->first();
        
            if ($correctAnswer && $correctAnswer->id == $selectedAnswerId) {
                $correctCount++;
            }
        }
    
        $score = ($correctCount / $totalQuestions) * 100;
        $passed = $score >= 70;
    
        TestResult::updateOrCreate(
            ['user_id' => Auth::id(), 'test_id' => $testId],
            ['score' => $score, 'passed' => $passed, 'course_id' => $test->course_id]
        );
    
        // Nếu chưa đạt, thiết lập thông báo lỗi
        if (!$passed) {
            $message = "❌ Bạn chưa đạt yêu cầu (Điểm: $score). Vui lòng thử lại!";
        } else {
            $message = "🎉 Chúc mừng! Bạn đã đỗ với số điểm: $score";
        }
    
        // Trả về view hiển thị kết quả bằng SweetAlert
        return view('courses.test_result', compact('score', 'passed', 'message', 'courseId'));
    }
    
    public function edit($id)
    {
        $test = Test::with('questions.answers')->findOrFail($id);
        return view('tests.edit', compact('test'));
    }

    public function update(Request $request, $testId)
    {
        $test = Test::findOrFail($testId);
        $test->update(['title' => $request->title]);
    
        // Lấy danh sách ID các câu hỏi hiện tại trong request
        $questionIds = collect($request->questions)->pluck('id')->filter()->toArray();
    
        // Xóa các câu hỏi không có trong request
        Question::where('test_id', $test->id)->whereNotIn('id', $questionIds)->each(function ($question) {
            $question->answers()->delete(); // Xóa hết đáp án trước
            $question->delete(); // Xóa câu hỏi
        });
    
        // Cập nhật hoặc tạo mới câu hỏi
        foreach ($request->questions as $qData) {
            $question = Question::updateOrCreate(
                ['id' => $qData['id'] ?? null], // Nếu không có ID thì tạo mới
                ['question' => $qData['question'], 'test_id' => $test->id]
            );
    
            $answerIds = collect($qData['answers'])->pluck('id')->filter()->toArray();
    
            // Xóa các câu trả lời không còn tồn tại trong request
            Answer::where('question_id', $question->id)->whereNotIn('id', $answerIds)->delete();
    
            // Đặt tất cả các đáp án của câu hỏi về `is_correct = 0` trước khi cập nhật
            Answer::where('question_id', $question->id)->update(['is_correct' => 0]);
    
            foreach ($qData['answers'] as $aData) {
                $answer = Answer::updateOrCreate(
                    ['id' => $aData['id'] ?? null], // Nếu không có ID thì tạo mới
                    [
                        'question_id' => $question->id,
                        'answer' => $aData['text'],
                        'is_correct' => 0 // Mặc định là sai, cập nhật đúng ở bước sau
                    ]
                );
    
                // Nếu đây là đáp án đúng, cập nhật lại `is_correct = 1`
                if (isset($qData['correct_answer']) && $qData['correct_answer'] == $answer->id) {
                    $answer->update(['is_correct' => 1]);
                }
            }
        }
    
        return redirect()->route('tests.edit', $test->id)->with('success', 'Bài kiểm tra đã được cập nhật!');
    }
    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        return view('tests.create', compact('course'));
    }

    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
    
        // Tạo bài kiểm tra
        $test = Test::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'pass_score' => $request->pass_score ?? 70,
        ]);
    
        // Lưu câu hỏi và đáp án
        foreach ($request->questions as $qIndex => $qData) {
            // Tạo câu hỏi
            $question = $test->questions()->create([
                'question' => $qData['question'],
            ]);
    
            // Tạo từng đáp án
            foreach ($qData['answers'] as $aIndex => $answerText) {
                $isCorrect = ($aIndex == intval($qData['correct_answer'])) ? 1 : 0;
                $question->answers()->create([
                    'answer' => $answerText,
                    'is_correct' => $isCorrect,
                ]);
            }
        }
    
        return redirect()->route('index', $course->id)
                         ->with('success', 'Bài kiểm tra đã được tạo!');
    }
    
    public function destroyQuestion($questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->answers()->delete();
        $question->delete();
    
        return redirect()->back()->with('success', 'Câu hỏi đã được xóa!');
    }
}
