<?php

namespace app\models;

use Database;

class ProdukModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM produk ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM produk WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function countAll()
    {
        $this->db->query("SELECT COUNT(*) as total FROM produk");
        $result = $this->db->single();
        return $result['total'];
    }

    public function add($data)
    {
        $query = "INSERT INTO produk (nama_produk, harga_produk, stok) VALUES (:nama_produk, :harga_produk, :stok)";
        $this->db->query($query) ;
        $this->db->bind('nama_produk', $data['nama_produk']);
        $this->db->bind('harga_produk', $data['harga_produk']);
        $this->db->bind('stok', $data['stok']);
        return $this->db->execute();
    }

    public function update($data)
    {
        $query = "UPDATE produk SET nama_produk = :nama_produk, harga_produk = :harga_produk, stok = :stok WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('nama_produk', $data['nama_produk']);
        $this->db->bind('harga_produk', $data['harga_produk']);
        $this->db->bind('stok', $data['stok']);
        return $this->db->execute();
    }

    public function deleteById($id)
    {
        $query = "DELETE FROM produk WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // ProdukModel.php
    public function getPaginated($limit, $offset)
    {
        $query = "SELECT * FROM produk ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $this->db->query($query);
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // ProdukModel.php
    public function search($keyword, $limit, $offset)
    {
        $query = "
        SELECT *
        FROM produk
        WHERE nama_produk LIKE :keyword
           OR harga_produk LIKE :keyword
           OR stok LIKE :keyword
           OR terjual LIKE :keyword
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ";
        $this->db->query($query);
        $this->db->bind(':keyword', '%' . $keyword . '%');
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, \PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    public function countSearchResults($keyword)
    {
        $query = "
        SELECT COUNT(*) as total
        FROM produk
        WHERE nama_produk LIKE :keyword
           OR harga_produk LIKE :keyword
           OR stok LIKE :keyword
           OR terjual LIKE :keyword
    ";
        $this->db->query($query);
        $this->db->bind(':keyword', '%' . $keyword . '%');

        $result = $this->db->single();
        return $result['total'];
    }

    // Menghitung total produk terjual
    public function countTotalSold()
    {
        $this->db->query("SELECT SUM(terjual) as total_sold FROM produk");
        $result = $this->db->single();
        return $result['total_sold'] ?? 0;
    }

// Menghitung total pendapatan
    public function countTotalRevenue()
    {
        $this->db->query("SELECT SUM(harga_produk * terjual) as total_revenue FROM produk");
        $result = $this->db->single();
        return $result['total_revenue'] ?? 0;
    }
}
