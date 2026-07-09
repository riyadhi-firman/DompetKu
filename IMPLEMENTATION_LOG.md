# Dokumentasi Implementasi & Riwayat Tugas (Task Log)

Dokumen ini berisi rangkuman riwayat perencanaan implementasi (*implementation plan*) dan daftar tugas (*tasks*) yang telah diselesaikan selama proses pengembangan aplikasi **DompetKu**.

---

## Tahap 1: Persiapan Fondasi & Struktur Dasar
**Tujuan:** Membangun antarmuka dasar dan struktur *database* untuk fitur keuangan utama.

**Daftar Tugas (Tasks):**
- [x] Merancang struktur *database* untuk tabel `users`, `categories`, `transactions`, dan `budgets`.
- [x] Membuat model dan migrasi *database*.
- [x] Membangun antarmuka utama (*layout*) termasuk *Sidebar* dan *Header*.
- [x] Membuat fitur Mode Gelap/Terang (*Dark/Light Mode*).
- [x] Membuat halaman *Dashboard* ringkasan (Total Saldo, Pemasukan, Pengeluaran).

---

## Tahap 2: Manajemen Kategori & Transaksi
**Tujuan:** Memungkinkan pengguna untuk mencatat transaksi dan mengelompokkannya dalam kategori kustom.

**Daftar Tugas (Tasks):**
- [x] Membuat halaman CRUD Kategori (Pemasukan & Pengeluaran).
- [x] Menambahkan sistem ikon dan pemilihan warna untuk tiap kategori.
- [x] Menambahkan perhitungan "Total Transaksi" (nominal & jumlah frekuensi) di daftar kategori.
- [x] Membuat halaman CRUD Transaksi.
- [x] Menambahkan filter pencarian transaksi (Berdasarkan Tipe, Kategori, Bulan, dan Tahun).
- [x] Mengimplementasikan *Pagination* (pembatasan 10 baris per halaman) pada tabel transaksi.

---

## Tahap 3: Manajemen Anggaran (Budgets) & Peringatan Dini
**Tujuan:** Membantu pengguna membatasi pengeluarannya setiap bulan.

**Daftar Tugas (Tasks):**
- [x] Membuat halaman CRUD untuk menetapkan target *Budget* bulanan.
- [x] Menambahkan *progress bar* visual untuk melihat persentase *budget* yang terpakai.
- [x] Mengintegrasikan peringatan *real-time* di halaman "Tambah Transaksi" (Sisa Setelah Transaksi akan menyala merah jika nominal melebihi sisa *budget*).
- [x] Memberikan tanda *highlight* merah muda dan *badge* peringatan (⚠️) pada transaksi yang melanggar *budget*.
- [x] Menerapkan tanda peringatan yang sama di halaman Dashboard (tabel Transaksi Terbaru).

---

## Tahap 4: Ekspor Laporan PDF
**Tujuan:** Memudahkan pengguna mencetak riwayat keuangannya.

**Daftar Tugas (Tasks):**
- [x] Menginstal paket `barryvdh/laravel-dompdf`.
- [x] Menambahkan tombol "Export PDF" di halaman Manajemen Transaksi.
- [x] Membuat tampilan PDF yang mendukung filter (*Smart Export*).
- [x] Mendesain ulang PDF dengan gaya modern (tabel *border-less*, tipografi bersih, penyertaan Logo DompetKu).
- [x] Menambahkan tanda peringatan (*highlight* & teks merah) pada PDF untuk transaksi yang *over-budget*.

---

## Tahap 5: Otentikasi Google (Socialite) & Profil Pengguna
**Tujuan:** Mempermudah alur masuk aplikasi dan manajemen profil akun.

**Daftar Tugas (Tasks):**
- [x] Menginstal paket `laravel/socialite`.
- [x] Memperbarui struktur *database* `users` (menambahkan kolom `google_id` dan mengubah `password` menjadi *nullable*).
- [x] Memperbarui `config/services.php` dengan *endpoint* kredensial Google.
- [x] Mengimplementasikan logika `redirectToGoogle` dan `handleGoogleCallback` di `AuthController`.
- [x] Mendesain ulang tombol *Login* dan *Register* dengan menyediakan logo resmi Google.
- [x] Membuat *ProfileController* dan halaman "Profil Saya" untuk mengubah Nama, Email, dan Password.
- [x] Merapikan tata letak *Header* agar nama pengguna diganti dengan ikon profil yang bisa diklik.

---

*Catatan: Dokumen ini dibuat sebagai rekam jejak historis seluruh proses pengembangan yang dipandu secara adaptif.*
