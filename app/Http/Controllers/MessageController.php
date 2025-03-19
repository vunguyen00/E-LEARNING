<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    // Hiển thị giao diện tin nhắn với danh sách người dùng
    public function chat()
    {
        $users = User::where('id', '!=', auth()->id())->get(); // Lấy danh sách user trừ chính mình
        return view('messages', compact('users'));
    }
    public function index($userId = null)
    {
        $users = User::where('id', '!=', Auth::id())->get(); // Lấy danh sách người dùng, trừ chính mình
        $selectedUser = $userId ? User::find($userId) : null;
        $messages = $selectedUser ? Message::where(function ($query) use ($selectedUser) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $selectedUser->id);
        })->orWhere(function ($query) use ($selectedUser) {
            $query->where('sender_id', $selectedUser->id)->where('receiver_id', Auth::id());
        })->orderBy('created_at')->get() : null;

        return view('messages', compact('users', 'selectedUser', 'messages'));
    }
    public function fetchMessages($receiverId)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
                $query->where('sender_id', auth()->id())->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($receiverId) {
                $query->where('sender_id', $receiverId)->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    
        return view('partials.chat-messages', compact('messages'));
    }

    // Gửi tin nhắn
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return response()->json($message);
    }
}
