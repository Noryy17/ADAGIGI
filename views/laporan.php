<?php 
require_once '../config/database.php'; 

// 1. Ambil Tanggal Hari Ini
$hari_ini = date('Y-m-d');

// 2. Hitung Total Pasien Hari Ini (Berdasarkan Rekam Medis)
$q_pasien = mysqli_query($koneksi, "SELECT COUNT(*) as total_pasien FROM rekam_medis WHERE DATE(waktu_periksa) = '$hari_ini'");
$res_pasien = mysqli_fetch_assoc($q_pasien);
$total_pasien = $res_pasien['total_pasien'];

// 3. Hitung Total Pendapatan Hari Ini (Hanya yang statusnya 'Lunas')
$q_duit = mysqli_query($koneksi, "SELECT SUM(total_biaya) as total_pendapatan FROM transaksi t 
                                  JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                  WHERE DATE(rm.waktu_periksa) = '$hari_ini' AND rm.status_tagihan = 'Lunas'");
$res_duit = mysqli_fetch_assoc($q_duit);
$total_pendapatan = $res_duit['total_pendapatan'] ?: 0;

// 4. Ambil Daftar Transaksi Hari Ini untuk Tabel
$query_tabel = mysqli_query($koneksi, "SELECT t.total_biaya, rm.no_rm, rm.waktu_periksa, p.nama_lengkap, rm.status_tagihan 
                                        FROM transaksi t 
                                        JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                        JOIN pasien p ON rm.no_rm = p.no_rm 
                                        WHERE DATE(rm.waktu_periksa) = '$hari_ini'
                                        ORDER BY rm.waktu_periksa DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - Klinik App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F4F6F9] text-gray-800 min-h-screen p-8">

    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <a href="../index.php" class="text-gray-500 hover:text-gray-900 font-medium flex items-center w-fit transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Harian</h1>
            <p class="text-gray-500">Ringkasan aktivitas klinik tanggal <?= date('d F Y', strtotime($hari_ini)); ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                <p class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-2">Total Pasien Hari Ini</p>
                <h3 class="text-4xl font-bold text-gray-900"><?= $total_pasien; ?> <span class="text-lg text-gray-400 font-medium">Orang</span></h3>
            </div>
            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                <p class="text-sm font-bold text-green-600 uppercase tracking-wider mb-2">Total Pendapatan (Lunas)</p>
                <h3 class="text-4xl font-bold text-gray-900">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-bold text-gray-900">Rincian Aktivitas Pasien</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-[11px] uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Nomor RM</th>
                            <th class="px-6 py-4">Nama Pasien</th>
                            <th class="px-6 py-4">Biaya</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php while($row = mysqli_fetch_assoc($query_tabel)): ?>
                        <tr class="hover:bg-gray-50 transition-colors text-sm">
                            <td class="px-6 py-4 text-gray-500"><?= date('H:i', strtotime($row['waktu_periksa'])); ?></td>
                            <td class="px-6 py-4 font-mono text-blue-600 font-bold"><?= $row['no_rm']; ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?= $row['nama_lengkap']; ?></td>
                            <td class="px-6 py-4 font-bold text-gray-700">Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $row['status_tagihan'] == 'Lunas' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' ?>">
                                    <?= $row['status_tagihan']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if(mysqli_num_rows($query_tabel) == 0): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic text-sm">Belum ada aktivitas pasien hari ini.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>