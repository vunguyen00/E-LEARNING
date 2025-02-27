@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Chỉnh sửa Module của khóa học: {{ $course->title }}</h1>

    <div class="card p-4 shadow">
        <form action="{{ route('module.update', ['course' => $course->id, 'module' => $module->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tiêu đề:</label>
                <input type="text" class="form-control" name="title" value="{{ $module->title }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả:</label>
                <textarea class="form-control" name="description" rows="3" required>{{ $module->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Chương:</label>
                <input type="number" class="form-control" name="chap" value="{{ $module->chap }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Video hiện tại:</label>
                @if($module->video_url)
                    <div class="d-flex align-items-center">
                        <video width="200" controls class="me-3">
                            <source src="{{ Storage::url($module->video_url) }}" type="video/mp4">
                        </video>
                    </div>
                @else
                    <p class="text-muted">Chưa có video</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Thay đổi video (nếu có):</label>
                <input type="file" class="form-control" name="video_url">
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('mentor.show', $course->id) }}" class="btn btn-secondary">Quay lại</a>
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection
