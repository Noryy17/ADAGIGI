<?php
// Memanggil koneksi database
require_once '../config/database.php';

// 1. Menangkap data yang dikirim dari form rekam_medis.php
$no_rm = $_POST['no_rm'];
$keluhan = mysqli_real_escape_string($koneksi, $_POST['keluhan']);
$diagnosis = mysqli_real_escape_string($koneksi, $_POST['diagnosis']);
$tindakan = mysqli_real_escape_string($koneksi, $_POST['tindakan']);

// 2. Memasukkan data ke tabel rekam_medis
// Status tagihan otomatis diset 'Pending' agar muncul di layar kasir
$query_rekam = "INSERT INTO rekam_medis (no_rm, keluhan, diagnosis, tindakan, status_tagihan) 
                VALUES ('$no_rm', '$keluhan', '$diagnosis', '$tindakan', 'Pending')";

if (mysqli_query($koneksi, $query_rekam)) {
    
    // 3. Mengambil ID periksa yang baru saja dibuat (Auto Increment ID)
    $id_periksa = mysqli_insert_id($koneksi);

    // 4. Membuat draft transaksi di tabel transaksi
    // Kita set biaya standar awal, misal Rp 50.000 (bisa diedit di kasir nanti)
    $biaya_standar = 50000;
    $query_transaksi = "INSERT INTO transaksi (id_periksa, total_biaya) 
                        VALUES ('$id_periksa', '$biaya_standar')";
    
    mysqli_query($koneksi, $query_transaksi);

    // Jika berhasil, munculkan notifikasi dan kembali ke halaman rekam medis
    echo "<script>
        alert('Data Pemeriksaan Berhasil Disimpan! Pasien telah diteruskan ke Kasir.');
        window.location.href = '../views/rekam_medis.php';
    </script>";

} else {
    // Jika gagal, tampilkan pesan error
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>