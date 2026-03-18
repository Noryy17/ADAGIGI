<?php 
require_once '../config/database.php'; 

// 1. Ambil data transaksi yang terpilih dari URL
$id_periksa_terpilih = isset($_GET['id_periksa']) ? $_GET['id_periksa'] : '';
$data_billing = null;

if ($id_periksa_terpilih) {
    // JOIN 3 Tabel: transaksi, rekam_medis, dan pasien untuk ambil data lengkap
    $q_detail = mysqli_query($koneksi, "SELECT t.*, rm.no_rm, rm.waktu_periksa, p.nama_lengkap 
                                        FROM transaksi t 
                                        JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                        JOIN pasien p ON rm.no_rm = p.no_rm 
                                        WHERE t.id_periksa = '$id_periksa_terpilih'");
    $data_billing = mysqli_fetch_assoc($q_detail);
}

// 2. Ambil daftar pasien yang BELUM BAYAR (status_tagihan = 'Pending')
$query_antrean = mysqli_query($koneksi, "SELECT t.id_periksa, t.total_biaya, rm.no_rm, rm.waktu_periksa, p.nama_lengkap 
                                         FROM transaksi t 
                                         JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                         JOIN pasien p ON rm.no_rm = p.no_rm 
                                         WHERE rm.status_tagihan = 'Pending' 
                                         ORDER BY rm.waktu_periksa ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Kasir - Klinik App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-klinik': '#F4F6F9',
                        'purple-accent': '#8B5CF6'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bg-klinik text-gray-800 min-h-screen p-8">

    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="../index.php" class="text-gray-500 hover:text-gray-900 font-medium flex items-center w-fit transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Pembayaran Kasir</h1>
            <p class="text-gray-500">Proses transaksi pasien yang telah selesai diperiksa</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-bold text-gray-900">Menunggu Pembayaran</h2>
                        <span class="bg-purple-100 text-purple-600 text-xs font-bold px-3 py-1 rounded-full"><?= mysqli_num_rows($query_antrean); ?></span>
                    </div>
                    
                    <div class="space-y-3">
                        <?php while($row = mysqli_fetch_assoc($query_antrean)): ?>
                        <a href="?id_periksa=<?= $row['id_periksa']; ?>" 
                           class="block p-4 rounded-xl border transition-all <?= ($id_periksa_terpilih == $row['id_periksa']) ? 'bg-purple-50 border-purple-200' : 'bg-gray-50 border-gray-100 hover:border-purple-200' ?>">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-bold text-gray-900"><?= $row['nama_lengkap']; ?></h3>
                                <span class="text-[10px] font-bold text-purple-500 bg-purple-50 px-2 py-0.5 rounded border border-purple-100">Pending</span>
                            </div>
                            <p class="text-[11px] text-gray-400">RM: <?= $row['no_rm']; ?></p>
                            <div class="flex justify-between items-end mt-4">
                                <p class="font-bold text-purple-600">Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></p>
                                <p class="text-[10px] text-gray-400"><?= date('H:i', strtotime($row['waktu_periksa'])); ?> WIB</p>
                            </div>
                        </a>
                        <?php endwhile; ?>

                        <?php if(mysqli_num_rows($query_antrean) == 0): ?>
                            <p class="text-center text-gray-400 text-sm py-10 italic">Belum ada antrean bayar.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <?php if ($data_billing): ?>
                    <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-8 pb-4 border-b border-gray-100">Ringkasan Tagihan</h2>
                        
                        <form action="../proses/kasir_aksi.php" method="POST" class="space-y-6">
                            <input type="hidden" name="id_periksa" value="<?= $data_billing['id_periksa']; ?>">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Nama Pasien</p>
                                    <p class="text-lg font-bold text-gray-800"><?= $data_billing['nama_lengkap']; ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Nomor RM</p>
                                    <p class="text-lg font-bold text-gray-800"><?= $data_billing['no_rm']; ?></p>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Total Biaya (Rp)</label>
                                <input type="number" name="total_biaya" value="<?= $data_billing['total_biaya']; ?>" 
                                    class="w-full text-3xl font-bold text-purple-600 bg-transparent border-none outline-none focus:ring-0" 
                                    placeholder="0">
                                <p class="text-[11px] text-gray-400 mt-2 italic">*Biaya ini adalah estimasi awal dari dokter, silakan sesuaikan jika ada tambahan obat.</p>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-purple-100 transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Konfirmasi Pembayaran & Cetak Struk
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100 flex flex-col items-center justify-center min-h-[450px] text-center">
                        <div class="bg-purple-50 p-6 rounded-full mb-6 text-purple-200">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Pilih Pembayaran</h2>
                        <p class="text-gray-500 mt-2 max-w-sm">Daftar tagihan pasien yang muncul di sini adalah pasien yang sudah selesai diperiksa oleh dokter.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>