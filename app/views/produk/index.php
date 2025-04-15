<?php require_once '../app/views/layouts/header.php'?>

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Produk</h2>
                </div>
                <form action="<?= BASE_URL ?>/produk" method="GET" class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" name="keyword" value="<?= isset($data['keyword']) ? $data['keyword'] : '' ?>" placeholder="Cari produk..." class="px-4 py-2 rounded-lg border border-pink-300 focus:outline-none focus:ring-2 focus:ring-pink focus:border-transparent">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                    <div class="relative">
                        <button type="submit" class="relative py-2 px-3 bg-pink-600 hover:bg-pink-700 rounded-lg">
                            <i class="fas fa-magnifying-glass text-white"></i>
                        </button>
                    </div>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4">
            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Produk Card -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-blue-100 rounded-lg mr-4">
                            <i class="fas fa-box text-primary text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Produk</h3>
                            <p class="text-2xl font-bold"><?= $data['total_produk'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Produk Terjual Card -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-green-100 rounded-lg mr-4">
                            <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Produk Terjual</h3>
                            <p class="text-2xl font-bold"><?= $data['total_terjual'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan Card -->
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <div class="flex items-start">
                        <div class="p-2 bg-yellow-100 rounded-lg mr-4">
                            <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Pendapatan</h3>
                            <p class="text-2xl font-bold">Rp <?= number_format($data['total_pendapatan'],0,',','.') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Buttons -->
            <div class="flex flex-wrap justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 md:mb-0">Kelola Produk</h2>
                <a href="<?= BASE_URL ?>/produk/tambah" class="px-4 py-2 bg-primary hover:bg-pink-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                </a>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                    <?= $_SESSION['success_message'] ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <!-- Products Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Terjual
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($data['produks'] as $produk): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= $produk['nama_produk'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Rp <?= number_format($produk['harga_produk'],0,',','.') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $produk['stok'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= $produk['terjual'] ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?= BASE_URL ?>/produk/edit/<?= $produk['id'] ?>" class="text-pink hover:text-pink-dark mr-2"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL ?>/produk/delete/<?= $produk['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <?php if ($data['current_page'] > 1): ?>
                            <a href="<?= BASE_URL ?>/produk?page=<?= $data['current_page'] - 1 ?><?= isset($data['keyword']) && !empty($data['keyword']) ? '&keyword=' . $data['keyword'] : '' ?>" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                            <a href="<?= BASE_URL ?>/produk?page=<?= $i ?><?= isset($data['keyword']) && !empty($data['keyword']) ? '&keyword=' . $data['keyword'] : '' ?>"
                               class="px-3 py-1 <?= $i === $data['current_page'] ? 'bg-pink-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?> rounded-md">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($data['current_page'] < $data['total_pages']): ?>
                            <a href="<?= BASE_URL ?>/produk?page=<?= $data['current_page'] + 1 ?><?= isset($data['keyword']) && !empty($data['keyword']) ? '&keyword=' . $data['keyword'] : '' ?>" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('[onclick]')) {
                const message = e.target.getAttribute('onclick').match(/return confirm\('([^']+)'\)/)[1];
                if (!confirm(message)) {
                    e.preventDefault();
                }
            }
        });
    </script>

<?php require_once '../app/views/layouts/footer.php'?>
