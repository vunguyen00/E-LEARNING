@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="text-center">Chào mentor, {{ Auth::user()->name }} 👋</h1>

    <div class="d-flex justify-content-between my-3">
        <h2>Khóa học của bạn</h2>
        <a href="{{ route('mentor.create') }}" class="btn btn-success">➕ Tạo khóa học</a>
    </div>

    @if($courses->isEmpty())
        <div class="alert alert-info text-center">
            <p>Bạn chưa có khóa học nào. Hãy tạo khóa học mới!</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td class="fw-bold">{{ $course->title }}</td>
                        <td>{{ Str::limit($course->description, 50) }}</td>
                        <td class="text-success fw-bold">{{ number_format($course->price) }} VND</td>
                        <td>
                            <a href="{{ route('mentor.show', $course->id) }}" class="btn btn-primary btn-sm">👀 Xem</a>
                            <a href="{{ route('mentor.edit', $course->id) }}" class="btn btn-warning btn-sm">✏️ Sửa</a>
                            <form action="{{ route('mentor.destroy', $course->id) }}" method="post" style="display:inline;">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️ Xóa</button>
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
