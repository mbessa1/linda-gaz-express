<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelleLivraisonMail;
use Twilio\Exceptions\RestException;

class NotificationService
{
    protected $twilio;
    protected $twilioFrom;          // Numéro SMS Twilio
    protected $twilioWhatsappFrom;  // Numéro WhatsApp Twilio

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->twilioFrom = config('services.twilio.from');
        $this->twilioWhatsappFrom = config('services.twilio.whatsapp_from');
    }

    /**
     * Envoyer un SMS ou WhatsApp
     */
    public function sendSms($to, $message, $viaWhatsapp = false)
    {
        if (empty($to)) return;

        try {
            if ($viaWhatsapp) {
                // Préfixer pour WhatsApp
                $toFormatted = 'whatsapp:' . $this->formatPhone($to);
                $from = $this->twilioWhatsappFrom;
            } else {
                $toFormatted = $this->formatPhone($to);
                $from = $this->twilioFrom;
            }

            // Éviter d’envoyer au même numéro que l’expéditeur
            if ($toFormatted === $from) return;

            $this->twilio->messages->create($toFormatted, [
                'from' => $from,
                'body' => $message,
            ]);

            \Log::info("Message envoyé à {$toFormatted} via " . ($viaWhatsapp ? "WhatsApp" : "SMS"));
        } catch (RestException $e) {
            \Log::error("Erreur Twilio lors de l'envoi à {$to}: " . $e->getMessage());
        }
    }

    /**
     * Envoyer un email
     */
    public function sendEmail($to, $commande)
    {
        if (!empty($to)) {
            Mail::to($to)->send(new NouvelleLivraisonMail($commande));
            \Log::info("Email envoyé à {$to}");
        }
    }

    /**
     * Notifier un livreur (email + SMS + WhatsApp)
     */
    public function notifyLivreur($livreur, $commande)
    {
        $message = "Bonjour {$livreur->name}, une nouvelle livraison (#{$commande->id}) vous a été affectée. Adresse: {$commande->adresse_livraison}";

        // SMS
        $this->sendSms($livreur->phone, $message);

        // WhatsApp
        $this->sendSms($livreur->phone, $message, true);

        // Email
        $this->sendEmail($livreur->email, $commande);
    }

    /**
     * Formater un numéro de téléphone en E.164
     */
    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Exemple pour le Cameroun (+237)
        if (substr($phone, 0, 1) === '0') {
            $phone = '+237' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
        }

        return $phone;
    }
}
