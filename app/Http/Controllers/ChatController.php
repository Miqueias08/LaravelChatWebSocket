<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Auth;

class ChatController extends Controller
{
    public function show(User $user){
        $userId = $user->id;
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('user_id', Auth::id())
                  ->where('recipient_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('recipient_id', Auth::id());
        })
        ->orderBy("created_at","asc")
        ->get();
        return view('chat.show', compact('user','messages'));
    }
    //CRIA A MENSAGEM
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'recipient_id' => 'required|exists:users,id',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
        ]);

        return redirect()->back();
    }
}
