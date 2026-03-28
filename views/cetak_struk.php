<?php
require_once '../config/database.php';

$id_periksa = isset($_GET['id_periksa']) ? $_GET['id_periksa'] : '';

if (!$id_periksa) {
    die("Data transaksi tidak ditemukan!");
}

$query = mysqli_query($koneksi, "SELECT t.*, rm.no_rm, rm.nama_dokter, rm.waktu_periksa, rm.plan as tindakan, p.nama_lengkap 
                                 FROM transaksi t 
                                 JOIN rekam_medis rm ON t.id_periksa = rm.id_periksa 
                                 JOIN pasien p ON rm.no_rm = p.no_rm 
                                 WHERE t.id_periksa = '$id_periksa'");
$data = mysqli_fetch_assoc($query);

// --- CARA BARU (ANTI ERROR INTELEPHENSE) ---
// Pakai strpos() dan substr() supaya murni text (string), bukan array
$tindakan_full = isset($data['tindakan']) ? (string)$data['tindakan'] : "";
$rincian_kasir = $tindakan_full; // Default tampilkan semua
$pemisah = "[Rincian Biaya Kasir]";
$posisi = strpos($tindakan_full, $pemisah);

if ($posisi !== false) {
    // Ambil text setelah kata "[Rincian Biaya Kasir]"
    $rincian_kasir = trim(substr($tindakan_full, $posisi + strlen($pemisah)));
}
// -------------------------------------------
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - <?= $data['nama_lengkap']; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; color: #000; background: #fff; margin: 0; padding: 20px; font-size: 12px; }
        .struk-container { max-width: 300px; margin: 0 auto; border: 1px dashed #000; padding: 15px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top;}
        @media print { .no-print { display: none; } body { padding: 0; } .struk-container { border: none; padding: 0; max-width: 100%; } }
    </style>
</head>
<body onload="window.print()">
    <div class="struk-container">
        
        <div class="text-center">
            <h2 style="margin:0; font-size: 16px;">Praktik dokter gigi anak dan dokter gigi</h2>
            <p style="margin:5px 0;">Dulang Asri RT 15, Wonokerso, Kedawung, Sragen, 57292<br>WA : 08112959191</p>
        </div>
        
        <div class="divider"></div>
        
        <table>
            <tr>
                <td>Waktu</td>
                <td class="text-right"><?= date('d/m/Y H:i', strtotime($data['waktu_bayar'] ?? date('Y-m-d H:i:s'))); ?></td>
            </tr>
            <tr>
                <td>No. RM</td>
                <td class="text-right font-bold"><?= $data['no_rm']; ?></td>
            </tr>
            <tr>
                <td>Pasien</td>
                <td class="text-right"><?= $data['nama_lengkap']; ?></td>
            </tr>
            <tr>
                <td>Dokter</td>
                <td class="text-right"><?= $data['nama_dokter']; ?></td>
            </tr>
        </table>

        <div class="divider"></div>

        <p class="font-bold" style="margin:5px 0;">Rincian Tindakan / Obat:</p>
        <div style="margin:0 0 10px 0; line-height: 1.6; white-space: pre-wrap; font-size: 11px;"><?= $rincian_kasir; ?></div>

        <div class="divider"></div>

        <table>
            <tr>
                <td class="font-bold" style="font-size:14px;">TOTAL BIAYA</td>
                <td class="text-right font-bold" style="font-size:14px;">Rp <?= number_format($data['total_biaya'], 0, ',', '.'); ?></td>
            </tr>
        </table>

        <div class="divider"></div>
        
        <div class="text-center" style="margin-top: 10px;">
            <p style="margin: 0;">Terima Kasih Atas Kunjungan Anda</p>
            <p style="margin: 5px 0 0 0;">Semoga Lekas Sembuh</p>
        </div>
        
        <div class="text-center no-print" style="margin-top: 30px;">
            <button onclick="window.location.href='kasir.php'" style="padding: 10px 20px; cursor:pointer; background: #2563EB; color: white; border: none; border-radius: 5px; font-weight: bold;">← Kembali ke Kasir</button>
        </div>

    </div>
</body>
</html>