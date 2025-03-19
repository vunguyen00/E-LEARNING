@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-primary text-center mb-4">📚 Khóa học: {{ $course->title }}</h1>

    <h2 class="text-secondary">📖 Danh sách Chương</h2>

    <div class="row">
        @foreach($course->modules as $index => $module)
            @php
                $isCompleted = in_array($module->id, $completedModules);
                $isUnlocked = $index === 0 || in_array($course->modules[$index - 1]->id, $completedModules);
            @endphp

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">📌 {{ $module->title }}</h5>

                        @if($isUnlocked)
                            <a href="{{ route('modules.show', ['module' => $module->id]) }}" class="btn btn-info">
                                <i class="fas fa-play-circle"></i> Học bài
                            </a>

                            @if($isCompleted)
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check-circle"></i> Đã hoàn thành
                                </button>
                            @endif
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-lock"></i> Chưa mở khóa
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
