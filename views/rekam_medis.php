<?php 
require_once '../config/database.php'; 

// Auto Patch Database
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS antrean (
    id INT AUTO_INCREMENT PRIMARY KEY, no_rm VARCHAR(50), waktu DATETIME, status ENUM('Menunggu', 'Selesai') DEFAULT 'Menunggu'
)");
$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM pasien LIKE 'alergi'");
if(mysqli_num_rows($cek_kolom) == 0) {
    mysqli_query($koneksi, "ALTER TABLE pasien ADD alergi VARCHAR(255) DEFAULT '-' AFTER tanggal_lahir");
    mysqli_query($koneksi, "ALTER TABLE pasien ADD penyakit_sistemik VARCHAR(255) DEFAULT '-' AFTER alergi");
}
$cek_dokter = mysqli_query($koneksi, "SHOW COLUMNS FROM rekam_medis LIKE 'nama_dokter'");
if(mysqli_num_rows($cek_dokter) == 0) {
    mysqli_query($koneksi, "ALTER TABLE rekam_medis ADD nama_dokter VARCHAR(100) DEFAULT '-' AFTER no_rm");
}

$id_antrean = isset($_GET['id_antrean']) ? $_GET['id_antrean'] : '';
$no_rm = isset($_GET['no_rm']) ? $_GET['no_rm'] : '';

$data = null;
$riwayat_query = null;

if ($no_rm != '') {
    $query_pasien = mysqli_query($koneksi, "SELECT * FROM pasien WHERE no_rm = '$no_rm'");
    $data = mysqli_fetch_assoc($query_pasien);
    
    // Tarik riwayat kunjungan sebelumnya
    $riwayat_query = mysqli_query($koneksi, "SELECT * FROM rekam_medis WHERE no_rm = '$no_rm' ORDER BY waktu_periksa DESC");
}

