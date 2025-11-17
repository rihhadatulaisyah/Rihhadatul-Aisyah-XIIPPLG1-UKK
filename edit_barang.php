<?php
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: barang.php");
    exit;
}

$id = intval($_GET['id']);

$data = $conn->query("
    SELECT barang.*, kategori.nama_kategori 
    FROM barang 
    LEFT JOIN kategori ON barang.kategori_id = kategori.id
    WHERE barang.id_barang = $id
")->fetch_assoc();

if (!$data) {
    header("Location: barang.php");
    exit;
}

$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['update'])) {
    $nama = trim($_POST['nama_barang']);
    $kat = $_POST['kategori_id'];
    $stok = $_POST['jumlah_stok'];
    $harga = $_POST['harga_barang'];
    $tgl = $_POST['tanggal_masuk'];

    if ($nama == '') {
        $error = "Nama tidak boleh kosong!";
    } elseif (!is_numeric($stok) || $stok < 0) {
        $error = "Stok tidak valid!";
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error = "Harga tidak valid!";
    } else {
        $update = $conn->query("
            UPDATE barang SET 
            nama_barang='$nama',
            kategori_id='$kat',
            jumlah_stok='$stok',
            harga_barang='$harga',
            tanggal_masuk='$tgl'
            WHERE id_barang=$id
        ");

        if ($update) {
            $success = "Barang berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui data.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#f3f4f6;}
        .sidebar{
            width:230px;height:100vh;background:#1f2937;color:white;
            position:fixed;padding:20px;
        }
        .sidebar a{
            color:#d1d5db;text-decoration:none;display:block;
            margin-bottom:8px;padding:10px;border-radius:5px;
        }
        .sidebar a:hover{background:#374151;}
        .content{margin-left:250px;padding:25px;}
        .card-header{background:#1f2937!important;color:white!important;}
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Inventaris</h4>
    <a href="home.php">Dashboard</a>
    <a href="barang.php">Data Barang</a>
    <a href="kategori.php">Kategori</a>
</div>

<div class="content">
    <div class="card">
        <div class="card-header">Edit Barang</div>
        <div class="card-body">
            <?php if(isset($error)){ echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if(isset($success)){ echo "<div class='alert alert-success'>$success</div>"; } ?>

            <form method="POST" class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($row = $kategori->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"
                                <?= ($row['id'] == $data['kategori_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['nama_kategori']) ?>
                            </option>
                        <?php endwhile ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jumlah Stok</label>
                    <input type="number" name="jumlah_stok" min="0" class="form-control" value="<?= $data['jumlah_stok'] ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Harga Barang</label>
                    <input type="number" name="harga_barang" min="0" class="form-control" value="<?= $data['harga_barang'] ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control" value="<?= $data['tanggal_masuk'] ?>" required>
                </div>

                <div class="col-md-12 text-end">
                    <button type="submit" name="update" class="btn btn-dark">Simpan Perubahan</button>
                    <a href="barang.php" class="btn btn-secondary">Kembali</a>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>
