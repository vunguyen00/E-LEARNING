@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Danh sách người dùng bên trái -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-users"></i> Danh sách người dùng
                </div>
                <div class="list-group user-list">
                    @foreach($users as $user)
                        <a href="{{ url('/messages/' . $user->id) }}" class="list-group-item list-group-item-action 
                            {{ isset($selectedUser) && $selectedUser->id == $user->id ? 'active' : '' }}">
                            <i class="fas fa-user-circle"></i> {{ $user->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Khung tin nhắn bên phải -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-comments"></i> Tin nhắn với 
                    {{ isset($selectedUser) ? $selectedUser->name : '...' }}
                </div>
                <div class="card-body chat-box" style="height: 400px; overflow-y: auto;">
                    @if(isset($messages))
                        @foreach($messages as $message)
                            <div class="mb-2 p-2 {{ $message->sender_id == auth()->id() ? 'text-end' : '' }}">
                                <span class="badge bg-{{ $message->sender_id == auth()->id() ? 'success' : 'secondary' }}">
                                    {{ $message->message }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Hãy chọn một người để nhắn tin</p>
                    @endif
                </div>

                <!-- Form gửi tin nhắn -->
                @if(isset($selectedUser))
                <div class="card-footer">
                    <form id="messageForm">
                        @csrf
                        <input type="hidden" id="receiver_id" value="{{ $selectedUser->id }}">
                        <div class="input-group">
                            <textarea id="message" class="form-control" placeholder="Nhập tin nhắn..."></textarea>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    // Hàm cập nhật tin nhắn
    function updateChat() {
        // Lấy id người nhận từ input ẩn
        const receiverId = document.getElementById('receiver_id').value;

        // Gửi request lấy tin nhắn mới (cần tạo thêm route và controller trả về HTML hoặc JSON)
        fetch(`/messages/${receiverId}/chat`, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Update nội dung chat-box
            const chatBox = document.querySelector('.chat-box');
            chatBox.innerHTML = html;
            // Sau khi load xong tin nhắn, tự động scroll xuống cuối
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => console.error('Error:', error));
    }

    // Lắng nghe sự kiện submit của form gửi tin nhắn
    document.getElementById('messageForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const receiverId = document.getElementById('receiver_id').value;
        const message = document.getElementById('message').value;

        fetch('/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ receiver_id: receiverId, message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Sau khi gửi, cập nhật lại khung chat
            updateChat();
            // Xoá nội dung textbox
            document.getElementById('message').value = '';
        });
    });

    // Khi trang load xong, tự động scroll xuống cuối chat box
    window.addEventListener('load', function() {
        const chatBox = document.querySelector('.chat-box');
        if(chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    // Đặt interval để tự động cập nhật tin nhắn (ví dụ mỗi 5 giây)
    setInterval(updateChat, 1000);
</script>

@endsection
