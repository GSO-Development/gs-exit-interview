<?php

namespace App\Mail;

use App\Models\ExitSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExitSurveyInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ExitSurvey $survey) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Exit Interview — '.$this->survey->company->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.exit-survey-invitation',
        );
    }
}
