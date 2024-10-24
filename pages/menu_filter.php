<?php
include('../includes/db.php');
session_start();

// 获取 POST 请求中的标签和排序选项
$tags = isset($_POST['tags']) ? json_decode($_POST['tags'], true) : [];
$sort = isset($_POST['sort']) ? $_POST['sort'] : '';

$mealkits_query = "SELECT DISTINCT mealkits.* FROM mealkits";

// 如果有标签选择，使用 JOIN 和 GROUP BY
if (!empty($tags)) {
    // 使用 GROUP BY 和 COUNT 来确保每个 mealkit 必须包含所有选中的标签
    $tag_placeholders = implode(',', array_fill(0, count($tags), '?'));
    $mealkits_query .= " INNER JOIN mealkit_tags ON mealkits.id = mealkit_tags.mealkit_id WHERE mealkit_tags.tag_id IN ($tag_placeholders)";
    $mealkits_query .= " GROUP BY mealkits.id HAVING COUNT(DISTINCT mealkit_tags.tag_id) = " . count($tags);  // 确保包含所有选中的标签
}

// 根据排序选项添加 ORDER BY 子句
switch ($sort) {
    case 'price_asc':
        $mealkits_query .= " ORDER BY mealkits.price ASC";
        break;
    case 'price_desc':
        $mealkits_query .= " ORDER BY mealkits.price DESC";
        break;
    case 'date_asc':
        $mealkits_query .= " ORDER BY mealkits.created_at ASC";
        break;
    case 'date_desc':
        $mealkits_query .= " ORDER BY mealkits.created_at DESC";
        break;
    case 'likes_desc':
        $mealkits_query .= " ORDER BY mealkits.likes DESC";
        break;
    default:
        // 默认按价格升序排列
        $mealkits_query .= " ORDER BY mealkits.price ASC";
}

// 预处理和执行查询
$stmt = $conn->prepare($mealkits_query);

// 如果有标签，绑定参数
if (!empty($tags)) {
    $stmt->bind_param(str_repeat('i', count($tags)), ...$tags);
}

$stmt->execute();
$result = $stmt->get_result();

// 返回 HTML，动态生成 MealKits 的卡片
while ($mealkit = $result->fetch_assoc()) {
    ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="<?php echo $mealkit['image_path']; ?>" class="card-img-top" alt="<?php echo $mealkit['name']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($mealkit['name']); ?></h5>
                <p class="card-text">$<?php echo htmlspecialchars($mealkit['price']); ?></p>
                <p class="card-text">❤ <?php echo htmlspecialchars($mealkit['likes']); ?> Likes</p>
                <button class="btn btn-primary add-to-cart" data-id="<?php echo $mealkit['id']; ?>">Add to Cart</button>
            </div>
        </div>
    </div>
    <?php
}

$stmt->close();
$conn->close();
?>
