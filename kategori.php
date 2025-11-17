<?php
include 'db.php';

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama_kategori']);
    if ($nama == '') {
        $error = "Nama kategori tidak boleh kosong!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM kategori WHERE nama_kategori=?");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        $cek = $stmt->get_result();

        if ($cek->num_rows > 0) {
            $error = "Kategori sudah ada!";
        } else {
            $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
            $stmt->bind_param("s", $nama);
            $stmt->execute();
            $success = "Kategori berhasil ditambahkan!";
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = "Kategori berhasil dihapus!";
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = trim($_POST['nama_kategori']);

    if ($nama == '') {
        $error = "Nama kategori tidak boleh kosong!";
    } else {
        $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=? WHERE id=?");
        $stmt->bind_param("si", $nama, $id);
        $stmt->execute();
        $success = "Kategori berhasil diperbarui!";
    }
}

$result = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kategori</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f3f4f6; }

        .sidebar {
            width: 230px;
            background: #1f2937;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            margin-bottom: 8px;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #374151;
            color: white;
        }
        .content {
            margin-left: 250px;
            padding: 25px;
        }
        .card-header {
            background: #1f2937 !important;
            color: white !important;
        }
        .table thead {
            background: #1f2937 !important;
            color: white;
        }
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

    <h3 class="mb-4">Kelola Kategori Barang</h3>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">Tambah Kategori</div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Masukkan nama kategori...">
                </div>
                <div class="col-md-4 d-grid">
                    <button type="submit" name="tambah" class="btn btn-dark">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Daftar Kategori</div>
        <div class="card-body p-0">
            <table class="table table-bordered text-center mb-0">
                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th>Nama Kategori</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>

                                <a href="?hapus=<?= $row['id'] ?>"
                                   onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                   class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title">Edit Kategori</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                            <label>Nama Kategori</label>
                                            <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($row['nama_kategori']) ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update" class="btn btn-success">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-muted">Belum ada kategori barang.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
