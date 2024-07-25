<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat com ') . $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col h-96">
                    <!-- Message List -->
                    <div class="overflow-auto flex-grow">
                        @foreach ($messages as $message)
                            <div class="flex message {{ $message->user_id == Auth::id() ? 'justify-start' : 'justify-start' }}">
                                <div class="bg-gray-200 dark:bg-gray-700 p-3 rounded-lg my-2 {{ $message->user_id == Auth::id() ? 'you-message' : 'recipient-message' }}">
                                    {{ $message->message }}
                                    <br><span class="identifier">{{ $message->user_id == Auth::id() ? 'Você' : $message->sender }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Form -->
                    <form wire:submit.prevent="sendMessage" class="flex send-form">
                        <input type="hidden" wire:model="user.id" value="{{ $user->id }}">
                        <input type="text" wire:model="messageText" class="flex-grow border border-gray-300 dark:border-gray-600 rounded-lg p-2 mr-2" placeholder="Escreva sua mensagem..." required>
                        <button type="submit" class="bg-blue-500 text-white rounded-lg px-4 py-2">
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
    .send-form {
        padding-top: 10px;
    }
    .message {
        padding: 5px;
    }
    .you-message {
        background-color: #228B22;
        color: white;
        padding: 5px;
    }
    .recipient-message {
        padding: 5px;
        background-color: #0000CD;
        color: white;
    }
    .identifier {
        font-size: 10px;
    }
    </style>
</div>
