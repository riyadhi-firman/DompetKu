@extends('layouts.app')

@section('page_title', 'Tambah Budget')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('budgets.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="category_id" class="form-label">Kategori Pengeluaran</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @if($categories->isEmpty())
                <small style="color: var(--danger-color); margin-top: 0.25rem; display: block;">
                    Anda belum memiliki kategori pengeluaran. <a href="{{ route('categories.create') }}">Buat kategori sekarang</a>.
                </small>
            @endif
        </div>

        <div class="grid-3" style="margin-bottom: 0;">
            <div class="form-group">
                <label for="month" class="form-label">Bulan</label>
                <select id="month" name="month" class="form-control" required>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ (old('month') ?? date('n')) == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group" style="grid-column: span 2;">
                <label for="year" class="form-label">Tahun</label>
                <input type="number" id="year" name="year" class="form-control" value="{{ old('year', date('Y')) }}" required min="2000" max="2100">
            </div>
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">Batas Anggaran (Rp)</label>
            <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount') }}" required min="1" step="0.01" placeholder="Contoh: 1000000">
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" {{ $categories->isEmpty() ? 'disabled' : '' }}>Simpan Budget</button>
            <a href="{{ route('budgets.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
