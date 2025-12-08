<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('salesOrders')
            ->withSum('salesOrders as total_spent', 'total')
            ->latest()
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        Customer::create($request->only(
            'name', 'email', 'phone', 'address'
        ));

        return redirect()->route('customers.index')
            ->with('success', 'Müşteri başarıyla eklendi!');
    }

    public function show($id)
    {
        $customer = Customer::with([
            'salesOrders.items.product',
            'notes'
        ])->findOrFail($id);

        $totalSpent = $customer->salesOrders()->sum('total') ?? 0;

        $lastOrder = $customer->salesOrders->sortByDesc('created_at')->first();

        return view('customers.show', compact(
            'customer',
            'totalSpent',
            'lastOrder'
        ));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($request->only(
            'name', 'email', 'phone', 'address'
        ));

        return redirect()->route('customers.index')
            ->with('success', 'Güncelleme başarılı!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->salesOrders()->exists()) {
            return back()->with('error', 'Bu müşterinin siparişleri olduğu için silinemez!');
        }

        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Müşteri silindi!');
    }


    /* =======================
       📌 NOT CRUD İŞLEMLERİ
       ======================= */

    // 🟢 Not Ekle
    public function addNote(Request $request, Customer $customer)
    {
        $request->validate([
            'note' => 'required|string|max:500'
        ]);

        CustomerNote::create([
            'customer_id' => $customer->id,
            'note' => $request->note,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', 'Not eklendi!');
    }

    // 🟡 Not Güncelle
    public function updateNote(Request $request, CustomerNote $note)
    {
        $request->validate([
            'note' => 'required|string|max:500'
        ]);

        $note->update([
            'note' => $request->note
        ]);

        return back()->with('success', 'Not güncellendi!');
    }

    // 🔴 Not Sil
    public function deleteNote(CustomerNote $note)
    {
        $note->delete();
        return back()->with('success', 'Not silindi!');
    }
}
