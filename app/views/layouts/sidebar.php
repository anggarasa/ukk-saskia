<?php
// Fungsi untuk memeriksa apakah URL saat ini cocok dengan path yang diberikan
function is_active($path) {
    // Ambil URL saat ini
    $current_url = $_SERVER['REQUEST_URI'];

    // Hapus query string jika ada
    if (strpos($current_url, '?') !== false) {
        $current_url = substr($current_url, 0, strpos($current_url, '?'));
    }

    // Jika path kosong (untuk halaman home/dashboard)
    if ($path === '') {
        return $current_url === BASE_URL || $current_url === BASE_URL . '/';
    }

    // Periksa apakah URL saat ini mengandung path yang diberikan
    return strpos($current_url, BASE_URL . '/' . $path) !== false;
}
?>

<aside id="sidebar" class="sidebar w-64 bg-white shadow-lg z-20 fixed h-full">
    <div class="p-4 flex flex-col h-full">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold text-primary">Kasir Jeni</h2>
            <button id="closeSidebar" class="md:hidden text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="flex-1">
            <ul class="space-y-1">
                <li>
                    <a href="<?= BASE_URL ?>" class="flex items-center p-3 rounded-lg <?= is_active('') ? 'text-primary bg-blue-50 font-medium' : 'text-gray-600 hover:bg-gray-100' ?>">
                        <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/transaksi" class="flex items-center p-3 rounded-lg <?= is_active('transaksi') ? 'text-primary bg-blue-50 font-medium' : 'text-gray-600 hover:bg-gray-100' ?>">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        <span>Transaksi Baru</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/riwayat" class="flex items-center p-3 rounded-lg <?= is_active('riwayat/transaksi') ? 'text-primary bg-blue-50 font-medium' : 'text-gray-600 hover:bg-gray-100' ?>">
                        <i class="fas fa-history w-5 h-5 mr-3"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/produk" class="flex items-center p-3 rounded-lg <?= is_active('produk') ? 'text-primary bg-blue-50 font-medium' : 'text-gray-600 hover:bg-gray-100' ?>">
                        <i class="fas fa-box w-5 h-5 mr-3"></i>
                        <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/pelanggan" class="flex items-center p-3 rounded-lg <?= is_active('pelanggan') ? 'text-primary bg-blue-50 font-medium' : 'text-gray-600 hover:bg-gray-100' ?>">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        <span>Pelanggan</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
