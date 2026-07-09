@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <!-- Hero Section -->
    <div class="auth-hero">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        
        <div class="auth-hero-content fade-in">
            <h1>Mulai Perjalanan Finansial Anda</h1>
            <p>Bergabunglah dengan DompetKu dan rasakan kemudahan mengontrol setiap arus kas Anda. Satu aplikasi untuk semua kebutuhan pencatatan keuangan pribadi.</p>
            
            <div class="hero-features">
                <div class="hero-feature-card">
                    <i class="fas fa-bullseye"></i>
                    <h4>Tetapkan Tujuan</h4>
                    <p>Buat budget bulanan dan pantau pengeluaran Anda agar tidak melewati batas.</p>
                </div>
                <div class="hero-feature-card">
                    <i class="fas fa-bolt"></i>
                    <h4>Cepat & Instan</h4>
                    <p>Catat transaksi dalam hitungan detik. Tanpa ribet, tanpa loading lama.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="auth-form-side">
        <div class="auth-card" style="max-width: 500px; padding: 3rem;">
            <div class="auth-header" style="margin-bottom: 2rem;">
                <h2><i class="fas fa-wallet"></i> DompetKu</h2>
                <p style="color: var(--text-muted);">Buat akun baru gratis</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                </div>

                <div class="grid-3" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0;">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required placeholder="Min. 8 karakter">
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Ulangi password">
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem;">
                        Daftar
                    </button>
                </div>
                
                <div style="display: flex; align-items: center; margin: 1.5rem 0;">
                    <hr style="flex-grow: 1; border: none; border-top: 1px solid var(--border);">
                    <span style="padding: 0 1rem; color: var(--text-muted); font-size: 0.85rem;">atau</span>
                    <hr style="flex-grow: 1; border: none; border-top: 1px solid var(--border);">
                </div>

                <a href="{{ route('auth.google') }}" class="btn btn-outline" style="width: 100%; padding: 0.8rem; font-size: 0.95rem; display: flex; justify-content: center; align-items: center; gap: 0.75rem; color: var(--text-main); border-color: #d1d5db; background: white; text-decoration: none;">
                    <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                    Daftar dengan Google
                </a>
                
                <p style="text-align: center; margin-top: 1.5rem; font-size: 0.95rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
