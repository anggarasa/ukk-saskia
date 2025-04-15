<?php

class Transaksi extends Controller {
    public function index() {
        // Load models
        $pelangganModel = $this->model('PelangganModel');
        $produkModel = $this->model('ProdukModel');

        $data = [
            'pelanggans' => $pelangganModel->getAll(),
            'produks' => $produkModel->getAll()
        ];

        $this->view('transaksi/index', $data);
    }

    public function create() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process JSON data
            $postData = json_decode(file_get_contents("php://input"), true);

            // Validasi JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['status' => 'error', 'message' => 'Format JSON tidak valid']);
                return;
            }

            // Calculate kembalian
            $kembalian = $postData['uang_diberikan'] - $postData['total_harga'];
            if ($kembalian < 0) {
                echo json_encode(['status' => 'error', 'message' => 'Uang yang diberikan kurang']);
                return;
            }

            // Format data for model
            $transaksiData = [
                'pelanggan_id' => $postData['pelanggan_id'],
                'items' => $postData['items'],
                'total_harga' => $postData['total_harga'],
                'uang_diberikan' => $postData['uang_diberikan'],
                'kembalian' => $kembalian
            ];

            // Save transaction
            $transaksiModel = $this->model('TransaksiModel');
            $transaksi_id = $transaksiModel->createTransaksi($transaksiData);

            if ($transaksi_id) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil disimpan',
                    'transaksi_id' => $transaksi_id,
                    'kembalian' => $kembalian
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan transaksi']);
            }
        } else {
            // Return error if not POST request
            echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
        }
    }
}
