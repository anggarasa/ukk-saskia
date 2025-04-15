<?php

namespace app\models;

use Database;

class RiwayatModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllTransaksi()
    {
        $this->db->query('SELECT 
                            t.id as transaksi_id,
                            t.tgl_transaksi,
                            t.uang_diberikan,
                            t.total_harga,
                            t.kembalian,
                            p.nama as nama_pelanggan
                          FROM transaksi t
                          JOIN pelanggan p ON t.pelanggan_id = p.id
                          ORDER BY t.id DESC');
        return $this->db->resultSet();
    }

    public function getDetailTransaksi($transaksi_id)
    {
        $this->db->query('SELECT 
                        t.id as transaksi_id,
                        t.tgl_transaksi,
                        t.uang_diberikan,
                        t.total_harga,
                        t.kembalian,
                        p.nama as nama_pelanggan,
                        p.email,
                        p.no_hp,
                        p.alamat,
                        pr.nama_produk,
                        pr.harga_produk,
                        dt.jumlah,
                        dt.subtotal
                      FROM transaksi t
                      JOIN pelanggan p ON t.pelanggan_id = p.id
                      JOIN detail_transaksi dt ON dt.transaksi_id = t.id
                      JOIN produk pr ON dt.produk_id = pr.id
                      WHERE t.id = :transaksi_id');
        $this->db->bind(':transaksi_id', (int)$transaksi_id);
        return $this->db->resultSet();
    }

    public function getTransaksiPaginated($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $this->db->query('SELECT 
                        t.id as transaksi_id,
                        t.tgl_transaksi,
                        t.uang_diberikan,
                        t.total_harga,
                        t.kembalian,
                        p.nama as nama_pelanggan
                      FROM transaksi t
                      JOIN pelanggan p ON t.pelanggan_id = p.id
                      ORDER BY t.id DESC
                      LIMIT :limit OFFSET :offset');

        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    public function getTotalTransaksi()
    {
        $this->db->query('SELECT COUNT(t.id) as total FROM transaksi t
                      JOIN pelanggan p ON t.pelanggan_id = p.id');
        return $this->db->single()['total'];
    }

    public function searchTransaksi($keyword, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $this->db->query('SELECT 
                    t.id as transaksi_id,
                    t.tgl_transaksi,
                    t.uang_diberikan,
                    t.total_harga,
                    t.kembalian,
                    p.nama as nama_pelanggan
                  FROM transaksi t
                  JOIN pelanggan p ON t.pelanggan_id = p.id
                  WHERE p.nama LIKE :keyword 
                  OR DATE_FORMAT(t.tgl_transaksi, "%d/%m/%Y") LIKE :keyword
                  OR t.total_harga LIKE :keyword
                  ORDER BY t.id DESC
                  LIMIT :limit OFFSET :offset');

        $this->db->bind(':keyword', "%$keyword%");
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    public function getTotalSearchTransaksi($keyword)
    {
        $this->db->query('SELECT COUNT(t.id) as total 
                  FROM transaksi t
                  JOIN pelanggan p ON t.pelanggan_id = p.id
                  WHERE p.nama LIKE :keyword 
                  OR DATE_FORMAT(t.tgl_transaksi, "%d/%m/%Y") LIKE :keyword
                  OR t.total_harga LIKE :keyword');

        $this->db->bind(':keyword', "%$keyword%");

        return $this->db->single()['total'];
    }
}
