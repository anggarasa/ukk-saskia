<?php require_once '../app/views/layouts/header.php'?>

<div class="container mx-auto px-4 py-8 max-w-6xl" x-data="{
        show: false,
        transaksi: null,
        detailItems: [],
        showModal(id) {
            this.show = true;
            this.fetchDetail(id);
        },
        fetchDetail(id) {
            fetch(`<?= BASE_URL ?>/riwayat/detail/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.transaksi = data.transaksi;
                        this.detailItems = Array.isArray(data.data) ? data.data : [data.data];
                    } else {
                        alert(data.message);
                        this.show = false;
                    }
                })
                .catch(error => {
                    console.error('Error fetching detail:', error);
                    alert('Gagal mengambil data transaksi.');
                    this.show = false;
                });
        }
    }">
    <!-- Header with pink gradient background -->
    <div class="rounded-xl bg-gradient-to-r from-pink-100 to-pink-200 p-6 shadow-md mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <i class="fas fa-history text-pink-500 text-2xl mr-3"></i>
                <h1 class="text-2xl font-bold text-pink-700">Riwayat Transaksi</h1>
            </div>
            <form action="<?= BASE_URL ?>/riwayat/search" method="post" class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" name="keyword" placeholder="Cari transaksi..."
                           value="<?= isset($data['keyword']) ? $data['keyword'] : '' ?>"
                           class="pl-10 pr-4 py-2 rounded-lg border border-pink-200 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent w-full sm:w-64 shadow-sm">
                    <i class="fas fa-search absolute left-3 top-3 text-pink-400"></i>
                </div>
                <button type="submit" class="bg-pink-500 px-4 py-2 text-center text-white rounded-lg hover:bg-pink-600 transition duration-300 shadow-sm">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <?php if(isset($data['keyword']) && !empty($data['keyword'])): ?>
                    <a href="<?= BASE_URL ?>/riwayat" class="bg-gray-500 px-4 py-2 text-center text-white rounded-lg hover:bg-gray-600 transition duration-300 shadow-sm">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Table for desktop with pink accents -->
    <div class="hidden md:block bg-white rounded-xl shadow-lg overflow-hidden mb-8 border border-pink-100">
        <table class="w-full">
            <thead>
            <tr class="bg-gradient-to-r from-pink-500 to-pink-400 text-white">
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Pelanggan</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Total Harga</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Pembayaran</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Kembalian</th>
                <th class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-pink-100">
            <?php foreach ($data['transaksis'] as $transaksi): ?>
                <tr class="hover:bg-pink-50 transition duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt text-pink-400 mr-2"></i>
                            <?= date('d/m/Y', strtotime($transaksi['tgl_transaksi'])) ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-800"><?= $transaksi['nama_pelanggan'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-pink-600">
                        Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        Rp <?= number_format($transaksi['uang_diberikan'], 0, ',', '.') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button @click="showModal($el.dataset.id)"
                                data-id="<?= $transaksi['transaksi_id'] ?>"
                                class="bg-pink-500 text-white px-3 py-1.5 rounded-md hover:bg-pink-600 transition duration-300 inline-flex items-center">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Cards for mobile -->
    <div class="md:hidden space-y-4">
        <?php foreach ($data['transaksis'] as $transaksi): ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-pink-100 hover:shadow-lg transition duration-300">
                <div class="bg-gradient-to-r from-pink-500 to-pink-400 px-4 py-2 text-white flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <span><?= date('d/m/Y', strtotime($transaksi['tgl_transaksi'])) ?></span>
                    </div>
                    <div class="text-pink-100">ID: <?= $transaksi['transaksi_id'] ?></div>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pelanggan:</span>
                        <span class="font-medium text-gray-800"><?= $transaksi['nama_pelanggan'] ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Total:</span>
                        <span class="font-bold text-pink-600">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Pembayaran:</span>
                        <span class="text-gray-800">Rp <?= number_format($transaksi['uang_diberikan'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kembalian:</span>
                        <span class="text-gray-800">Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></span>
                    </div>
                    <button @click="showModal($el.dataset.id)"
                            data-id="<?= $transaksi['transaksi_id'] ?>"
                            class="mt-2 w-full bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-eye mr-2"></i> Lihat Detail
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($data['pagination'])): ?>
        <div class="flex justify-center mt-6">
            <div class="inline-flex rounded-md shadow-sm">
                <?php if ($data['pagination']['current_page'] > 1): ?>
                    <a href="<?= BASE_URL ?>/riwayat?page=<?= $data['pagination']['current_page'] - 1 ?>" class="px-4 py-2 text-sm font-medium text-pink-500 bg-white border border-pink-200 rounded-l-lg hover:bg-pink-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php else: ?>
                    <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-l-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $data['pagination']['total_pages']; $i++): ?>
                    <?php if ($i == $data['pagination']['current_page']): ?>
                        <span class="px-4 py-2 text-sm font-medium text-white bg-pink-500 border border-pink-500">
                            <?= $i ?>
                        </span>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/riwayat?page=<?= $i ?>" class="px-4 py-2 text-sm font-medium text-pink-500 bg-white border border-pink-200 hover:bg-pink-50">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($data['pagination']['current_page'] < $data['pagination']['total_pages']): ?>
                    <a href="<?= BASE_URL ?>/riwayat?page=<?= $data['pagination']['current_page'] + 1 ?>" class="px-4 py-2 text-sm font-medium text-pink-500 bg-white border border-pink-200 rounded-r-lg hover:bg-pink-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php else: ?>
                    <span class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-r-lg cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Detail Modal -->
    <div x-show="show" x-cloak class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="show = false" class="bg-white rounded-xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <!-- Modal header -->
            <div class="bg-gradient-to-r from-pink-500 to-pink-400 p-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Detail Transaksi</h3>
                <button @click="show = false" class="text-white hover:text-pink-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal body -->
            <div class="p-6">
                <div x-show="transaksi" class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">ID Transaksi:</span>
                            <span x-text="transaksi?.transaksi_id" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Tanggal:</span>
                            <span x-text="transaksi?.tgl_transaksi" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Pelanggan:</span>
                            <span x-text="transaksi?.nama_pelanggan" class="font-medium text-gray-800"></span>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Total:</span>
                            <span x-text="'Rp ' + Number(transaksi?.total_harga).toLocaleString('id-ID')" class="font-bold text-pink-600"></span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Pembayaran:</span>
                            <span x-text="'Rp ' + Number(transaksi?.uang_diberikan).toLocaleString('id-ID')" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 w-32">Kembalian:</span>
                            <span x-text="'Rp ' + Number(transaksi?.kembalian).toLocaleString('id-ID')" class="font-medium text-gray-800"></span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-pink-100 pt-4">
                    <h4 class="text-lg font-medium text-pink-700 mb-3">Item yang Dibeli</h4>
                    <div class="bg-pink-50 p-4 rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-pink-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Harga Satuan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-pink-100">
                                <template x-for="(item, index) in detailItems" :key="index">
                                    <tr class="hover:bg-pink-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-800" x-text="item.nama_produk"></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700" x-text="'Rp ' + Number(item.harga_produk).toLocaleString('id-ID')"></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700" x-text="item.jumlah"></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-pink-600" x-text="'Rp ' + Number(item.subtotal).toLocaleString('id-ID')"></td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="bg-gray-50 px-6 py-3 flex justify-end rounded-b-xl">
                <button @click="show = false" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition duration-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'?>
