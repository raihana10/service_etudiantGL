<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reclamation;

class ReclamationResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamation;

    /**
     * Create a new message instance.
     */
    public function __construct(Reclamation $reclamation)
    {
        $this->reclamation = $reclamation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@uae.ac.ma', 'Services Étudiants - UAE'),
            subject: 'Réponse à votre réclamation - ' . $this->reclamation->idReclamation,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reclamation-response',
            with: [
                'reclamation' => $this->reclamation,
                'etudiant' => $this->reclamation->etudiant,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
