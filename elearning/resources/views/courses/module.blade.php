@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $module->title }}</h1>
    <p>{{ $module->description }}</p>

    @if($module->video_url)
        <video id="moduleVideo" width="100%" height="500vh" controls>
            <source src="{{ asset('videos/' . basename($module->video_url)) }}" type="video/mp4">
            Trình duyệt của bạn không hỗ trợ video.
        </video>
    @else
        <p class="text-muted">Không có video cho module này.</p>
    @endif

    <form id="completeForm" action="{{ route('chap.complete', ['module' => $module->id]) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <a href="{{ route('courses.learn', ['id' => $module->course_id]) }}" class="btn btn-secondary mt-3">Quay lại khóa học</a>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const video = document.getElementById("moduleVideo");
        const form = document.getElementById("completeForm");

        video.addEventListener("ended", function() {
            form.submit();
        });
    });
</script>
@endsection