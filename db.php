<?php 
$host="localhost";
$user="root";
$password=" ";
$db="aisyah_xiipplg1_inventaris";

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect("localhost", "root", "", "aisyah_xiipplg1_inventaris");


  if(!$conn) {
        $error_message = mysqli_connect_error();
        die('Terjadi Kesalahan Saat Menghubungkan Tabel: 
            <strong>'. $error_message .'</strong>');
  }
  ?>