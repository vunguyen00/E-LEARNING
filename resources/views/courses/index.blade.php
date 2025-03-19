@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary text-center mb-4">Chào học sinh {{ Auth::user()->name }}</h1>

    {{-- Thanh tìm kiếm --}}
    <form method="GET" action="{{ route('courses.index') }}" class="my-4 d-flex justify-content-center">
        <input type="text" name="search" class="form-control w-50 me-2" placeholder="Tìm kiếm khóa học..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>

    {{-- Tabs điều hướng --}}
    <ul class="nav nav-tabs mb-3" id="courseTabs">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#allCourses">📚 Tất cả khóa học</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#ongoingCourses">🟡 Đang học</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#completedCourses">✅ Đã hoàn thành</a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Tất cả khóa học --}}
        <div id="allCourses" class="tab-pane fade show active">
            @include('partials.course_table', ['courses' => $courses])
        </div>

        {{-- Khóa học đang học --}}
        <div id="ongoingCourses" class="tab-pane fade">
            @php
                $ongoingCourses = $courses->filter(fn($course) => $course->isRegistered(Auth::id()) && $course->getProgress(Auth::id()) < 100);
            @endphp
            @include('partials.course_table', ['courses' => $ongoingCourses])
        </div>

        {{-- Khóa học đã hoàn thành --}}
        <div id="completedCourses" class="tab-pane fade">
            @php
                $completedCourses = $courses->filter(fn($course) => $course->isRegistered(Auth::id()) && $course->getProgress(Auth::id()) == 100);
            @endphp
            @include('partials.course_table', ['courses' => $completedCourses])
        </div>
    </div>
</div>
@endsection
