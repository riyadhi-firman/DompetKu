@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="grid-3">
    <!-- Balance Card -->
    <div class="card">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-details">
                <h3>Total Saldo</h3>
                <p>Rp {{ number_format($balance, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Income Card -->
    <div class="card">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-arrow-down"></i>
            </div>
            <div class="stat-details">
                <h3>Pemasukan Bulan Ini</h3>
                <p>Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Expense Card -->
    <div class="card">
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-arrow-up"></i>
            </div>
            <div class="stat-details">
                <h3>Pengeluaran Bulan Ini</h3>
                <p>Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.25rem; color: var(--text-main);">Transaksi Terbaru</h3>
        <a href="{{ route('transactions.index') }}" class="btn btn-outline" style="font-size: 0.8rem; padding: 0.4rem 0.8rem;">Lihat Semua</a>
    </div>
    
    @if(count($recentTransactions) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                        @php
                            $isOverBudget = false;
                            if($transaction->category) {
                                $isOverBudget = in_array($transaction->category_id, $overBudgetCategories ?? []) 
                                                && $transaction->type === 'expense'
                                                && \Carbon\Carbon::parse($transaction->transaction_date)->format('m-Y') === date('m-Y');
                            }
                        @endphp
                        <tr style="{{ $isOverBudget ? 'background: rgba(239, 68, 68, 0.05);' : '' }}">
                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td>
                                @if($transaction->category)
                                    @if($isOverBudget)
                                        <i class="fas fa-exclamation-triangle" style="color: var(--danger-color); font-size: 0.8rem; margin-right: 0.25rem;" title="Melebihi Budget"></i>
                                    @endif
                                    <i class="{{ $transaction->category->icon }}" style="color: {{ $transaction->category->color }}"></i>
                                    {{ $transaction->category->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($transaction->type === 'income')
                                    <span class="badge badge-income">Pemasukan</span>
                                @else
                                    <span class="badge badge-expense">Pengeluaran</span>
                                @endif
                            </td>
                            <td style="font-weight: 600; color: {{ $transaction->type === 'income' ? 'var(--secondary-color)' : 'var(--danger-color)' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
            <i class="fas fa-receipt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Belum ada transaksi</p>
        </div>
    @endif
</div>
@endsection