// Ambil Daftar Antrean Menunggu
$q_antrean = mysqli_query($koneksi, "SELECT a.id as id_antrean, a.no_rm, p.nama_lengkap FROM antrean a JOIN pasien p ON a.no_rm = p.no_rm WHERE a.status = 'Menunggu' ORDER BY a.waktu ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis Dokter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="p-8">

    <div class="max-w-[1400px] mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <a href="../index.php" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-all font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Kembali ke Dashboard Utama
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari Pasien Cepat
                    </h2>
                    <input type="text" id="liveSearch" placeholder="Ketik Nama atau RM..." class="w-full bg-gray-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <div id="hasilCari" class="mt-3 hidden border border-gray-100 rounded-xl overflow-hidden max-h-[300px] overflow-y-auto"></div>
                </div>

                <div class="bg-white rounded-[24px] shadow-sm border border-gray-100 p-6 sticky top-8">
                    <h2 class="font-bold text-gray-900 mb-4 flex items-center justify-between">
                        <span>Antrean Menunggu</span>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs"><?= mysqli_num_rows($q_antrean); ?></span>
                    </h2>
                    
                    <div class="space-y-3 max-h-[50vh] overflow-y-auto pr-2">
                        <?php while($antre = mysqli_fetch_assoc($q_antrean)): ?>
                        <a href="?id_antrean=<?= $antre['id_antrean']; ?>&no_rm=<?= $antre['no_rm']; ?>" 
                           class="block p-4 rounded-xl border transition-all <?= ($id_antrean == $antre['id_antrean']) ? 'bg-blue-50 border-blue-200 shadow-inner' : 'bg-gray-50 border-gray-100 hover:border-blue-200' ?>">
                            <p class="font-bold text-gray-900 text-sm mb-1"><?= $antre['nama_lengkap']; ?></p>
                            <p class="text-[11px] text-gray-500 font-mono">RM: <?= $antre['no_rm']; ?></p>
                        </a>
                        <?php endwhile; ?>

                        <?php if(mysqli_num_rows($q_antrean) == 0): ?>
                            <div class="text-center py-8">
                                <p class="text-gray-400 text-sm italic">Belum ada antrean.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <?php if (!$data): ?>
                    <div class="bg-white p-12 rounded-[24px] shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center min-h-[500px]">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <h2 class="text-xl font-bold text-gray-900">Pilih Pasien untuk Diperiksa</h2>
                        <p class="text-gray-500 mt-2">Klik pasien pada antrean di sebelah kiri, atau gunakan kolom pencarian.</p>
                    </div>
                <?php else: ?>
                    
                    <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 flex justify-between items-center mb-6">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pasien Saat Ini</p>
                            <h1 class="text-2xl font-bold text-gray-900"><?= $data['nama_lengkap']; ?></h1>
                            <p class="text-sm font-mono font-bold text-blue-600 mt-1"><?= $data['no_rm']; ?></p>
                        </div>
                        <div class="text-right">
                            <?php if($data['alergi'] && $data['alergi'] != '-'): ?>
                                <span class="inline-block bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-xs font-bold mb-1">Alergi: <?= $data['alergi']; ?></span><br>
                            <?php endif; ?>
                            <?php if($data['penyakit_sistemik'] && $data['penyakit_sistemik'] != '-'): ?>
                                <span class="inline-block bg-orange-50 text-orange-600 border border-orange-200 px-3 py-1 rounded-full text-xs font-bold">Sistemik: <?= $data['penyakit_sistemik']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <div class="lg:col-span-1">
                            <div class="bg-[#EEF2FF] p-6 rounded-[24px] border border-[#E0E7FF] sticky top-8">
                                <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Riwayat Kunjungan
                                </h3>
                                <div class="space-y-3 max-h-[600px] overflow-y-auto pr-2">
                                    <?php 
                                    $jml_kunjungan = mysqli_num_rows($riwayat_query);
                                    if($jml_kunjungan > 0): 
                                        echo '<p class="text-xs font-bold text-blue-600 mb-3">Total: ' . $jml_kunjungan . ' Kunjungan</p>';
                                        while($rw = mysqli_fetch_assoc($riwayat_query)): 
                                    ?>
                                        <details class="bg-white p-3 rounded-xl shadow-sm text-xs border border-blue-100 cursor-pointer group">
                                            <summary class="font-bold text-gray-800 outline-none hover:text-blue-600 transition-colors flex justify-between items-center">
                                                <span><?= date('d M Y', strtotime($rw['waktu_periksa'])); ?></span>
                                                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </summary>
                                            <div class="mt-3 space-y-2 text-gray-600 border-t border-gray-100 pt-3">
                                                <p class="mb-2"><span class="font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded">Drg:</span> <?= $rw['nama_dokter']; ?></p>
                                                <p><span class="font-bold text-gray-800">S:</span> <?= $rw['subjektif']; ?></p>
                                                <p><span class="font-bold text-gray-800">O:</span> <?= $rw['objektif']; ?></p>
                                                <p><span class="font-bold text-gray-800">A:</span> <span class="text-blue-600 font-semibold"><?= $rw['assessment']; ?></span></p>
                                                <div class="bg-gray-50 p-2 rounded-lg mt-1">
                                                    <span class="font-bold text-gray-800 block mb-1">P (Rencana/Tindakan):</span> 
                                                    <span class="whitespace-pre-line"><?= $rw['plan']; ?></span>
                                                </div>
                                            </div>
                                        </details>
                                    <?php 
                                        endwhile; 
                                    else: 
                                    ?>
                                        <p class="text-sm text-gray-500 italic bg-white p-4 rounded-xl border border-blue-50">Belum ada riwayat. Ini adalah kunjungan pertama pasien.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <form action="../proses/rekam_medis_aksi.php" method="POST" class="space-y-6">
                                <input type="hidden" name="no_rm" value="<?= $data['no_rm']; ?>">
                                <input type="hidden" name="id_antrean" value="<?= $id_antrean; ?>">

                                <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="md:col-span-2">
                                            <label class="text-[11px] font-bold text-gray-500 mb-2 block uppercase">Dokter Pemeriksa *</label>
                                            <select name="nama_dokter" required class="w-full bg-gray-50 p-4 rounded-xl border-none outline-none font-bold text-gray-700">
                                                <option value="" disabled selected>-- Pilih Dokter --</option>
                                                <option value="drg. Nendika Dyah Ayu. Sp.KGA">drg. Nendika Dyah Ayu. Sp.KGA</option>
                                                <option value="drg. Hanifah Arya Lutfita">drg. Hanifah Arya Lutfita</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-red-500 mb-2 block uppercase">Update Alergi Obat/Makanan</label>
                                            <input type="text" name="alergi" value="<?= $data['alergi'] == '-' ? '' : $data['alergi']; ?>" placeholder="Misal: Amoxicillin" class="w-full bg-red-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-red-400">
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-orange-500 mb-2 block uppercase">Update Penyakit Sistemik</label>
                                            <input type="text" name="penyakit_sistemik" value="<?= $data['penyakit_sistemik'] == '-' ? '' : $data['penyakit_sistemik']; ?>" placeholder="Misal: Diabetes" class="w-full bg-orange-50 p-4 rounded-xl border-none outline-none focus:ring-2 focus:ring-orange-400">
                                        </div>
                                    </div>
                                    
                                    <h3 class="font-bold text-gray-900 mb-4 mt-8 flex items-center"><span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> Pemeriksaan Fisik & SOAP</h3>
                                    <div class="grid grid-cols-3 md:grid-cols-5 gap-4 mb-6">
                                        <input type="number" name="berat_badan" placeholder="BB (kg)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-blue-500">
                                        <input type="number" name="tinggi_badan" placeholder="TB (cm)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-blue-500">
                                        <input type="text" name="tensi" placeholder="Tensi (120/80)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-blue-500">
                                        <input type="text" name="suhu" placeholder="Suhu (°C)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-blue-500">
                                        <input type="number" name="nadi" placeholder="Nadi (x/mnt)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Subjektif (Keluhan)</label><textarea name="subjektif" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                        <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Objektif (Hasil Pemeriksaan)</label><textarea name="objektif" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                        <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Asesmen (Diagnosis)</label><textarea name="assessment" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                        <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Planning (Rencana Tindakan Medis)</label><textarea name="plan" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                    </div>
                                </div>

                                <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                                    <div class="flex justify-between items-center mb-6">
                                        <h3 class="font-bold text-gray-900 flex items-center"><span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> Rincian Tindakan (Biaya Kasir)</h3>
                                        <button type="button" onclick="tambahBaris()" class="bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-all">+ Tambah</button>
                                    </div>
                                    
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left border-collapse">
                                            <thead><tr class="text-[10px] text-gray-400 uppercase tracking-widest border-b border-gray-100"><th class="pb-3 w-5/12">Nama Tindakan/Obat</th><th class="pb-3 w-3/12">Harga (Rp)</th><th class="pb-3 w-2/12">Qty</th><th class="pb-3 w-2/12 text-right">Subtotal</th><th></th></tr></thead>
                                            <tbody id="tbody-tindakan">
                                                <tr class="border-b border-gray-50">
                                                    <td class="py-3 pr-2"><input type="text" name="tindakan_nama[]" placeholder="Isi nama tindakan..." required class="w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm"></td>
                                                    <td class="py-3 pr-2"><input type="number" name="tindakan_harga[]" placeholder="Harga" required class="harga-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm" onkeyup="hitungTotal()"></td>
                                                    <td class="py-3 pr-2"><input type="number" name="tindakan_qty[]" value="1" required class="qty-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm" onchange="hitungTotal()" onkeyup="hitungTotal()"></td>
                                                    <td class="py-3 text-right font-bold text-gray-700 text-sm subtotal-teks">Rp 0</td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-6 flex justify-between items-center bg-gray-50 p-5 rounded-xl border border-gray-100">
                                        <span class="font-bold text-gray-500 uppercase text-xs tracking-widest">Grand Total</span>
                                        <span id="grand-total-teks" class="font-bold text-2xl text-blue-600">Rp 0</span>
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-[#0F172A] hover:bg-black text-white font-bold py-5 rounded-2xl shadow-xl transition-all tracking-widest text-sm uppercase">
                                    Simpan & Selesai Diperiksa
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // SCRIPT PENCARIAN LIVE
        document.getElementById('liveSearch').addEventListener('keyup', function() {
            let keyword = this.value;
            let hasilCari = document.getElementById('hasilCari');
            
            if (keyword.length > 0) {
                hasilCari.classList.remove('hidden');
                fetch('../proses/cari_pasien.php?keyword=' + keyword)
                    .then(res => res.text())
                    .then(data => { hasilCari.innerHTML = data; });
            } else {
                hasilCari.classList.add('hidden');
                hasilCari.innerHTML = '';
            }
        });

        // SCRIPT TABEL KASIR
        function tambahBaris() {
            const tr = document.createElement('tr');
            tr.className = "border-b border-gray-50";
            tr.innerHTML = `
                <td class="py-3 pr-2"><input type="text" name="tindakan_nama[]" required class="w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm"></td>
                <td class="py-3 pr-2"><input type="number" name="tindakan_harga[]" required class="harga-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm" onkeyup="hitungTotal()"></td>
                <td class="py-3 pr-2"><input type="number" name="tindakan_qty[]" value="1" required class="qty-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm" onchange="hitungTotal()" onkeyup="hitungTotal()"></td>
                <td class="py-3 text-right font-bold text-gray-700 text-sm subtotal-teks">Rp 0</td>
                <td class="py-3 pl-2"><button type="button" onclick="hapusBaris(this)" class="text-red-400 hover:text-red-600 font-bold text-xl">&times;</button></td>
            `;
            document.getElementById('tbody-tindakan').appendChild(tr);
        }

        function hapusBaris(btn) { 
            btn.closest('tr').remove(); 
            hitungTotal(); 
        }

        function hitungTotal() {
            let grandTotal = 0;
            document.querySelectorAll('#tbody-tindakan tr').forEach(row => {
                const h = parseFloat(row.querySelector('.harga-input').value) || 0;
                const q = parseFloat(row.querySelector('.qty-input').value) || 0;
                const subtotal = h * q;
                row.querySelector('.subtotal-teks').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                grandTotal += subtotal;
            });
            let labelTotal = document.getElementById('grand-total-teks');
            if(labelTotal) labelTotal.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }
    </script>
</body>
</html>