@extends('layouts.app')

@section('page_title', 'Tambah Kategori')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name" class="form-label">Nama Kategori</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Gaji, Makanan, Transportasi">
        </div>

        <div class="form-group">
            <label for="type" class="form-label">Tipe Kategori</label>
            <select id="type" name="type" class="form-control" required>
                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Ikon Kategori</label>
            <input type="hidden" id="icon" name="icon" value="{{ old('icon', 'fas fa-wallet') }}">
            
            @php
            $icons = ['fas fa-money-bill-wave', 'fas fa-wallet', 'fas fa-piggy-bank', 'fas fa-credit-card', 'fas fa-coins', 'fas fa-chart-line', 'fas fa-shopping-cart', 'fas fa-utensils', 'fas fa-hamburger', 'fas fa-car', 'fas fa-gas-pump', 'fas fa-plane', 'fas fa-home', 'fas fa-bolt', 'fas fa-tint', 'fas fa-phone', 'fas fa-wifi', 'fas fa-tv', 'fas fa-gamepad', 'fas fa-heartbeat', 'fas fa-pills', 'fas fa-graduation-cap', 'fas fa-book', 'fas fa-baby', 'fas fa-gift', 'fas fa-box', 'fas fa-ellipsis-h', 'fas fa-laptop-code', 'fas fa-file-invoice-dollar'];
            $oldIcon = old('icon', 'fas fa-wallet');
            @endphp
            
            <div class="icon-picker">
                @foreach($icons as $ic)
                    <div class="icon-option {{ $oldIcon == $ic ? 'selected' : '' }}" onclick="selectIcon('{{ $ic }}', this)">
                        <i class="{{ $ic }}"></i>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="color" class="form-label">Warna</label>
            <input type="color" id="color" name="color" class="form-control" value="{{ old('color', '#4f46e5') }}" style="height: 50px; padding: 0.25rem;">
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>

<script>
function selectIcon(iconClass, element) {
    // Update hidden input
    document.getElementById('icon').value = iconClass;
    
    // Remove selected class from all options
    document.querySelectorAll('.icon-option').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    element.classList.add('selected');
}
</script>
@endsection
