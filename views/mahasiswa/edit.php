<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Mahasiswa</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
  <h1 class="mb-3">Edit Mahasiswa</h1>

  <!-- Form Update: POST ke action=update -->
  <form method="POST" action="index.php?action=update" class="row g-3">
    <!-- Token CSRF -->
    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">

    <!-- NIM (kunci) ditampilkan disabled + dikirim via hidden -->
    <div class="col-md-4">
      <label class="form-label">NIM (tidak bisa diubah)</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($row['nim']) ?>" disabled>
      <input type="hidden" name="nim" value="<?= htmlspecialchars($row['nim']) ?>">
    </div>

    <!-- Nama -->
    <div class="col-md-8">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control <?= isset($errors['nama'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($row['nama']) ?>">
      <?php if (!empty($errors['nama'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['nama']) ?></div><?php endif; ?>
    </div>

    <!-- Alamat -->
    <div class="col-12">
      <label class="form-label">Alamat</label>
      <textarea name="alamat" class="form-control <?= isset($errors['alamat'])?'is-invalid':'' ?>" rows="3"><?= htmlspecialchars($row['alamat']) ?></textarea>
      <?php if (!empty($errors['alamat'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['alamat']) ?></div><?php endif; ?>
    </div>

    <!-- Jurusan -->
    <div class="col-md-6">
      <label class="form-label">Jurusan</label>
      <input type="text" name="jurusan" class="form-control <?= isset($errors['jurusan'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($row['jurusan']) ?>">
      <?php if (!empty($errors['jurusan'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['jurusan']) ?></div><?php endif; ?>
    </div>

    <!-- Telepon -->
    <div class="col-md-6">
      <label class="form-label">Nomor Telepon</label>
      <input type="text" name="telepon" class="form-control <?= isset($errors['telepon'])?'is-invalid':'' ?>" value="<?= htmlspecialchars($row['telepon']) ?>">
      <?php if (!empty($errors['telepon'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errors['telepon']) ?></div><?php endif; ?>
    </div>

    <!-- Aksi -->
    <div class="col-12">
      <a href="index.php?action=list" class="btn btn-secondary">Batal</a><!-- Kembali -->
      <button class="btn btn-primary" type="submit">Update</button><!-- Submit update -->
    </div>
  </form>
</div>
</body>
</html>
