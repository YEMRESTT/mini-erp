<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Notification;

class CheckOverdueInvoices extends Command
{
    protected $signature = 'invoices:check-overdue';
    protected $description = 'Vadesi geçen faturaları gecikmiş olarak işaretler';

    public function handle()
    {
        $invoices = Invoice::where('status', 'Pending')
            ->whereDate('due_date', '<', now())
            ->get();

        foreach ($invoices as $invoice) {

            // status güncelle
            $invoice->update([
                'status' => 'Overdue'
            ]);

            // notification oluştur
            Notification::create([
                'type'    => 'invoice_overdue',
                'message' => "Fatura #{$invoice->id} vadesi geçti.",
            ]);
        }

        $this->info($invoices->count().' adet fatura gecikmiş olarak işaretlendi.');
    }
}

