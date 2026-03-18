<?php
// Memanggil jembatan database
require_once '../config/database.php';

// 1. Tangkap data dari form kasir.php
$id_periksa = $_POST['id_periksa'];
$total_biaya = $_POST['total_biaya'];
$waktu_bayar = date('Y-m-d H:i:s'); // Mengambil waktu saat ini secara presisi

// 2. Update data di tabel transaksi
// Kita perbarui total biaya (siapa tahu ada perubahan) dan catat waktu bayarnya
$query_transaksi = "UPDATE transaksi SET 
                    total_biaya = '$total_biaya', 
                    waktu_bayar = '$waktu_bayar' 
                    WHERE id_periksa = '$id_periksa'";

if (mysqli_query($koneksi, $query_transaksi)) {
    
    // 3. Update status di tabel rekam_medis
    // Hubungannya: Agar pasien ini tidak muncul lagi di antrean kasir (karena sudah lunas)
    $query_lunas = "UPDATE rekam_medis SET status_tagihan = 'Lunas' WHERE id_periksa = '$id_periksa'";
    mysqli_query($koneksi, $query_lunas);

    // INI BAGIAN YANG BERUBAH:
    // Jika sukses, lempar ke halaman cetak struk membawa ID-nya
    echo "<script>
        alert('Pembayaran Berhasil! Mengalihkan ke halaman cetak struk...');
        window.location.href = '../views/cetak_struk.php?id_periksa=$id_periksa';
    </script>";

} else {
    // Jika ada error di database
    echo "Terjadi kesalahan sistem: " . mysqli_error($koneksi);
}
?>