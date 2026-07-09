@extends('layouts.app')

@section('page_title', 'Tambah Transaksi')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
            <input type="date" id="transaction_date" name="transaction_date" class="form-control" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
        </div>

        <div class="form-group">
            <label for="type" class="form-label">Tipe Transaksi</label>
            <select id="type" name="type" class="form-control" required onchange="filterCategories()">
                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="category_id" class="form-label">Kategori</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" data-type="{{ $category->type }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <div id="budget-info" style="display: none; padding: 1rem; background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 12px; margin-top: 1rem; font-size: 0.9rem;">
            </div>
            @if($categories->isEmpty())
                <small style="color: var(--danger-color); margin-top: 0.25rem; display: block;">
                    Anda belum memiliki kategori. <a href="{{ route('categories.create') }}">Buat kategori sekarang</a>.
                </small>
            @endif
        </div>

        <div class="form-group">
            <label for="amount" class="form-label">Nominal (Rp)</label>
            <input type="number" id="amount" name="amount" class="form-control" value="{{ old('amount') }}" required min="1" step="0.01" placeholder="0">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" class="form-control" rows="3" placeholder="Keterangan transaksi...">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" {{ $categories->isEmpty() ? 'disabled' : '' }}>Simpan Transaksi</button>
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
    
    let firstVisible = null;
    
    options.forEach(opt => {
        if (opt.getAttribute('data-type') === type) {
            opt.style.display = '';
            if (!firstVisible) firstVisible = opt;
        } else {
            opt.style.display = 'none';
        }
    });
    
    categorySelect.value = '';
    updateBudgetInfo();
}

const budgetData = {!! isset($budgetData) ? $budgetData : '{}' !!};

function updateBudgetInfo() {
    const catId = document.getElementById('category_id').value;
    const type = document.getElementById('type').value;
    const infoDiv = document.getElementById('budget-info');
    const amountVal = parseFloat(document.getElementById('amount').value) || 0;
    
    if (type === 'expense' && catId && budgetData[catId]) {
        const data = budgetData[catId];
        const remaining = data.limit - data.used;
        const remainingAfter = remaining - amountVal;
        
        const formatRp = (num) => 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        let color = 'var(--text-main)';
        let alertHtml = '';
        
        if (remainingAfter < 0) {
            color = 'var(--danger-color)';
            alertHtml = `<div style="margin-top: 0.75rem; padding: 0.5rem 0.75rem; background: rgba(239, 68, 68, 0.1); color: var(--danger-color); border-radius: 6px; font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid rgba(239, 68, 68, 0.2);"><i class="fas fa-exclamation-circle"></i> Peringatan: Transaksi ini akan membuat Anda melebihi batas budget!</div>`;
        } else if (remainingAfter < data.limit * 0.2) {
            color = '#eab308'; // Warning color
        }
        
        infoDiv.style.display = 'block';
        infoDiv.innerHTML = `
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
                <span style="color: var(--text-muted);"><i class="fas fa-chart-pie" style="margin-right: 0.4rem;"></i>Batas Budget Bulan Ini:</span>
                <strong>${formatRp(data.limit)}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
                <span style="color: var(--text-muted);"><i class="fas fa-receipt" style="margin-right: 0.4rem;"></i>Telah Terpakai:</span>
                <strong>${formatRp(data.used)}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 0.5rem; margin-top: 0.5rem;">
                <span style="color: var(--text-muted); font-weight: 600;">Sisa Setelah Transaksi:</span>
                <strong style="color: ${color}; font-size: 1.05rem;">${remainingAfter < 0 ? '-' : ''}${formatRp(Math.abs(remainingAfter))}</strong>
            </div>
            ${alertHtml}
        `;
    } else {
        infoDiv.style.display = 'none';
    }
}

document.getElementById('category_id').addEventListener('change', updateBudgetInfo);
document.getElementById('type').addEventListener('change', filterCategories);
document.getElementById('amount').addEventListener('input', updateBudgetInfo);

// Run on load
document.addEventListener('DOMContentLoaded', filterCategories);
</script>
@endsection
