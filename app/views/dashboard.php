<?php require_once 'layouts/header.php'?>

    <!-- Page Content -->
    <main class="p-4 md:p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold mb-2">Dashboard</h1>
            <p class="text-gray-600">Selamat datang kembali, lihat ringkasan bisnis Anda hari ini</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 font-medium">Total Pendapatan</h3>
                    <span class="rounded-full p-2 bg-blue-100 text-primary">
                    <i class="fas fa-money-bill-wave"></i>
                </span>
                </div>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold">Rp <?= number_format($data['total_pendapatan']['total_pendapatan'], 0, ',', '.') ?></h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 font-medium">Transaksi</h3>
                    <span class="rounded-full p-2 bg-green-100 text-success">
                    <i class="fas fa-shopping-cart"></i>
                </span>
                </div>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold"><?= $data['jumlah_transaksi']['jumlah_transaksi'] ?></h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 font-medium">Produk Terjual</h3>
                    <span class="rounded-full p-2 bg-orange-100 text-warning">
                    <i class="fas fa-box"></i>
                </span>
                </div>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold"><?= $data['produk_terjual']['produk_terjual'] ?></h2>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-500 font-medium">Pelanggan Baru</h3>
                    <span class="rounded-full p-2 bg-purple-100 text-purple-500">
                    <i class="fas fa-users"></i>
                </span>
                </div>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold"><?= $data['pelanggan_baru']['pelanggan_baru'] ?></h2>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Best Selling Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Transactions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4">Transaksi Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Pelanggan</th>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data['transaksi_terbaru'] as $transaksi): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 font-medium">#<?= $transaksi['id'] ?></td>
                                <td class="px-4 py-2"><?= $transaksi['nama_pelanggan'] ?></td>
                                <td class="px-4 py-2"><?= date('d/m/Y', strtotime($transaksi['tgl_transaksi'])) ?></td>
                                <td class="px-4 py-2">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Best Selling Products -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4">Produk Terlaris</h3>
                <div class="space-y-4">
                    <?php foreach($data['produk_terlaris'] as $index => $produk): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 font-semibold">
                                    <?= $index + 1 ?>
                                </span>
                                    <h4 class="font-medium"><?= $produk['nama_produk'] ?></h4>
                            </div>
                            <div class="w-24">
                                <p class="text-base text-gray-500"><?= $produk['total_terjual'] ?> terjual</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'layouts/footer.php'?>
