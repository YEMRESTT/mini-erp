<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;




class SupplierController extends Controller
{
    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders.items']);

        $totalOrders = $supplier->purchaseOrders->count();

        $completedOrders = $supplier->purchaseOrders
            ->where('status', 'Completed');

        $totalSpent = $completedOrders->sum(function ($order) {
            return $order->items->sum(fn ($i) => $i->quantity * $i->price);
        });

        return view('suppliers.show', compact(
            'supplier',
            'totalOrders',
            'totalSpent'
        ));
    }

}
