<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Klinik</title>
    
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
                        'bg-klinik': '#F4F6F9', /* Warna background presisi Figma */
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bg-klinik text-gray-800 min-h-screen flex flex-col items-center justify-center p-8">

    <div class="text-center mb-12">
        <h1 class="text-[32px] font-bold text-gray-900 mb-3 flex items-center justify-center tracking-tight">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-3"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            Sistem Manajemen Klinik
        </h1>
        <p class="text-gray-500 text-base">Kelola data pasien, rekam medis, dan pembayaran dengan mudah</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl w-full mb-8">
        
        <a href="views/pendaftaran.php" class="bg-white p-6 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-blue-200 transition-all duration-300 group">
            <div class="bg-[#EFF6FF] w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-100 transition-colors">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Pendaftaran Pasien</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Daftarkan pasien baru dan kelola data pasien</p>
        </a>

        <a href="views/rekam_medis.php" class="bg-white p-6 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-green-200 transition-all duration-300 group">
            <div class="bg-[#F0FDF4] w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-100 transition-colors">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Rekam Medis</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Input data pemeriksaan dan diagnosis dokter</p>
        </a>

        <a href="views/kasir.php" class="bg-white p-6 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-purple-200 transition-all duration-300 group">
            <div class="bg-[#FAF5FF] w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-100 transition-colors">
                <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Pembayaran</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Proses pembayaran dan cetak struk</p>
        </a>

        <a href="views/laporan.php" class="bg-white p-6 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 hover:border-orange-200 transition-all duration-300 group">
            <div class="bg-[#FFF7ED] w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-100 transition-colors">
                <svg class="w-7 h-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Laporan Harian</h2>
            <p class="text-gray-400 text-sm leading-relaxed">Ringkasan pasien dan pendapatan harian</p>
        </a>

    </div>

    <div class="bg-white p-8 rounded-[20px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] border border-gray-100 max-w-6xl w-full">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="text-yellow-500 mr-3 text-xl">🔒</span> Sistem Jaringan Lokal (Offline)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-bold text-gray-900 flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2"></span> Multi-PC Access</h4>
                <p class="text-gray-500 text-sm mt-1.5 ml-4">Dapat diakses di PC Kasir dan PC Dokter melalui LAN/WiFi</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900 flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 mr-2"></span> Data Aman</h4>
                <p class="text-gray-500 text-sm mt-1.5 ml-4">Data tersimpan lokal di jaringan klinik, tidak terhubung internet</p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-gray-900 flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-purple-500 mr-2"></span> Real-time Sync</h4>
                <p class="text-gray-500 text-sm mt-1.5 ml-4">Perubahan data langsung tersinkronisasi antar PC</p>
            </div>
        </div>
    </div>

</body>
</html>