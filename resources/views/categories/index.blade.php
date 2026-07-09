@extends('layouts.app')

@section('page_title', 'Manajemen Kategori')

@section('content')
<div class="card">
    <div class="section-header">
        <h3 style="font-size: 1.25rem; margin: 0;">Daftar Kategori</h3>
        <div class="header-actions">
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>
    </div>

    @if(count($categories) > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Tipe</th>
                        <th>Ikon</th>
                        <th>Total Transaksi</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if($category->type === 'income')
                                    <span class="badge badge-income">Pemasukan</span>
                                @else
                                    <span class="badge badge-expense">Pengeluaran</span>
                                @endif
                            </td>
                            <td>
                                <i class="{{ $category->icon }}" style="color: {{ $category->color }}; font-size: 1.2rem;"></i>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: {{ $category->type === 'income' ? 'var(--secondary-color)' : 'var(--danger-color)' }};">
                                    Rp {{ number_format($category->transactions_sum_amount ?? 0, 0, ',', '.') }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    {{ $category->transactions_count }} transaksi
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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
            <i class="fas fa-tags" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>Belum ada kategori yang ditambahkan.</p>
        </div>
    @endif
</div>
@endsection
