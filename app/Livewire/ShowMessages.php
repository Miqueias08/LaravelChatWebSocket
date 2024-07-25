<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Auth;
use App\Events\MessageSent;
use App\Events\NewMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

class ShowMessages extends Component
{
    public $user;
    public $messageText='';
    public $messages = [];

    // protected $listeners = ['echo:message,MessageSent' => 'busca_mensagens'];
    protected $listeners = ["busca_mensagens","sendMessage"];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->busca_mensagens();
    }

    public function busca_mensagens(){
        $userId = $this->user->id;
        $this->messages = Message::select(
            "messages.*",
            "users.name as sender",
            "recipient.name as recipient"
        )
        ->join("users","users.id","=","messages.user_id")
        ->join("users as recipient","recipient.id","=","messages.recipient_id")
        ->where(function ($query) use ($userId) {
            $query->where('user_id', Auth::id())
                  ->where('recipient_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('recipient_id', Auth::id());
        })
        ->orderBy("created_at","asc")
        ->get();
    }

    public function sendMessage()
    {
        try {
            $mensagem = Message::create([
                'user_id' => Auth::id(),
                'recipient_id' => $this->user->id,
                'message' => $this->messageText,
            ]);
    
            $this->reset('messageText');

            // Event::dispatch(new MessageSent());
            $updatedData = ['id' => $this->user->id, 'content' => 'New content'];
            Event::dispatch(new NewMessage($updatedData));
            
            $this->busca_mensagens();
        } catch (\Throwable $th) {
            dd($th);
            Log::info('ERRO CRIAR MENSAGEM',$th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.show-messages');
    }
}
