<?php
class Mahasiswa {
    private PDO $pdo;
    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public function all(): array {
        $stmt = $this->pdo->query("SELECT * FROM mahasiswa ORDER BY nama ASC");
        return $stmt->fetchAll();
    }

    public function find(string $nim): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO mahasiswa (nim, nama, alamat, jurusan, telepon)
                VALUES (:nim, :nama, :alamat, :jurusan, :telepon)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nim'     => $data['nim'],
            ':nama'    => $data['nama'],
            ':alamat'  => $data['alamat'],
            ':jurusan' => $data['jurusan'],
            ':telepon' => $data['telepon'],
        ]);
    }

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

    public function delete(string $nim): bool {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE nim = ?");
        return $stmt->execute([$nim]);
    }
}
