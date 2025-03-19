@extends('layouts.app') 
{{-- Giả sử bạn dùng layout app.blade.php --}}

@section('content')
<div class="container">
    <form method="POST" action="{{ route('tests.store', $course->id) }}">
        @csrf

        {{-- Thông tin bài kiểm tra --}}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <strong>Tạo bài kiểm tra mới</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên bài kiểm tra</label>
                    <input type="text" class="form-control" name="title" placeholder="VD: Kiểm tra cuối khoá" required>
                </div>

                {{-- Danh sách câu hỏi --}}
                <h5 class="mt-4">Danh sách câu hỏi</h5>
                <div id="questions">
                    {{-- Câu hỏi đầu tiên (mặc định) --}}
                    <div class="question card border-secondary mb-3">
                        <div class="card-header">Câu hỏi 1</div>
                        <div class="card-body">
                            <input type="text" 
                                   class="form-control mb-3" 
                                   name="questions[0][question]" 
                                   placeholder="Nhập câu hỏi" required>

                            <div class="answers">
                                <input type="text" class="form-control mb-2" name="questions[0][answers][]" placeholder="Đáp án 1" required>
                                <input type="text" class="form-control mb-2" name="questions[0][answers][]" placeholder="Đáp án 2" required>
                                <input type="text" class="form-control mb-2" name="questions[0][answers][]" placeholder="Đáp án 3" required>

                                <label class="form-label mt-2">
                                    Đáp án đúng:
                                    <input type="number" 
                                           class="form-control d-inline-block w-auto ms-2" 
                                           name="questions[0][correct_answer]" 
                                           min="0" 
                                           max="2" 
                                           value="0" 
                                           required>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-question" class="btn btn-outline-secondary">
                    ➕ Thêm câu hỏi
                </button>
            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    💾 Lưu bài kiểm tra
                </button>
                @if(isset($course->test))
                    <a href="{{ route('tests.edit', $course->test->id) }}" class="btn btn-warning">✏️ Sửa bài kiểm tra</a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- Script để thêm câu hỏi động --}}
<script>
document.getElementById('add-question').addEventListener('click', function () {
    let index = document.querySelectorAll('.question').length; 
    let newQuestion = document.createElement('div');
    newQuestion.classList.add('question', 'card', 'border-secondary', 'mb-3');
    
    // Tạo template cho câu hỏi mới
    newQuestion.innerHTML = `
        <div class="card-header">Câu hỏi ${index + 1}</div>
        <div class="card-body">
            <input type="text" 
                   class="form-control mb-3" 
                   name="questions[${index}][question]" 
                   placeholder="Nhập câu hỏi" required>

            <div class="answers">
                <input type="text" class="form-control mb-2" name="questions[${index}][answers][]" placeholder="Đáp án 1" required>
                <input type="text" class="form-control mb-2" name="questions[${index}][answers][]" placeholder="Đáp án 2" required>
                <input type="text" class="form-control mb-2" name="questions[${index}][answers][]" placeholder="Đáp án 3" required>

                <label class="form-label mt-2">
                    Đáp án đúng:
                    <input type="number" 
                           class="form-control d-inline-block w-auto ms-2" 
                           name="questions[${index}][correct_answer]" 
                           min="0" 
                           max="2" 
                           value="0" 
                           required>
                </label>
            </div>
        </div>
    `;
    document.getElementById('questions').appendChild(newQuestion);
});
</script>
@endsection
