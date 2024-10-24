<?php
// login_process.php
include('../includes/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 检查用户是否存在
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // 验证密码
        if (password_verify($password, $user['password'])) {
            // 密码正确，登录成功
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nickname'] = $user['nickname'];
            $_SESSION['user_avatar'] = $user['avatar'];
            header('Location: userinfo.php');
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
}
