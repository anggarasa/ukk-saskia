<?php require_once '../app/views/layouts/header.php'?>
<!-- Main Content -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden fade-in">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Pelanggan Baru</h3>
            <p class="mt-1 text-sm text-gray-500">Silakan isi data pelanggan dengan lengkap dan benar.</p>
        </div>

        <div class="flex justify-center">
            <a href="<?= BASE_URL ?>/pelanggan" class="flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <?php
        $oldInput = isset($_SESSION['old_input']) ? $_SESSION['old_input'] : [];
        $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
        unset($_SESSION['old_input'], $_SESSION['errors']);
    ?>

    <form action="<?= BASE_URL ?>/pelanggan/store" method="post" class="px-6 py-5 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Pelanggan -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pelanggan <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="<?= isset($oldInput['nama']) ? htmlspecialchars($oldInput['nama']) : '' ?>" required class="mt-1 block w-full px-3 py-2 bg-white border <?= isset($errors['nama']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan nama pelanggan">
                <?php if (isset($errors['nama'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['nama'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Email Pelanggan -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= isset($oldInput['email']) ? htmlspecialchars($oldInput['email']) : '' ?>" class="mt-1 block w-full px-3 py-2 bg-white border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="email@contoh.com">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <!-- No. Telepon -->
            <div class="md:col-span-2">
                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. Telepon <span class="text-red-500">*</span></label>
                <input type="tel" name="no_hp" id="no_hp" value="<?= isset($oldInput['no_hp']) ? htmlspecialchars($oldInput['no_hp']) : '' ?>" required class="mt-1 block w-full px-3 py-2 bg-white border <?= isset($errors['no_hp']) ? 'border-red-500' : 'border-gray-300' ?> rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="08xxxxxxxxxx">
                <?php if (isset($errors['no_hp'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['no_hp'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Alamat -->
            <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan alamat lengkap"><?= isset($oldInput['alamat']) ? htmlspecialchars($oldInput['alamat']) : '' ?></textarea>
            </div>
        </div>

        <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
            <a href="<?= BASE_URL ?>/pelanggan" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Simpan
            </button>
        </div>
    </form>
</div>
<?php require_once '../app/views/layouts/footer.php'?>
