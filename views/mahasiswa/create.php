<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Mahasiswa</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
  <h1 class="mb-3">Tambah Mahasiswa</h1>

  <!-- Form Create: method POST ke action=store -->
  <form method="POST" action="index.php?action=store" class="row g-3">
    <!-- Token CSRF wajib -->
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <!-- Field NIM -->
    <div class="col-md-4">
      <label class="form-label">NIM</label>
      <!-- Tambah is-invalid jika ada error nim -->
      <input type="text" name="nim" class="form-control <?= isset($errors['nim'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['nim']) ?>">
      <!-- Pesan error nim -->
      <?php if (!empty($errors['nim'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['nim']) ?></div><?php endif; ?>
    </div>

    <!-- Field Nama -->
    <div class="col-md-8">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control <?= isset($errors['nama'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['nama']) ?>">
      <?php if (!empty($errors['nama'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['nama']) ?></div><?php endif; ?>
    </div>

    <!-- Field Alamat -->
    <div class="col-12">
      <label class="form-label">Alamat</label>
      <textarea name="alamat" class="form-control <?= isset($errors['alamat'])?'is-invalid':'' ?>" rows="3"><?= htmlspecialchars($old['alamat']) ?></textarea>
      <?php if (!empty($errors['alamat'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['alamat']) ?></div><?php endif; ?>
    </div>

    <!-- Field Jurusan -->
    <div class="col-md-6">
      <label class="form-label">Jurusan</label>
      <input type="text" name="jurusan" class="form-control <?= isset($errors['jurusan'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['jurusan']) ?>">
      <?php if (!empty($errors['jurusan'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['jurusan']) ?></div><?php endif; ?>
    </div>

    <!-- Field Telepon -->
    <div class="col-md-6">
      <label class="form-label">Nomor Telepon</label>
      <input type="text" name="telepon" class="form-control <?= isset($errors['telepon'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($old['telepon']) ?>">
      <?php if (!empty($errors['telepon'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['telepon']) ?></div><?php endif; ?>
    </div>

    <!-- Aksi -->
    <div class="col-12">
      <a href="index.php?action=list" class="btn btn-secondary">Batal</a><!-- Kembali ke list -->
      <button class="btn btn-primary" type="submit">Simpan</button><!-- Submit create -->
    </div>
  </form>
</div>
</body>
</html>
