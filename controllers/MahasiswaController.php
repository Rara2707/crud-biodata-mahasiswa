<?php
// Controller mengorkestrasi request: ambil input -> validasi -> panggil model -> pilih view

// Tarik helper global: koneksi PDO, CSRF, flash, validasi
require_once __DIR__ . '/../db.php';
// Tarik model Mahasiswa
require_once __DIR__ . '/../models/Mahasiswa.php';

class MahasiswaController {
    // Simpan instance model
    private Mahasiswa $model;

    // Inject PDO -> buat model
    public function __construct(PDO $pdo) { $this->model = new Mahasiswa($pdo); }

    // Tampilkan daftar (Read)
    public function index(): void {
        // Ambil semua data mahasiswa
        $rows = $this->model->all();
        // Ambil flash message (jika ada) untuk ditampilkan di view
        $flashSuccess = flash('success'); 
        $flashError   = flash('error');
        // Sertakan view daftar
        include __DIR__ . '/../views/mahasiswa/index.php';
    }

    // Tampilkan form create
    public function create(): void {
        // Siapkan error kosong & nilai lama kosong
        $errors = [];
        $old = ['nim'=>'','nama'=>'','alamat'=>'','jurusan'=>'','telepon'=>''];
        // Sertakan view create
        include __DIR__ . '/../views/mahasiswa/create.php';
    }

    // Proses simpan data baru (POST)
    public function store(): void {
        // Pastikan token CSRF valid
        check_csrf();

        // Ambil & rapikan input (trim)
        $data = [
            'nim'     => trim($_POST['nim'] ?? ''),
            'nama'    => trim($_POST['nama'] ?? ''),
            'alamat'  => trim($_POST['alamat'] ?? ''),
            'jurusan' => trim($_POST['jurusan'] ?? ''),
            'telepon' => trim($_POST['telepon'] ?? ''),
        ];

        // Validasi server-side (Create -> NIM dicek)
        $errors = validateMahasiswa($data, false);
        if ($errors) {
            // Jika invalid, tampilkan lagi form dengan error & nilai lama
            $old = $data;
            include __DIR__ . '/../views/mahasiswa/create.php';
            return; // hentikan agar tidak lanjut eksekusi
        }

        try {
            // Coba buat data via model
            $this->model->create($data);
            // Simpan pesan sukses utk satu kali tampil
            flash('success', 'Data berhasil ditambahkan.');
            // Redirect ke daftar
            redirect('index.php?action=list');
        } catch (PDOException $e) {
            // Kode SQLSTATE 23000 biasanya constraint violation (PK duplicate)
            if ($e->getCode() === '23000') {
                $errors = ['nim' => 'NIM sudah terdaftar.'];
                $old = $data;
                include __DIR__ . '/../views/mahasiswa/create.php';
                return;
            }
            // Jika error lain, tampilkan di halaman list dgn flash error
            flash('error', 'Gagal menambah data: ' . $e->getMessage());
            redirect('index.php?action=list');
        }
    }

    // Tampilkan form edit berdasarkan NIM
    public function edit(): void {
        // Ambil NIM dari query string
        $nim = $_GET['nim'] ?? '';
        // Cari data di DB
        $row = $this->model->find($nim);
        if (!$row) {
            // Jika tidak ditemukan, beri flash error dan balik ke list
            flash('error','Data tidak ditemukan');
            redirect('index.php?action=list');
        }
        // Siapkan tempat error (jika nanti re-render)
        $errors = [];
        // Tampilkan view edit (bawa $row)
        include __DIR__ . '/../views/mahasiswa/edit.php';
    }

    // Proses update (POST)
    public function update(): void {
        // Cek CSRF
        check_csrf();

        // NIM dikirim sebagai hidden dan tidak boleh diubah
        $nim = $_POST['nim'] ?? '';
        // Ambil field editable
        $data = [
            'nama'    => trim($_POST['nama'] ?? ''),
            'alamat'  => trim($_POST['alamat'] ?? ''),
            'jurusan' => trim($_POST['jurusan'] ?? ''),
            'telepon' => trim($_POST['telepon'] ?? ''),
        ];

        // Validasi untuk update (NIM tidak divalidasi karena fixed)
        $errors = validateMahasiswa(array_merge($data, ['nim'=>$nim]), true);
        if ($errors) {
            // Jika invalid, render ulang view edit dengan error dan nilai baru
            $row = array_merge(['nim'=>$nim], $data);
            include __DIR__ . '/../views/mahasiswa/edit.php';
            return;
        }

        try {
            // Lakukan update via model
            $this->model->update($nim, $data);
            flash('success', 'Data berhasil diperbarui.');
        } catch (PDOException $e) {
            flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
        // Kembali ke daftar
        redirect('index.php?action=list');
    }

    // Proses hapus (POST)
    public function destroy(): void {
        // Cek CSRF
        check_csrf();

        // Ambil NIM dari form hidden
        $nim = $_POST['nim'] ?? '';
        if (!$nim) {
            flash('error','NIM tidak valid');
            redirect('index.php?action=list');
        }

        try {
            // Hapus via model
            $this->model->delete($nim);
            flash('success', 'Data berhasil dihapus.');
        } catch (PDOException $e) {
            flash('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
        // Kembali ke daftar
        redirect('index.php?action=list');
    }
}
