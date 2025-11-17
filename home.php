<?php
include "db.php"; 

$kat = $conn->query("SELECT * FROM kategori");

$filKat = isset($_GET['kategori']) ? $_GET['kategori'] : "";
$cari = isset($_GET['search']) ? $_GET['search'] : "";

$q = "SELECT barang.*, kategori.nama_kategori 
      FROM barang 
      LEFT JOIN kategori ON barang.kategori_id = kategori.id
      WHERE 1=1";

if($filKat != ""){
    $q .= " AND kategori.nama_kategori='".$filKat."'";
}

if($cari != ""){
    $q .= " AND barang.nama_barang LIKE '%".$cari."%'";
}

$q .= " ORDER BY barang.id_barang DESC";

$dt = $conn->query($q);

$tot = $conn->query("SELECT COUNT(*) as jml FROM barang")->fetch_assoc()['jml'];
$katTot = $conn->query("SELECT COUNT(DISTINCT kategori_id) as jml FROM barang")->fetch_assoc()['jml'];
$tipis = $conn->query("SELECT COUNT(*) as jml FROM barang WHERE jumlah_stok<10")->fetch_assoc()['jml'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Inventaris Gudang</title>
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
    .stat-box{background:white;padding:18px;border-radius:6px;border:1px solid #ddd;text-align:center;}
    .table-warning{background:#fff3cd!important;}
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
    <h3>Dashboard Inventaris Gudang</h3>

    <!-- statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <small>Total Barang</small>
                <h2><?= $tot ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box">
                <small>Total Kategori</small>
                <h2><?= $katTot ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-box">
                <small>Stok Menipis</small>
                <h2><?= $tipis ?></h2>
            </div>
        </div>
    </div>

    <form class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" value="<?= htmlspecialchars($cari) ?>" class="form-control" placeholder="Cari barang...">
        </div>

        <div class="col-md-4">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php 
                while($k = $kat->fetch_assoc()){
                    $sel = ($filKat == $k['nama_kategori']) ? "selected" : "";
                    echo "<option $sel value='".htmlspecialchars($k['nama_kategori'])."'>".htmlspecialchars($k['nama_kategori'])."</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-2"><button class="btn btn-dark w-100">Cari</button></div>
        <div class="col-md-2"><a class="btn btn-secondary w-100" href="home.php">Reset</a></div>
    </form>

    <div class="card">
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($dt && $dt->num_rows > 0){
                        while($b = $dt->fetch_assoc()){
                            $warn = ($b['jumlah_stok'] < 10) ? "table-warning" : "";
                            ?>
                            <tr class="<?= $warn ?>">
                                <td><?= $b['id_barang'] ?></td>
                                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                                <td><?= htmlspecialchars($b['nama_kategori'] ?? '-') ?></td>
                                <td><?= $b['jumlah_stok'] ?></td>
                                <td>Rp <?= number_format($b['harga_barang'],0,',','.') ?></td>
                                <td><?= date("d-m-Y", strtotime($b['tanggal_masuk'])) ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-muted py-3'>Data tidak ada</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
