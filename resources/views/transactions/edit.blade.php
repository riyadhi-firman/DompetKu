@extends('layouts.app')

@section('page_title', 'Edit Transaksi')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
            <input type="date" id="transaction_date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="type" class="form-label">Tipe Transaksi</label>
            <select id="type" name="type" class="form-control" required onchange="filterCategories()">
                <option value="expense" {{ old('type', $transaction->type) == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                <option value="income" {{ old('type', $transaction->type) == 'income' ? 'selected' : '' }}>Pemasukan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="category_id" class="form-label">Kategori</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" data-type="{{ $category->type }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">Nominal (Rp)</label>
            <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount', $transaction->amount) }}" required min="1" step="0.01">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $transaction->description) }}</textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function filterCategories() {
    const type = document.getElementById('type').value;
    const categorySelect = document.getElementById('category_id');
    const options = categorySelect.querySelectorAll('option[data-type]');
    
    // Check if current selected option is still visible
    let currentSelectedValid = false;
    
    options.forEach(opt => {
        if (opt.getAttribute('data-type') === type) {
            opt.style.display = '';
            if (opt.selected) currentSelectedValid = true;
        } else {
            opt.style.display = 'none';
            if (opt.selected) opt.selected = false;
        }
    });
    
    if (!currentSelectedValid) {
        categorySelect.value = '';
    }
}

// Run on load but don't reset value since it's edit mode
document.addEventListener('DOMContentLoaded', () => {
    const type = document.getElementById('type').value;
    const options = document.getElementById('category_id').querySelectorAll('option[data-type]');
    options.forEach(opt => {
        if (opt.getAttribute('data-type') !== type) {
            opt.style.display = 'none';
        }
    });
});
</script>
@endsection
