<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class BalkonVitrinYarismaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope()
    {
        return new Envelope(subject: $this->data['subject']);
    }

    public function content()
    {
        return new Content(view: 'user.mailSablon.yarismaBasvuruSablon');
    }

    public function attachments()
    {
        $attachments = [];
        $data = $this->data;

        // Fotoğrafları ekle
        if (!empty($data['fotograflar']) && is_array($data['fotograflar'])) {
            foreach ($data['fotograflar'] as $fotoYolu) {
                // $fotoYolu zaten "images/yarisma/suslemeler/dosya.jpg" şeklinde geliyor
                $fullPath = public_path($fotoYolu);
                if (file_exists($fullPath)) {
                    $attachments[] = Attachment::fromPath($fullPath);
                }
            }
        }

        // Veli İzin Belgesini ekle
        if (!empty($data['veli_belgesi'])) {
            $fullPath = public_path($data['veli_belgesi']);
            if (file_exists($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath);
            }
        }

        return $attachments;
    }
}
