# 🛒 Sistem Kasir & 📑 MVC Biodata Mahasiswa

Repositori ini berisi **dua project PHP** sederhana dengan arsitektur **MVC (Model-View-Controller)**:

1. **Sistem Kasir** – Aplikasi Point of Sale (POS) sederhana untuk mengelola transaksi kasir.  
2. **MVC Biodata Mahasiswa** – Aplikasi CRUD untuk mengelola data biodata mahasiswa menggunakan pola MVC.

---

## 🚀 Fitur Utama

### 🛒 Sistem Kasir
- Login Admin & User.
- Kelola produk (tambah, edit, hapus).
- Kelola transaksi penjualan.
- Laporan sederhana penjualan harian/bulanan.
- Validasi input & pesan feedback (sukses/gagal).

### 📑 MVC Biodata Mahasiswa
- **Create** → Tambah data mahasiswa.
- **Read** → Tampilkan daftar mahasiswa dalam tabel.
- **Update** → Edit data mahasiswa.
- **Delete** → Hapus data mahasiswa.
- Validasi server-side untuk NIM unik & nomor telepon.
- Proteksi dasar: CSRF token & SQL Injection prevention dengan PDO.

---

## 🛠️ Teknologi yang Digunakan
- **PHP 8+** (native, tanpa framework besar).
- **MySQL/MariaDB** sebagai database.
- **PDO (PHP Data Object)** untuk koneksi database (aman dari SQL Injection).
- **Bootstrap 5** untuk tampilan sederhana & responsif.

---

## 📂 Struktur Direktori (MVC Biodata)

Struktur folder project **mvc-biodata**:

```text
mvc-biodata/
├─ index.php                      # Front controller + router sederhana
├─ db.php                         # Koneksi PDO + CSRF + Flash + Validasi
├─ models/
│  └─ Mahasiswa.php               # Model (CRUD DB)
├─ controllers/
│  └─ MahasiswaController.php     # Controller (orchestrator)
└─ views/
   └─ mahasiswa/
      ├─ index.php                # View daftar (Read + aksi Delete/Edit)
      ├─ create.php               # View form tambah (Create)
      └─ edit.php                 # View form edit (Update)
```


---

## 🔗 Koneksi Antar File & Alur Proses (Lifecycle)

### Gambaran besar (MVC)
- **View** → halaman HTML (tabel/form).  
- **Controller** → mengatur flow, validasi, dan memanggil model.  
- **Model** → mengakses database (CRUD) menggunakan PDO.  
- **`db.php`** → koneksi PDO + helper (CSRF, flash, validasi umum).  
- **`index.php`** → front controller (router) → memetakan `?action=...` ke method Controller.  

### 1) READ (Daftar Mahasiswa)
- User buka `index.php` → `action` default `list`.  
- Router panggil `$controller->index()`.  
- Controller `index()`:
  - Ambil semua data via `$this->model->all()`.  
  - Ambil flash message bila ada.  
  - `include` view `views/mahasiswa/index.php` → render tabel.  

### 2) CREATE (Tambah Mahasiswa)
**GET Form**
- User klik “+ Tambah Mahasiswa” → `index.php?action=create`.  
- Router → `$controller->create()` → `include` view `create.php`.  

**POST Simpan**
- Submit form → `POST index.php?action=store`.  
- Router → `$controller->store()`:  
  - `check_csrf()` memverifikasi token.  
  - Validasi input (`validateMahasiswa(..., false)`).  
  - Jika valid → `$model->create($data)`.  
  - Jika sukses → `flash('success', ...)` → redirect ke `list`.  
  - Jika gagal (NIM duplicate) → render ulang `create.php` dengan error.  

### 3) UPDATE (Edit Mahasiswa)
**GET Form Edit**
- Klik “Edit” → `index.php?action=edit&nim=XXXX`.  
- Router → `$controller->edit()`.  
- Jika data ada → `include edit.php`.  
- Jika tidak → flash error → redirect ke `list`.  

**POST Update**
- Submit form → `POST index.php?action=update`.  
- Controller `update()`:
  - `check_csrf()`.  
  - Ambil input.  
  - Validasi.  
  - Jika valid → `$model->update(nim, data)` → flash sukses → redirect `list`.  
  - Jika error → flash error → redirect `list`.  

### 4) DELETE (Hapus Mahasiswa)
- Klik “Delete” → form `POST index.php?action=delete` (bawa NIM + CSRF).  
- Controller `destroy()`:
  - `check_csrf()`.  
  - Validasi NIM.  
  - `$model->delete(nim)` → flash sukses/gagal → redirect `list`.  

---

## ⚙️ Instalasi & Cara Menjalankan
1. Clone repo:
   ```bash
   git clone https://github.com/Rara2707/crud-biodata-mahasiswa.git

## 📚 Database

Masukkan perintah berikut untuk membuat **database** beserta **tabel**:

```sql
CREATE DATABASE IF NOT EXISTS mvc_biodata 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE mvc_biodata;

CREATE TABLE IF NOT EXISTS mahasiswa (
  nim        VARCHAR(20)  PRIMARY KEY,
  nama       VARCHAR(100) NOT NULL,
  alamat     TEXT         NOT NULL,
  jurusan    VARCHAR(100) NOT NULL,
  telepon    VARCHAR(20)  NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
              ON UPDATE CURRENT_TIMESTAMP
);


