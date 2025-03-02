<?php

namespace Login;

use Engine\Base;
use Service\Database;
use Service\Session;
use Login\LoginModel;
use Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class LoginController extends Base
{
    private LoginModel $loginModel;
    private Session $session;

    public function __construct()
    {
        try {
            $database = new Database('localhost', 'root', 'qlsach', '');
            $this->loginModel = new LoginModel($database);

            // Sử dụng session với PHP default handler
            $this->session = new Session(new \SessionHandler(), 'user_session');
            $this->session->start();
        } catch (Exception $e) {
            error_log("Lỗi kết nối database: " . $e->getMessage());
            throw new Exception("Không thể kết nối đến database.");
        }
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(): void
    {
        $username = htmlspecialchars($_POST['USERNAME'] ?? '');
        $password = $_POST['PASSWORD'] ?? '';
        $data = ['error' => null, 'USERNAME' => $username];

        if (empty($username) || empty($password)) {
            $data['error'] = 'Vui lòng nhập đầy đủ thông tin đăng nhập.';
        } else {
            try {
                $user = $this->loginModel->findByUsername($username);
                if ($user && password_verify($password, $user['PASSWORD'])) {
                    $this->session->set(
                        'user',
                        [
                            'id' => $user['ID_TAIKHOAN'],
                            'username' => $user['USERNAME'],
                            'name' => $user['NAME'],
                            'email' => $user['EMAIL'],
                            'sdt' => $user['SDT'],
                            'role' => $user['ID_QUYEN']
                        ]
                    );

                    $this->session->set('user_logged_in', true);

                    header('Location: /index');
                    exit;
                } else {
                    $data['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng.';
                }
            } catch (Exception $e) {
                error_log("Lỗi hệ thống: " . $e->getMessage());
                $data['error'] = 'Lỗi hệ thống, vui lòng thử lại sau.';
            }
        }
        $this->output->load("login/login", $data);
    }

    /**
     * Hiển thị trang index khi đăng nhập thành công
     */
    public function indexView(): void
    {
        if (!$this->session->get('user_logged_in')) {
            header("Location: /");
            exit();
        }
        # Dữ liệu cần sử lý ở đây rồi đưa lên indexView (Controller_Type = Session_Hacked).
        $_SESSION['book_count'] = array();
        $_SESSION['book_count'] = $this->loginModel->Outputdata_books();

        $_SESSION['book_count_in'] = array();
        $_SESSION['book_count_in'] = $this->loginModel->Outputdata_books_in();

        $_SESSION['book_count_out'] = array();
        $_SESSION['book_count_out'] = $this->loginModel->Outputdata_books_out();

        $_SESSION['book_cal_in'] = array();
        $_SESSION['book_cal_in'] = $this->loginModel->Outputdata_books_cal_in();

        $_SESSION['book_cal_out'] = array();
        $_SESSION['book_cal_out'] = $this->loginModel->Outputdata_books_cal_out();

        $_SESSION['sale_value'] = array();
        $_SESSION['sale_value'] = $this->loginModel->Outputdata_money();

        $this->output->load("login/index");
    }

    /**
     * Xử lý đăng ký tài khoản
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => htmlspecialchars($_POST['USERNAME'] ?? ''),
                'password' => $_POST['PASSWORD'] ?? '',
                'confirmPassword' => $_POST['CONFIRM_PASSWORD'] ?? '',
                'name' => htmlspecialchars($_POST['NAME'] ?? ''),
                'email' => htmlspecialchars($_POST['EMAIL'] ?? ''),
                'sdt' => htmlspecialchars($_POST['SDT'] ?? ''),
                'diachi' => htmlspecialchars($_POST['DIACHI'] ?? ''),
                'idQuyen' => 2 // Mặc định là quyền user, có thể thay đổi tùy logic của bạn
            ];

            $response = $this->loginModel->registerUser($userData);

            // Trong hàm register của LoginController
            if ($response['success']) {
                echo json_encode(['success' => true, 'message' => $response['message']]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => $response['message']]);
                exit;
            }
        } else {
            // Nếu không phải POST, hiển thị form đăng ký
            $this->output->load("login/login");
        }
    }

    /**
     * Xử lý quên mật khẩu
     */
    public function forgotPassword(): void
    {
        // header('Content-Type: application/json'); // Đặt header JSON

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = [
                'username' => htmlspecialchars($_POST['USERNAME'] ?? ''),
                'newPassword' => $_POST['newPassword'] ?? '',
                'csrf_token' => $_POST['csrf_token'] ?? ''
            ];

            $response = $this->loginModel->resetPassword($postData);

            echo json_encode([
                'success' => $response['success'],
                'message' => $response['message']
            ]);
            exit;
        } else {
            // Nếu không phải POST, hiển thị form quên mật khẩu
            $this->output->load("login/forgot-password");
        }
    }

    /**
     * Đăng xuất
     */
    public function logout(): void
    {
        $this->session->forget();

        header("Location: /login");
        exit();
    }
}
