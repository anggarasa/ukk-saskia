<?php require_once '../app/views/layouts/header.php'?>

<!-- Main Content -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden fade-in">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Produk Baru</h3>
            <p class="mt-1 text-sm text-gray-500">Silakan masukan data produk baru.</p>
        </div>

        <div class="flex justify-center">
            <a href="<?= BASE_URL ?>/produk" class="flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Feedback -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            <?= $_SESSION['success_message'] ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <?= $_SESSION['error_message'] ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/produk/store" method="post" class="px-6 py-5 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Produl -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="<?= isset($oldInput['nama']) ? htmlspecialchars($oldInput['nama']) : '' ?>" required class="mt-1 block w-full px-3 py-2 bg-white border <?= isset($errors['nama']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm" placeholder="Masukkan nama produk">
                <?php if (isset($errors['nama'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['nama'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Harga Produk -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" name="harga" id="harga" value="<?= isset($oldInput['harga']) ? htmlspecialchars($oldInput['harga']) : '' ?>" class="mt-1 block w-full px-3 py-2 bg-white border <?= isset($errors['harga']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm" placeholder="Rp. ">
                <?php if (isset($errors['harga'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['harga'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Stok -->
            <div class="md:col-span-2">
                <label for="stok" class="block text-sm font-medium text-gray-700">Stok<span class="text-red-500">*</span></label>
                <input type="number" name="stok" id="stok" value="<?= isset($oldInput['stok']) ? htmlspecialchars($oldInput['stok']) : '' ?>" required class="mt-1 block w-full px-3 py-2 bg-white border <?= isset($errors['stok']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm" placeholder="0">
                <?php if (isset($errors['stok'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['stok'] ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
            <a href="<?= BASE_URL ?>/produk" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                Simpan
            </button>
        </div>
    </form>
</div>

<?php require_once '../app/views/layouts/footer.php'?>
