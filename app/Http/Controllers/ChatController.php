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

        $user = $request->user();

        // Disparar el evento MessageSent
        broadcast(new MessageSent($message, $user->roles->first()->name))->toOthers();

        return response()->json(['message' => 'Mensaje enviado', 'data' => $message]);
    }


    public function logEvent($sender, $message)
    {
        $this->chatService->logEvent($sender, $message);
    }

    public function fetchMessages()
    {
        // Asegúrate de incluir el nombre del rol del usuario en cada mensaje
        $messages = ChatMessage::with('user')->oldest()->limit(50)->get()->map(function ($message) {
            return [
                'message' => $message->message,
                'created_at' => $message->created_at->format('d M, H:i'),
                'sender' => $message->user->name,
                'role' => $message->user->getRoleNames()->first() // Obtener el primer rol del usuario
            ];
        });

        return response()->json($messages);
    }
}
