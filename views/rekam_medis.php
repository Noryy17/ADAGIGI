<?php 
require_once '../config/database.php'; 

// --- AUTO-PATCH DATABASE (Biar alergi ga kosong lagi tanpa perlu buka phpMyAdmin) ---
$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM pasien LIKE 'alergi'");
if(mysqli_num_rows($cek_kolom) == 0) {
    mysqli_query($koneksi, "ALTER TABLE pasien ADD alergi VARCHAR(255) DEFAULT '-' AFTER tanggal_lahir");
    mysqli_query($koneksi, "ALTER TABLE pasien ADD penyakit_sistemik VARCHAR(255) DEFAULT '-' AFTER alergi");
}
$cek_dokter = mysqli_query($koneksi, "SHOW COLUMNS FROM rekam_medis LIKE 'nama_dokter'");
if(mysqli_num_rows($cek_dokter) == 0) {
    mysqli_query($koneksi, "ALTER TABLE rekam_medis ADD nama_dokter VARCHAR(100) DEFAULT '-' AFTER no_rm");
}
// ------------------------------------------------------------------------------------

$no_rm = isset($_GET['no_rm']) ? $_GET['no_rm'] : '';
$data = null;
$riwayat_query = null;

if ($no_rm != '') {
    $query_pasien = mysqli_query($koneksi, "SELECT * FROM pasien WHERE no_rm = '$no_rm'");
    $data = mysqli_fetch_assoc($query_pasien);
    
    // Tarik riwayat kunjungan sebelumnya
    $riwayat_query = mysqli_query($koneksi, "SELECT * FROM rekam_medis WHERE no_rm = '$no_rm' ORDER BY waktu_periksa DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis Pemeriksaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        input::-webkit-calendar-picker-indicator { opacity: 0; }
    </style>
</head>
<body class="p-8">

    <div class="max-w-6xl mx-auto">
        
        <div class="mb-6">
            <?php if ($no_rm == '' || !$data): ?>
                <a href="../index.php" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-all font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Kembali ke Dashboard Utama
                </a>
            <?php else: ?>
                <a href="pendaftaran.php?tab=lama" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-all font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg> Kembali ke Antrean
                </a>
            <?php endif; ?>
        </div>

        <?php if ($no_rm == '' || !$data): ?>
            <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Pilih Pasien untuk Diperiksa</h2>
                </div>
                <div class="relative mb-6">
                    <input type="text" id="liveSearchRekamMedis" placeholder="Ketik Nama atau Nomor RM di sini..." class="w-full p-5 pl-12 bg-gray-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-lg font-medium">
                </div>
                <div id="hasilCariTable" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            </div>
            <script>
                window.onload = function() { loadDataRekamMedis(''); };
                document.getElementById('liveSearchRekamMedis').addEventListener('keyup', function() { loadDataRekamMedis(this.value); });
                function loadDataRekamMedis(keyword) {
                    fetch('../proses/cari_pasien.php?keyword=' + keyword).then(res => res.text()).then(data => { document.getElementById('hasilCariTable').innerHTML = data; });
                }
            </script>

        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pasien Saat Ini</p>
                        <h1 class="text-xl font-bold text-gray-900 mb-1"><?= $data['nama_lengkap']; ?></h1>
                        <p class="text-sm font-mono font-bold text-blue-600 mb-4"><?= $data['no_rm']; ?></p>
                        
                        <?php if($data['alergi'] && $data['alergi'] != '-'): ?>
                            <div class="bg-red-50 text-red-600 border border-red-200 p-3 rounded-xl text-xs font-bold mb-2">
                                ⚠️ Alergi: <?= $data['alergi']; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($data['penyakit_sistemik'] && $data['penyakit_sistemik'] != '-'): ?>
                            <div class="bg-orange-50 text-orange-600 border border-orange-200 p-3 rounded-xl text-xs font-bold">
                                ⚠️ Sistemik: <?= $data['penyakit_sistemik']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="bg-[#EEF2FF] p-6 rounded-[24px] border border-[#E0E7FF]">
                        <h3 class="font-bold text-blue-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Riwayat Kunjungan
                        </h3>
                        <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                            <?php 
                            $jml_kunjungan = mysqli_num_rows($riwayat_query);
                            if($jml_kunjungan > 0): 
                                echo '<p class="text-xs font-bold text-blue-600 mb-3">Total Kunjungan: ' . $jml_kunjungan . ' kali</p>';
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
                                            <span class="font-bold text-gray-800 block mb-1">P (Rencana & Tindakan):</span> 
                                            <span class="whitespace-pre-line"><?= $rw['plan']; ?></span>
                                        </div>
                                    </div>
                                </details>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <p class="text-sm text-gray-500 italic">Pasien baru, belum ada riwayat kunjungan.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <form action="../proses/rekam_medis_aksi.php" method="POST" class="space-y-6">
                        <input type="hidden" name="no_rm" value="<?= $data['no_rm']; ?>">

                        <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="text-[11px] font-bold text-gray-500 mb-2 block uppercase">Dokter Pemeriksa *</label>
                                    <input type="text" name="nama_dokter" required list="daftar_dokter" placeholder="Ketik atau pilih nama..." class="w-full bg-gray-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-700">
                                    <datalist id="daftar_dokter">
                                        <option value="Drg. Nendika">
                                        <option value="Drg. Andhika">
                                        <option value="Drg. Sarah">
                                        <option value="Drg. Budi">
                                    </datalist>
                                    <p class="text-[10px] text-gray-400 mt-2 italic">*Bebas ketik nama dokter baru jika tidak ada di pilihan.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[11px] font-bold text-red-500 mb-2 block uppercase">Alergi Obat/Makanan</label>
                                    <input type="text" name="alergi" value="<?= $data['alergi'] == '-' ? '' : $data['alergi']; ?>" placeholder="Misal: Amoxicillin" class="w-full bg-red-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-red-400 outline-none">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold text-orange-500 mb-2 block uppercase">Penyakit Sistemik</label>
                                    <input type="text" name="penyakit_sistemik" value="<?= $data['penyakit_sistemik'] == '-' ? '' : $data['penyakit_sistemik']; ?>" placeholder="Misal: Diabetes, Hipertensi" class="w-full bg-orange-50 p-4 rounded-xl border-none focus:ring-2 focus:ring-orange-400 outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-6 flex items-center">
                                <span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> Pemeriksaan Fisik & SOAP
                            </h3>
                            <div class="grid grid-cols-3 md:grid-cols-5 gap-4 mb-6">
                                <input type="number" name="berat_badan" placeholder="BB (kg)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm">
                                <input type="number" name="tinggi_badan" placeholder="TB (cm)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm">
                                <input type="text" name="tensi" placeholder="Tensi (120/80)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm">
                                <input type="text" name="suhu" placeholder="Suhu (°C)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm">
                                <input type="number" name="nadi" placeholder="Nadi (x/mnt)" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none text-sm">
                            </div>
                            
                            <div class="space-y-4">
                                <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Subjektif (Keluhan)</label><textarea name="subjektif" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Objektif (Hasil Pemeriksaan)</label><textarea name="objektif" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Asesmen (Diagnosis)</label><textarea name="assessment" rows="2" class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                                <div><label class="text-[11px] font-bold text-gray-500 mb-1 block uppercase">Planning (Rencana Tindakan Medis)</label><textarea name="plan" rows="2" placeholder="Tuliskan resep obat atau rencana perawatan di sini..." class="w-full bg-gray-50 p-3 rounded-xl border-none outline-none focus:ring-2 focus:ring-blue-500"></textarea></div>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-[24px] shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="font-bold text-gray-900 flex items-center">
                                    <span class="w-2 h-5 bg-blue-600 rounded-full mr-3"></span> Rincian Tindakan & Obat (Biaya Kasir)
                                </h3>
                                <button type="button" onclick="tambahBaris()" class="bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-all">
                                    + Tambah Tindakan
                                </button>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-[10px] text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                            <th class="pb-3 w-5/12">Nama Tindakan / Obat</th>
                                            <th class="pb-3 w-3/12">Harga Satuan (Rp)</th>
                                            <th class="pb-3 w-2/12">Jumlah</th>
                                            <th class="pb-3 w-2/12 text-right">Subtotal</th>
                                            <th class="pb-3 w-1/12"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-tindakan">
                                        <tr class="border-b border-gray-50">
                                            <td class="py-3 pr-2"><input type="text" name="tindakan_nama[]" placeholder="Misal: Tambal Gigi" required class="w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm"></td>
                                            <td class="py-3 pr-2"><input type="number" name="tindakan_harga[]" placeholder="100000" min="0" required class="harga-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none text-sm" onkeyup="hitungTotal()"></td>
                                            <td class="py-3 pr-2"><input type="number" name="tindakan_qty[]" value="1" min="1" required class="qty-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none text-sm" onchange="hitungTotal()" onkeyup="hitungTotal()"></td>
                                            <td class="py-3 text-right font-bold text-gray-700 text-sm subtotal-teks">Rp 0</td>
                                            <td class="py-3 text-center"></td>
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
                            Simpan Rekam Medis & Kirim ke Kasir
                        </button>
                    </form>
                </div>
            </div>

            <script>
                function tambahBaris() {
                    const tbody = document.getElementById('tbody-tindakan');
                    const tr = document.createElement('tr');
                    tr.className = "border-b border-gray-50";
                    tr.innerHTML = `
                        <td class="py-3 pr-2"><input type="text" name="tindakan_nama[]" placeholder="Nama Tindakan/Obat" required class="w-full bg-gray-50 p-3 rounded-lg border-none outline-none focus:ring-2 focus:ring-blue-500 text-sm"></td>
                        <td class="py-3 pr-2"><input type="number" name="tindakan_harga[]" placeholder="Harga" min="0" required class="harga-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none text-sm" onkeyup="hitungTotal()"></td>
                        <td class="py-3 pr-2"><input type="number" name="tindakan_qty[]" value="1" min="1" required class="qty-input w-full bg-gray-50 p-3 rounded-lg border-none outline-none text-sm" onchange="hitungTotal()" onkeyup="hitungTotal()"></td>
                        <td class="py-3 text-right font-bold text-gray-700 text-sm subtotal-teks">Rp 0</td>
                        <td class="py-3 pl-2 text-center"><button type="button" onclick="hapusBaris(this)" class="text-red-400 hover:text-red-600 text-xl font-bold">&times;</button></td>
                    `;
                    tbody.appendChild(tr);
                }

                function hapusBaris(btn) {
                    btn.closest('tr').remove();
                    hitungTotal();
                }

                function hitungTotal() {
                    let grandTotal = 0;
                    const rows = document.querySelectorAll('#tbody-tindakan tr');
                    rows.forEach(row => {
                        const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                        const subtotal = harga * qty;
                        row.querySelector('.subtotal-teks').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                        grandTotal += subtotal;
                    });
                    document.getElementById('grand-total-teks').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
                }
            </script>
        <?php endif; ?>
    </div>
</body>
</html>