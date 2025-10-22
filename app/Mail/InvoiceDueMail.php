<?php

namespace App\Mail;

use App\Models\TenantContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceDueMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contract;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TenantContract $contract)
    {
        $this->contract = $contract;
    }

    public function build()
    {
        return $this->subject('Invoice Due Reminder')
            ->markdown('emails.invoice_due');
    }
}
