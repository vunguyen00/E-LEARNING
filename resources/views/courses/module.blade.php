@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="text-primary text-center mb-4">
                🎬 {{ $module->title }}
            </h1>
            <p class="text-muted text-center">{{ $module->description }}</p>

            {{-- Hiển thị video --}}
            @if($module->video_url)
                <div class="video-container text-center">
                    <video id="moduleVideo" class="rounded shadow" width="100%" height="500px" controls>
                        <source src="{{ asset('videos/' . basename($module->video_url)) }}" type="video/mp4">
                        Trình duyệt của bạn không hỗ trợ video.
                    </video>
                </div>
            @else
                <p class="text-muted text-center">🚫 Không có video cho module này.</p>
            @endif

            {{-- Form hoàn thành bài học --}}
            <form id="completeForm" action="{{ route('chap.complete', ['module' => $module->id]) }}" method="POST" style="display: none;">
                @csrf
            </form>

            {{-- Nút quay lại khóa học --}}
            <div class="text-center mt-4">
                <a href="{{ route('courses.learn', ['id' => $module->course_id]) }}" class="btn btn-secondary">
                    ⬅️ Quay lại khóa học
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Script hoàn thành khi kết thúc video --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const video = document.getElementById("moduleVideo");
        const form = document.getElementById("completeForm");

        video.addEventListener("ended", function() {
            Swal.fire({
                title: "🎉 Chúc mừng!",
                text: "Bạn đã hoàn thành bài học này.",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                form.submit();
            });
        });
    });
</script>
@endsection
