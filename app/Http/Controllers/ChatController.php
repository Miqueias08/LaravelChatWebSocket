<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Event;

class ChatController extends Controller
{
    public function show(User $user){
        return view('chat.show', compact('user'));
    }
}
