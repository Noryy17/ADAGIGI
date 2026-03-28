<?php
require_once '../config/database.php';

$nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$tanggal_lahir = $_POST['tanggal_lahir'];
$no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
$nama_ortu = mysqli_real_escape_string($koneksi, $_POST['nama_ortu']);
$nik = mysqli_real_escape_string($koneksi, $_POST['nik']);

$cek_query = "SELECT no_rm FROM pasien WHERE (nik = '$nik' AND nik != '') OR (nama_lengkap = '$nama_lengkap' AND tanggal_lahir = '$tanggal_lahir')";
$cek_hasil = mysqli_query($koneksi, $cek_query);

if (mysqli_num_rows($cek_hasil) > 0) {
    $data_lama = mysqli_fetch_assoc($cek_hasil);
    $rm_lama = $data_lama['no_rm'];
    echo "<script>
        alert('Data pasien sudah ada! Terdaftar sebagai Pasien Lama dgn RM: $rm_lama. Silakan cari di tab Pasien Lama lalu masukkan antrean.');
        window.location.href = '../views/pendaftaran.php';
    </script>";
    exit; 
}

$tahun = date('Y'); 
$huruf_awal = strtoupper(substr($nama_lengkap, 0, 1)); 
$angka_huruf = ord($huruf_awal) - 64; 
$kode_awalan = str_pad($angka_huruf, 2, "0", STR_PAD_LEFT); 

$hitung_query = "SELECT COUNT(*) as total FROM pasien"; 
$hitung_hasil = mysqli_query($koneksi, $hitung_query);
$data_hitung = mysqli_fetch_assoc($hitung_hasil);
$urut_selanjutnya = $data_hitung['total'] + 1; 
$nomor_urut = str_pad($urut_selanjutnya, 5, "0", STR_PAD_LEFT); 

$no_rm = "RM" . $tahun . $jenis_kelamin . $kode_awalan . $nomor_urut;

$insert_query = "INSERT INTO pasien (no_rm, nama_lengkap, jenis_kelamin, alamat, tanggal_lahir, no_hp, nama_ortu, nik) 
                 VALUES ('$no_rm', '$nama_lengkap', '$jenis_kelamin', '$alamat', '$tanggal_lahir', '$no_hp', '$nama_ortu', '$nik')";

if (mysqli_query($koneksi, $insert_query)) {
    
    // AUTO PATCH & MASUKKAN KE ANTREAN
    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS antrean (
        id INT AUTO_INCREMENT PRIMARY KEY, no_rm VARCHAR(50), waktu DATETIME, status ENUM('Menunggu', 'Selesai') DEFAULT 'Menunggu'
    )");
    mysqli_query($koneksi, "INSERT INTO antrean (no_rm, waktu, status) VALUES ('$no_rm', NOW(), 'Menunggu')");

    echo "<script>
        alert('Berhasil! Pasien otomatis masuk Antrean Dokter dengan RM: $no_rm');
        window.location.href = '../views/rekam_medis.php';
    </script>";
} else {
    echo "Terjadi kesalahan sistem: " . mysqli_error($koneksi);
}
?>