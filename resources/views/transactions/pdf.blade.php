<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #334155;
            margin: 0;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
        }
        .header-table td {
            border: none;
            padding: 0 0 20px 0;
        }
        .brand {
            font-size: 26px;
            font-weight: 800;
            color: #4338ca;
            letter-spacing: -0.5px;
        }
        .brand-icon {
            display: inline-block;
            background-color: #4338ca;
            color: #ffffff;
            padding: 2px 10px;
            border-radius: 6px;
            margin-right: 6px;
        }
        .report-title {
            margin: 0;
            font-size: 18px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-meta {
            margin: 5px 0 0;
            color: #64748b;
            font-size: 11px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: left;
            border: none;
        }
        .data-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 1px;
            border-bottom: 2px solid #cbd5e1;
        }
        .data-table td {
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .income {
            color: #10b981;
            font-weight: 600;
        }
        .expense {
            color: #ef4444;
            font-weight: 600;
        }
        .over-budget td {
            background-color: #fef2f2;
            border-bottom: 1px solid #fee2e2;
        }
        .data-table tfoot th {
            background-color: #ffffff;
            color: #0f172a;
            border-top: 2px solid #cbd5e1;
            border-bottom: none;
            padding-top: 15px;
            font-size: 12px;
            text-transform: none;
            letter-spacing: normal;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td style="width: 50%; vertical-align: middle;">
                <div class="brand">
                    <span class="brand-icon">D</span>DompetKu
                </div>
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle;">
                <h2 class="report-title">Laporan Transaksi</h2>
                <p class="report-meta">Tanggal Cetak: {{ date('d F Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kategori</th>
                <th width="25%">Deskripsi</th>
                <th width="15%">Tipe</th>
                <th width="20%" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPemasukan = 0; $totalPengeluaran = 0; @endphp
            @forelse($transactions as $index => $transaction)
                @php
                    if($transaction->type == 'income') $totalPemasukan += $transaction->amount;
                    else $totalPengeluaran += $transaction->amount;
                    
                    $isOverBudget = false;
                    if($transaction->category) {
                        $isOverBudget = in_array($transaction->category_id, $overBudgetCategories ?? []) 
                                        && $transaction->type === 'expense'
                                        && \Carbon\Carbon::parse($transaction->transaction_date)->format('m-Y') === date('m-Y');
                    }
                @endphp
                <tr class="{{ $isOverBudget ? 'over-budget' : '' }}">
                    <td class="text-center" style="color: #94a3b8;">{{ $index + 1 }}</td>
                    <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                    <td>
                        <strong style="color: #334155;">{{ $transaction->category ? $transaction->category->name : '-' }}</strong>
                        @if($isOverBudget)
                            <br><span style="color: #ef4444; font-size: 10px; font-weight: bold; letter-spacing: 0.5px;">[!] OVER BUDGET</span>
                        @endif
                    </td>
                    <td style="color: #64748b;">{{ $transaction->description ?: '-' }}</td>
                    <td>
                        @if($transaction->type == 'income')
                            <span style="background: #ecfdf5; color: #10b981; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase;">Pemasukan</span>
                        @else
                            <span style="background: #fef2f2; color: #ef4444; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase;">Pengeluaran</span>
                        @endif
                    </td>
                    <td class="text-right {{ $transaction->type == 'income' ? 'income' : 'expense' }}">
                        {{ $transaction->type == 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: #94a3b8;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total Pemasukan</th>
                <th class="text-right income">+ Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-right">Total Pengeluaran</th>
                <th class="text-right expense">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="5" class="text-right">Total Selisih</th>
                <th class="text-right {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? 'income' : 'expense' }}" style="font-size: 14px;">
                    {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? '+' : '' }} Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

</body>
</html>
