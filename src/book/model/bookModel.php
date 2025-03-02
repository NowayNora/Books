<?php

namespace Book;

use Engine\Base;
use Service\Database;
use Exception;

class BookModel extends Base
{

    private Database $database;

    public function __construct(Database $database)
    {
        if (!$database instanceof Database) {
            throw new Exception("Database không hợp lệ");
        }
        $this->database = $database;
    }

    public function getAllBooks(): array
    {
        $books = $this->database->query("SELECT * FROM `sachs`") ?: [];

        foreach ($books as &$book) {
            if (!empty($book['HINHANHSACH'])) {
                $book['HINHANHSACH'] = 'data:image/jpeg;base64,' . base64_encode($book['HINHANHSACH']);
            } else {
                $book['HINHANHSACH'] = '/uploads/book.png'; // Ảnh mặc định nếu không có ảnh
            }
        }

        return $books;
    }



    public function getBooksByCategoryId(int $categoryId): array
    {
        return $this->database->query("SELECT * FROM sachs WHERE ID_LOAI = ?", [$categoryId]) ?: [];
    }

    public function getAllCategories(): array
    {
        return $this->database->query("SELECT ID_LOAI, TENLOAI FROM loaisachs") ?: [];
    }

    public function findBookById(int $ID_SACH)
    {
        $result = $this->database->query("SELECT * FROM sachs WHERE ID_SACH = ? LIMIT 1", [$ID_SACH]);
        return $result[0] ?? null;
    }

    public function findBooksByKeyword(string $keyword): array
    {
        $keyword = "%" . $keyword . "%";
        return $this->database->query(
            "SELECT * FROM sachs WHERE TENSACH LIKE ? OR TACGIA LIKE ?",
            [$keyword, $keyword]
        ) ?: [];
    }

    public function create(int $ID_LOAI, string $TENSACH, string $TACGIA, int $GIASACH, int $SOLUONG, int $TINHTRANG, ?string $HINHANHSACH)
    {
        $sql = "INSERT INTO sachs (ID_LOAI, TENSACH, TACGIA, GIASACH, SOLUONG, TINHTRANG, HINHANHSACH) 
            VALUES (:ID_LOAI, :TENSACH, :TACGIA, :GIASACH, :SOLUONG, :TINHTRANG, :HINHANHSACH)";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([
            'ID_LOAI' => $ID_LOAI,
            'TENSACH' => $TENSACH,
            'TACGIA' => $TACGIA,
            'GIASACH' => $GIASACH,
            'SOLUONG' => $SOLUONG,
            'TINHTRANG' => $TINHTRANG,
            'HINHANHSACH' => $HINHANHSACH
        ]);
    }



    public function updateBook(
        int $ID_SACH,
        int $ID_LOAI,
        string $TENSACH,
        string $TACGIA,
        string $MOTASACH,
        string $NHAUXUATBAN,
        int $GIASACH,
        int $SOLUONG,
        int $TINHTRANG,
        string $HINHANHSACH
    ) {

        // var_dump($this->database);
        // exit();

        $query = "UPDATE sachs SET 
                ID_LOAI = ?, TENSACH = ?,
                TACGIA = ?, MOTASACH = ?, 
                NHAUXUATBAN = ?, GIASACH = ?,
                SOLUONG = ?, TINHTRANG = ?, 
                HINHANHSACH = ? WHERE ID_SACH = ?";

        return $this->database->query($query, [$ID_LOAI, $TENSACH, $TACGIA, $MOTASACH, $NHAUXUATBAN, $GIASACH, $SOLUONG, $TINHTRANG, $HINHANHSACH, $ID_SACH]);
    }

    public function deleteBook(int $ID_SACH): bool
    {
        return (bool) $this->database->query("DELETE FROM sachs WHERE ID_SACH = ?", [$ID_SACH]);
    }

    public function getSachsForChart(): array
    {
        return $this->database->query("SELECT ID_LOAI, COUNT(*) as total FROM sachs GROUP BY ID_LOAI") ?: [];
    }

    public function getAllLoaiSachs(): array
    {
        return $this->database->query("SELECT * FROM loaisachs") ?: [];
    }

    public function checkLoaiSachExists($idLoai): bool
    {
        $sql = "SELECT COUNT(*) as count FROM loaisachs WHERE ID_LOAI = :idLoai";
        $stmt = $this->database->prepare($sql); // ✅ SỬA LẠI
        $stmt->execute(['idLoai' => $idLoai]);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    }

    public function countBooks(): int
    {
        $result = $this->database->query("SELECT COUNT(*) as total FROM sachs");
        return $result[0]['total'] ?? 0;
    }

    public function getPaginatedBooks(int $limit, int $offset): array
    {
        return $this->database->query("SELECT * FROM sachs LIMIT ? OFFSET ?", [$limit, $offset]) ?: [];
    }
}
