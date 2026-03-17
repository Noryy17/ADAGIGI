<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-klinik': '#EEF2FF', /* Warna biru sangat muda untuk background layar */
                        'card-white': '#FFFFFF', 
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bg-klinik font-sans min-h-screen flex flex-col items-center justify-center p-8">

    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-[#0B132B] mb-3 flex items-center justify-center tracking-tight">
            <span class="text-blue-600 mr-3 text-4xl">~v~</span> Sistem Manajemen Klinik
        </h1>
        <p class="text-gray-500 text-lg">Kelola data pasien, rekam medis, dan pembayaran dengan mudah</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl w-full mb-8">
        
        <a href="views/pendaftaran.php" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="bg-blue-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-100 transition-colors">
                <span class="text-blue-600 text-2xl">👤</span>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Pendaftaran Pasien</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Daftarkan pasien baru dan kelola data pasien</p>
        </a>

        <a href="views/rekam_medis.php" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-green-200 transition-all group">
            <div class="bg-green-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-100 transition-colors">
                <span class="text-green-500 text-2xl">🩺</span>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Rekam Medis</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Input data pemeriksaan dan diagnosis dokter</p>
        </a>

        <a href="views/kasir.php" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-purple-200 transition-all group">
            <div class="bg-purple-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 group-hover:bg-purple-100 transition-colors">
                <span class="text-purple-500 text-2xl">💳</span>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Pembayaran</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Proses pembayaran dan cetak struk</p>
        </a>

        <a href="views/laporan.php" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-orange-200 transition-all group">
            <div class="bg-orange-50 w-14 h-14 rounded-xl flex items-center justify-center mb-6 group-hover:bg-orange-100 transition-colors">
                <span class="text-orange-400 text-2xl">📄</span>
            </div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Laporan Harian</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Ringkasan pasien dan pendapatan harian</p>
        </a>

    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 max-w-6xl w-full">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <span class="text-yellow-500 mr-2">🔒</span> Sistem Jaringan Lokal (Offline)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-bold text-gray-700 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Multi-PC Access</h4>
                <p class="text-gray-500 text-sm mt-1 ml-4">Dapat diakses di PC Kasir dan PC Dokter melalui LAN/WiFi</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-700 flex items-center"><span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Data Aman</h4>
                <p class="text-gray-500 text-sm mt-1 ml-4">Data tersimpan lokal di jaringan klinik, tidak terhubung internet</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-700 flex items-center"><span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span> Real-time Sync</h4>
                <p class="text-gray-500 text-sm mt-1 ml-4">Perubahan data langsung tersinkronisasi antar PC</p>
            </div>
        </div>
    </div>

</body>
</html>