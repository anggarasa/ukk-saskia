<?php require_once '../app/views/layouts/header.php'?>

<div class="min-h-screen bg-pink-50 flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 p-6 flex justify-center items-center">
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-semibold text-pink-600 mb-6">Form Edit Pelanggan</h2>

            <?php
            $oldInput = isset($_SESSION['old_input']) ? $_SESSION['old_input'] : [];
            $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
            unset($_SESSION['old_input'], $_SESSION['errors']);
            ?>

            <form action="<?= BASE_URL ?>/pelanggan/update/<?= $data['pelanggan']['id'] ?>" method="post" class="grid grid-cols-1 gap-4">
                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" value="<?= isset($oldInput['nama']) ? htmlspecialchars($oldInput['nama']) : htmlspecialchars($data['pelanggan']['nama']) ?>" required placeholder="Masukkan nama lengkap"  class="mt-1 w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500" />
                    <?php if (isset($errors['nama'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['nama'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?= isset($oldInput['email']) ? htmlspecialchars($oldInput['email']) : htmlspecialchars($data['pelanggan']['email']) ?>" required placeholder="contoh@email.com" class="mt-1 w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500" />
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- No Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">No Telepon</label>
                    <input type="tel" name="no_hp" value="<?= isset($oldInput['no_hp']) ? htmlspecialchars($oldInput['no_hp']) : htmlspecialchars($data['pelanggan']['no_hp']) ?>" required placeholder="08xxxxxxxxxx" class="mt-1 w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500" />
                    <?php if (isset($errors['no_hp'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['no_hp'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea rows="3" name="alamat" placeholder="Masukkan alamat lengkap" class="mt-1 w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500"><?= isset($oldInput['alamat']) ? htmlspecialchars($oldInput['alamat']) : htmlspecialchars($data['pelanggan']['alamat']) ?></textarea>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-4 mt-4">
                    <a href="<?= BASE_URL ?>/pelanggan" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition duration-200">Batal</a>
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-xl transition duration-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once '../app/views/layouts/footer.php'?>
