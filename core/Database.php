<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;

    private $dbh; // database handler
    private $stmt;

    public function __construct() {
        // Data Source Name
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            die('Koneksi gagal: ' . $e->getMessage());
        }
    }

    // Metode untuk menjalankan query
    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    // Metode untuk binding data
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Metode untuk eksekusi query
    public function execute() {
        return $this->stmt->execute();
    }

    // Metode untuk mengambil semua data
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Metode untuk mengambil satu data
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Metode untuk menghitung jumlah baris
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    // Metode untuk memulai transaksi
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    // Metode untuk commit transaksi
    public function commit() {
        return $this->dbh->commit();
    }

    // Metode untuk rollback transaksi
    public function rollBack() {
        return $this->dbh->rollBack();
    }

    // Metode untuk mendapatkan ID terakhir yang di-insert
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
