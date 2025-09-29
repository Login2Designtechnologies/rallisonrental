<?php

namespace App\Mail;

use App\Models\OtherInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtherInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OtherInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        return $this->subject('Invoice #' . $this->invoice->invoice_no)
                    ->view('email.other_invoice')
                    ->with([
                        'otherInvoice' => $this->invoice,
                    ]);
    }
    
}
