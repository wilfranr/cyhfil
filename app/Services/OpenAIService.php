<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    public function analizarImagen(string $imagePath): string
    {
        $base64Image = base64_encode(file_get_contents($imagePath));

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-vision-preview',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un experto en identificar repuestos de maquinaria pesada.'],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:image/jpeg;base64,' . $base64Image,
                                ],
                            ],
                            [
                                'type' => 'text',
                                'text' => '¿Qué repuesto es este? Describe modelo, uso y características si es posible.',
                            ],
                        ],
                    ],
                ],
                'max_tokens' => 500,
            ]);

        return $response->json('choices.0.message.content') ?? 'No se pudo obtener una respuesta.';
    }

public function analizarImagenDesdeContenido(string $contenido): string
{
    $base64Image = base64_encode($contenido);

    $response = Http::withToken(env('OPENAI_API_KEY'))
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un experto en repuestos de maquinaria pesada.'],
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:image/jpeg;base64,' . $base64Image,
                            ],
                        ],
                        [
                            'type' => 'text',
                            'text' => '¿Qué repuesto es este? Describe el tipo, marca y posible uso si es posible.',
                        ],
                    ],
                ],
            ],
            'max_tokens' => 500,
        ]);

dd($response->json());
    return $response->json('choices.0.message.content') ?? 'No se pudo obtener una respuesta de la IA.';

}
}
