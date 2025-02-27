@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="text-center mb-4">📚 Tạo Module cho khóa học: <strong>{{ $course->title }}</strong></h1>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('module.store', ['course' => $course]) }}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">📌 Tiêu đề Module</label>
                <input type="text" name="title" class="form-control" required placeholder="Nhập tiêu đề module">
            </div>

            <div class="mb-3">
                <label class="form-label">📝 Mô tả</label>
                <textarea name="description" class="form-control" rows="3" required placeholder="Nhập mô tả module"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">📖 Chương</label>
                <input type="number" name="chap" step="0.1" class="form-control" required placeholder="Nhập số chương">
            </div>

            <div class="mb-3">
                <label class="form-label">🎥 Chọn Video</label>
                <input type="file" name="video_url" class="form-control" accept="video/*" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mentor.show', ['course' => $course]) }}" class="btn btn-secondary">🔙 Quay lại</a>
                <button type="submit" class="btn btn-primary">✅ Thêm Module</button>
            </div>
        </form>
    </div>
</div>

@endsection
