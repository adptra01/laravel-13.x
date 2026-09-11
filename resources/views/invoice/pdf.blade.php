<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #171717; font-size: 14px; margin: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #059669; padding-bottom: 20px; margin-bottom: 24px; }
        .company { font-size: 20px; font-weight: bold; color: #059669; }
        .invoice-title { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .muted { color: #737373; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { text-align: left; background: #ecfdf5; padding: 8px 12px; font-size: 12px; text-transform: uppercase; color: #047857; }
        td { padding: 8px 12px; border-bottom: 1px solid #e5e5e5; }
        .right { text-align: right; }
        .total-row td { font-weight: bold; border-top: 2px solid #171717; }
        .summary { margin-top: 24px; }
        .footer { margin-top: 40px; font-size: 12px; color: #737373; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company">{{ $invoice->order?->merchant?->company_name ?? config('app.name') }}</div>
            <div class="muted">{{ $invoice->order?->merchant?->address }}</div>
            <div class="muted">{{ $invoice->order?->merchant?->phone }}</div>
        </div>
        <div style="text-align: right;">
            <div class="invoice-title">INVOICE</div>
            <div class="muted">No: {{ $invoice->invoice_number }}</div>
            <div class="muted">Terbit: {{ $invoice->issued_at->format('d M Y') }}</div>
            <div class="muted">Jatuh tempo: {{ $invoice->due_date->format('d M Y') }}</div>
        </div>
    </div>

    <div>
        <strong>Ditagihkan ke:</strong><br>
        {{ $invoice->order?->customer?->company_name }}<br>
        {{ $invoice->order?->customer?->user?->email }}<br>
        {{ $invoice->order?->customer?->address }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Harga</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->order?->items ?? [] as $item)
                <tr>
                    <td>{{ $item->menu?->name ?? '-' }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="right">Subtotal</td>
                <td class="right">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="right">PPN (11%)</td>
                <td class="right">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="right">Total</td>
                <td class="right">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Terima kasih telah menggunakan Marketplace Katering.<br>
        Invoice ini dibuat otomatis oleh sistem.
    </div>
</body>
</html>