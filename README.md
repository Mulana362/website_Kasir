# Kasir Serba-Serbi Banten (Web POS)

Aplikasi **website kasir (Point of Sale)** untuk toko “Serba-Serbi Banten”.  
Mendukung login **Admin**, pengelolaan produk, transaksi penjualan, dan laporan.

## Fitur
- Login Admin
- Manajemen Produk (CRUD)
- Manajemen Stok
- Transaksi Penjualan (POS) + riwayat transaksi
- Laporan penjualan (ringkasan)

## Tech Stack
- Laravel (PHP)
- MySQL / MariaDB
- HTML/CSS/Bootstrap
- Git & GitHub

## halaman login
![Halaman Login](https://github.com/user-attachments/assets/a7428c62-2ce0-492b-a505-68e2c263c057)

Fitur pada halaman POS:
- Pilih produk + auto tampil harga
- Input Qty + hitung subtotal & total otomatis
- Uang bayar & hitung kembalian
- Simpan transaksi + riwayat/laporan penjualan

 ### 2) Halaman Kasir (POS)
![Halaman Kasir](https://github.com/user-attachments/assets/6f5e5b08-be7c-4e24-bc0e-1e984ccb0c9d)

Fitur laporan:
- Filter tanggal (Dari–Sampai)
- Ringkasan KPI: Omzet, Benefit/Profit, Margin, Jumlah transaksi
- Export laporan ke CSV
- Download laporan PDF
- Detail transaksi per invoice

### 3) Laporan Penjualan
![Laporan Penjualan](https://github.com/user-attachments/assets/4909c5a0-580d-47f7-896f-21abcf2b6dee)

## Cara Menjalankan (Local)
1. Clone
```bash
git clone https://github.com/Mulana362/website_Kasir.git
cd website_Kasir
```

2. Install & setup
composer install
cp .env.example .env
php artisan key:generate

3.jalankan
php artisan migrate --seed
php artisan serve

## Akun Demo
> Password tidak ditampilkan.(Role yang tersedia: Admin)
- Admin: admin@example.com
 
## Catatan
Project ini dibuat sebagai latihan/penerapan konsep POS & reporting menggunakan Laravel + MySQL.
AI digunakan untuk brainstorming/debugging, sedangkan implementasi & pengujian dilakukan sendiri.
