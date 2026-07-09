@extends('layouts.app')

@section('page_title', 'Edit Budget')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('budgets.update', $budget->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="category_id" class="form-label">Kategori Pengeluaran</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $budget->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid-3" style="margin-bottom: 0;">
            <div class="form-group">
                <label for="month" class="form-label">Bulan</label>
                <select id="month" name="month" class="form-control" required>
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ old('month', $budget->month) == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="form-group" style="grid-column: span 2;">
                <label for="year" class="form-label">Tahun</label>
                <input type="number" id="year" name="year" class="form-control" value="{{ old('year', $budget->year) }}" required min="2000" max="2100">
            </div>
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">Batas Anggaran (Rp)</label>
            <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount', $budget->amount) }}" required min="1" step="0.01">
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('budgets.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
