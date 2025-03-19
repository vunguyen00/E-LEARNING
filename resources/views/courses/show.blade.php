@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg p-4">
        <div class="row">
            {{-- Thông tin khóa học --}}
            <div class="col-md-8">
                <h1 class="text-primary">{{ $course->title }}</h1>
                <h4 class="text-muted">👨‍🏫 Mentor: <strong>{{ $course->mentor ? $course->mentor->name : 'Không xác định' }}</strong></h4>
                <p><strong>📖 Mô tả:</strong> {{ $course->description }}</p>
                <p><strong>📚 Số chương:</strong> {{ $course->modules->count() }}</p>
                <p><strong>💰 Giá:</strong> <span class="badge bg-danger fs-5">{{ number_format($course->price, 0, ',', '.') }} VNĐ</span></p>

                @if($course->isRegistered(Auth::id()))
                    <p><strong>⏳ Tiến trình:</strong> <span class="badge bg-success">{{ $course->getProgress(Auth::id()) }}%</span></p>
                    <a href="{{ route('courses.learn', $course->id) }}" class="btn btn-success"><i class="fas fa-play"></i> Vào học</a>
                @else
                    <a href="{{ route('courses.payment', $course->id) }}" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Đăng ký học</a>
                @endif
            </div>
        </div>
    </div>

    {{-- Mentor nhắn tin với học sinh --}}
    @if(Auth::id() === $course->mentor_id)
        <div class="mt-4">
            <h2>💬 Nhắn tin với học sinh</h2>
            <ul class="list-group">
                @foreach($course->students as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $student->name }}
                        <a href="{{ route('messages.show', ['course' => $course->id, 'receiver' => $student->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-comments"></i> Nhắn tin</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Đánh giá khóa học --}}
    <div class="mt-5">
        <h2>⭐ Đánh giá khóa học</h2>
        @if($course->reviews->count() > 0)
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>⭐ Đánh giá trung bình: <span class="text-warning">{{ number_format($course->reviews->avg('rating'), 1) }}/5</span></h4>
                    <ul class="list-group">
                        @foreach($course->reviews as $review)
                            <li class="list-group-item">
                                <strong>{{ $review->user->name }}</strong> - <span class="text-warning">{{ str_repeat('⭐', $review->rating) }}</span>
                                <p>{{ $review->comment }}</p>
                                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <p class="text-muted">Chưa có đánh giá nào cho khóa học này.</p>
        @endif
    </div>

    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Quay lại danh sách khóa học</a>
</div>
@endsection
