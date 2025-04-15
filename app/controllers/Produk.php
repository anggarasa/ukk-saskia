<?php

class Produk  extends Controller
{
    public function index()
    {
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : null;

        $produkModel = $this->model('ProdukModel');

        if ($keyword) {
            // Jika ada kata kunci pencarian
            $data['produks'] = $produkModel->search($keyword, $limit, $offset);
            $totalData = $produkModel->countSearchResults($keyword);
        } else {
            // Default tanpa pencarian
            $data['produks'] = $produkModel->getPaginated($limit, $offset);
            $totalData = $produkModel->countAll();
        }

        // Menambahkan data statistik
        $data['total_pages'] = ceil($totalData / $limit);
        $data['current_page'] = $page;
        $data['keyword'] = $keyword; // Untuk mempertahankan keyword di input
        $data['total_produk'] = $produkModel->countAll();
        $data['total_terjual'] = $produkModel->countTotalSold();
        $data['total_pendapatan'] = $produkModel->countTotalRevenue();

        $this->view('produk/index', $data);
    }

    public function tambah()
    {
        $this->view('produk/tambah');
    }

    // Produk.php
    public function store()
    {
        // Mengambil data dari input POST
        $data = [
            'nama_produk' => trim($_POST['nama']),
            'harga_produk' => $_POST['harga'] != '' ? (int) $_POST['harga'] : null,
            'stok' => $_POST['stok'] != '' ? (int) $_POST['stok'] : null
        ];

        // Validasi input
        $errors = [];

        if (empty($data['nama_produk'])) {
            $errors['nama'] = 'Nama produk wajib diisi.';
        } elseif (strlen($data['nama_produk']) < 3) {
            $errors['nama'] = 'Nama produk harus memiliki panjang minimal 3 karakter.';
        }

        if (!is_null($data['harga_produk']) && $data['harga_produk'] < 0) {
            $errors['harga'] = 'Harga tidak boleh kurang dari 0.';
        }

        if (is_null($data['stok']) || $data['stok'] < 0) {
            $errors['stok'] = 'Stok wajib diisi dan tidak boleh kurang dari 0.';
        }

        // Jika ada error kembalikan ke halaman tambah produk
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/produk/tambah');
            exit;
        }

        // Simpan ke database
        $produkModel = $this->model('ProdukModel');
        if ($produkModel->add($data)) {
            $_SESSION['success_message'] = 'Produk berhasil ditambahkan!';
            header('Location: ' . BASE_URL . '/produk');
        } else {
            $_SESSION['error_message'] = 'Terjadi kesalahan saat menyimpan produk.';
            header('Location: ' . BASE_URL . '/produk');
        }
        exit;
    }

    public function edit($id)
    {
        $data['produk'] = $this->model('ProdukModel')->getById($id);
        $this->view('produk/edit', $data);
    }

    // Produk.php
    public function update($id)
    {
        // Mengambil data dari input POST
        $data = [
            'id' => (int)$id,
            'nama_produk' => trim($_POST['nama']),
            'harga_produk' => $_POST['harga'] != '' ? (int)$_POST['harga'] : null,
            'stok' => $_POST['stok'] != '' ? (int)$_POST['stok'] : null
        ];

        // Validasi input
        $errors = [];

        if (empty($data['nama_produk'])) {
            $errors['nama'] = 'Nama produk wajib diisi.';
        } elseif (strlen($data['nama_produk']) < 3) {
            $errors['nama'] = 'Nama produk harus memiliki panjang minimal 3 karakter.';
        }

        if (!is_null($data['harga_produk']) && $data['harga_produk'] < 0) {
            $errors['harga'] = 'Harga tidak boleh kurang dari 0.';
        }

        if (is_null($data['stok']) || $data['stok'] < 0) {
            $errors['stok'] = 'Stok wajib diisi dan tidak boleh kurang dari 0.';
        }

        // Jika ada error kembalikan ke halaman edit produk
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/produk/edit/' . $id);
            exit;
        }

        // Update data ke database
        $produkModel = $this->model('ProdukModel');
        if ($produkModel->update($data)) {
            $_SESSION['success_message'] = 'Produk berhasil diperbarui!';
            header('Location: ' . BASE_URL . '/produk');
        } else {
            $_SESSION['error_message'] = 'Terjadi kesalahan saat memperbarui produk.';
            header('Location: ' . BASE_URL . '/produk/edit/' . $id);
        }
        exit;
    }

    public function delete($id)
    {
        $id = (int)$id; // Pastikan id adalah integer
        $produkModel = $this->model('ProdukModel');

        if ($produkModel->deleteById($id)) {
            $_SESSION['success_message'] = 'Produk berhasil dihapus!';
        } else {
            $_SESSION['error_message'] = 'Terjadi kesalahan saat menghapus produk.';
        }

        header('Location: ' . BASE_URL . '/produk');
        exit;
    }
}
