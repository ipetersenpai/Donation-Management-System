<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DonationReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;

    public function __construct($donation)
    {
        $this->donation = $donation;
    }

    public function build()
    {
        return $this->view('smtp_templates.receipt')
                    ->subject('Donation Receipt')
                    ->with([
                        'amount' => $this->donation->amount,
                        'date_created' => $this->donation->created_at->format('Y-m-d H:i:s'),
                        'reference_no' => $this->donation->reference_no,
                        'payment_option' => $this->donation->payment_option,
                    ]);
    }
}
