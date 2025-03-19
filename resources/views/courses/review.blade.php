@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center text-primary fw-bold mb-4">Đánh giá khóa học</h2>
    
    <div class="card shadow-lg p-4">
        <h3 class="text-center text-secondary mb-3">{{ $course->title }}</h3>

        <form action="{{ route('courses.review.submit', $course->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Chọn số sao:</label>
                <select name="rating" class="form-select">
                    <option value="5">⭐⭐⭐⭐⭐ - Tuyệt vời</option>
                    <option value="4">⭐⭐⭐⭐ - Tốt</option>
                    <option value="3">⭐⭐⭐ - Bình thường</option>
                    <option value="2">⭐⭐ - Kém</option>
                    <option value="1">⭐ - Rất tệ</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Bình luận của bạn:</label>
                <textarea name="comment" class="form-control" rows="4" required placeholder="Hãy chia sẻ cảm nhận của bạn về khóa học này..."></textarea>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Gửi đánh giá</button>
            </div>
        </form>
    </div>

    <h3 class="text-center text-secondary mt-5 mb-3">Đánh giá từ học viên</h3>

    @if($course->reviews->count() > 0)
        @foreach($course->reviews as $review)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-primary">
                        <i class="bi bi-person-circle"></i> {{ $review->user->name }}
                        <span class="text-warning ms-2">{{ str_repeat('⭐', $review->rating) }}</span>
                    </h5>
                    <p class="card-text">{{ $review->comment }}</p>
                    <small class="text-muted"><i class="bi bi-clock"></i> {{ $review->created_at->format('d/m/Y') }}</small>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted text-center">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
    @endif
</div>
@endsection
