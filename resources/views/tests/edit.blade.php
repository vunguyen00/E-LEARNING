@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center">✏️ Chỉnh sửa bài kiểm tra: {{ $test->title }}</h2>
    <p class="text-muted">Khóa học: <strong>{{ $test->course->title }}</strong></p>

    <form method="POST" action="{{ route('tests.update', $test->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên bài kiểm tra</label>
            <input type="text" class="form-control" name="title" value="{{ $test->title }}" required>
        </div>

        <h3>Câu hỏi</h3>
        <div id="questions">
            @foreach($test->questions as $index => $question)
                <div class="question mb-4">
                    <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">
                    <input type="text" class="form-control" name="questions[{{ $index }}][question]" value="{{ $question->question }}" required>

                    <div class="answers mt-2">
                        @foreach($question->answers as $answerIndex => $answer)
                            <div class="d-flex align-items-center mb-2">
                                <input type="text" class="form-control me-2" name="questions[{{ $index }}][answers][{{ $answerIndex }}][text]" value="{{ $answer->answer }}" required>
                                <input type="hidden" name="questions[{{ $index }}][answers][{{ $answerIndex }}][id]" value="{{ $answer->id }}">
                                <input type="radio" name="questions[{{ $index }}][correct_answer]" value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                <label class="ms-1">Đúng</label>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-danger remove-question">🗑 Xóa câu hỏi</button>
                </div>
            @endforeach
        </div>

        <button type="button" id="add-question" class="btn btn-secondary mt-2">➕ Thêm câu hỏi</button>
        <button type="submit" class="btn btn-primary mt-2">💾 Lưu thay đổi</button>
    </form>
</div>
<script>
document.getElementById('add-question').addEventListener('click', function () {
    let index = document.querySelectorAll('.question').length;
    let newQuestion = document.createElement('div');
    newQuestion.classList.add('question', 'mb-4');

    newQuestion.innerHTML = `
        <!-- Nếu muốn có input hidden ID cho câu hỏi mới, bạn có thể thêm ở đây, ví dụ:
             <input type=\"hidden\" name=\"questions[\${index}][id]\" value=\"\" /> 
             để nhất quán với cấu trúc cũ. -->

        <input type=\"text\" class=\"form-control\" 
               name=\"questions[\${index}][question]\" 
               placeholder=\"Nhập câu hỏi mới\" required>

        <div class=\"answers mt-2\">
            <div class=\"d-flex align-items-center mb-2\">
                <input type=\"text\" class=\"form-control me-2\" 
                       name=\"questions[\${index}][answers][0][text]\" 
                       placeholder=\"Đáp án 1\" required>
                <input type=\"radio\" name=\"questions[\${index}][correct_answer]\" value=\"0\">
                <label class=\"ms-1\">Đúng</label>
            </div>

            <div class=\"d-flex align-items-center mb-2\">
                <input type=\"text\" class=\"form-control me-2\" 
                       name=\"questions[\${index}][answers][1][text]\" 
                       placeholder=\"Đáp án 2\" required>
                <input type=\"radio\" name=\"questions[\${index}][correct_answer]\" value=\"1\">
                <label class=\"ms-1\">Đúng</label>
            </div>

            <div class=\"d-flex align-items-center mb-2\">
                <input type=\"text\" class=\"form-control me-2\" 
                       name=\"questions[\${index}][answers][2][text]\" 
                       placeholder=\"Đáp án 3\" required>
                <input type=\"radio\" name=\"questions[\${index}][correct_answer]\" value=\"2\">
                <label class=\"ms-1\">Đúng</label>
            </div>
        </div>

        <button type=\"button\" class=\"btn btn-sm btn-danger remove-question\">🗑 Xóa câu hỏi</button>
    `;

    document.getElementById('questions').appendChild(newQuestion);
});

// Xóa câu hỏi
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-question')) {
        event.target.parentElement.remove();
    }
});
</script>

@endsection
