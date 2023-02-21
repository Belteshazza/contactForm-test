<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $submission;

    public function __construct($submission)
    {
        $this->submission = $submission;
    }

    public function build()
    {
        return $this->view('emails.contactSubmissionNotification')
                    ->with([
                        'submission' => $this->submission,
                    ]);
    }
}