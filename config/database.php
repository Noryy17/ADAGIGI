<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "klinik_app"; 

$koneksi = mysqli_connect($server, $username, $password, $database);

if (!$koneksi) {
    die("Aduh, koneksi gagal Dain: " . mysqli_connect_error());
} else {
    echo "Mantap! Koneksi ke database berhasil terhubung.";
}
?>