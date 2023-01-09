<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageIsSeen;
use App\Events\MessageSeen;

class MessageController extends Controller
{
    //
    public function setMessageSeen(Request $request) {
        $validated = $request->validate([
            'user_id' => 'required',
            'message_id' => 'required'
        ]);
        $message = MessageIsSeen::create([
            'user_id' => $validated['user_id'],
            'message_id' => $validated['message_id']
        ]);
        broadcast(new MessageSeen($message));
        //}
    }
}
