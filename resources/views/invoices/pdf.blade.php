<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fatura #{{ $invoice->id }}</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2>FATURA #{{ $invoice->id }}</h2>

<p>
    <strong>Müşteri:</strong> {{ $invoice->order->customer->name }} <br>
    <strong>Tarih:</strong> {{ $invoice->created_at->format('d.m.Y') }}
</p>

<table>
    <thead>
    <tr>
        <th>Ürün</th>
        <th>Adet</th>
        <th>Birim Fiyat</th>
        <th>Toplam</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₺{{ number_format($item->price,2,',','.') }}</td>
            <td>
                ₺{{ number_format($item->price * $item->quantity,2,',','.') }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<p style="margin-top:15px">
    <strong>Ara Toplam:</strong> ₺{{ number_format($subtotal,2,',','.') }} <br>
    <strong>Genel Toplam:</strong> ₺{{ number_format($total,2,',','.') }}
</p>

</body>
</html>
