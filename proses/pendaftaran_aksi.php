<?php
// Panggil jembatan database
require_once '../config/database.php';

// 1. TANGKAP DATA DARI FORM (POST)
$nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
$jenis_kelamin = $_POST['jenis_kelamin'];
$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
$tanggal_lahir = $_POST['tanggal_lahir'];
$no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
$nama_ortu = mysqli_real_escape_string($koneksi, $_POST['nama_ortu']);
$nik = mysqli_real_escape_string($koneksi, $_POST['nik']);

// 2. LOGIKA VALIDASI DUPLIKAT (Sesuai Kebutuhanmu)
// Cek apakah NIK atau (Nama + Tanggal Lahir) sudah ada di database
$cek_query = "SELECT no_rm FROM pasien WHERE (nik = '$nik' AND nik != '') OR (nama_lengkap = '$nama_lengkap' AND tanggal_lahir = '$tanggal_lahir')";
$cek_hasil = mysqli_query($koneksi, $cek_query);

if (mysqli_num_rows($cek_hasil) > 0) {
    // Jika data ketemu, ambil nomor RM lamanya
    $data_lama = mysqli_fetch_assoc($cek_hasil);
    $rm_lama = $data_lama['no_rm'];
    
    // Munculkan Alert dan kembalikan ke halaman form
    echo "<script>
        alert('Data pasien sudah ada! Pasien ini terdaftar sebagai Pasien Lama dengan Nomor RM: $rm_lama. Silakan gunakan fitur pencarian pasien.');
        window.location.href = '../views/pendaftaran.php';
    </script>";
    exit; // Menghentikan eksekusi kode di bawahnya agar data duplikat tidak tersimpan
}

// 3. LOGIKA PEMBUATAN NOMOR RM OTOMATIS
// A. Tahun (4 digit)
$tahun = date('Y'); 

// B. Kode Kelamin (01/02) -> Sudah didapat otomatis dari dropdown form

// C. Kode Awalan Nama (A=01, B=02, dst)
$huruf_awal = strtoupper(substr($nama_lengkap, 0, 1)); // Ambil 1 huruf paling depan, paksa jadi huruf besar
$angka_huruf = ord($huruf_awal) - 64; // Konversi kode ASCII (Huruf 'A' itu nilainya 65. Jadi 65 - 64 = 1)
$kode_awalan = str_pad($angka_huruf, 2, "0", STR_PAD_LEFT); // Paksa jadi 2 digit. Jika 1, berubah jadi '01'.

// D. Nomor Urut 5 Digit
$hitung_query = "SELECT COUNT(*) as total FROM pasien"; // Hitung ada berapa jumlah baris di tabel pasien
$hitung_hasil = mysqli_query($koneksi, $hitung_query);
$data_hitung = mysqli_fetch_assoc($hitung_hasil);
$urut_selanjutnya = $data_hitung['total'] + 1; // Jumlah pasien saat ini ditambah 1
$nomor_urut = str_pad($urut_selanjutnya, 5, "0", STR_PAD_LEFT); // Paksa jadi 5 digit (cth: 00001)

// E. Susun Nomor RM Final
$no_rm = "RM" . $tahun . $jenis_kelamin . $kode_awalan . $nomor_urut;

// 4. SIMPAN KE DATABASE MY_SQL
$insert_query = "INSERT INTO pasien (no_rm, nama_lengkap, jenis_kelamin, alamat, tanggal_lahir, no_hp, nama_ortu, nik) 
                 VALUES ('$no_rm', '$nama_lengkap', '$jenis_kelamin', '$alamat', '$tanggal_lahir', '$no_hp', '$nama_ortu', '$nik')";

if (mysqli_query($koneksi, $insert_query)) {
    echo "<script>
        alert('Berhasil! Pasien didaftarkan dengan Nomor RM: $no_rm');
        window.location.href = '../views/pendaftaran.php';
    </script>";
} else {
    echo "Terjadi kesalahan sistem: " . mysqli_error($koneksi);
}
?>