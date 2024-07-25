<x-app-layout>
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
                            <div class="flex {{ $message->user_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="bg-gray-200 dark:bg-gray-700 p-3 rounded-lg my-2">
                                    {{ $message->message }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Form -->
                    <form action="{{ route('chat.send') }}" method="POST" class="flex">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $user->id }}">
                        <input type="text" name="message" class="flex-grow border border-gray-300 dark:border-gray-600 rounded-lg p-2 mr-2" placeholder="Escreva sua mensagem..." required>
                        <button type="submit" class="bg-blue-500 text-white rounded-lg px-4 py-2">
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
