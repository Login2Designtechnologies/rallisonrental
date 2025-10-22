<?php

namespace App\Console\Commands;

use App\Mail\InvoiceDueMail;
use App\Models\TenantContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendInvoiceDueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:send-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice due date reminder emails to tenants';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->day;

        $contracts = TenantContract::with('tenant')
            ->where('invoice_due_date', $today)
            ->get();

        info('Contracts', [$contracts]);

        if ($contracts->isEmpty()) {
            $this->info('No invoices due today.');
            return 0;
        }

        foreach ($contracts as $contract) {
            if (!empty($contract->tenant->user->email)) {
                Mail::to($contract->tenant->user->email)->send(new InvoiceDueMail($contract));
                $this->info("Reminder sent to: {$contract->tenant->email}");
            }
        }

        $this->info('Invoice due reminders sent successfully.');
        return 0;
    }
}
