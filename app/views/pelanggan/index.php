<?php require_once '../app/views/layouts/header.php'?>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div id="flashModal" class="fixed z-50 inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl p-6 space-y-4">
            <h2 class="text-lg font-medium <?= $_SESSION['flash_type'] === 'success' ? 'text-green-600' : 'text-red-600' ?>">
                <?= $_SESSION['flash_type'] === 'success' ? 'Sukses' : 'Gagal' ?>
            </h2>
            <p><?= $_SESSION['flash_message'] ?></p>
            <button onclick="document.getElementById('flashModal').remove();" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Tutup</button>
        </div>
    </div>
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
<?php endif; ?>

    <!-- Page Content -->
    <main x-data="{
                    show: false,
                    pelanggan: null,
                    show()\n        showModal(id) {
                        this.show = true;
                        this.fetchDetail(id);
                    },
                    fetchDetail(id) {
                        fetch(`<?= BASE_URL ?>/pelanggan/detail/${id}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    this.pelanggan = data.data;
                                } else {
                                    alert(data.message);
                                    this.show = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching detail:', error);
                                alert('Gagal mengambil data pelanggan.');
                                this.show = false;
                            });
                    }
                }" class="p-4 md:p-6">
        <!-- Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold mb-1">Daftar Pelanggan</h1>
                <p class="text-gray-600">Kelola dan temukan semua data pelanggan</p>
            </div>

            <a href="<?= BASE_URL ?>/pelanggan/tambah" class="flex items-center justify-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600">
                <i class="fas fa-user-plus mr-2"></i>
                <span>Tambah Pelanggan</span>
            </a>
        </div>

        <!-- Search & Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <form id="searchForm" class="flex items-stretch" method="get" action="<?= BASE_URL ?>/pelanggan">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchCustomer" name="keyword" value="<?= isset($data['keyword']) ? htmlspecialchars($data['keyword']) : '' ?>" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Cari berdasarkan nama, email, atau No. Telepon...">
                        </div>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-lg hover:bg-blue-600 flex items-center justify-center">
                            <span>Cari</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-nowrap">
                    <thead>
                    <tr class="bg-gray-50 text-left text-gray-600">
                        <th class="p-4 font-medium">Nama</th>
                        <th class="p-4 font-medium">Email</th>
                        <th class="p-4 font-medium">No. Telepon</th>
                        <th class="p-4 font-medium">Alamat</th>
                        <th class="p-4 font-medium">Aksi</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y">
                    <?php foreach ($data['pelanggans'] as $pelanggan): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4"><?= htmlspecialchars($pelanggan['nama']) ?></td>
                            <td class="p-4"><?= htmlspecialchars($pelanggan['email']) ?></td>
                            <td class="p-4"><?= htmlspecialchars($pelanggan['no_hp']) ?></td>
                            <td class="p-4">
                                <?= htmlspecialchars(substr($pelanggan['alamat'], 0, 20)) . (strlen($pelanggan['alamat']) > 20 ? '...' : '') ?>
                            </td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    <button class="p-1.5 text-primary rounded hover:bg-blue-100"
                                            title="Lihat Detail"
                                            @click="showModal($el.dataset.id)"
                                            data-id="<?= $pelanggan['id'] ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="<?= BASE_URL ?>/pelanggan/edit/<?= $pelanggan['id'] ?>" class="p-1.5 bg-gray-50 text-gray-600 rounded hover:bg-gray-100" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>/pelanggan/delete/<?= $pelanggan['id'] ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                                        <button type="submit" class="p-1.5 text-red-600 rounded hover:bg-red-200" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Detail -->
                        <div x-show="show"
                             class="fixed z-50 inset-0 flex items-center justify-center bg-transparent" style="display: none;">
                            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 space-y-4">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-lg font-medium text-gray-700">Detail Pelanggan</h2>
                                    <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <p><strong>Nama:</strong> <span x-text="pelanggan?.nama || '-'"></span></p>
                                    <p><strong>Email:</strong> <span x-text="pelanggan?.email || '-'"></span></p>
                                    <p><strong>No. Telepon:</strong> <span x-text="pelanggan?.no_hp || '-'"></span></p>
                                    <p><strong>Alamat:</strong> <span x-text="pelanggan?.alamat || '-'"></span></p>
                                </div>
                                <div class="flex justify-end">
                                    <button @click="show = false" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav class="mt-6">
                <ul class="flex justify-center space-x-2">
                    <?php if ($data['currentPage'] > 1): ?>
                        <li>
                            <a href="<?= BASE_URL ?>/pelanggan?page=<?= $data['currentPage'] - 1 ?>&keyword=<?= $data['keyword'] ?? '' ?>" class="px-4 py-2 bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
                                Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                        <li>
                            <a href="<?= BASE_URL ?>/pelanggan?page=<?= $i ?>&keyword=<?= $data['keyword'] ?? '' ?>" class="px-4 py-2 <?= $i == $data['currentPage'] ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-600' ?> rounded hover:bg-gray-300">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($data['currentPage'] < $data['totalPages']): ?>
                        <li>
                            <a href="<?= BASE_URL ?>/pelanggan?page=<?= $data['currentPage'] + 1 ?>&keyword=<?= $data['keyword'] ?? '' ?>" class="px-4 py-2 bg-gray-200 text-gray-600 rounded hover:bg-gray-300">
                                Next
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </main>
<?php require_once '../app/views/layouts/footer.php'?>
