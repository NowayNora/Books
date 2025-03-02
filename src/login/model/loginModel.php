<?php

namespace Login;

use Engine\Base;
use Service\Database;
use PDO;
use Exception;

class LoginModel extends Base
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->database->prepare("SELECT t.ID_TAIKHOAN, t.USERNAME, t.PASSWORD, t.ID_QUYEN, t.TRANGTHAI, 
                                           n.NAME, n.EMAIL, n.SDT 
                                    FROM taikhoans t
                                    JOIN nguoidungs n ON t.ID_NGUOIDUNG = n.ID_NGUOIDUNG
                                    WHERE t.USERNAME = :USERNAME AND t.TRANGTHAI = 1");
        $stmt->bindValue(':USERNAME', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }



    // public function getSachsForChart(): array
    // {
    //     $stmt = $this->database->query("SELECT ID_LOAI, COUNT(*) as total FROM sachs GROUP BY ID_LOAI");
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function registerUser(array $userData): array
    {
        $response = ['success' => false, 'message' => ''];

        if (empty($userData['username']) || empty($userData['password']) || empty($userData['confirmPassword']) || empty($userData['name']) || empty($userData['email'])) {
            $response['message'] = 'Vui lòng nhập đầy đủ thông tin.';
        } elseif ($userData['password'] !== $userData['confirmPassword']) {
            $response['message'] = 'Mật khẩu xác nhận không khớp.';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Email không hợp lệ.';
        } elseif (!preg_match('/^0[0-9]{9,10}$/', $userData['sdt'])) {
            $response['message'] = 'Số điện thoại phải bắt đầu bằng số 0 và có 10 hoặc 11 chữ số.';
        } else {
            if ($this->findByUsername($userData['username'])) {
                $response['message'] = 'Tên đăng nhập đã tồn tại.';
            } elseif ($this->createUserWithDetails(
                $userData['username'],
                $userData['password'],
                $userData['idQuyen'],
                $userData['name'],
                $userData['email'],
                $userData['sdt'],
                $userData['diachi']
            )) {
                $response['success'] = true;
                $response['message'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
            } else {
                $response['message'] = 'Đăng ký thất bại. Vui lòng thử lại.';
            }
        }

        return $response;
    }

    public function resetPassword(array $postData): array
    {
        $response = ['success' => false, 'message' => ''];

        if (!isset($_SESSION['csrf_token']) || $postData['csrf_token'] !== $_SESSION['csrf_token']) {
            $response['message'] = 'Token không hợp lệ.';
            return $response;
        }

        if (empty($postData['username']) || empty($postData['newPassword'])) {
            $response['message'] = 'Vui lòng nhập đầy đủ thông tin.';
        } elseif (strlen($postData['newPassword']) < 8) {
            $response['message'] = 'Mật khẩu phải có ít nhất 8 ký tự.';
        } else {
            $user = $this->findByUsername($postData['username']);
            if ($user) {
                $hashedPassword = password_hash($postData['newPassword'], PASSWORD_DEFAULT);
                if ($this->updatePassword($postData['username'], $hashedPassword)) {
                    $response['success'] = true;
                    $response['message'] = 'Mật khẩu đã được đặt lại thành công.';
                } else {
                    $response['message'] = 'Có lỗi xảy ra, vui lòng thử lại.';
                }
            } else {
                $response['message'] = 'Tên tài khoản không tồn tại.';
            }
        }

        return $response;
    }

    public function updatePassword(string $username, string $hashedPassword): bool
    {
        $stmt = $this->database->prepare("UPDATE taikhoans SET PASSWORD = :password WHERE USERNAME = :username");
        return $stmt->execute([
            ':password' => $hashedPassword,
            ':username' => $username
        ]);
    }

    public function createUserWithDetails(
        string $username,
        string $password,
        int $idQuyen,
        string $name,
        string $email,
        string $sdt,
        string $diachi
    ): bool {
        try {
            $this->database->beginTransaction();

            // Thêm người dùng vào bảng `nguoidungs`
            $stmtNguoiDung = $this->database->prepare("
            INSERT INTO nguoidungs (NAME, EMAIL, SDT, DIACHI) 
            VALUES (:NAME, :EMAIL, :SDT, :DIACHI)
        ");
            $stmtNguoiDung->execute([
                ':NAME' => $name,
                ':EMAIL' => $email,
                ':SDT' => $sdt,
                ':DIACHI' => $diachi
            ]);

            $idNguoidung = $this->database->getLastId();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Thêm tài khoản vào bảng `taikhoans`
            $stmtTaiKhoan = $this->database->prepare("
            INSERT INTO taikhoans (USERNAME, PASSWORD, ID_QUYEN, ID_NGUOIDUNG, NGAYTAO, TRANGTHAI) 
            VALUES (:USERNAME, :PASSWORD, :ID_QUYEN, :ID_NGUOIDUNG, NOW(), 1)
        ");
            $stmtTaiKhoan->execute([
                ':USERNAME' => $username,
                ':PASSWORD' => $hashedPassword,
                ':ID_QUYEN' => $idQuyen,
                ':ID_NGUOIDUNG' => $idNguoidung
            ]);

            $this->database->commit();
            return true;
        } catch (Exception $e) {
            $this->database->rollBack();
            error_log("Lỗi khi tạo người dùng: " . $e->getMessage());
            return false;
        }
    }

    # Model lấy dữ liệu (Model: Legit)
    public function Outputdata_books(): array
    {
        return $this->database->query("SELECT SOLUONG FROM sachs");
    }

    public function Outputdata_books_in(): array
    {
        return $this->database->query("SELECT * FROM donbans");
    }

    public function Outputdata_books_out(): array
    {
        return $this->database->query("SELECT * FROM donnhaps");
    }

    public function Outputdata_books_cal_in(): array
    {
        return $this->database->query("SELECT SOLUONG FROM chitietdonnhap");
    }

    public function Outputdata_books_cal_out(): array
    {
        return $this->database->query("SELECT SOLUONG FROM chitietdonban");
    }

    public function Outputdata_money(): array
    {
        return $this->database->query("SELECT TONGTIEN, THOIGIANLAPBAN FROM donbans");
    }
}
