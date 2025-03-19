@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card mx-auto shadow-lg p-4" style="max-width: 500px;">
        <h2 class="text-center text-primary mb-4">Thanh toán khóa học</h2>
        
        <div class="text-center">
            <h4 class="fw-bold">{{ $course->title }}</h4>
            <p class="fs-5 text-danger fw-bold">Giá: {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('courses.processPayment', $course->id) }}" class="d-grid gap-3 mt-3">
            @csrf
            <button type="submit" class="btn btn-success fw-bold">💳 Thanh toán ngay</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">⬅ Quay lại</a>
        </div>
    </div>
</div>
@endsection
