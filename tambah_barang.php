<?php
include "db.php";

$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama_barang']);
    $kategori_id = $_POST['kategori_id'];
    $stok = $_POST['jumlah_stok'];
    $harga = $_POST['harga_barang'];
    $tanggal = $_POST['tanggal_masuk'];

    if ($nama == "") {
        $error = "Nama barang tidak boleh kosong!";
    } elseif (!is_numeric($stok)) {
        $error = "Jumlah stok harus berupa angka!";
    } elseif ($stok < 0) {
        $error = "Jumlah stok tidak boleh minus!";
    } elseif (!is_numeric($harga)) {
        $error = "Harga barang harus berupa angka!";
    } elseif ($harga < 0) {
        $error = "Harga barang tidak boleh minus!";
    } elseif ($kategori_id == "") {
        $error = "Kategori harus dipilih!";
    } else {

        $query = "INSERT INTO barang (nama_barang, kategori_id, jumlah_stok, harga_barang, tanggal_masuk)
                  VALUES ('$nama', '$kategori_id', '$stok', '$harga', '$tanggal')";

        if ($conn->query($query)) {
            $success = "Barang berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan barang: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Barang</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<style>
    body { background: #f3f4f6; }

    .sidebar {
        width: 230px;
        background: #1f2937;
        height: 100vh;
        position: fixed;
        color: white;
        padding: 20px;
    }
    .sidebar h4 { margin-bottom: 20px; font-size: 20px; }
    .sidebar a {
        display: block; padding: 10px; color: #d1d5db;
        margin-bottom: 8px; border-radius: 5px; text-decoration: none;
    }
    .sidebar a:hover { background: #374151; color: white; }

    .content { margin-left: 250px; padding: 25px; }

    .card-header { background: #1f2937 !important; color: white !important; }
</style>
</head>
<body>

<div class="sidebar">
    <h4> Inventaris</h4>
    <a href="home.php">Dashboard</a>
    <a href="barang.php">Data Barang</a>
    <a href="kategori.php">Kategori</a>
</div>

<div class="content">

    <h3 class="mb-4">Tambah Barang Baru</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">Form Tambah Barang</div>
        <div class="card-body">

            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>

                        <?php while ($row = $kategori->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['nama_kategori'] ?></option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="jumlah_stok" class="form-control" min="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Harga Barang</label>
                    <input type="number" name="harga_barang" class="form-control" min="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" required>
                </div>

                <div class="col-md-12 text-end mt-3">
                    <button type="submit" name="simpan" class="btn btn-dark px-4">Simpan</button>
                    <a href="barang.php" class="btn btn-secondary px-4">Kembali</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
