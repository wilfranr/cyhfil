<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Services\ChatService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(Request $request)
    {
        Log::info('Usuario intentando enviar mensaje', ['user_id' => $request->user()->id]);

        // Agrega un log al inicio para verificar si la solicitud llega al controlador
        Log::info('Solicitud recibida en sendMessage.', ['request' => $request->all()]);

    try {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        Log::info('Validación exitosa', ['validated' => $validated]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Error de validación', ['errors' => $e->errors()]);
        return response()->json(['error' => $e->errors()], 422);
    }

        // Si el mensaje es recibido correctamente, lo registramos en los logs
        Log::info('Mensaje recibido: ', ['message' => $request->message]);

        $userId = $request->user()->id;
        $message = $this->chatService->sendMessage($userId, $request->message);

        // Disparar el evento MessageSent
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['message' => 'Mensaje enviado', 'data' => $message]);
    }


    public function logEvent($sender, $message)
    {
        $this->chatService->logEvent($sender, $message);
    }

    public function fetchMessages()
    {
        $messages = ChatMessage::latest()->limit(50)->get();
        return response()->json($messages);
    }
}
