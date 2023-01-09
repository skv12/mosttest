<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MessageIsSeen;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Events\MessageSent;
use App\Events\ChatCreated;
class ChatController extends Controller
{
    
    public function index(){
        $chats = Chat::whereHas("users", function($query){
                return $query->where("user_id", auth()->id());
            })->get();
        foreach($chats as $chat){
                $chat->users;
        }   
        return $chats;
    }

    public function addChat(Request $request){
        $validated = $request->validate([
            'chats.name' => 'required|string|unique:chats',
            'users.*' => 'required'
        ]);
        $chat = Chat::create($validated['chats']);
        foreach($validated['users'] as $user){
            ChatUser::create([
                'chat_id' => $chat->id,
                'user_id' => $user
            ]);
        }
        ChatUser::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id()
        ]);
        $chat->users;
        broadcast(new ChatCreated($chat, $request->user()));
    }

    public function chat($id)
    {
        $chat = Chat::find($id);
        $chat->users;
        $messages = $chat->messages;
        foreach($messages as $message){
            $message['username'] = $message->user['name'];
            $message['is_seen'] = $message->isSeen;
        }
        foreach($chat->users as $user){
            $user['is_online'] = cache()->has('is_online' . $user->id);
        }
        return response()->json([
            'chat' => $chat, 'messages' => $messages]);
    }

    public function message($id, Request $request){
            $message = $request->validate([
                'message' => 'required|string'
            ]);
            $message['chat_id'] = $id;
            $message['user_id'] = auth()->id();
            $createdMessage = Message::create($message);
            $createdMessage['userMame'] = $createdMessage->user;
            $createdMessage['is_seen'] = $createdMessage->isSeen;
            error_log($createdMessage);
            broadcast(new MessageSent(Chat::find($id), $createdMessage, $request->user()));
    }
}
