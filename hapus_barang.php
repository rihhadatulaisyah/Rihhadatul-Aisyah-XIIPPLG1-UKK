<?php
include 'db.php';

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $conn->query("DELETE FROM barang WHERE id_barang=$id");
}

header("Location: barang.php");
exit;
