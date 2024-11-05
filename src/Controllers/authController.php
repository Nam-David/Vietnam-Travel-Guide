<?php
class AuthController{
    private Config $conn;

    // hàm tạo
    public function __construct()
    {
        if(!isset($_SESSION)){
            session_start();
        }
        $this->conn = new Config();
    }

    // hàm check người dùng có đăng nhập chưa
    public function checkAuth()
    {
        if(!isset($_SESSION['blogger_id'])){
            header("location: login.php");
        }
        exit;
    }

    // check Email tồn tại chưa
    private function checkIssetEmail($email)
    {
        $sql = mysqli_query($this->conn->connect(), "SELECT * FROM users WHERE email = '{$email}'");
        if(mysqli_num_rows($sql) > 0){
            echo "$email - Email này đã tồn tại!";
            return true;
        }
        return false;
    }

    public function signUp()
    {
        
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $email = $_POST['email'] ?? null;

        if (!$username || !$password || !$email) {
            echo "Vui lòng điền đầy đủ thông tin!";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email không hợp lệ!";
            return;
        }

        if (strlen($username) < 5) {
            echo "Tên người dùng phải có ít nhất 5 ký tự!";
            return;
        }

        if (strlen($password) < 8) {
            echo "Mật khẩu phải có ít nhất 8 ký tự!";
            return;
        }

        // dùng Bcrypt
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Sử dụng Prepared Statements (an toàn)
        $sql = "INSERT INTO users (userName, email, pass_word, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->connect()->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $hashedPassword, "blogger");

        if ($stmt->execute()) {
            echo "Đăng ký thành công!";
            $_SESSION['blogger_id'] = $this->conn->connect()->insert_id;
            $_SESSION['role'] = "blogger";
            header("location: welcome.php");
        } else {
            echo "Đăng ký thất bại. Vui lòng thử lại!";
        }

        $stmt->close();
    }

    public function login()
    {
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            echo "Vui lòng điền đầy đủ thông tin!";
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Email không hợp lệ!";
            return;
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->connect()->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "Email hoặc mật khẩu không đúng!";
            return;
        }

        $user = mysqli_fetch_array($result);
        
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['pass_word'])) {
            $_SESSION['blogger_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            echo "Đăng nhập thành công!";
            header("location: welcome.php");
            exit;
        } else {
            echo "Email hoặc mật khẩu không đúng!";
        }

        $stmt->close();
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // xóa tất cả mọi thứ trong phiên đăng nhập
        session_unset();

        // hủy session
        session_destroy();

        header("location: login.php");
        exit;
    }
}