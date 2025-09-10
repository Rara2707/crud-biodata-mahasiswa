Struktur Folder
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


