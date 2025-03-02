<?php

namespace Book;

use Engine\Container;
use Service\Database;
use Engine\Base;
use Exception;

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }


class BookController extends Base
{

    private BookModel $bookModel;
    private Database $database;

    public function __construct(Container $container)
    {
        $this->bookModel = $container->get('bookModel');
        $this->database = $container->get('database');

        if (!$this->bookModel instanceof BookModel) {
            throw new Exception("Lỗi: bookModel không hợp lệ");
        }
        if (!$this->database instanceof Database) {
            throw new Exception("Lỗi: Database không hợp lệ");
        }
    }

    public function listBooks(): void
    {
        $categories = $this->bookModel->getAllCategories();

        // $book = $this->bookModel->findBookById(73); // Thử lấy 1 sách
        // var_dump($book['HINHANHSACH']); // Kiểm tra dữ liệu ảnh

        $keyword = filter_input(INPUT_GET, 'keyword') ?? '';
        $categoryId = filter_input(INPUT_GET, 'ID_LOAI', FILTER_VALIDATE_INT);
        $page = max(1, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalBooks = $this->bookModel->countBooks();
        $totalPages = ceil($totalBooks / $limit);

        $books = match (true) {
            !empty($keyword) => $this->bookModel->findBooksByKeyword($keyword),
            $categoryId      => $this->bookModel->getBooksByCategoryId($categoryId),
            default          => $this->bookModel->getPaginatedBooks($limit, $offset),
        };

        $books = array_map([$this, 'processBookImage'], $books);

        // var_dump($books);
        // exit;


        $data = [
            'categories' => $categories,
            'books' => $books,
            'keyword' => $keyword,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];

        // $book = $this->bookModel->findBookById(73);
        // header("Content-Type: image/jpeg");
        // echo $book['HINHANHSACH'];
        // exit;


        $this->output->load("book/listBook", $data);
    }


    public function searchBooks(): void
    {
        $keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_STRING) ?? '';
        $books = !empty($keyword) ? $this->bookModel->findBooksByKeyword($keyword) : $this->bookModel->getAllBooks();

        if (!$books) {
            echo "<p>Không có sách nào được tìm thấy.</p>";
            return;
        }

        echo $this->renderBookTable($books);
    }

    public function create(): void
    {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->getBookFormData();
            $formData['HINHANHSACH'] = $this->handleImageUpload();

            if (empty($formData['TENSACH']) || empty($formData['TACGIA']) || $formData['ID_LOAI'] <= 0) {
                $data['error'] = "Vui lòng nhập đầy đủ thông tin sách.";
            } else {
                if ($this->bookModel->create(
                    $formData['ID_LOAI'],
                    $formData['TENSACH'],
                    $formData['TACGIA'],
                    $formData['GIASACH'],
                    $formData['SOLUONG'],
                    $formData['TINHTRANG'],
                    $formData['HINHANHSACH']
                )) {
                    $data['success'] = "Thêm sách thành công!";
                } else {
                    $data['error'] = "Có lỗi khi thêm sách.";
                }
            }
        }

        // $formData = $this->getBookFormData();
        // var_dump($formData);
        // exit;

        $this->output->load("book/add", $data);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = $this->getBookFormData(true);
            var_dump($formData); // Kiểm tra dữ liệu nhập vào
            if (!$this->bookModel->checkLoaiSachExists($formData['ID_LOAI'])) {
                die("Lỗi: ID_LOAI không tồn tại.");
            }

