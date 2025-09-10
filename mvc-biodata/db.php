<?php
// Mulai session utk simpan token CSRF & flash message
if (session_status() === PHP_SESSION_NONE) session_start();

/** ====== KONFIGURASI DATABASE ====== */
// Konstanta koneksi database (ubah sesuai mesinmu)
const DB_HOST = 'localhost';   // Host DB
const DB_NAME = 'mvc_biodata'; // Nama DB
const DB_USER = 'root';        // User DB (ubah)
const DB_PASS = '';            // Password DB (ubah)

try {
    // Inisialisasi PDO (driver MySQL) + charset utf8mb4 untuk aman emoji/simbol
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            // Semua error dilempar jadi Exception -> mudah ditangani
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Default fetch associative array
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Jika koneksi gagal, beri HTTP 500 dan hentikan
    http_response_code(500);
    // htmlspecialchars mencegah XSS jika pesan error diprint
    die("DB connection error: " . htmlspecialchars($e->getMessage()));
}

/** ====== CSRF PROTECTION ====== */
// Membuat/ambil token CSRF agar form POST valid
function csrf_token(): string {
    // Jika belum ada token di session, buat acak 32 byte -> hex
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// Memastikan setiap request POST membawa token CSRF yang benar
function check_csrf(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil token dari form
        $token = $_POST['csrf'] ?? '';
        // Valid jika ada di session dan sama persis (hash_equals = timing-safe)
        if (!$token || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(400);
            die('Invalid CSRF token');
        }
    }
}

/** ====== FLASH MESSAGE (sukses/gagal) ====== */
// Setter (dengan $message) atau getter (tanpa $message) flash message
function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) {
        // Simpan pesan sekali-pakai di session
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    // Ambil & hapus pesan (agar tidak tampil dua kali)
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

/** ====== HELPER REDIRECT ====== */
// Helper untuk redirect dan exit (hindari eksekusi lanjutan)
function redirect(string $path): void { header("Location: {$path}"); exit; }

/** ====== VALIDASI SERVER-SIDE ====== */
// Memeriksa input mahasiswa; $isUpdate=true artinya NIM tidak divalidasi (tidak diubah)
function validateMahasiswa(array $data, bool $isUpdate = false): array {
    $errors = [];

    // Saat create, NIM wajib & format alfanumerik 5–20
    if (!$isUpdate) {
        if (empty($data['nim'])) $errors['nim'] = 'NIM wajib diisi';
        elseif (!preg_match('/^[A-Za-z0-9]{5,20}$/', $data['nim'])) {
            $errors['nim'] = 'NIM 5–20 karakter alfanumerik';
        }
    }

    // Field wajib lain
    if (empty($data['nama']))      $errors['nama'] = 'Nama wajib diisi';
    if (empty($data['alamat']))    $errors['alamat'] = 'Alamat wajib diisi';
    if (empty($data['jurusan']))   $errors['jurusan'] = 'Jurusan wajib diisi';

    // Telepon: 8–15 digit, boleh spasi/dash/+
    if (empty($data['telepon']))   $errors['telepon'] = 'Nomor telepon wajib diisi';
    elseif (!preg_match('/^\+?\d[\d\s\-]{7,14}$/', $data['telepon'])) {
        $errors['telepon'] = 'Telepon tidak valid (8–15 digit, boleh +, spasi, -)';
    }

    // Kembalikan daftar error per-field (kosong artinya valid)
    return $errors;
}
