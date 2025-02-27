@extends('layouts.app')

@section('content')
<h1>Thanh toán khóa học: {{ $course->title }}</h1>
<p>Giá: {{ number_format($course->price, 0, ',', '.') }} VNĐ</p>

@if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<form method="POST" action="{{ route('courses.processPayment', $course->id) }}">
    @csrf
    <button type="submit" class="btn btn-primary">Thanh toán ngay</button>
</form>

<a href="{{ route('courses.index') }}" class="btn btn-secondary">Quay lại</a>
@endsection
