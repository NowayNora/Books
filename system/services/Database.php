<?php

namespace Service;

use PDO;
use PDOException;

class Database
{
    private ?PDO $_connection = null;
    private string $_host;
    private string $_user;
    private string $_db_name;
    private string $_pass;

    public function __construct(string $host, string $user, string $db_name, string $pass)
    {
        $this->_host = $host;
        $this->_user = $user;
        $this->_db_name = $db_name;
        $this->_pass = $pass;
        $this->initialize(); // Khởi tạo kết nối khi tạo đối tượng
    }

    /**
     * Khởi tạo kết nối PDO
     */
    public function initialize(): void
    {
        try {
            $this->_connection = new PDO(
                "mysql:host={$this->_host};dbname={$this->_db_name};charset=utf8mb4",
                $this->_user,
                $this->_pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // Bật chuẩn bị thực sự
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new PDOException("Không thể kết nối đến database.");
        }
    }

    /**
     * Chuẩn bị truy vấn
     */
    public function prepare(string $query)
    {
        if (!$this->_connection) {
            throw new PDOException("Database chưa được khởi tạo.");
        }
        return $this->_connection->prepare($query);
    }

    /**
     * Thực thi truy vấn SQL với các tham số an toàn
     */
    public function query(string $sql_query, array $params = []): array|bool
    {
        if (!$this->_connection) {
            throw new PDOException("Database chưa được khởi tạo.");
        }

        $stmt = $this->_connection->prepare($sql_query);
        $success = $stmt->execute($params);

        // Nếu là SELECT, trả về kết quả
        if (stripos($sql_query, "SELECT") === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        }

        // Nếu là UPDATE, INSERT, DELETE -> Trả về true nếu có dòng bị ảnh hưởng
        return $success && $stmt->rowCount() > 0;
    }


    /**
     * Lấy ID của bản ghi vừa chèn vào
     */
    public function getLastId(): int
    {
        return $this->_connection ? (int)$this->_connection->lastInsertId() : 0;
    }

    /**
     * Bắt đầu transaction
     */
    public function beginTransaction(): void
    {
        if ($this->_connection) {
            $this->_connection->beginTransaction();
        }
    }

    public function commit(): void
    {
        if ($this->_connection) {
            $this->_connection->commit();
        }
    }

    public function rollBack(): void
    {
        if ($this->_connection) {
            $this->_connection->rollBack();
        }
    }

    /**
     * Hủy kết nối database
     */
    public function __destruct()
    {
        $this->_connection = null;
    }
}
