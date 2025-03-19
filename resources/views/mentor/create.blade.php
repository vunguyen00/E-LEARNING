@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="text-center">📚 Tạo Khóa Học Mới</h1>

    <div class="card p-4 shadow">
        <form method="POST" action="{{ route('mentor.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">📌 Tiêu đề khóa học:</label>
                <input type="text" class="form-control" name="title" placeholder="Nhập tiêu đề khóa học" required>
            </div>

            <div class="mb-3">
                <label class="form-label">📝 Mô tả khóa học:</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Nhập mô tả chi tiết" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">💰 Giá khóa học (VND):</label>
                <input type="number" class="form-control" name="price" placeholder="Nhập giá khóa học" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('index') }}" class="btn btn-secondary">🔙 Quay lại</a>
                <button type="submit" class="btn btn-success">🚀 Tạo Khóa Học</button>
            </div>
        </form>
    </div>
</div>

@endsection