            if ($this->bookModel->updateBook(...array_values($formData))) {
                header("Location: /book");
                exit();
            } else {
                die("Lỗi: Không thể cập nhật sách.");
            }
        }

        $bookId = filter_input(INPUT_GET, 'ID_SACH', FILTER_VALIDATE_INT);
        if (!$bookId) {
            die("Lỗi: ID_SACH không hợp lệ!");
        }

        $data = [
            'book' => $this->bookModel->findBookById($bookId),
            'loaiSachs' => $this->bookModel->getAllLoaiSachs()
        ];

        if (!$data['book']) {
            die("Lỗi: Không tìm thấy sách với ID_SACH $bookId");
        }

        $this->output->load("book/update", $data);
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = filter_input(INPUT_POST, 'ID_SACH', FILTER_VALIDATE_INT)
                ?? filter_input(INPUT_GET, 'ID_SACH', FILTER_VALIDATE_INT);

            if ($bookId && $this->bookModel->deleteBook($bookId)) {
                header("Location: /index");
                exit();
            } else {
                echo "Lỗi: Không thể xóa sách.";
            }
        } else {
            echo "Yêu cầu không hợp lệ.";
        }
    }

    public function chartPage(): void
    {
        $result = $this->bookModel->getSachsForChart();
        $labels = array_map(fn($row) => "Loại " . $row['ID_LOAI'], $result);
        $sizes = array_column($result, 'total');

        $data = [
            'labels' => $labels,
            'datasets' => [['data' => $sizes, 'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']]]
        ];

        $this->output->load("book/chartPage", $data);
    }

    private function getBookFormData(bool $isUpdate = false): array
    {
        return [
            'ID_SACH' => filter_input(INPUT_POST, 'ID_SACH', FILTER_VALIDATE_INT) ?: 0,
            'ID_LOAI' => filter_input(INPUT_POST, 'ID_LOAI', FILTER_VALIDATE_INT) ?: 0,
            'TENSACH' => trim($_POST['TENSACH'] ?? ''),
            'TACGIA' => trim($_POST['TACGIA'] ?? ''),
            'MOTASACH' => trim($_POST['MOTASACH'] ?? ''),
            'NHAUXUATBAN' => trim($_POST['NHAUXUATBAN'] ?? ''), // ✅ Đảm bảo là string
            'GIASACH' => isset($_POST['GIASACH']) ? (int) $_POST['GIASACH'] : 0,
            'SOLUONG' => filter_input(INPUT_POST, 'SOLUONG', FILTER_VALIDATE_INT) ?: 0, // ✅ Luôn là int
            'TINHTRANG' => filter_input(INPUT_POST, 'TINHTRANG', FILTER_VALIDATE_INT) ?: 0,
            'HINHANHSACH' => trim($_POST['HINHANHSACH'] ?? '')
        ];
    }

    private function handleImageUpload(): ?string
    {
        if (empty($_FILES['HINHANHSACH']['tmp_name'])) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/'; // Đường dẫn thư mục uploads trong public
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Tạo thư mục nếu chưa tồn tại
        }

        $fileName = time() . '_' . basename($_FILES['HINHANHSACH']['name']); // Tạo tên file duy nhất
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['HINHANHSACH']['tmp_name'], $filePath)) {
            return "uploads/" . $fileName; // Lưu đường dẫn vào database
        }

        return null;
    }

    private function processBookImage(array $book): array
    {
        if (!empty($book['HINHANHSACH'])) {
            if (is_string($book['HINHANHSACH']) && file_exists(__DIR__ . '/../../../public/' . $book['HINHANHSACH'])) {
                $book['HINHANHSACH'] = '/' . $book['HINHANHSACH'];
            } else {
                $book['HINHANHSACH'] = 'uploads/book.png'; // Ảnh mặc định nếu không có ảnh hợp lệ
            }
        } else {
            $book['HINHANHSACH'] = 'uploads/book.png';
        }
        return $book;
    }


    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function renderBookTable(array $books): string
    {
        if (empty($books)) {
            return "<p>Không có sách nào được tìm thấy.</p>";
        }

        $html = '<table class="book-table">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên Sách</th>
                <th>Tác Giả</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($books as $book) {
            $html .= '<tr>
            <td><img src="' . htmlspecialchars($book['HINHANHSACH']) . '" style="max-width: 100px;"></td>
            <td>' . htmlspecialchars($book['TENSACH']) . '</td>
            <td>' . htmlspecialchars($book['TACGIA']) . '</td>
            <td>' . number_format($book['GIASACH'], 0, ',', '.') . ' đ</td>
            <td>' . htmlspecialchars($book['SOLUONG']) . '</td>
            <td class="action-buttons">
                <a href="/book/update?ID_SACH=' . $book['ID_SACH'] . '" class="edit-button">Sửa</a>
                <form action="/book/deleteBook/' . $book['ID_SACH'] . '" method="post">
                    <button type="submit" class="delete-button">Xóa</button>
                </form>
            </td>
        </tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }
}
