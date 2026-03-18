// assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    // Cari semua form di dalam halaman
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Munculkan pop-up peringatan bawaan browser
            const konfirmasi = confirm('Apakah data yang dimasukkan sudah benar? Klik OK untuk melanjutkan.');
            
            // Kalau user klik "Cancel", batalkan pengiriman data
            if (!konfirmasi) {
                e.preventDefault();
            }
        });
    });
});