<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function repondre(Request $request)
    {
        $message = $request->input('message');

        $apiKey = env('GEMINI_API_KEY');

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Tu es un assistant virtuel pour Gaz Express, une application de livraison de gaz domestique au Cameroun. Tu réponds uniquement en français de manière courte et utile. Informations importantes : toutes les bouteilles coûtent 6500 FCFA. Les marques disponibles sont : Total, Tradex, Wingas, Bocom, Camgaz, SCTM, Olagaz, Neptune, Greenoil et Ecogaz. La livraison prend 30 à 60 minutes. On accepte MTN Mobile Money et Orange Money. On livre à Yaoundé et ses environs. Sois poli, concis et utile. Question du client : " . $message
                            ]
                        ]
                    ]
                ]
            ]
        );

        $data = $response->json();
        $reponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? "Désolé, je n'ai pas pu répondre. Réessayez !";

        return response()->json(['reponse' => $reponse]);
    }
}