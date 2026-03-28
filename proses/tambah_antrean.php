<?php
require_once '../config/database.php';

$no_rm = $_GET['no_rm'];

// Auto buat tabel antrean jika belum ada
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS antrean (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_rm VARCHAR(50),
    waktu DATETIME,
    status ENUM('Menunggu', 'Selesai') DEFAULT 'Menunggu'
)");

// Masukkan pasien ke antrean
if (mysqli_query($koneksi, "INSERT INTO antrean (no_rm, waktu, status) VALUES ('$no_rm', NOW(), 'Menunggu')")) {
    echo "<script>
        alert('Berhasil! Pasien ditambahkan ke Antrean Dokter.');
        window.location.href = '../views/rekam_medis.php';
    </script>";
} else {
    echo "Gagal menambahkan ke antrean: " . mysqli_error($koneksi);
}
?>