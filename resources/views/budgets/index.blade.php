@extends('layouts.app')

@section('page_title', 'Manajemen Budget')

@section('content')
<div class="card">
    <div class="section-header">
        <h3 style="font-size: 1.25rem; margin: 0;">Daftar Budget (Anggaran)</h3>
        <div class="header-actions">
            <a href="{{ route('budgets.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Budget
            </a>
        </div>
    </div>

    @if(count($budgets) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Kategori</th>
                        <th>Batas Anggaran</th>
                        <th>Terpakai</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($budgets as $budget)
                        @php
                            $isOverBudget = $budget->total_expenses > $budget->amount;
                        @endphp
                        <tr style="{{ $isOverBudget ? 'background-color: rgba(239, 68, 68, 0.05);' : '' }}">
                            <td>{{ date('F', mktime(0, 0, 0, $budget->month, 10)) }} {{ $budget->year }}</td>
                            <td>
                                @if($isOverBudget)
                                    <span class="badge badge-expense" style="padding: 2px 6px; margin-right: 0.25rem;" title="Melebihi budget!"><i class="fas fa-exclamation-triangle"></i></span>
                                @endif
                                @if($budget->category)
                                    <i class="{{ $budget->category->icon }}" style="color: {{ $budget->category->color }}; margin-right: 0.5rem;"></i>
                                    {{ $budget->category->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-weight: 600;">
                                Rp {{ number_format($budget->amount, 0, ',', '.') }}
                            </td>
                            <td style="font-weight: 600; color: {{ $isOverBudget ? 'var(--danger-color)' : 'var(--text-main)' }};">
                                Rp {{ number_format($budget->total_expenses, 0, ',', '.') }}
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons">
                                    <a href="{{ route('budgets.edit', $budget->id) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('budgets.destroy', $budget->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus budget ini?');">
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
    @else
        <div style="text-align: center; padding: 3rem; color: var(--text-muted);">
            <i class="fas fa-chart-pie" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Belum ada budget yang ditentukan.</p>
        </div>
    @endif
</div>
@endsection
