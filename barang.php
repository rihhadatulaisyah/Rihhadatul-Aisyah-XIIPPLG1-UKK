<?php
include 'db.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$sql = "SELECT barang.*, kategori.nama_kategori 
        FROM barang 
        LEFT JOIN kategori ON barang.kategori_id = kategori.id
        ORDER BY barang.id_barang DESC
        LIMIT $start, $limit";
$result = $conn->query($sql);

$totalRows = $conn->query("SELECT COUNT(*) AS total FROM barang")->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$maxPagesToShow = 5;
$startPage = max(1, $page - floor($maxPagesToShow / 2));
$endPage = min($totalPages, $startPage + $maxPagesToShow - 1);
$startPage = max(1, $endPage - $maxPagesToShow + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

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

    .card-header{
        background:#1f2937!important;
        color:white!important;
        font-weight:500;
    }

    .table thead{
        background:#1f2937!important;
        color:white;
    }

    .table-warning{background:#fff3cd!important;}

    table tbody tr:hover{
        background:#e5e7eb;
        transition:0.2s;
    }
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

    <h3 class="mb-4">Data Barang Gudang</h3>

    <div class="d-flex justify-content-between mb-3">
        <a href="tambah_barang.php" class="btn btn-dark">+ Tambah Barang</a>
       
    </div>

    <div class="card shadow-sm">
        <div class="card-header">Daftar Barang</div>

        <div class="card-body p-0">
            <table class="table table-bordered text-center mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Tanggal Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="<?= ($row['jumlah_stok'] < 10) ? 'table-warning' : '' ?>">
                            <td><?= $row['id_barang'] ?></td>
                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                            <td><?= $row['jumlah_stok'] ?></td>
                            <td>Rp <?= number_format($row['harga_barang'], 0, ',', '.') ?></td>
                            <td><?= date("d-m-Y", strtotime($row['tanggal_masuk'])) ?></td>

                            <td>
                                <a href="edit_barang.php?id=<?= $row['id_barang'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapus_barang.php?id=<?= $row['id_barang'] ?>"
                                   onclick="return confirm('Hapus barang ini?')"
                                   class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-muted py-3">Data tidak ada</td>
                    </tr>
                <?php endif ?>
                </tbody>

            </table>
        </div>
    </div>

    <nav class="mt-3">
        <ul class="pagination">
            <?php if($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?>">&laquo;</a>
                </li>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= ($i == $page ? 'active' : '') ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

</div>
</body>
</html>
