@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="text-center">✏️ Chỉnh Sửa Khóa Học</h1>

    <div class="card p-4 shadow">
        <form method="post" action="{{ route('mentor.update', $course->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">📌 Tiêu đề khóa học:</label>
                <input type="text" class="form-control" name="title" value="{{ $course->title }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">📝 Mô tả khóa học:</label>
                <textarea class="form-control" name="description" rows="4" required>{{ $course->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">💰 Giá khóa học (VND):</label>
                <input type="number" class="form-control" name="price" value="{{ $course->price }}" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('index') }}" class="btn btn-secondary">🔙 Quay lại</a>
                <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection
