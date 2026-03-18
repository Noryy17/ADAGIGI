<?php 
require_once '../config/database.php'; 

// Ambil data pasien yang terpilih dari URL (jika ada)
$no_rm_terpilih = isset($_GET['no_rm']) ? $_GET['no_rm'] : '';
$data_pasien_terpilih = null;

if ($no_rm_terpilih) {
    $q_detail = mysqli_query($koneksi, "SELECT * FROM pasien WHERE no_rm = '$no_rm_terpilih'");
    $data_pasien_terpilih = mysqli_fetch_assoc($q_detail);
}

// Ambil daftar semua pasien untuk sidebar
$query_pasien = mysqli_query($koneksi, "SELECT * FROM pasien ORDER BY no_rm DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - Klinik App</title>
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
                        'card-white': '#FFFFFF',
                        'accent-blue': '#2563EB'
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
                Kembali ke Dashboard
            </a>
        </div>

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900">Rekam Medis Dokter</h1>
            <p class="text-gray-500">Pilih pasien untuk memulai pemeriksaan medis</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100">
                    <h2 class="font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-2 h-6 bg-blue-600 rounded-full mr-3"></span>
                        Pasien Terdaftar
                    </h2>
                    
                    <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                        <?php while($row = mysqli_fetch_assoc($query_pasien)): ?>
                        <a href="?no_rm=<?= $row['no_rm']; ?>" 
                           class="block p-4 rounded-xl border transition-all <?= ($no_rm_terpilih == $row['no_rm']) ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-100 hover:border-blue-200' ?>">
                            <p class="text-xs font-bold text-blue-600 mb-1"><?= $row['no_rm']; ?></p>
                            <h3 class="font-bold text-gray-900"><?= $row['nama_lengkap']; ?></h3>
                            <p class="text-[11px] text-gray-500 mt-1"><?= $row['no_hp']; ?></p>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <?php if ($data_pasien_terpilih): ?>
                    <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-8 border-b border-gray-100 pb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900"><?= $data_pasien_terpilih['nama_lengkap']; ?></h2>
                                <p class="text-gray-500 text-sm">NIK: <?= $data_pasien_terpilih['nik'] ?: '-'; ?> | Lahir: <?= date('d M Y', strtotime($data_pasien_terpilih['tanggal_lahir'])); ?></p>
                            </div>
                            <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-bold text-xs"><?= $data_pasien_terpilih['no_rm']; ?></span>
                        </div>

                        <form action="../proses/rekam_medis_aksi.php" method="POST" class="space-y-6">
                            <input type="hidden" name="no_rm" value="<?= $data_pasien_terpilih['no_rm']; ?>">
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Keluhan Utama *</label>
                                <textarea name="keluhan" required rows="3" class="w-full p-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Apa yang dirasakan pasien?"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Diagnosis Dokter *</label>
                                    <textarea name="diagnosis" required rows="4" class="w-full p-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Hasil pemeriksaan dokter..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tindakan & Resep *</label>
                                    <textarea name="tindakan" required rows="4" class="w-full p-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Obat atau tindakan yang diberikan..."></textarea>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Simpan Rekam Medis & Kirim ke Kasir
                                </button>
                            </div>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100 flex flex-col items-center justify-center min-h-[500px] text-center">
                        <div class="bg-blue-50 p-6 rounded-full mb-6">
                            <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Belum Ada Pasien Terpilih</h2>
                        <p class="text-gray-500 mt-2 max-w-sm">Klik salah satu nama pasien di daftar sebelah kiri untuk mulai menginput data rekam medis.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>