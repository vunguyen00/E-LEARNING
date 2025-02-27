@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if (session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h2 class="text-center">📝 Bài kiểm tra: {{ $test->title }}</h2>
    <p class="text-muted">Khóa học: <strong>{{ $test->course->title }}</strong></p>

    @if($test->questions->isEmpty())
        <div class="alert alert-warning">⚠️ Chưa có câu hỏi nào.</div>
    @else
        <!-- Chỉ có 1 form, không lồng form -->
        <form action="{{ route('courses.test.submit', ['course' => $test->course->id, 'test' => $test->id]) }}" method="POST">
            @csrf

            @foreach($test->questions as $index => $question)
                <div class="mb-4">
                    <h5>❓ Câu hỏi {{ $index + 1 }}: {{ $question->question }}</h5>

                    @if($question->answers->isEmpty())
                        <p class="text-danger">⚠️ Chưa có câu trả lời.</p>
                    @else
                        @foreach($question->answers as $answer)
                            <div class="form-check">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" class="form-check-input" required>
                                <label class="form-check-label">{{ $answer->answer }}</label>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach

            <!-- Nút submit nằm trong form chính -->
            <button type="submit" class="btn btn-success mt-3">📤 Nộp bài</button>
        </form>
    @endif
</div>
@endsection
