<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    private $feedback;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $feedback = $this->feedback;
        $user = Auth::user();

        $this->subject('Новый Feedback #'.$feedback->id)
            ->from('info@vrisc.ru', 'VRISC' )
            ->view('feedbackmail', compact('feedback', 'user'));
    }
}
