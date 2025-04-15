<?php

namespace app\models;

use Database;

class TransaksiModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllTransaksi() {
        $this->db->query('SELECT t.*, p.nama FROM transaksi t 
                         JOIN pelanggan p ON t.pelanggan_id = p.id
                         ORDER BY t.tgl_transaksi DESC');
        return $this->db->resultSet();
    }

    public function getTransaksiById($id) {
        $this->db->query('SELECT t.*, p.nama FROM transaksi t
                         JOIN pelanggan p ON t.pelanggan_id = p.id
                         WHERE t.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getDetailTransaksi($transaksi_id) {
        $this->db->query('SELECT dt.*, p.nama_produk, p.harga_produk FROM detail_transaksi dt
                         JOIN produk p ON dt.produk_id = p.id
                         WHERE dt.transaksi_id = :transaksi_id');
        $this->db->bind(':transaksi_id', $transaksi_id);
        return $this->db->resultSet();
    }

    public function createTransaksi($data) {
        try {
            $this->db->beginTransaction();

            // Insert transaksi
            $this->db->query('INSERT INTO transaksi (uang_diberikan, total_harga, kembalian, tgl_transaksi, pelanggan_id) 
                             VALUES (:uang_diberikan, :total_harga, :kembalian, :tgl_transaksi, :pelanggan_id)');
            $this->db->bind(':uang_diberikan', $data['uang_diberikan']);
            $this->db->bind(':total_harga', $data['total_harga']);
            $this->db->bind(':kembalian', $data['kembalian']);
            $this->db->bind(':tgl_transaksi', date('Y-m-d'));
            $this->db->bind(':pelanggan_id', $data['pelanggan_id']);
            $this->db->execute();

            // Get last insert ID for transaksi
            $transaksi_id = $this->db->lastInsertId();

            // Insert detail transaksi
            foreach ($data['items'] as $item) {
                $this->db->query('INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, subtotal) 
                                 VALUES (:transaksi_id, :produk_id, :jumlah, :subtotal)');
                $this->db->bind(':transaksi_id', $transaksi_id);
                $this->db->bind(':produk_id', $item['produk_id']);
                $this->db->bind(':jumlah', $item['quantity']);
                $this->db->bind(':subtotal', $item['subtotal']);
                $this->db->execute();

                // Update product stok and terjual
                $this->db->query('UPDATE produk SET stok = stok - :jumlah, terjual = terjual + :jumlah 
                                 WHERE id = :produk_id');
                $this->db->bind(':jumlah', $item['quantity']);
                $this->db->bind(':produk_id', $item['produk_id']);
                $this->db->execute();
            }

            $this->db->commit();
            return $transaksi_id;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
