@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary text-center mb-4">Chào học sinh {{ Auth::user()->name }}</h1>

    {{-- Thanh tìm kiếm --}}
    <form method="GET" action="{{ route('courses.index') }}" class="my-4 d-flex justify-content-center">
        <input type="text" name="search" class="form-control w-50 me-2" placeholder="Tìm kiếm khóa học..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
    </form>

    <h2 class="text-secondary text-center mb-3">Danh sách khóa học</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Tên khóa học</th>
                <th>Số chương</th>
                <th>Tiến trình</th>
                <th>Điểm đánh giá ⭐</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>
                    <a href="{{ route('courses.show', $course->id) }}" class="text-decoration-none fw-bold">{{ $course->title }}</a>
                </td>
                <td>{{ $course->modules_count }}</td>
                <td>
                    @if($course->isRegistered(Auth::user()->id))
                        <span class="badge bg-success">{{ $course->getProgress(Auth::user()->id) }}%</span>
                    @else
                        <span class="badge bg-secondary">Chưa đăng ký</span>
                    @endif
                </td>
                <td>
                    ⭐  {{ number_format($course->reviews->avg('rating'), 1) }}/5
                </td>
                <td>
                    @if($course->isRegistered(Auth::user()->id))
                        @php
                            $progress = $course->getProgress(Auth::user()->id);
                            $testResult = \App\Models\TestResult::where('user_id', Auth::id())->where('course_id', $course->id)->first();
                        @endphp
                        
                        @if($progress == 100 && !$testResult)
                        @if($course->test)
                            <a href="{{ route('courses.test', ['course' => $course->id, 'test' => $course->test->id]) }}" class="btn btn-info btn-sm">Làm bài kiểm tra</a>
                        @else
                            <span class="badge bg-warning">Chưa có bài kiểm tra</span>
                        @endif

                        @elseif($testResult && !$testResult->passed)
                            <span class="badge bg-danger">Chưa đạt yêu cầu</span>
                            @if($course->test)
                                <a href="{{ route('courses.test', ['course' => $course->id, 'test' => $course->test->id]) }}" class="btn btn-warning btn-sm">Thi lại</a>
                            @endif

                        @elseif($testResult && $testResult->passed)
                            <span class="badge bg-success">Đã hoàn thành</span>
                            <a href="{{ route('courses.review', $course->id) }}" class="btn btn-primary btn-sm">Đánh giá khóa học</a>
                        @else
                            <a href="{{ route('courses.learn', $course->id) }}" class="btn btn-success btn-sm">Vào học</a>
                        @endif
                    @else
                        <form action="{{ route('courses.register', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Đăng ký học</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>

        </table>
    </div>
</div>
@endsection
