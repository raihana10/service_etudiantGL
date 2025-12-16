<?php

namespace App\Mail;

use App\Models\Demande;
use App\Models\Etudiant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $etudiant;

    /**
     * Create a new message instance.
     */
    public function __construct(Demande $demande, Etudiant $etudiant)
    {
        $this->demande = $demande;
        $this->etudiant = $etudiant;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre demande de document - N° ' . $this->demande->num_demande,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.demande-confirmation',
            with: [
                'numDemande' => $this->demande->num_demande,
                'typeDoc' => $this->demande->typeDoc,
                'dateSoumission' => $this->demande->datesoumission->format('d/m/Y H:i'),
                'etudiant' => $this->etudiant,
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
