@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Đánh giá khóa học: {{ $course->title }}</h2>
    
    <form action="{{ route('courses.review.submit', $course->id) }}" method="POST">
        @csrf
        <label>Chọn số sao:</label>
        <select name="rating" class="form-select">
            <option value="5">⭐⭐⭐⭐⭐ - Tuyệt vời</option>
            <option value="4">⭐⭐⭐⭐ - Tốt</option>
            <option value="3">⭐⭐⭐ - Bình thường</option>
            <option value="2">⭐⭐ - Kém</option>
            <option value="1">⭐ - Rất tệ</option>
        </select>

        <label>Bình luận:</label>
        <textarea name="comment" class="form-control" required></textarea>
        
        <button type="submit" class="btn btn-primary mt-3">Gửi đánh giá</button>
    </form>

    <h3 class="mt-5">Đánh giá từ học viên</h3>
    @foreach($course->reviews as $review)
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">{{ $review->user->name }} - {{ str_repeat('⭐', $review->rating) }}</h5>
                <p class="card-text">{{ $review->comment }}</p>
                <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
            </div>
        </div>
    @endforeach
</div>
@endsection
