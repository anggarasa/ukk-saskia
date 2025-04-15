<?php require_once '../app/views/layouts/header.php'?>

<!-- Page Content -->
<main class="p-4 md:p-6">
    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Daftar Produk</h1>
            <p class="text-gray-600">Kelola dan temukan semua data Produk</p>
        </div>

        <a href="<?= BASE_URL ?>/produk/tambah" class="flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600">
            <i class="fas fa-user-plus mr-2"></i>
            <span>Tambah Produk</span>
        </a>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <form id="searchForm" class="flex items-stretch" method="get" action="<?= BASE_URL ?>/produk">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchProduk" name="keyword" value="<?= isset($data['keyword']) ? htmlspecialchars($data['keyword']) : '' ?>" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Cari berdasarkan nama produk, harga, stok, atau terjual">
                    </div>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 flex items-center justify-center">
                        <span>Cari</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($data['keyword']) && $data['keyword']): ?>
        <div class="bg-gray-100 border-l-4 border-gray-400 text-gray-700 p-4 mb-4">
            Menampilkan hasil pencarian untuk: <span class="font-bold"><?= htmlspecialchars($data['keyword']) ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <?= $_SESSION['success_message'] ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Produks Table -->
    <div class="bg-white rounded-lg shadow-sm mb-6 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="bg-gray-50 text-left text-gray-600">
                    <th class="p-4 font-medium">Nama Produk</th>
                    <th class="p-4 font-medium">Harga Produk</th>
                    <th class="p-4 font-medium">Stok</th>
                    <th class="p-4 font-medium">Terjual</th>
                    <th class="p-4 font-medium">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                <?php foreach ($data['produks'] as $produk): ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-4"><?= $produk['nama_produk'] ?></td>
                    <td class="p-4">Rp <?= number_format($produk['harga_produk'],0,',','.') ?></td>
                    <td class="p-4"><?= $produk['stok'] ?></td>
                    <td class="p-4"><?= $produk['terjual'] ?></td>
                    <td class="p-4">
                        <div class="flex space-x-2">
                            <a href="<?= BASE_URL ?>/produk/edit/<?= $produk['id'] ?>" class="p-1.5 bg-gray-50 text-gray-600 rounded hover:bg-gray-100" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= BASE_URL ?>/produk/delete/<?= $produk['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')" class="p-1.5 bg-red-50 text-red-600 rounded hover:bg-red-100" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </a>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 flex flex-col sm:flex-row items-center justify-between border-t">
            <?php if ($data['total_pages'] > 1): ?>
                <div class="flex space-x-1">
                    <!-- Tombol Previous -->
                    <?php if ($data['current_page'] > 1): ?>
                        <a href="<?= BASE_URL ?>/produk?page=<?= $data['current_page'] - 1 ?>&keyword=<?= htmlspecialchars($data['keyword']) ?>" class="px-3 py-1.5 text-sm rounded border border-gray-200 text-gray-600 hover:bg-gray-100">
                            <i class="fas fa-chevron-left"></i> Prev
                        </a>
                    <?php else: ?>
                        <button class="px-3 py-1.5 text-sm rounded border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                            <i class="fas fa-chevron-left"></i> Prev
                        </button>
                    <?php endif; ?>

                    <!-- Tombol Halaman -->
                    <?php for ($i = 1; $i <= $data['total_pages']; $i++): ?>
                        <a href="<?= BASE_URL ?>/produk?page=<?= $i ?>&keyword=<?= htmlspecialchars($data['keyword']) ?>" class="px-3 py-1.5 text-sm rounded border <?= $data['current_page'] == $i ? 'bg-blue-600 text-white' : 'border-gray-200 text-gray-600 hover:bg-gray-100' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Tombol Next -->
                    <?php if ($data['current_page'] < $data['total_pages']): ?>
                        <a href="<?= BASE_URL ?>/produk?page=<?= $data['current_page'] + 1 ?>&keyword=<?= htmlspecialchars($data['keyword']) ?>" class="px-3 py-1.5 text-sm rounded border border-gray-200 text-gray-600 hover:bg-gray-100">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php else: ?>
                        <button class="px-3 py-1.5 text-sm rounded border border-gray-200 text-gray-400 cursor-not-allowed" disabled>
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
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
