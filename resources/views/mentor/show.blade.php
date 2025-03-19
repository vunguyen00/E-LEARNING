@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="text-center mb-4">📚 Danh sách Modules của khóa học: <strong>{{ $course->title }}</strong></h1>

    <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('module.create', ['course' => $course->id]) }}" class="btn btn-primary">➕ Thêm Module</a>
    <a href="{{ route('test.create', ['course' => $course->id]) }}" class="btn btn-success">📝 Tạo bài kiểm tra</a>
    </div>

    @if($modules->isEmpty())
        <div class="alert alert-warning text-center">⚠️ Chưa có module nào trong khóa học này.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>📖 Chương</th>
                        <th>📌 Tiêu đề</th>
                        <th>📝 Mô tả</th>
                        <th>🎥 Video</th>
                        <th>⚙️ Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modules as $module)
                    <tr>
                        <td class="align-middle text-center">{{ $module->chap }}</td>
                        <td class="align-middle">{{ $module->title }}</td>
                        <td class="align-middle">{{ $module->description }}</td>
                        <td class="align-middle text-center">
                            @if($module->video_url)
                                <video width="200" controls>
                                    <source src="{{ asset('videos/' . basename($module->video_url)) }}" type="video/mp4">
                                    Trình duyệt của bạn không hỗ trợ video.
                                </video>
                            @else
                                <span class="text-muted">❌ Chưa có video</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <a href="{{ route('module.edit', ['course' => $course->id, 'module' => $module->id]) }}" class="btn btn-warning btn-sm">✏️ Sửa</a>
                            <form action="{{ route('module.destroy', ['course' => $course->id, 'module' => $module->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('⚠️ Bạn có chắc chắn muốn xóa module này không?');">🗑️ Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
