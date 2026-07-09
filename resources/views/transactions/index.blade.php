@extends('layouts.app')

@section('page_title', 'Manajemen Transaksi')

@section('content')
<div class="card">
    <div class="section-header">
        <h3 style="font-size: 1.25rem; margin: 0;">Daftar Transaksi</h3>
        <div class="header-actions">
            <a href="{{ route('transactions.export', request()->query()) }}" class="btn btn-outline" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </a>
        </div>
    </div>
    
    <div style="margin-bottom: 1.5rem;">
        <form action="{{ route('transactions.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 150px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; display: block;">Tipe</label>
                <select name="type" class="form-control" style="padding: 0.5rem; font-size: 0.9rem;" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; display: block;">Kategori</label>
                <select name="category_id" class="form-control" style="padding: 0.5rem; font-size: 0.9rem;" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1; min-width: 120px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; display: block;">Bulan</label>
                <select name="month" class="form-control" style="padding: 0.5rem; font-size: 0.9rem;" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div style="flex: 1; min-width: 100px;">
                <label style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.25rem; display: block;">Tahun</label>
                <select name="year" class="form-control" style="padding: 0.5rem; font-size: 0.9rem;" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                @if(request()->anyFilled(['type', 'category_id', 'month', 'year']))
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(count($transactions) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        @php
                            $isOverBudget = false;
                            if($transaction->category) {
                                $isOverBudget = in_array($transaction->category_id, $overBudgetCategories ?? []) 
                                                && $transaction->type === 'expense'
                                                && \Carbon\Carbon::parse($transaction->transaction_date)->format('m-Y') === date('m-Y');
                            }
                        @endphp
                        <tr style="{{ $isOverBudget ? 'background-color: rgba(239, 68, 68, 0.05);' : '' }}">
                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td>
                                @if($isOverBudget)
                                    <span class="badge badge-expense" style="padding: 2px 6px; margin-right: 0.25rem;" title="Kategori ini melebihi budget!"><i class="fas fa-exclamation-triangle"></i></span>
                                @endif
                                @if($transaction->category)
                                    <i class="{{ $transaction->category->icon }}" style="color: {{ $transaction->category->color }}; margin-right: 0.5rem;"></i>
                                    {{ $transaction->category->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $transaction->description ?: '-' }}</td>
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
                            <td style="text-align: right;">
                                <div class="action-buttons">
                                    <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? (Saldo Anda akan disesuaikan kembali)');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="pagination-wrapper" style="padding: 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--text-muted); font-size: 0.9rem;">
                    Menampilkan <strong>{{ $transactions->firstItem() }}</strong> sampai <strong>{{ $transactions->lastItem() }}</strong> dari <strong>{{ $transactions->total() }}</strong> transaksi
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    @if($transactions->onFirstPage())
                        <span class="btn btn-outline" style="opacity: 0.5; cursor: not-allowed; padding: 0.4rem 0.8rem; font-size: 0.85rem;"><i class="fas fa-chevron-left"></i> Prev</span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;"><i class="fas fa-chevron-left"></i> Prev</a>
                    @endif
                    
                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Next <i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="btn btn-outline" style="opacity: 0.5; cursor: not-allowed; padding: 0.4rem 0.8rem; font-size: 0.85rem;">Next <i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
            <i class="fas fa-receipt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Belum ada transaksi yang ditambahkan.</p>
        </div>
    @endif
</div>
@endsection
