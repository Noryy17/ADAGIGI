<?php
// 1. Hubungkan ke database
require_once '../config/database.php';

// 2. Tangkap data identitas pasien & waktu
$no_rm          = $_POST['no_rm'];
$waktu_periksa  = date('Y-m-d H:i:s');

// 3. Tangkap data Vital Signs (Angka)
$berat_badan    = $_POST['berat_badan'] ?: 0;
$tinggi_badan   = $_POST['tinggi_badan'] ?: 0;
$tensi          = mysqli_real_escape_string($koneksi, $_POST['tensi']);
$suhu           = mysqli_real_escape_string($koneksi, $_POST['suhu']);
$nadi           = $_POST['nadi'] ?: 0;

// 4. Tangkap data SOAP (Teks Deskripsi)
// Gunakan mysqli_real_escape_string agar tanda petik (') tidak bikin SQL Error
$subjektif      = mysqli_real_escape_string($koneksi, $_POST['subjektif']);
$objektif       = mysqli_real_escape_string($koneksi, $_POST['objektif']);
$assessment     = mysqli_real_escape_string($koneksi, $_POST['assessment']);
$plan           = mysqli_real_escape_string($koneksi, $_POST['plan']);

// 5. Tangkap data Biaya Manual
$tindakan_nama  = mysqli_real_escape_string($koneksi, $_POST['tindakan_manual']);
$total_biaya    = $_POST['total_biaya'] ?: 0;

// --- PROSES SIMPAN TAHAP 1: Tabel rekam_medis ---
$query_rm = "INSERT INTO rekam_medis (
                no_rm, waktu_periksa, berat_badan, tinggi_badan, 
                tensi, suhu, nadi, subjektif, objektif, assessment, plan
            ) VALUES (
                '$no_rm', '$waktu_periksa', '$berat_badan', '$tinggi_badan', 
                '$tensi', '$suhu', '$nadi', '$subjektif', '$objektif', '$assessment', '$plan'
            )";

if (mysqli_query($koneksi, $query_rm)) {
    
    // Ambil ID periksa yang barusan dibuat otomatis oleh database
    $id_periksa_baru = mysqli_insert_id($koneksi);

    // --- PROSES SIMPAN TAHAP 2: Tabel transaksi (Untuk Antrean Kasir) ---
    // Simpan id_periksa sebagai penghubung dan total biayanya
    $query_transaksi = "INSERT INTO transaksi (id_periksa, total_biaya) 
                        VALUES ('$id_periksa_baru', '$total_biaya')";
    
    mysqli_query($koneksi, $query_transaksi);

    // 6. Selesai! Beri notif dan lempar balik ke halaman pendaftaran
    echo "<script>
            alert('Sukses! Data Medis & Tagihan Pasien Berhasil Disimpan.');
            window.location.href = '../views/pendaftaran.php';
          </script>";
} else {
    // Jika ada yang salah, tampilkan error-nya biar gampang benerinnya
    echo "Waduh, ada error di database: " . mysqli_error($koneksi);
}
?>