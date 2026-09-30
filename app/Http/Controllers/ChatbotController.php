<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class ChatbotController extends Controller
{
    public function repondre(Request $request)
    {
        $message = $request->input('message');
        $msg = strtolower($message);
        if (str_contains($msg, 'command') || str_contains($msg, 'acheter'))
            $reponse = "Pour commander, allez dans le Catalogue et cliquez sur Commander !";
        elseif (str_contains($msg, 'prix') || str_contains($msg, 'combien'))
            $reponse = "Toutes nos bouteilles sont a 6 500 FCFA !";
        elseif (str_contains($msg, 'livraison') || str_contains($msg, 'delai'))
            $reponse = "La livraison prend 30 a 60 minutes !";
        elseif (str_contains($msg, 'mtn') || str_contains($msg, 'orange') || str_contains($msg, 'paiement'))
            $reponse = "Nous acceptons MTN Mobile Money et Orange Money !";
        elseif (str_contains($msg, 'contact') || str_contains($msg, 'joindre'))
            $reponse = "Contactez-nous via la page A propos. Disponibles 7j/7 !";
        elseif (str_contains($msg, 'tuyau'))
            $reponse = "Nous proposons des Tuyaux Flexibles et Armes. Disponibles dans le catalogue !";
        elseif (str_contains($msg, 'tendeur') || str_contains($msg, 'detendeur'))
            $reponse = "Nous avons le Detendeur Standard et Haute Pression !";
        elseif (str_contains($msg, 'bonjour') || str_contains($msg, 'salut') || str_contains($msg, 'hello'))
            $reponse = "Bonjour ! Bienvenue sur Gaz Express. Comment puis-je vous aider ?";
        elseif (str_contains($msg, 'merci'))
            $reponse = "Avec plaisir ! N hesitez pas si vous avez d autres questions !";
        elseif (str_contains($msg, 'aide') || str_contains($msg, 'help'))
            $reponse = "Je peux vous aider sur : Commander, Prix, Livraison, Paiement, Tuyaux, Contact !";
        else
            $reponse = "Essayez : Commander, Prix, Livraison, Tuyaux ou Contact !";
        return response()->json(['reponse' => $reponse]);
    }
}
