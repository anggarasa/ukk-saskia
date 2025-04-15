<?php

class Dashboard extends Controller
{
    private $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = $this->model('DashboardModel');
    }

    public function index()
    {
        // Mengambil data dari model
        $data = [
            'total_pendapatan' => $this->dashboardModel->getTotalPendapatan(),
            'jumlah_transaksi' => $this->dashboardModel->getJumlahTransaksi(),
            'produk_terjual' => $this->dashboardModel->getJumlahProdukTerjual(),
            'pelanggan_baru' => $this->dashboardModel->getJumlahPelangganBaru(),
            'transaksi_terbaru' => $this->dashboardModel->getTransaksiTerbaru(),
            'produk_terlaris' => $this->dashboardModel->getProdukTerlaris()
        ];

        $this->view('dashboard', $data);
    }
}
