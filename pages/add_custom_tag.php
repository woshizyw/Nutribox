<?php
include('../includes/db.php');
session_start();

$tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';

if (!empty($tag)) {
    // 检查数据库中是否已有该标签
    $checkQuery = $conn->prepare("SELECT COUNT(*) as count FROM tags WHERE name = ?");
    $checkQuery->bind_param("s", $tag);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // 如果已存在，返回失败响应
        echo json_encode(['success' => false, 'message' => 'Tag already exists']);
    } else {
        // 插入新标签
        $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
        $stmt->bind_param("s", $tag);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'tag_id' => $stmt->insert_id]); // 返回成功并传递 tag_id
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add tag']);
        }
        $stmt->close();
    }
    $checkQuery->close();
}

$conn->close();
?>
