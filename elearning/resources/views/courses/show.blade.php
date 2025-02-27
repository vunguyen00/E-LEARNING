@extends('layouts.app')

@section('content')
    <h1>{{ $course->title }}</h1>
    <h3><strong>Khóa học của: {{ $course->mentor ? $course->mentor->name : 'Không xác định' }}</strong></h3>
    <p><strong>Mô tả:</strong> {{ $course->description }}</p>
    <p><strong>Số chương:</strong> {{ $course->modules->count() }}</p>
    <p><strong>Giá:</strong> {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>

    @if($course->isRegistered(Auth::id()))
        <p><strong>Tiến trình:</strong> {{ $course->getProgress(Auth::id()) }}%</p>
        <a href="{{ route('courses.learn', $course->id) }}" class="btn btn-success">Vào học</a>

        {{-- Mentor nhắn tin với học sinh --}}
        @if(Auth::id() === $course->mentor_id)
            <h2>Nhắn tin với học sinh</h2>
            <ul>
                @foreach($course->students as $student)
                    <li>
                        {{ $student->name }}
                        <a href="{{ route('messages.show', ['course' => $course->id, 'receiver' => $student->id]) }}" class="btn btn-primary">Nhắn tin</a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif

    <h2 class="mt-4">Đánh giá khóa học</h2>
    @if($course->reviews->count() > 0)
        <div class="card">
            <div class="card-body">
                <h4>⭐ Đánh giá trung bình: {{ number_format($course->reviews->avg('rating'), 1) }}/5</h4>
                <ul class="list-group">
                    @foreach($course->reviews as $review)
                        <li class="list-group-item">
                            <strong>{{ $review->user->name }}</strong> - {{ str_repeat('⭐', $review->rating) }}
                            <p>{{ $review->comment }}</p>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @else
        <p>Chưa có đánh giá nào cho khóa học này.</p>
    @endif

    <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách khóa học</a>
@endsection
