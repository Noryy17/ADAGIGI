<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien - Klinik App</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-klinik': '#F4F6F9', /* Warna background biru sangat muda */
                        'card-white': '#FFFFFF', /* Warna latar Card Form */
                        'btn-dark': '#0B132B', /* Warna mutlak tombol daftar hitam gelap */
                        'blue-accent': '#3B82F6' /* Biru untuk text highlight RM */
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bg-klinik text-gray-800 font-sans min-h-screen p-8">

    <div class="mb-6">
        <a href="../index.php" class="text-gray-600 hover:text-black font-medium flex items-center">
            <span class="mr-2">←</span> Kembali
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pendaftaran Pasien</h1>
        <p class="text-gray-500 mt-1">Daftarkan pasien baru atau tambahkan pasien lama ke antrean</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <div class="bg-card-white p-8 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100">
            <h2 class="text-xl font-bold mb-6 flex items-center text-gray-800">
                <span class="mr-3 text-xl">👤+</span> Form Pendaftaran Pasien Baru
            </h2>
            
            <form action="../proses/pendaftaran_aksi.php" method="POST" class="space-y-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" required 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-blue-500 focus:ring-blue-500">
                        <option value="" disabled selected>Pilih jenis kelamin</option>
                        <option value="01">Laki-laki</option>
                        <option value="02">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat *</label>
                    <textarea name="alamat" required rows="2" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. HP / Telepon *</label>
                        <input type="number" name="no_hp" required 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Orang Tua (Opsional)</label>
                        <input type="text" name="nama_ortu" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIK (Opsional)</label>
                        <input type="number" name="nik" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="pt-4">
                    <div class="bg-blue-50 text-blue-700 p-3 rounded-lg text-sm mb-4 border border-blue-100 flex items-start">
                        <span class="mr-2">ℹ️</span>
                        <p>Nomor RM akan dibuat otomatis oleh sistem saat data disimpan.</p>
                    </div>
                    <button type="submit" 
                        class="w-full bg-[#0B132B] hover:bg-black text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                        Daftarkan Pasien Baru
                    </button>
                </div>
            </form>
            </div>

        <div class="bg-[#EEF2FF] p-8 rounded-2xl border border-[#E0E7FF]">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Format Nomor Rekam Medis</h2>
            
            <p class="text-gray-400 italic">Contoh Format: RM2026011200001 <br><br> Area desain detail aturan RM akan disempurnakan nanti...</p>
        </div>

    </div>

</body>
</html>