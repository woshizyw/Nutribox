<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

// 检查用户是否已经点赞
$sql_check = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param('ii', $user_id, $post_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // 如果用户已经点赞，删除点赞（取消赞）
    $sql_delete = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('ii', $user_id, $post_id);
    $stmt_delete->execute();
} else {
    // 如果用户没有点赞，添加点赞
    $sql_insert = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param('ii', $user_id, $post_id);
    $stmt_insert->execute();
}

// 计算点赞数
$sql_like_count = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?";
$stmt_like_count = $conn->prepare($sql_like_count);
$stmt_like_count->bind_param('i', $post_id);
$stmt_like_count->execute();
$like_count = $stmt_like_count->get_result()->fetch_assoc()['like_count'];

echo json_encode(['success' => true, 'like_count' => $like_count]);
?>
