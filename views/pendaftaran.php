<?php require_once '../config/database.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien - Klinik App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F4F6F9; }
    </style>
</head>
<body class="p-8">

    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="../index.php" class="text-gray-500 hover:text-gray-900 font-medium flex items-center w-fit transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Dashboard
            </a>
        </div>

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pendaftaran Pasien</h1>
            <p class="text-gray-500 mt-1">Daftarkan pasien baru atau tambahkan pasien lama ke antrean</p>
        </div>

        <div class="flex gap-4 mb-8 bg-gray-200/50 p-1.5 rounded-2xl w-fit">
            <button onclick="switchTab('baru')" id="btn-baru" class="px-8 py-3 rounded-xl font-bold transition-all bg-white shadow-sm text-blue-600">
                Pasien Baru
            </button>
            <button onclick="switchTab('lama')" id="btn-lama" class="px-8 py-3 rounded-xl font-bold transition-all text-gray-500 hover:bg-gray-200">
                Pasien Lama
            </button>
        </div>

        <div id="tab-baru" class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div class="bg-white p-8 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                <form action="../proses/pendaftaran_aksi.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" required class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" required class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none">
                            <option value="" disabled selected>Pilih jenis kelamin</option>
                            <option value="01">Laki-laki</option>
                            <option value="02">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat *</label>
                        <textarea name="alamat" required rows="2" class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir *</label>
                            <input type="date" name="tanggal_lahir" required class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">No. HP / Telepon *</label>
                            <input type="number" name="no_hp" required class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ortu (Opsional)</label>
                            <input type="text" name="nama_ortu" class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">NIK (Opsional)</label>
                            <input type="number" name="nik" class="w-full bg-gray-50 rounded-xl border-none p-4 focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#0B132B] hover:bg-black text-white font-bold py-4 mt-4 rounded-xl transition duration-200">
                        Daftarkan Pasien Baru
                    </button>
                </form>
            </div>
            
            <div class="bg-[#EEF2FF] p-8 rounded-2xl border border-[#E0E7FF]">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Format Nomor Rekam Medis</h2>
                <p class="text-blue-800 text-sm">Contoh Format: <span class="font-mono font-bold text-blue-600 text-lg">RM2026011200001</span></p>
                <p class="text-gray-500 mt-4 text-xs italic">*Sistem akan meng-generate otomatis setelah pasien didaftarkan.</p>
            </div>
        </div>

        <div id="tab-lama" class="hidden">
            <div class="bg-white p-8 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="relative mb-6">
                    <input type="text" id="liveSearch" placeholder="Ketik Nama atau Nomor RM di sini..." class="w-full p-5 pl-12 bg-gray-50 border-none rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-lg font-medium">
                    <svg class="w-6 h-6 absolute left-4 top-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div id="hasilCariTable" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    </div>
            </div>
        </div>
    </div>

    <script>
    function switchTab(type) {
        const tabBaru = document.getElementById('tab-baru');
        const tabLama = document.getElementById('tab-lama');
        const btnBaru = document.getElementById('btn-baru');
        const btnLama = document.getElementById('btn-lama');

        if(type === 'baru') {
            tabBaru.classList.remove('hidden'); tabLama.classList.add('hidden');
            btnBaru.className = "px-8 py-3 rounded-xl font-bold bg-white shadow-sm text-blue-600 transition-all";
            btnLama.className = "px-8 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition-all";
        } else {
            tabLama.classList.remove('hidden'); tabBaru.classList.add('hidden');
            btnLama.className = "px-8 py-3 rounded-xl font-bold bg-white shadow-sm text-blue-600 transition-all";
            btnBaru.className = "px-8 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-200 transition-all";
        }
    }

    // PINTAR: Baca URL ?tab=lama saat dari Rekam Medis
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'lama') {
            switchTab('lama');
        }
        loadData(''); // Load semua pasien awal
    };

    // Live Search
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        loadData(this.value);
    });

    function loadData(keyword) {
        fetch('../proses/cari_pasien.php?keyword=' + keyword)
            .then(res => res.text())
            .then(data => { document.getElementById('hasilCariTable').innerHTML = data; });
    }
    </script>
</body>
</html>