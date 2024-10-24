<?php
// 确保 session 已启动
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 检查是否登录并设置头像
$avatar = isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : '/nutribox/assets/images/DefaultUser.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
</head>

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="/nutribox/index.php">
      <img src="/nutribox/assets/images/logo.png" alt="NutriBox" width="40" height="40" class="d-inline-block align-text-top">
      NutriBox
    </a>
    <ul class="navbar-nav d-flex flex-row">
      <li class="nav-item me-3">
        <a class="nav-link" href="/nutribox/index.php">Home</a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link" href="/nutribox/pages/menu.php">Menu</a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link" href="/nutribox/pages/community.php">Community</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/nutribox/pages/userinfo.php">
          <img src="<?php echo $avatar; ?>" alt="User" width="40" height="40" class="user-icon">
        </a>
      </li>
    </ul>
  </div>
</nav>
