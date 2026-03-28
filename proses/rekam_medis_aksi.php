<?php
require_once '../config/database.php';

$no_rm          = $_POST['no_rm'];
$id_antrean     = isset($_POST['id_antrean']) ? $_POST['id_antrean'] : '';
$waktu_periksa  = date('Y-m-d H:i:s');
$nama_dokter    = mysqli_real_escape_string($koneksi, $_POST['nama_dokter']);

$alergi = mysqli_real_escape_string($koneksi, $_POST['alergi'] ?: '-');
$penyakit = mysqli_real_escape_string($koneksi, $_POST['penyakit_sistemik'] ?: '-');
mysqli_query($koneksi, "UPDATE pasien SET alergi = '$alergi', penyakit_sistemik = '$penyakit' WHERE no_rm = '$no_rm'");

$berat_badan    = $_POST['berat_badan'] ?: 0;
$tinggi_badan   = $_POST['tinggi_badan'] ?: 0;
$tensi          = mysqli_real_escape_string($koneksi, $_POST['tensi']);
$suhu           = mysqli_real_escape_string($koneksi, $_POST['suhu']);
$nadi           = $_POST['nadi'] ?: 0;

$subjektif      = mysqli_real_escape_string($koneksi, $_POST['subjektif']);
$objektif       = mysqli_real_escape_string($koneksi, $_POST['objektif']);
$assessment     = mysqli_real_escape_string($koneksi, $_POST['assessment']);
$plan_awal      = mysqli_real_escape_string($koneksi, $_POST['plan']); 

$arr_nama  = $_POST['tindakan_nama'];
$arr_harga = $_POST['tindakan_harga'];
$arr_qty   = $_POST['tindakan_qty'];

$rincian_tindakan = "";
$grand_total = 0;

for ($i = 0; $i < count($arr_nama); $i++) {
    $nama  = mysqli_real_escape_string($koneksi, $arr_nama[$i]);
    $harga = (int)$arr_harga[$i];
    $qty   = (int)$arr_qty[$i];
    $subtotal = $harga * $qty;
    
    if ($nama != '') {
        $rincian_tindakan .= "• $nama ($qty x Rp" . number_format($harga,0,',','.') . ") = Rp" . number_format($subtotal,0,',','.') . "\n";
        $grand_total += $subtotal;
    }
}

$plan_gabungan = $plan_awal . "\n\n[Rincian Biaya Kasir]\n" . $rincian_tindakan; 

$query_rm = "INSERT INTO rekam_medis (
                no_rm, nama_dokter, waktu_periksa, berat_badan, tinggi_badan, 
                tensi, suhu, nadi, subjektif, objektif, assessment, plan
            ) VALUES (
                '$no_rm', '$nama_dokter', '$waktu_periksa', '$berat_badan', '$tinggi_badan', 
                '$tensi', '$suhu', '$nadi', '$subjektif', '$objektif', '$assessment', '$plan_gabungan'
            )";

if (mysqli_query($koneksi, $query_rm)) {
    $id_periksa_baru = mysqli_insert_id($koneksi);
    
    // Simpan ke Transaksi
    $query_transaksi = "INSERT INTO transaksi (id_periksa, total_biaya) VALUES ('$id_periksa_baru', '$grand_total')";
    mysqli_query($koneksi, $query_transaksi);

    // HAPUS DARI ANTREAN
    if($id_antrean != ''){
        mysqli_query($koneksi, "UPDATE antrean SET status = 'Selesai' WHERE id = '$id_antrean'");
    }

    echo "<script>alert('Sukses! Rekam Medis Tersimpan. Silakan panggil pasien selanjutnya.'); window.location.href = '../views/rekam_medis.php';</script>";
} else {
    echo "Error Database: " . mysqli_error($koneksi);
}
?>