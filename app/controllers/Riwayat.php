<?php

class Riwayat extends Controller
{
    public function index($page = 1)
    {
        // Convert $page ke integer dan pastikan minimal 1
        $page = (int)$page;
        if ($page < 1) $page = 1;

        $limit = 5; // Jumlah data per halaman

        $data['keyword'] = ''; // Tambahkan ini
        $data['transaksis'] = $this->model('RiwayatModel')->getTransaksiPaginated($page, $limit);
        $data['total_data'] = $this->model('RiwayatModel')->getTotalTransaksi();
        $data['total_pages'] = ceil($data['total_data'] / $limit);
        $data['current_page'] = $page;

        $this->view('riwayat/index', $data);
    }

    public function detail($transaksi_id)
    {
        $items = $this->model('RiwayatModel')->getDetailTransaksi($transaksi_id);

        if ($items) {
            // Ambil data transaksi umum dari item pertama
            $transaksiInfo = !empty($items[0]) ? [
                'transaksi_id' => $items[0]['transaksi_id'],
                'tgl_transaksi' => $items[0]['tgl_transaksi'],
                'nama_pelanggan' => $items[0]['nama_pelanggan'],
                'email' => $items[0]['email'],
                'no_hp' => $items[0]['no_hp'],
                'alamat' => $items[0]['alamat'],
                'uang_diberikan' => $items[0]['uang_diberikan'],
                'total_harga' => $items[0]['total_harga'],
                'kembalian' => $items[0]['kembalian']
            ] : [];

            echo json_encode([
                'status' => 'success',
                'data' => $items,
                'transaksi' => $transaksiInfo
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data transaksi tidak ditemukan.'
            ]);
        }
    }

    public function search()
    {
        $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;

        if ($page < 1) $page = 1;

        $limit = 5; // Jumlah data per halaman

        $data['keyword'] = $keyword;
        $data['transaksis'] = $this->model('RiwayatModel')->searchTransaksi($keyword, $page, $limit);
        $data['total_data'] = $this->model('RiwayatModel')->getTotalSearchTransaksi($keyword);
        $data['total_pages'] = ceil($data['total_data'] / $limit);
        $data['current_page'] = $page;

        $this->view('riwayat/index', $data);
    }

}
