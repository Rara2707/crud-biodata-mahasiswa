<?php
// Model bertanggung jawab atas operasi ke database (CRUD) untuk entitas Mahasiswa
class Mahasiswa {
    // Simpan instance PDO
    private PDO $pdo;

    // Inject PDO lewat constructor (dependency injection)
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    // Ambil semua mahasiswa (Read)
    public function all(): array {
        // Query sederhana urut nama ASC
        $stmt = $this->pdo->query("SELECT * FROM mahasiswa ORDER BY nama ASC");
        // Kembalikan array asosiatif
        return $stmt->fetchAll();
    }

    // Cari satu mahasiswa berdasarkan NIM (Read detail)
    public function find(string $nim): ?array {
        // Prepared statement (aman dari SQL Injection)
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        $row = $stmt->fetch();
        return $row ?: null; // null jika tidak ketemu
    }

    // Tambah mahasiswa (Create)
    public function create(array $data): bool {
        // Gunakan named parameters agar jelas
        $sql = "INSERT INTO mahasiswa (nim, nama, alamat, jurusan, telepon)
                VALUES (:nim, :nama, :alamat, :jurusan, :telepon)";
        $stmt = $this->pdo->prepare($sql);
        // Eksekusi dengan binding parameter
        return $stmt->execute([
            ':nim'     => $data['nim'],
            ':nama'    => $data['nama'],
            ':alamat'  => $data['alamat'],
            ':jurusan' => $data['jurusan'],
            ':telepon' => $data['telepon'],
        ]);
    }

    // Update mahasiswa (Update)
    public function update(string $nim, array $data): bool {
        $sql = "UPDATE mahasiswa
                   SET nama = :nama, alamat = :alamat, jurusan = :jurusan, telepon = :telepon
                 WHERE nim = :nim";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nama'    => $data['nama'],
            ':alamat'  => $data['alamat'],
            ':jurusan' => $data['jurusan'],
            ':telepon' => $data['telepon'],
            ':nim'     => $nim,
        ]);
    }

    // Hapus mahasiswa (Delete)
    public function delete(string $nim): bool {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE nim = ?");
        return $stmt->execute([$nim]);
    }
}
