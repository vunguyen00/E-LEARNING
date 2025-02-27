@extends('layouts.app')

@section('content')
<h1>Khóa học: {{ $course->title }}</h1>

<h2>Danh sách Chương</h2>

@foreach($course->modules as $index => $module)
    <div class="module">
        <h3>{{ $module->title }}</h3>

        @php
            $isCompleted = in_array($module->id, $completedModules);
            $isUnlocked = $index === 0 || in_array($course->modules[$index - 1]->id, $completedModules);
        @endphp

        @if($isUnlocked)
            <a href="{{ route('modules.show', ['module' => $module->id]) }}" class="btn btn-info">Học bài</a>
            
            @if($isCompleted)
                <button class="btn btn-success" disabled>Đã hoàn thành</button>
            @endif
        @else
            <button class="btn btn-secondary" disabled>Chưa mở khóa</button>
        @endif
    </div>
    <hr>
@endforeach

@endsection
