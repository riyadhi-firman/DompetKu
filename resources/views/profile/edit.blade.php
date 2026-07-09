@extends('layouts.app')

@section('page_title', 'Profil Saya')

@section('content')
<div class="card" style="max-width: 600px;">
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')
        
        <h4 style="margin-top: 0; margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">Informasi Dasar</h4>

        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <h4 style="margin-top: 2rem; margin-bottom: 1.5rem; color: var(--text-main); font-size: 1.1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.75rem;">Ubah Password <span style="font-size: 0.8rem; font-weight: 400; color: var(--text-muted);">(Opsional)</span></h4>

        <div class="form-group">
            <label for="current_password" class="form-label">Password Saat Ini</label>
            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline">Batal</a>
        </div>
    </form>
</div>
@endsection
