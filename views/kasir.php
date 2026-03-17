<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Kasir - Klinik App</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg-klinik': '#F4F6F9', 
                        'card-white': '#FFFFFF', 
                        'purple-price': '#8B5CF6' /* Warna ungu presisi untuk harga dari Figma-mu */
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bg-klinik text-gray-800 font-sans min-h-screen p-8">

    <div class="mb-6">
        <a href="../index.php" class="text-gray-600 hover:text-black font-medium flex items-center w-fit transition-colors">
            <span class="mr-2">←</span> Kembali
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pembayaran Kasir</h1>
        <p class="text-gray-500 mt-1">Proses pembayaran pasien dan cetak struk</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="bg-card-white p-6 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 lg:col-span-1">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-800">Menunggu Pembayaran</h2>
                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">1</span>
            </div>
            
            <div class="border border-gray-200 rounded-xl p-4 hover:border-purple-300 hover:shadow-md transition-all cursor-pointer bg-white group">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Faid Arya</h3>
                    <span class="text-[10px] font-bold border border-gray-300 text-gray-500 px-2 py-1 rounded-md">Pending</span>
                </div>
                <p class="text-xs text-gray-500 mb-2">No. RM: RM2026010600003</p>
                <div class="flex justify-between items-end mt-4">
                    <p class="font-bold text-purple-price">Rp 100.000</p>
                    <p class="text-[11px] text-gray-400">9/3/2026, 20.37.57</p>
                </div>
            </div>

            </div>

        <div class="bg-card-white p-8 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-gray-100 lg:col-span-2 flex flex-col items-center justify-center min-h-[400px]">
            
            <div class="text-gray-300 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <p class="text-gray-500 text-center">Pilih pembayaran dari daftar untuk memproses</p>
            
        </div>

    </div>

</body>
</html>