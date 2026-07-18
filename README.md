# SiSehat — Platform Monitoring Kesehatan & Evaluasi Kinerja Organisasi UMKM

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="350" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=black" alt="AlpineJS">
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

---

## 📌 Tentang SiSehat

**SiSehat** adalah platform berbasis web yang dirancang khusus untuk memantau, menilai, dan mengevaluasi tingkat kesehatan serta kinerja organisasi pada Usaha Mikro, Kecil, dan Menengah (UMKM). Platform ini membantu pemilik usaha (Owners) menganalisis jalannya operasional organisasi melalui berbagai faktor penilaian serta menerima rekomendasi peningkatan secara langsung.

---

## 🎨 Cuplikan Antarmuka (Screenshots)

*(Ganti gambar di bawah ini dengan screenshot asli aplikasi Anda setelah di-upload)*

<p align="center">
  <img src="public/assets/dashboard-mockup.png" width="800" alt="Dashboard SiSehat Mockup">
</p>

<p align="center">
  <em>Tampilan Dashboard Utama bagi Pemilik UMKM untuk Memantau Kesehatan Organisasi.</em>
</p>

---

## 💼 Peran & Kontribusi (UI/UX Designer & Front End)

Sebagai pengembang UI/UX dan Front End pada proyek SiSehat, fokus utama diarahkan pada penciptaan antarmuka yang bersih, intuitif, berkinerja tinggi, dan ramah pengguna (user-friendly) guna mempermudah pelaku UMKM dalam melakukan asesmen mandiri.

### Poin-Poin Implementasi Utama:
* **Antarmuka Responsif & Modern**: Merancang dan mengimplementasikan tampilan antarmuka adaptif di berbagai ukuran perangkat menggunakan **Laravel Blade Templates** dan **Tailwind CSS**.
* **Dashboard Interaktif**: Menyajikan visualisasi data ringkasan metrik kesehatan organisasi berdasarkan berbagai indikator penilaian penting.
* **Sistem Autentikasi & Otorisasi**: Mengintegrasikan **Laravel Breeze** untuk manajemen registrasi, login, dan pemisahan hak akses antara Owner (Pemilik UMKM) dan Employee (Karyawan).
* **Optimasi Build Tool**: Memanfaatkan **Vite** untuk kompilasi aset (CSS & JS) super cepat demi kenyamanan pengguna dengan pemuatan halaman instan.
* **Ekspor Laporan Dinamis**: Menyediakan fitur ekspor hasil asesmen organisasi menjadi dokumen PDF siap cetak dengan integrasi **Barryvdh Laravel Dompdf**.

---

## 🛠️ Tech Stack & Dependensi

| Kategori | Teknologi/Pustaka | Deskripsi |
| :--- | :--- | :--- |
| **Core Framework** | PHP ^8.3 & Laravel 13 | Backend tangguh dengan arsitektur MVC yang modular dan bersih. |
| **Frontend Setup** | Tailwind CSS & Alpine.js | Desain UI responsif berbasis utility-first dan interaktivitas ringan. |
| **Build & Tooling** | Vite | Manajemen modul & bundling aset modern berkinerja tinggi. |
| **Auth Kit** | Laravel Breeze | Sistem otentikasi minimalis yang andal dan aman. |
| **PDF Generator** | Barryvdh Dompdf | Konversi layout Blade HTML ke format dokumen PDF. |
| **Testing Engine** | Pest PHP | Unit & integration testing otomatis yang ringkas. |

📖 Detail endpoint API serta fungsionalitas backend dapat diakses langsung pada [Dokumentasi API](api_documentation.md).

---

## 🚀 Cara Menjalankan Proyek Secara Lokal

Pastikan Anda telah menginstal **PHP ^8.3**, **Composer**, dan **Node.js** di perangkat Anda.

1. **Clone repositori ini**:
   ```bash
   git clone https://github.com/username/sisehat.git
   cd sisehat
   ```

2. **Jalankan script setup otomatis** (akan menginstal dependensi Composer & NPM, membuat database SQLite, menjalankan migrasi, dan mem-build aset):
   ```bash
   composer run setup
   ```

3. **Jalankan server pengembangan lokal**:
   ```bash
   composer run dev
   ```

4. **Akses aplikasi di browser**:
   Buka [http://127.0.0.1:8000](http://127.0.0.1:8000) pada browser Anda.
