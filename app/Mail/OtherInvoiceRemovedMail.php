<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtherInvoiceRemovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $messageText;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public function __construct($invoice, $messageText)
    {
        $this->invoice = $invoice;
        $this->messageText = $messageText;
    }
    public function build()
    {
        return $this->subject('Notification: Your Invoice Has Been Removed')
            ->view('email.other_invoice_removed')
            ->with([
                'invoiceNo' => $this->invoice->invoice_no,
                'tenantName' => $this->invoice->tenant->user->name ?? 'Tenant',
                'messageText' => $this->messageText,
            ]);
    }
}
