<?php
require_once '../config/database.php';

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if ($keyword != '') {
    $keyword = mysqli_real_escape_string($koneksi, $keyword);
    $query = mysqli_query($koneksi, "SELECT * FROM pasien WHERE nama_lengkap LIKE '%$keyword%' OR no_rm LIKE '%$keyword%' LIMIT 5");

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            echo '<div class="p-4 border-b border-gray-100 hover:bg-blue-50 transition-colors group">';
            echo '  <p class="font-bold text-gray-900 text-sm">' . $row['nama_lengkap'] . '</p>';
            echo '  <p class="text-[11px] text-gray-500 mt-1">RM: <span class="font-bold text-blue-600 font-mono">' . $row['no_rm'] . '</span> | NIK: ' . ($row['nik'] ?: '-') . '</p>';
            
            echo '  <div class="mt-3 flex gap-2">';
            // Tombol 1: Langsung buka Rekam Medis
            echo '      <a href="../views/rekam_medis.php?no_rm=' . $row['no_rm'] . '" class="text-[10px] bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-md font-bold transition-all text-center flex-1">Buka RM</a>';
            
            // Tombol 2: Masukkan ke Antrean
            echo '      <a href="../proses/tambah_antrean.php?no_rm=' . $row['no_rm'] . '" class="text-[10px] bg-green-100 text-green-700 hover:bg-green-600 hover:text-white px-3 py-1.5 rounded-md font-bold transition-all text-center flex-1">+ Antrean</a>';
            echo '  </div>';
            
            echo '</div>';
        }
    } else {
        echo '<div class="p-8 text-center">';
        echo '  <p class="text-gray-900 font-bold text-sm mb-1">Pasien Tidak Ditemukan</p>';
        echo '  <p class="text-xs text-gray-500">Silakan daftarkan sebagai pasien baru.</p>';
        echo '</div>';
    }
}
?>