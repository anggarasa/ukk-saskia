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
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Riwayat Transaksi</h1>
        <form action="<?= BASE_URL ?>/riwayat/search" method="post" class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input type="text" name="keyword" placeholder="Cari transaksi..."
                       value="<?= isset($data['keyword']) ? $data['keyword'] : '' ?>"
                       class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <button type="submit" class="bg-blue-600 px-4 py-2 text-center text-white rounded-lg hover:bg-blue-500">
                Cari
            </button>
            <?php if(isset($data['keyword']) && !empty($data['keyword'])): ?>
                <a href="<?= BASE_URL ?>/riwayat" class="bg-gray-500 px-4 py-2 text-center text-white rounded-lg hover:bg-gray-400">
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Table for desktop -->
    <div class="hidden md:block bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <table class="w-full">
            <thead>
            <tr class="bg-gray-50 border-b">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kembalian</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            <?php foreach ($data['transaksis'] as $transaksi): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($transaksi['tgl_transaksi'])) ?></td>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-800"><?= $transaksi['nama_pelanggan'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp <?= number_format($transaksi['uang_diberikan'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button @click="showModal($el.dataset.id)"
                                data-id="<?= $transaksi['transaksi_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-2"><i class="far fa-eye"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Pagination -->
        <?php if($data['total_pages'] > 1): ?>
            <div class="flex justify-center mt-6">
                <div class="inline-flex rounded-md shadow-sm">
                    <?php if($data['current_page'] > 1): ?>
                        <?php if(!empty($data['keyword'])): ?>
                            <form action="<?= BASE_URL ?>/riwayat/search" method="post">
                                <input type="hidden" name="keyword" value="<?= $data['keyword'] ?>">
                                <input type="hidden" name="page" value="<?= $data['current_page'] - 1 ?>">
                                <button type="submit" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                    &laquo; Sebelumnya
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/riwayat/index/<?= $data['current_page'] - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                &laquo; Sebelumnya
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $data['total_pages']; $i++): ?>
                        <?php if(!empty($data['keyword'])): ?>
                            <form action="<?= BASE_URL ?>/riwayat/search" method="post" class="inline">
                                <input type="hidden" name="keyword" value="<?= $data['keyword'] ?>">
                                <input type="hidden" name="page" value="<?= $i ?>">
                                <button type="submit" class="px-3 py-2 text-sm font-medium <?= $i == $data['current_page'] ? 'text-blue-700 bg-blue-100' : 'text-gray-700 bg-white' ?> border border-gray-300 hover:bg-gray-50">
                                    <?= $i ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/riwayat/index/<?= $i ?>" class="px-3 py-2 text-sm font-medium <?= $i == $data['current_page'] ? 'text-blue-700 bg-blue-100' : 'text-gray-700 bg-white' ?> border border-gray-300 hover:bg-gray-50">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if($data['current_page'] < $data['total_pages']): ?>
                        <?php if(!empty($data['keyword'])): ?>
                            <form action="<?= BASE_URL ?>/riwayat/search" method="post">
                                <input type="hidden" name="keyword" value="<?= $data['keyword'] ?>">
                                <input type="hidden" name="page" value="<?= $data['current_page'] + 1 ?>">
                                <button type="submit" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                    Selanjutnya &raquo;
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/riwayat/index/<?= $data['current_page'] + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                Selanjutnya &raquo;
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Card view for mobile -->
    <div class="md:hidden space-y-4 mb-8">
        <?php foreach ($data['transaksis'] as $transaksi): ?>
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-medium text-gray-800"><?= $transaksi['nama_pelanggan'] ?></h3>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Transaksi</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($transaksi['tgl_transaksi'])) ?></span>
                    <span class="font-medium text-blue-600">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Harga</span>
                        <span class="font-medium text-gray-800">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Pembayaran</span>
                        <span class="font-medium text-gray-800">Rp <?= number_format($transaksi['uang_diberikan'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Kembalian</span>
                        <span class="font-medium text-gray-800">Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></span>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Berhasil</span>
                    <div>
                        <button @click="showModal($el.dataset.id)"
                                data-id="<?= $transaksi['transaksi_id'] ?>" class="text-blue-600 hover:text-blue-900 p-1"><i class="far fa-eye"></i></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- Pagination -->
        <?php if($data['total_pages'] > 1): ?>
            <div class="flex justify-center mt-6">
                <div class="inline-flex rounded-md shadow-sm">
                    <?php if($data['current_page'] > 1): ?>
                        <?php if(!empty($data['keyword'])): ?>
                            <form action="<?= BASE_URL ?>/riwayat/search" method="post">
                                <input type="hidden" name="keyword" value="<?= $data['keyword'] ?>">
                                <input type="hidden" name="page" value="<?= $data['current_page'] - 1 ?>">
                                <button type="submit" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                    &laquo; Sebelumnya
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/riwayat/index/<?= $data['current_page'] - 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                &laquo; Sebelumnya
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for($i = 1; $i <= $data['total_pages']; $i++): ?>
                        <?php if(!empty($data['keyword'])): ?>
                            <form action="<?= BASE_URL ?>/riwayat/search" method="post" class="inline">
                                <input type="hidden" name="keyword" value="<?= $data['keyword'] ?>">
                                <input type="hidden" name="page" value="<?= $i ?>">
                                <button type="submit" class="px-3 py-2 text-sm font-medium <?= $i == $data['current_page'] ? 'text-blue-700 bg-blue-100' : 'text-gray-700 bg-white' ?> border border-gray-300 hover:bg-gray-50">
                                    <?= $i ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/riwayat/index/<?= $i ?>" class="px-3 py-2 text-sm font-medium <?= $i == $data['current_page'] ? 'text-blue-700 bg-blue-100' : 'text-gray-700 bg-white' ?> border border-gray-300 hover:bg-gray-50">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if($data['current_page'] < $data['total_pages']): ?>
                        <?php if(!empty($data['keyword'])): ?>
                            <form action="<?= BASE_URL ?>/riwayat/search" method="post">
                                <input type="hidden" name="keyword" value="<?= $data['keyword'] ?>">
                                <input type="hidden" name="page" value="<?= $data['current_page'] + 1 ?>">
                                <button type="submit" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                    Selanjutnya &raquo;
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/riwayat/index/<?= $data['current_page'] + 1 ?>" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                Selanjutnya &raquo;
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Detail -->
    <div x-show="show"
         class="fixed inset-0 bg-gray-600 bg-opacity-50 z-30 flex items-center justify-center" x-transition>
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Detail Transaksi</h2>
                <button @click="show = false" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Informasi Pelanggan</h3>
                    <p><strong>Nama:</strong> <span x-text="transaksi.nama_pelanggan || '-'"></span></p>
                    <p><strong>Email:</strong> <span x-text="transaksi.email || '-'"></span></p>
                    <p><strong>No. HP:</strong> <span x-text="transaksi.no_hp || '-'"></span></p>
                    <p><strong>Alamat:</strong> <span x-text="transaksi.alamat || '-'"></span></p>
                </div>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Informasi Transaksi</h3>
                    <p><strong>ID Transaksi:</strong> <span x-text="transaksi.transaksi_id || '-'"></span></p>
                    <p><strong>Tanggal:</strong> <span x-text="transaksi.tgl_transaksi ? new Date(transaksi.tgl_transaksi).toLocaleDateString('id-ID') : '-'"></span></p>
                    <p><strong>Total Harga:</strong> <span x-text="transaksi.total_harga ? 'Rp ' + Number(transaksi.total_harga).toLocaleString('id-ID') : '-'"></span></p>
                    <p><strong>Pembayaran:</strong> <span x-text="transaksi.uang_diberikan ? 'Rp ' + Number(transaksi.uang_diberikan).toLocaleString('id-ID') : '-'"></span></p>
                    <p><strong>Kembalian:</strong> <span x-text="transaksi.kembalian ? 'Rp ' + Number(transaksi.kembalian).toLocaleString('id-ID') : '-'"></span></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Detail Produk</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2 text-left text-sm font-medium text-gray-600">Produk</th>
                                <th class="border p-2 text-left text-sm font-medium text-gray-600">Harga Satuan</th>
                                <th class="border p-2 text-left text-sm font-medium text-gray-600">Jumlah</th>
                                <th class="border p-2 text-left text-sm font-medium text-gray-600">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <template x-for="item in detailItems" :key="item.nama_produk">
                                <tr>
                                    <td class="border p-2 text-sm text-gray-600" x-text="item.nama_produk"></td>
                                    <td class="border p-2 text-sm text-gray-600" x-text="'Rp ' + Number(item.harga_produk).toLocaleString('id-ID')"></td>
                                    <td class="border p-2 text-sm text-gray-600" x-text="item.jumlah"></td>
                                    <td class="border p-2 text-sm text-gray-600" x-text="'Rp ' + Number(item.subtotal).toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                            <tr x-show="!detailItems.length">
                                <td colspan="4" class="border p-2 text-sm text-gray-600 text-center">Tidak ada produk</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button @click="show = false" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'?>
