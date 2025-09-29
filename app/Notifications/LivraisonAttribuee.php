<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LivraisonAttribuee extends Notification
{
    use Queueable;

    protected $commande;

    public function __construct($commande)
    {
        $this->commande = $commande;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nouvelle livraison assignée')
                    ->greeting("Bonjour {$notifiable->name},")
                    ->line("Une nouvelle livraison vous a été assignée.")
                    ->line("Adresse de livraison : {$this->commande->adresse_livraison}")
                    ->line("Produit : {$this->commande->produit->marque} ({$this->commande->quantite})")
                    ->action('Voir les détails', url('/livreur/commandes'));
    }
}
