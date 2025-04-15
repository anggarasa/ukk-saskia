<?php

namespace app\models;

class DashboardModel
{
    private $db;

    public function __construct()
    {
        $this->db = new \Database();
    }

    public function getTotalPendapatan()
    {
        // Asumsi ada tabel 'transaksi' dengan kolom 'total'
        $this->db->query("SELECT SUM(total_harga) as total_pendapatan FROM transaksi");
        return $this->db->single();
    }

    public function getJumlahTransaksi()
    {
        $this->db->query("SELECT COUNT(*) as jumlah_transaksi FROM transaksi");
        return $this->db->single();
    }

    public function getJumlahProdukTerjual()
    {
        // Asumsi ada tabel 'detail_transaksi' dengan kolom 'jumlah'
        $this->db->query("SELECT SUM(jumlah) as produk_terjual FROM detail_transaksi");
        return $this->db->single();
    }

    public function getJumlahPelangganBaru()
    {
        // Asumsi ada tabel 'pelanggan' dengan kolom 'tanggal_daftar'
        $this->db->query("SELECT COUNT(*) as pelanggan_baru FROM pelanggan");
        return $this->db->single();
    }

    public function getTransaksiTerbaru($limit = 5)
    {
        // Asumsi ada relasi antara transaksi dan pelanggan
        $this->db->query("SELECT t.*, p.nama as nama_pelanggan 
                          FROM transaksi t 
                          JOIN pelanggan p ON t.pelanggan_id = p.id
                          ORDER BY t.id DESC 
                          LIMIT :limit");
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    public function getProdukTerlaris($limit = 5)
    {
        // Asumsi ada relasi antara detail_transaksi dan produk
        $this->db->query("SELECT p.nama_produk, SUM(dt.jumlah) as total_terjual 
                          FROM detail_transaksi dt 
                          JOIN produk p ON dt.produk_id = p.id
                          GROUP BY dt.produk_id
                          ORDER BY terjual DESC
                          LIMIT :limit");
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
}
