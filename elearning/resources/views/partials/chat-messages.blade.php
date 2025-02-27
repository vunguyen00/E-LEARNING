@if($messages->isEmpty())
    <p class="text-muted">Chưa có tin nhắn nào.</p>
@else
    @foreach($messages as $message)
        <div class="mb-2 p-2 {{ $message->sender_id == auth()->id() ? 'text-end' : '' }}">
            <span class="badge bg-{{ $message->sender_id == auth()->id() ? 'success' : 'secondary' }}">
                {{ $message->message }}
            </span>
        </div>
    @endforeach
@endif
