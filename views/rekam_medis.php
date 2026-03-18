<?php 
require_once '../config/database.php'; 
$no_rm = isset($_GET['no_rm']) ? $_GET['no_rm'] : '';
$query_pasien = mysqli_query($koneksi, "SELECT * FROM pasien WHERE no_rm = '$no_rm'");
$data = mysqli_fetch_assoc($query_pasien);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeriksaan: <?= $data['nama_lengkap'] ?? 'Pasien'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="p-8">

    <div class="max-w-5xl mx-auto">
        
        <div class="mb-6">
            <a href="pendaftaran.php" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-all font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100 mb-8 flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Pasien</p>
                <h1 class="text-2xl font-bold text-gray-900"><?= $data['nama_lengkap'] ?? 'Pilih Pasien Terlebih Dahulu'; ?></h1>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">No. Rekam Medis</p>
                <p class="text-2xl font-mono font-bold text-blue-600"><?= $data['no_rm'] ?? 'RM-XXXXXXXX'; ?></p>
            </div>
        </div>

        <form action="../proses/rekam_medis_aksi.php" method="POST" class="space-y-8">
            <input type="hidden" name="no_rm" value="<?= $data['no_rm'] ?? ''; ?>">

            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> Vital Signs
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Berat Badan (kg)</label>
                        <input type="number" name="berat_badan" placeholder="70" class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Tinggi Badan (cm)</label>
                        <input type="number" name="tinggi_badan" placeholder="170" class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Tekanan Darah</label>
                        <input type="text" name="tensi" placeholder="120/80" class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Suhu (°C)</label>
                        <input type="text" name="suhu" placeholder="36.5" class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Nadi (x/mnt)</label>
                        <input type="number" name="nadi" placeholder="80" class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> SOAP
                </h3>
                <div class="space-y-6 text-sm">
                    <div>
                        <label class="font-bold text-gray-600 mb-2 block">Subjektif (Keluhan Pasien)</label>
                        <textarea name="subjektif" rows="2" placeholder="Keluhan yang disampaikan pasien..." class="w-full bg-gray-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="font-bold text-gray-600 mb-2 block">Objektif (Hasil Pemeriksaan)</label>
                        <textarea name="objektif" rows="2" placeholder="Hasil pemeriksaan fisik..." class="w-full bg-gray-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="font-bold text-gray-600 mb-2 block">Asesmen (Diagnosis)</label>
                        <textarea name="assessment" rows="2" placeholder="Diagnosis kondisi pasien..." class="w-full bg-gray-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="font-bold text-gray-600 mb-2 block">Planning (Rencana Tindakan)</label>
                        <textarea name="plan" rows="2" placeholder="Rencana pengobatan atau tindakan..." class="w-full bg-gray-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> Tindakan dan Biaya
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Tindakan Medis</label>
                        <input type="text" name="tindakan_manual" placeholder="Contoh: Cabut Gigi Geraham" class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 mb-2 block uppercase">Total Biaya (Rp)</label>
                        <input type="number" name="total_biaya" placeholder="Masukkan nominal (Tanpa titik/koma)..." class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-blue-600">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#0F172A] hover:bg-black text-white font-bold py-5 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim ke Kasir
            </button>
        </form>
    </div>

</body>
</html>