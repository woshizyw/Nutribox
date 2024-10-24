<?php
session_start();

// 检查用户是否从 checkout.php 进入
if (!isset($_SESSION['checkout_campus']) || !isset($_SESSION['checkout_location']) || !isset($_SESSION['checkout_pickup_time'])) {
    header('Location: checkout.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 假设用户输入了付款信息
    $_SESSION['payment_time'] = date("Y-m-d H:i:s");  // 记录付款时间

    // 跳转到付款成功页面
    header('Location: success.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/nutribox/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
</head>
<body>

<?php include('../components/navbar.php'); ?>  <!-- 导航栏 -->

<div class="container mt-5">
    <h2>Payment</h2>
    <form action="payment.php" method="POST">
        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="card_number" name="card_number" required>
        </div>

        <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="text" class="form-control" id="expiry_date" name="expiry_date" required>
        </div>

        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" required>
        </div>

        <button type="submit" class="btn btn-primary">Pay Now</button>
    </form>
</div>

<?php include('../components/footer.php'); ?>  <!-- 页脚 -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
