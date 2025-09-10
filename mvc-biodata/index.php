<?php
// Front controller: semua request masuk lewat file ini → kita routing ke aksi controller

// Tarik controller (di dalamnya ikut tarik db.php & model)
require_once __DIR__ . '/controllers/MahasiswaController.php';

// Buat instance controller dengan PDO global dari db.php
$controller = new MahasiswaController($pdo);

// Ambil parameter action dari URL (?action=...), default 'list'
$action = $_GET['action'] ?? 'list';

// Router sangat sederhana berdasarkan action
switch ($action) {
    case 'list':   // Tampilkan daftar mahasiswa (GET)
        $controller->index();
        break;

    case 'create': // Tampilkan form tambah (GET)
        $controller->create();
        break;

    case 'store':  // Proses simpan data baru (POST)
        $controller->store();
        break;

    case 'edit':   // Tampilkan form edit (GET)
        $controller->edit();
        break;

    case 'update': // Proses update data (POST)
        $controller->update();
        break;

    case 'delete': // Proses delete data (POST)
        $controller->destroy();
        break;

    default:
        // Jika action tidak dikenali → 404
        http_response_code(404);
        echo "404 Not Found";
}
