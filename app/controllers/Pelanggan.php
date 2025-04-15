<?php

class Pelanggan extends Controller
{
    public function index()
    {
        $limit = 5; // Jumlah data per halaman
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman saat ini
        $start = ($page - 1) * $limit; // Hitung offset

        $pelangganModel = $this->model('PelangganModel');

        // Cek apakah ada pencarian
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;

        if ($keyword) {
            // Jika ada pencarian, gunakan query pencarian
            $data['pelanggans'] = $pelangganModel->search($keyword, $start, $limit);
            $totalData = $pelangganModel->countSearch($keyword);
        } else {
            // Jika tidak ada pencarian, gunakan pagination biasa
            $data['pelanggans'] = $pelangganModel->getPaginated($start, $limit);
            $totalData = $pelangganModel->countAll();
        }

        // Hitung jumlah halaman
        $data['totalPages'] = ceil($totalData / $limit);
        $data['currentPage'] = $page;
        $data['keyword'] = $keyword;

        // Kirim data ke view
        $this->view('pelanggan/index', $data);
    }

    public function tambah()
    {
        $this->view('pelanggan/tambah');
    }

    public function store()
    {
        // Validasi input
        $errors = [];

        // Input Lama (untuk ditampilkan kembali saat ada error)
        $oldInput = $_POST;

        if (empty($_POST['nama'])) {
            $errors['nama'] = 'Nama pelanggan wajib diisi';
        }

        if (empty($_POST['no_hp'])) {
            $errors['no_hp'] = 'No. Telepon wajib diisi';
        } elseif (!preg_match('/^[0-9]{10,13}$/', $_POST['no_hp'])) {
            $errors['no_hp'] = 'Format No. Telepon tidak valid (masukkan 10-13 angka)';
        }

        if (!empty($_POST['email'])) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format Email tidak valid';
            } else {
                // Periksa apakah email sudah pernah terdaftar
                if ($this->model('PelangganModel')->isEmailExist($_POST['email'])) {
                    $errors['email'] = 'Email sudah digunakan, silakan gunakan email lain';
                }
            }
        }

        // Jika ada error, kembalikan ke form tambah pelanggan
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $oldInput;
            header('Location: ' . BASE_URL . '/pelanggan/tambah');
            exit;
        }

        $pelanggan = $this->model('PelangganModel');
        $result = $pelanggan->add($_POST);

        if ($result) {
            // Set flash message sukses
            $_SESSION['flash_message'] = 'Data pelanggan berhasil ditambahkan!';
            $_SESSION['flash_type'] = 'success';
        } else {
            // Set flash message gagal
            $_SESSION['flash_message'] = 'Gagal menambahkan data pelanggan!';
            $_SESSION['flash_type'] = 'error';
        }

        // Redirect ke halaman index
        header('Location: ' . BASE_URL . '/pelanggan');
        exit;
    }

    public function edit($id)
    {
        $data['pelanggan'] = $this->model('PelangganModel')->getById($id);
        $this->view('pelanggan/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        $errors = [];
        $oldInput = $_POST;

        if (empty($_POST['nama'])) {
            $errors['nama'] = 'Nama pelanggan wajib diisi';
        }

        if (empty($_POST['no_hp'])) {
            $errors['no_hp'] = 'No. Telepon wajib diisi';
        } elseif (!preg_match('/^[0-9]{10,13}$/', $_POST['no_hp'])) {
            $errors['no_hp'] = 'Format No. Telepon tidak valid (masukkan 10-13 angka)';
        }

        if (!empty($_POST['email'])) {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format Email tidak valid';
            } else {
                // Periksa jika email sudah digunakan oleh pelanggan lain
                if ($this->model('PelangganModel')->isEmailExistForOtherUser($_POST['email'], $id)) {
                    $errors['email'] = 'Email sudah digunakan oleh pelanggan lain, silakan gunakan email lain';
                }
            }
        }

        // Jika ada error, kembalikan ke halaman edit pelanggan
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $oldInput;
            header('Location: ' . BASE_URL . '/pelanggan/edit/' . $id);
            exit;
        }

        // Simpan pembaruan data pelanggan
        $pelangganModel = $this->model('PelangganModel');
        $result = $pelangganModel->update($id, $_POST);

        if ($result) {
            // Set flash message untuk sukses
            $_SESSION['flash_message'] = 'Data pelanggan berhasil diperbarui!';
            $_SESSION['flash_type'] = 'success';
        } else {
            // Set flash message untuk gagal
            $_SESSION['flash_message'] = 'Gagal memperbarui data pelanggan!';
            $_SESSION['flash_type'] = 'error';
        }

        // Redirect ke halaman index
        header('Location: ' . BASE_URL . '/pelanggan');
        exit;
    }

    public function detail($id)
    {
        $pelanggan = $this->model('PelangganModel')->getById($id);

        if ($pelanggan) {
            echo json_encode([
                'status' => 'success',
                'data' => $pelanggan
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data pelanggan tidak ditemukan.'
            ]);
        }
    }

    public function delete($id)
    {
        $pelangganModel = $this->model('PelangganModel'); // Panggil model
        $result = $pelangganModel->delete($id); // Hapus data berdasarkan ID

        if ($result) {
            // Set flash message untuk sukses
            $_SESSION['flash_message'] = 'Data pelanggan berhasil dihapus!';
            $_SESSION['flash_type'] = 'success';
        } else {
            // Set flash message untuk gagal
            $_SESSION['flash_message'] = 'Gagal menghapus data pelanggan!';
            $_SESSION['flash_type'] = 'error';
        }

        // Redirect kembali ke halaman index
        header('Location: ' . BASE_URL . '/pelanggan');
        exit;
    }
}
