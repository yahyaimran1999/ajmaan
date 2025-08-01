<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice_number }}</title>
    <style>
        /* @page {
            margin: 0;
            size: A4;
        }
         */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Arial', sans-serif; 
            color: #2c3e50; 
            line-height: 1.2;
            font-size: 10px;
            background: white;
            height: 297mm;
            width: 210mm;
            margin: 0 auto;
            position: relative;
        }
        
        .invoice-container {
            padding: 10mm;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .header { 
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .logo-section {
            background: white;
            padding: 8px 15px;
            border-radius: 4px;
            margin-right: 15px;
        }
        
        .logo-section .logo-text {
            color: #1e3a8a;
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 1px;
        }
        
        .header-content h1 {
            font-size: 24px;
            font-weight: 300;
            margin-bottom: 3px;
            letter-spacing: 1px;
        }
        
        .header-content .company-name {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 500;
        }
        
        .header-right {
            text-align: right;
        }
        
        .invoice-number {
            background: rgba(255,255,255,0.2);
            padding: 6px 12px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 10px;
            opacity: 0.8;
        }
        
        .content-section {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            position: relative;
        }
        
        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: #1e3a8a;
            border-radius: 3px 0 0 3px;
        }
        
        .info-card h3 {
            color: #1e3a8a;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-card p {
            margin-bottom: 4px;
            font-size: 9px;
            color: #475569;
        }
        
        .info-card strong {
            color: #1e293b;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-completed, .status-paid { 
            background: #dcfce7;
            color: #166534;
        }
        
        .status-pending { 
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-failed { 
            background: #fee2e2;
            color: #991b1b;
        }
        
        .items-section {
            margin-bottom: 12px;
            flex: 1;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
            background: white;
        }
        
        .items-table th {
            background: #1e3a8a;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9px;
            color: #475569;
        }
        
        .items-table tbody tr:hover {
            background: #f8fafc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .amount {
            font-weight: 600;
            color: #1e293b;
        }
        
        .totals-section {
            margin-top: auto;
            padding-top: 12px;
        }
        
        .totals-container {
            display: flex;
            justify-content: flex-end;
        }
        
        .totals-table {
            width: 280px;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
            background: white;
        }
        
        .totals-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 9px;
        }
        
        .totals-table .subtotal-row td {
            color: #475569;
        }
        
        .totals-table .discount-row td {
            color: #059669;
        }
        
        .totals-table .tax-row td {
            color: #7c3aed;
        }
        
        .totals-table .total-row {
            background: #1e3a8a;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        
        .totals-table .total-row td {
            border-bottom: none;
            padding: 10px;
        }
        
        .footer-section {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
        }
        
        .payment-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 12px;
            text-align: center;
        }
        
        .payment-info .payment-method {
            font-weight: 600;
            color: #0369a1;
            font-size: 10px;
        }
        
        .company-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            font-size: 8px;
            color: #64748b;
        }
        
        .company-footer .left-info h4 {
            color: #1e293b;
            font-size: 10px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .company-footer .right-info {
            text-align: right;
        }
        
        .company-footer .right-info h4 {
            color: #1e293b;
            font-size: 10px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .thank-you {
            text-align: center;
            margin: 8px 0;
            padding: 8px;
            background: #f8fafc;
            border-radius: 4px;
            color: #475569;
            font-size: 9px;
        }
        
        .thank-you strong {
            color: #1e3a8a;
            font-size: 10px;
        }
        
        .watermark {
            position: absolute;
            bottom: 3mm;
            right: 10mm;
            color: #cbd5e1;
            font-size: 7px;
            font-style: italic;
        }
        
        /* Print specific styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="header-left">
                <div class="logo-section">
                    <div class="logo-text">AJMAAN</div>
                </div>
                <div class="header-content">
                    <h1>INVOICE</h1>
                    <div class="company-name">{{ $company_info['name'] }}</div>
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-number">{{ $invoice_number }}</div>
                <div class="invoice-date">{{ $invoice_date }}</div>
            </div>
        </div>

        <div class="content-section">
            <div class="info-grid">
                <div class="info-card">
                    <h3>Bill To</h3>
                    <p><strong>{{ $customer_info['name'] }}</strong></p>
                    <p><strong>Email:</strong> {{ $customer_info['email'] }}</p>
                    <p><strong>Phone:</strong> {{ $customer_info['phone'] }}</p>
                </div>
                
                <div class="info-card">
                    <h3>Invoice Details</h3>
                    <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
                    <p><strong>Date:</strong> {{ $invoice_date }}</p>
                    <p><strong>Status:</strong> 
                        <span class="status-badge status-{{ strtolower($payment_status) }}">
                            {{ $payment_status }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="items-section">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Description</th>
                            <th class="text-center" style="width: 15%;">Qty</th>
                            {{-- <th class="text-right" style="width: 17.5%;">Unit Price</th> --}}
                            <th class="text-right" style="width: 17.5%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($line_items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item['description'] }}</strong>
                                <br><small style="color: #64748b;">Package Subscription Service</small>
                            </td>
                            <td class="text-center">{{ $item['quantity']?? '1' }}</td>
                            {{-- <td class="text-right amount">{{ $order->mw_currency->symbol ?? 'AED' }} {{ number_format($item['unit_price'], 2) }}</td> --}}
                            <td class="text-right amount">{{ $order->mw_currency->symbol ?? 'AED' }} {{ number_format($item['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="totals-section">
                <div class="totals-container">
                    <table class="totals-table">
                        <tr class="subtotal-row">
                            <td>Subtotal:</td>
                            <td class="text-right amount">{{ $order->mw_currency->symbol ?? 'AED' }} {{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @if($discount_amount > 0)
                        <tr class="discount-row">
                            <td>Discount:</td>
                            <td class="text-right amount">-{{ $order->mw_currency->symbol ?? 'AED' }} {{ number_format($discount_amount, 2) }}</td>
                        </tr>
                        @endif
                        @if($tax_amount > 0)
                        <tr class="tax-row">
                            <td>Tax (VAT):</td>
                            <td class="text-right amount">{{ $order->mw_currency->symbol ?? 'AED' }} {{ number_format($tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="total-row">
                            <td><strong>TOTAL AMOUNT</strong></td>
                            <td class="text-right"><strong>{{ $order->mw_currency->symbol ?? 'AED' }} {{ number_format($total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="footer-section">
            <div class="payment-info">
                <div class="payment-method">Payment Method: {{ $payment_method }}</div>
            </div>

            <div class="thank-you">
                <strong>Thank you for your business!</strong><br>
                Your subscription is now active and ready to use.
            </div>

            <div class="company-footer">
                <div class="left-info">
                    <h4>{{ $company_info['name'] }}</h4>
                    <p>{{ $company_info['address'] }}</p>
                    <p>{{ $company_info['city'] }}</p>
                </div>
                <div class="right-info">
                    <h4>Contact Information</h4>
                    <p>{{ $company_info['phone'] }}</p>
                    <p> {{ $company_info['email'] }}</p>
                    <p> {{ $company_info['website'] }}</p>
                </div>
            </div>
        </div>

        <div class="watermark">
            Computer Generated Invoice - No Signature Required
        </div>
    </div>
</body>
</html>