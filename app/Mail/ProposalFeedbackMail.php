<?php
// app/Mail/ProposalFeedbackMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProposalFeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $proposal;
    public $feedback;

    public function __construct($proposal, $feedback)
    {
        $this->proposal = $proposal;
        $this->feedback = $feedback;
    }

    public function build()
    {
        return $this->view('emails.proposal_feedback');
    }
}