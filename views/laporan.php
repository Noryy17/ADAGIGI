<?php 
require_once '../config/database.php'; 

// Jika klik Download Excel
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Laporan_Klinik.xls");
    $is_export = true;
} else {
    $is_export = false;
}

// Filter Logika
$tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
$dokter = isset($_GET['dokter']) ? $_GET['dokter'] : '';

$filter_query = "DATE(rm.waktu_periksa) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
if ($dokter != '') {
    $filter_query .= " AND rm.nama_dokter = '$dokter'";
}

$q_pasien = mysqli_query($koneksi, "SELECT COUNT(*) as total_pasien FROM rekam_medis rm WHERE $filter_query");
$res_pasien = mysqli_fetch_assoc($q_pasien);
$total_pasien = $res_pasien['total_pasien'];

$q_duit = mysqli_query($koneksi, "SELECT SUM(t.total_biaya) as total_pendapatan FROM transaksi t 
                                  JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                  WHERE $filter_query AND rm.status_tagihan = 'Lunas'");
$res_duit = mysqli_fetch_assoc($q_duit);
$total_pendapatan = $res_duit['total_pendapatan'] ?: 0;

$query_tabel = mysqli_query($koneksi, "SELECT t.total_biaya, rm.no_rm, rm.waktu_periksa, rm.nama_dokter, p.nama_lengkap, rm.status_tagihan 
                                        FROM transaksi t 
                                        JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                        JOIN pasien p ON rm.no_rm = p.no_rm 
                                        WHERE $filter_query ORDER BY rm.waktu_periksa DESC");
?>

<?php if($is_export): ?>
    <table border="1">
        <tr><th colspan="6">Laporan Klinik (<?= $tgl_awal ?> s/d <?= $tgl_akhir ?>)</th></tr>
        <tr><th>Waktu</th><th>No RM</th><th>Pasien</th><th>Dokter</th><th>Biaya</th><th>Status</th></tr>
        <?php while($row = mysqli_fetch_assoc($query_tabel)): ?>
            <tr>
                <td><?= $row['waktu_periksa']; ?></td><td><?= $row['no_rm']; ?></td>
                <td><?= $row['nama_lengkap']; ?></td><td><?= $row['nama_dokter']; ?></td>
                <td><?= $row['total_biaya']; ?></td><td><?= $row['status_tagihan']; ?></td>
            </tr>
        <?php endwhile; ?>
        <tr><td colspan="4"><b>TOTAL PENDAPATAN (LUNAS)</b></td><td colspan="2"><b><?= $total_pendapatan; ?></b></td></tr>
    </table>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Keuangan - Klinik App</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'); body { font-family: 'Inter', sans-serif; }</style>
    </head>
    <body class="bg-[#F4F6F9] text-gray-800 p-8">
        <div class="max-w-6xl mx-auto">
            <a href="../index.php" class="text-gray-500 font-medium flex items-center mb-6"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Kembali</a>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Laporan Klinik</h1>

            <div class="bg-white p-6 rounded-[24px] shadow-sm mb-8 border border-gray-100">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div><label class="block text-xs font-bold text-gray-500 mb-1">Dari Tanggal</label><input type="date" name="tgl_awal" value="<?= $tgl_awal ?>" class="bg-gray-50 p-3 rounded-lg outline-none"></div>
                    <div><label class="block text-xs font-bold text-gray-500 mb-1">Sampai Tanggal</label><input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>" class="bg-gray-50 p-3 rounded-lg outline-none"></div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Dokter</label>
                        <select name="dokter" class="bg-gray-50 p-3 rounded-lg outline-none">
                            <option value="">Semua Dokter</option>
                            <option value="drg. Nendika Dyah Ayu. Sp.KGA" <?= $dokter == 'drg. Nendika Dyah Ayu. Sp.KGA' ? 'selected' : '' ?>>drg. Nendika</option>
                            <option value="drg. Hanifah Arya Lutfita" <?= $dokter == 'drg. Hanifah Arya Lutfita' ? 'selected' : '' ?>>drg. Hanifah</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold">Filter Data</button>
                    <a href="?tgl_awal=<?= $tgl_awal ?>&tgl_akhir=<?= $tgl_akhir ?>&dokter=<?= $dokter ?>&export=excel" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold">Download Excel</a>
                </form>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                    <p class="text-sm font-bold text-blue-600 mb-2">Total Pasien Tertangani</p>
                    <h3 class="text-4xl font-bold text-gray-900"><?= $total_pasien; ?> Orang</h3>
                </div>
                <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                    <p class="text-sm font-bold text-green-600 mb-2">Total Pendapatan (Lunas)</p>
                    <h3 class="text-4xl font-bold text-gray-900">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                </div>
            </div>

            <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-[11px] uppercase font-bold">
                        <tr><th class="px-6 py-4">Waktu</th><th class="px-6 py-4">No RM</th><th class="px-6 py-4">Nama Pasien</th><th class="px-6 py-4">Dokter</th><th class="px-6 py-4">Biaya</th><th class="px-6 py-4">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php while($row = mysqli_fetch_assoc($query_tabel)): ?>
                        <tr class="text-sm">
                            <td class="px-6 py-4 text-gray-500"><?= $row['waktu_periksa']; ?></td>
                            <td class="px-6 py-4 font-mono font-bold"><?= $row['no_rm']; ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?= $row['nama_lengkap']; ?></td>
                            <td class="px-6 py-4 text-gray-600"><?= $row['nama_dokter']; ?></td>
                            <td class="px-6 py-4 font-bold">Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                            <td class="px-6 py-4 text-[10px] font-bold"><?= $row['status_tagihan']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    </html>
<?php endif; ?>