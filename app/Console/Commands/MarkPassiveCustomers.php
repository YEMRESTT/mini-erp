<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;

class MarkPassiveCustomers extends Command
{
    protected $signature = 'customers:mark-passive';
    protected $description = 'Son 180 gündür sipariş vermeyen müşterileri inactive yapar';

    public function handle()
    {
        $limitDate = now()->subDays(180);

        $customers = Customer::where('status', 'active')
            ->whereDoesntHave('salesOrders', function ($q) use ($limitDate) {
                $q->where('created_at', '>=', $limitDate);
            })
            ->get();

        foreach ($customers as $customer) {
            $customer->update([
                'status' => 'inactive'
            ]);
        }

        $this->info($customers->count().' müşteri pasif (inactive) yapıldı.');
    }
}

