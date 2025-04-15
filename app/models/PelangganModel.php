<?php
namespace app\models;

use Database;

class PelangganModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM pelanggan ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function add($data)
    {
        $query = "INSERT INTO pelanggan (nama, email, alamat, no_hp) VALUES (:nama, :email, :alamat, :no_hp)";
        $this->db->query($query) ;
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('alamat', $data['alamat']);
        $this->db->bind('no_hp', $data['no_hp']);
        return $this->db->execute();
    }

    public function isEmailExist($email)
    {
        // Gunakan query() untuk mempersiapkan SQL
        $this->db->query("SELECT COUNT(*) as jumlah FROM pelanggan WHERE email = :email");
        $this->db->bind(':email', $email); // Binding parameter
        $result = $this->db->single(); // Ambil hasil satu baris data

        return $result['jumlah'] > 0; // Kembalikan true jika email sudah ada
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM pelanggan WHERE id = :id");
        $this->db->bind(':id', $id); // Bind parameter untuk keamanan
        return $this->db->single(); // Ambil satu baris hasil
    }

    public function update($id, $data)
    {
        $query = "UPDATE pelanggan SET nama = :nama, email = :email, no_hp = :no_hp, alamat = :alamat WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':no_hp', $data['no_hp']);
        $this->db->bind(':alamat', $data['alamat']);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function isEmailExistForOtherUser($email, $id)
    {
        $query = "SELECT COUNT(*) as jumlah FROM pelanggan WHERE email = :email AND id != :id";
        $this->db->query($query);
        $this->db->bind(':email', $email);
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        return $result['jumlah'] > 0; // Return true jika email ditemukan untuk pengguna lain
    }

    public function delete($id)
    {
        $query = "DELETE FROM pelanggan WHERE id = :id";
        $this->db->query($query);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getPaginated($start, $limit)
    {
        $query = "SELECT * FROM pelanggan ORDER BY id DESC LIMIT :start, :limit";
        $this->db->query($query);
        $this->db->bind(':start', $start, \PDO::PARAM_INT); // Ikat offset dan harus berupa integer
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT); // Ikat limit dan harus berupa integer
        return $this->db->resultSet(); // Ambil data
    }

    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM pelanggan";
        $this->db->query($query);
        $result = $this->db->single(); // Ambil count total data
        return $result['total']; // Kembalikan total jumlah data
    }

    public function search($keyword, $start, $limit)
    {
        $query = "SELECT * FROM pelanggan 
              WHERE nama LIKE :keyword OR email LIKE :keyword OR no_hp LIKE :keyword 
              ORDER BY id DESC 
              LIMIT :start, :limit";
        $this->db->query($query);
        $this->db->bind(':keyword', "%$keyword%");
        $this->db->bind(':start', $start, \PDO::PARAM_INT);
        $this->db->bind(':limit', $limit, \PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function countSearch($keyword)
    {
        $query = "SELECT COUNT(*) as total FROM pelanggan 
              WHERE nama LIKE :keyword OR email LIKE :keyword OR no_hp LIKE :keyword";
        $this->db->query($query);
        $this->db->bind(':keyword', "%$keyword%");
        $result = $this->db->single();
        return $result['total'];
    }
}
