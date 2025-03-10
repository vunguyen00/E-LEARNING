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

        if (!$passed) {
            return redirect()->route('courses.index')->with('error', "❌ Bạn chưa đạt yêu cầu (Điểm: $score). Vui lòng thử lại!");
        }
        
        return redirect()->route('courses.show', $courseId)->with('success', "🎉 Chúc mừng! Bạn đã đỗ với số điểm: $score");
    }

    public function create(Course $course)
    {
        return view('tests.create', compact('course'));
    }

    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        $test = Test::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'pass_score' => $request->pass_score ?? 50,
        ]);

        foreach ($request->questions as $qData) {
            $question = $test->questions()->create(['question' => $qData['question']]);

            foreach ($qData['answers'] as $index => $answer) {
                $isCorrect = $index == intval($qData['correct_answer']) ? 1 : 0;
                $question->answers()->create([
                    'answer' => $answer,
                    'is_correct' => $isCorrect,
                ]);
            }
        }

        return redirect()->route('mentor.show', $course->id)->with('success', 'Bài kiểm tra đã được tạo!');
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

        foreach ($request->questions as $qData) {
            $question = isset($qData['id']) ? Question::findOrFail($qData['id']) : $test->questions()->create(['question' => $qData['question']]);

            foreach ($qData['answers'] as $index => $aData) {
                $answer = isset($aData['id']) ? Answer::findOrFail($aData['id']) : $question->answers()->create(['answer' => $aData['text'], 'is_correct' => 0]);
                $answer->update(['answer' => $aData['text']]);
            }

            if (isset($qData['correct_answer'])) {
                Answer::where('question_id', $question->id)->update(['is_correct' => 0]);
                Answer::where('id', $qData['correct_answer'])->update(['is_correct' => 1]);
            }
        }

        return redirect()->route('tests.edit', $test->id)->with('success', 'Bài kiểm tra đã được cập nhật!');
    }
}
