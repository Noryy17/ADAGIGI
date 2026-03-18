<?php
require_once '../config/database.php';

// Tangkap huruf yang diketik oleh user (dikirim via JavaScript nanti)
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if ($keyword != '') {
    // Bersihkan input agar aman dari error tanda petik
    $keyword = mysqli_real_escape_string($koneksi, $keyword);
    
    // Cari pasien yang nama atau nomor RM-nya mirip dengan ketikan (Maksimal 5 orang aja biar ga penuh)
    $query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE nama_lengkap LIKE '%$keyword%' OR no_rm LIKE '%$keyword%' LIMIT 5");

    if (mysqli_num_rows($query) > 0) {
        // Kalau ketemu, cetak kotak daftarnya
        while ($row = mysqli_fetch_assoc($query)) {
            echo '<div class="p-4 border-b border-gray-100 hover:bg-blue-50 transition-colors group">';
            echo '  <p class="font-bold text-gray-900 text-sm">' . $row['nama_lengkap'] . '</p>';
            echo '  <p class="text-[11px] text-gray-500 mt-1">RM: <span class="font-bold text-blue-600 font-mono">' . $row['no_rm'] . '</span> | NIK: ' . ($row['nik'] ?: '-') . '</p>';
            echo '  <a href="rekam_medis.php?no_rm=' . $row['no_rm'] . '" class="inline-block mt-3 text-[10px] bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-md font-bold transition-all">Teruskan ke Rekam Medis →</a>';
            echo '</div>';
        }
    } else {
        // Kalau nama tidak ada di database
        echo '<div class="p-8 text-center">';
        echo '  <p class="text-gray-900 font-bold text-sm mb-1">Pasien Tidak Ditemukan</p>';
        echo '  <p class="text-xs text-gray-500">Silakan daftarkan sebagai pasien baru di form sebelah kiri.</p>';
        echo '</div>';
    }
}
?>