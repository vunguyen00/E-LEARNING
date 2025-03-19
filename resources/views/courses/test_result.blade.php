@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Script SweetAlert2 để hiển thị kết quả bài kiểm tra -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            Swal.fire({
                icon: "{{ $passed ? 'success' : 'error' }}",
                title: "Kết quả bài kiểm tra",
                text: "{{ $message }}",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "{{ route('courses.index') }}";
            });
        });
    </script>
</div>
@endsection
