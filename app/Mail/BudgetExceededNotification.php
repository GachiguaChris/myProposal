<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BudgetExceededNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $proposal;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

   public function build()
{
    $remainingBalance = $this->proposal->category->budget - $this->proposal->category->approvedBudget;

    return $this->subject('Project Budget Constraint Notification')
        ->view('emails.budget_exceeded')
        ->with([
            'proposalTitle' => $this->proposal->title,
            'categoryName' => optional($this->proposal->category)->name ?? 'N/A',

            'budgetRequested' => $this->proposal->budget,
            'totalBudget' => $this->proposal->category->budget,
            'remainingBalance' => $remainingBalance,
        ]);
}
}
