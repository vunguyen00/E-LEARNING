@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('score'))
        <!-- Hiển thị điểm nếu có -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Debug: in ra console để xác nhận script được chạy
                console.log("Hiển thị kết quả bài kiểm tra");

                Swal.fire({
                    icon: "{{ session('passed') ? 'success' : 'error' }}",
                    title: '🎉 Kết quả bài kiểm tra!',
                    text: "Bạn đã đạt {{ session('score') }} điểm. {{ session('passed') ? 'Đạt' : 'Không đạt' }}",
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if(result.isConfirmed) {
                        window.location.href = "{{ route('courses.index') }}"; // Chuyển hướng sau khi nhấn OK
                    }
                });
            });
        </script>
    @endif

    <h2 class="text-center">📝 Bài kiểm tra: {{ $test->title }}</h2>
    <p class="text-muted">Khóa học: <strong>{{ $test->course->title }}</strong></p>

    @if($test->questions->isEmpty())
        <div class="alert alert-warning">⚠️ Chưa có câu hỏi nào.</div>
    @else
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

            <button type="submit" class="btn btn-success mt-3">📤 Nộp bài</button>
        </form>
    @endif
</div>
@endsection
