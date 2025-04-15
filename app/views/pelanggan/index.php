<?php require_once '../app/views/layouts/header.php'?>

<div class="bg-white rounded-lg shadow-md p-6 mb-8"
     x-data="{
        show: false,
        pelanggan: null,
        showModal(id) {
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
     }">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Data Pelanggan</h1>
        <div class="flex flex-col sm:flex-row gap-3">
            <form action="<?= BASE_URL ?>/pelanggan" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" name="keyword" placeholder="Cari pelanggan..."
                           value="<?= isset($data['keyword']) ? htmlspecialchars($data['keyword']) : '' ?>"
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button type="submit" class="bg-primary hover:bg-pink-600 text-white py-2 px-4 rounded-lg flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </form>
            <a href="<?= BASE_URL ?>/pelanggan/tambah" class="bg-primary hover:bg-secondary text-white py-2 px-4 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Tambah Pelanggan
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
            <tr class="bg-gray-50 border-b">
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Alamat</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['pelanggans'] as $pelanggan): ?>
            <tr class="hover:bg-gray-50 border-b transition-all">
                <td class="px-4 py-3"><?= $pelanggan['nama'] ?></td>
                <td class="px-4 py-3 hidden md:table-cell"><?= $pelanggan['email'] ?></td>
                <td class="px-4 py-3"><?= $pelanggan['no_hp'] ?></td>
                <td class="px-4 py-3 hidden lg:table-cell"><?= htmlspecialchars(substr($pelanggan['alamat'], 0, 20)) . (strlen($pelanggan['alamat']) > 20 ? '...' : '') ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <button class="text-pink-500 hover:text-pink-700"
                                @click="showModal($el.dataset.id)"
                                data-id="<?= $pelanggan['id'] ?>">
                            <i class="fas fa-eye"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/pelanggan/edit/<?= $pelanggan['id'] ?>" class="text-blue-500 hover:text-blue-700">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="<?= BASE_URL ?>/pelanggan/delete/<?= $pelanggan['id'] ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');">
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
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
                    <a href="<?= BASE_URL ?>/pelanggan?page=<?= $i ?>&keyword=<?= $data['keyword'] ?? '' ?>" class="px-4 py-2 <?= $i == $data['currentPage'] ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-600' ?> rounded hover:bg-gray-300">
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

    <!-- Modal Detail Pelanggan -->
    <div x-show="show" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full mx-auto shadow-lg z-50">
            <div class="px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Pelanggan</h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6" x-show="pelanggan">
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-semibold" x-text="pelanggan?.nama"></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold" x-text="pelanggan?.email || '-'"></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">No. Telepon</p>
                    <p class="font-semibold" x-text="pelanggan?.no_hp"></p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Alamat</p>
                    <p class="font-semibold" x-text="pelanggan?.alamat || '-'"></p>
                </div>
            </div>
            <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
                <button @click="show = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-medium">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'?>
