<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = User::where('id', '!=', $user->id)
            ->where(function ($q) use ($user) {
                $q->whereHas('sentMessages', fn($q2) => $q2->where('receiver_id', $user->id))
                  ->orWhereHas('receivedMessages', fn($q2) => $q2->where('sender_id', $user->id));
            })
            ->get()
            ->map(function ($otherUser) use ($user) {
                $lastMessage = Message::where(function ($q) use ($user, $otherUser) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $otherUser->id);
                })->orWhere(function ($q) use ($user, $otherUser) {
                    $q->where('sender_id', $otherUser->id)->where('receiver_id', $user->id);
                })->latest()->first();

                $unreadCount = Message::where('sender_id', $otherUser->id)
                    ->where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->count();

                $otherUser->last_message = $lastMessage;
                $otherUser->unread_count = $unreadCount;
                return $otherUser;
            })
            ->sortByDesc(fn($u) => $u->last_message?->created_at);

        $users = User::where('id', '!=', $user->id)->where('status', 'active')->get();

        return view('messages.index', compact('conversations', 'users'));
    }

    public function show(Request $request, User $user)
    {
        $authUser = $request->user();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $authUser->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $authUser->id);
        })->with('sender', 'receiver')->orderBy('created_at', 'asc')->get();

        $allUsers = User::where('id', '!=', $authUser->id)->where('status', 'active')->get();

        return view('messages.show', compact('user', 'messages', 'allUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:2000',
            'calamity_id' => 'nullable|exists:calamities,id',
        ]);

        $validated['sender_id'] = $request->user()->id;

        Message::create($validated);

        return redirect()->route('messages.show', $validated['receiver_id'])
            ->with('success', 'Message sent.');
    }
}
