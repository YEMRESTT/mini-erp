<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function createFromOrder($orderId)
    {
        $order = SalesOrder::with('items.product')->findOrFail($orderId);

        if ($order->invoice) {
            return back()->with('error', 'Bu sipariş için zaten fatura oluşturulmuş.');
        }

        // ✔ Siparişin ara toplamı
        $subtotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $vat = round($subtotal * 0.20, 2);
        $total = $subtotal + $vat;

        // ✔ Fatura oluştur
        $invoice = Invoice::create([
            'sales_order_id' => $order->id,
            'invoice_number' => 'INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'subtotal'       => $subtotal,
            'vat'            => $vat,
            'total'          => $total,
            'due_date'       => now()->addDays(7),
            'status'         => 'Pending',
        ]);

        // ✔ Fatura kalemlerini oluştur
        foreach ($order->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,       // 🟢 EN KRİTİK SATIR
            ]);
        }

        // ✔ Sipariş durumunu güncelle
        $order->update(['status' => 'Invoiced']);

        return redirect()
            ->route('invoices.show', $invoice->id)
            ->with('success', 'Fatura oluşturuldu!');
    }




    public function show(Invoice $invoice)
    {
        $invoice->load('items.product', 'order.customer');

        // 🔹 Fatura satırlarından ara toplamı hesapla
        $subtotal = $invoice->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $vatRate   = 0.20; // %20
        $vatAmount = round($subtotal * $vatRate, 2);
        $grandTotal = $subtotal + $vatAmount;

        return view('invoices.show', compact(
            'invoice',
            'subtotal',
            'vatAmount',
            'grandTotal'
        ));
    }





    public function pdf(Invoice $invoice)
    {
        $invoice->load(['items.product', 'order.customer']);

        // Hesaplar (KDV YOK)
        $subtotal = $invoice->items->sum(fn($i) => $i->price * $i->quantity);
        $total    = $subtotal;

        $pdf = Pdf::loadView('invoices.pdf', compact(
            'invoice',
            'subtotal',
            'total'
        ))->setPaper('a4');

        return $pdf->download(
            'Fatura_'.$invoice->id.'.pdf'
        );
    }



    public function viewPdf(Invoice $invoice)
    {
        $invoice->load(['items.product', 'order.customer']);

        // KDV yok
        $subtotal = $invoice->items->sum(fn($i) => $i->price * $i->quantity);
        $total    = $subtotal;

        $pdf = Pdf::loadView('invoices.pdf', compact(
            'invoice',
            'subtotal',
            'total'
        ))->setPaper('a4');

        // 🔥 TARAYICIDA GÖSTER
        return $pdf->stream('Fatura_'.$invoice->id.'.pdf');
    }




}
