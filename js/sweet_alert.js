document.addEventListener("DOMContentLoaded", function() {
    // Mengecek apakah ada parameter 'status' di URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    // Menampilkan alert berdasarkan nilai 'status'
    if (status) {
        let title = '';
        let text = '';
        let icon = '';
        
        switch (status) {
            case 'success_create':
                title = 'Berhasil!';
                text = 'Data berhasil ditambahkan!';
                icon = 'success';
                break;
            case 'success_update':
                title = 'Update Berhasil!';
                text = 'Data berhasil diperbarui!';
                icon = 'success';
                break;
            case 'success_delete':
                title = 'Hapus Berhasil!';
                text = 'Data telah dihapus!';
                icon = 'success';
                break;
            case 'error':
            default:
                title = 'Oops!';
                text = 'Terjadi kesalahan, silakan coba lagi!';
                icon = 'error';
                break;
        }

        // Menampilkan SweetAlert dengan pesan yang sesuai
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            timer: 2000,
            showConfirmButton: false
        });

        // Menghapus parameter 'status' setelah 2 detik
        setTimeout(() => {
            window.history.replaceState(null, "", window.location.pathname);
        }, 2000);
    }
});
