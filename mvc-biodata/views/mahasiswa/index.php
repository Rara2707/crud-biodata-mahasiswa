<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><!-- Encoding aman -->
  <title>Biodata Mahasiswa - Daftar</title><!-- Judul halaman -->
  <!-- Bootstrap CSS (untuk tabel & tombol) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4"><!-- Padding agar tidak mepet -->
  <div class="container"><!-- Kontainer tengah -->
    <h1 class="mb-3">Daftar Mahasiswa</h1><!-- Heading -->

    <!-- Tampilkan flash sukses jika ada -->
    <?php if ($msg = $flashSuccess): ?>
      <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Tampilkan flash error jika ada -->
    <?php if ($msg = $flashError): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Tombol ke form tambah -->
    <a class="btn btn-primary mb-3" href="index.php?action=create">+ Tambah Mahasiswa</a>

    <!-- Tabel responsif -->
    <div class="table-responsive">
      <table class="table table-striped align-middle"><!-- Tabel dengan striping -->
        <thead>
          <tr>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Alamat</th>
            <th>Jurusan</th>
            <th>Telepon</th>
            <th style="width:160px">Aksi</th><!-- Kolom tombol -->
          </tr>
        </thead>
        <tbody>
          <!-- Jika tidak ada data, tampilkan baris kosong -->
          <?php if (empty($rows)): ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
          <?php else: foreach ($rows as $r): ?><!-- Loop data -->
            <tr>
              <!-- Escape semua output untuk mencegah XSS -->
              <td><?= htmlspecialchars($r['nim']) ?></td>
              <td><?= htmlspecialchars($r['nama']) ?></td>
              <td><?= nl2br(htmlspecialchars($r['alamat'])) ?></td><!-- nl2br agar newline terlihat -->
              <td><?= htmlspecialchars($r['jurusan']) ?></td>
              <td><?= htmlspecialchars($r['telepon']) ?></td>
              <td>
                <!-- Tombol Edit → ke action=edit bawa nim via query string -->
                <a class="btn btn-sm btn-warning" href="index.php?action=edit&nim=<?= urlencode($r['nim']) ?>">Edit</a>

                <!-- Tombol Delete → form POST untuk aman + token CSRF -->
                <form class="d-inline" action="index.php?action=delete" method="POST" onsubmit="return confirm('Hapus data ini?')">
                  <input type="hidden" name="csrf" value="<?= csrf_token() ?>"><!-- Token CSRF -->
                  <input type="hidden" name="nim" value="<?= htmlspecialchars($r['nim']) ?>"><!-- Kirim NIM -->
                  <button class="btn btn-sm btn-danger" type="submit">Delete</button><!-- Submit delete -->
                </form>
              </td>
            </tr>
          <?php endforeach; endif; ?><!-- Akhir loop -->
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
