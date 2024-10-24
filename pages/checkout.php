<?php
include('../includes/db.php');
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 从 session 中获取购物车的详细信息
$mealkit_id = isset($_SESSION['mealkit_id']) ? $_SESSION['mealkit_id'] : null;
$quantity = isset($_SESSION['quantity']) ? $_SESSION['quantity'] : null;
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : null;

// 检查是否有购物车内容
if (!$mealkit_id || !$quantity || !$total_price) {
    echo "Your cart is empty or missing order information.";
    exit();  // 提示购物车为空或信息缺失，停止执行
}

// 校区和配送点数据
$locations = [
    'St Lucia' => [
        ['name' => 'UQ Canteen', 'lat' => -27.498993827243297, 'lng' => 153.0120756270213],
        ['name' => 'UQ Food Court', 'lat' => -27.49691419082293, 'lng' => 153.01601429908672],
        ['name' => 'Advanced Engineering Building (49)', 'lat' => -27.499279913869415, 'lng' => 153.01548048831032],
        ['name' => 'Central Library hall', 'lat' => -27.496031823711636, 'lng' => 153.01384432502783],
        ['name' => 'UQ Business School', 'lat' => -27.494672680223793, 'lng' => 153.01422495150456],
    ],
    'Gatton' => [
        ['name' => 'Dining Hall', 'lat' => -27.555039279624808, 'lng' => 152.33587914616442],
        ['name' => 'UQU Lawes Club', 'lat' => -27.54958783559103, 'lng' => 152.33904460935062],
    ],
    'Herston' => [
        ['name' => 'Cafe Dose', 'lat' => -27.448082201601544, 'lng' => 153.02434269462177],
    ],
    'Dutton Park' => [
        ['name' => 'Pacemaker Cafe', 'lat' => -27.500196007668098, 'lng' => 153.03082954034338],
    ],
];

// 用户提交了校区和配送点
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campus = $_POST['campus'];
    $location = $_POST['location'];
    $pickup_time = $_POST['pickup_time'];

    // 将信息存储到 session 中
    $_SESSION['checkout_campus'] = $campus;
    $_SESSION['checkout_location'] = $location;
    $_SESSION['checkout_pickup_time'] = $pickup_time;

    // 跳转到付款页面
    header('Location: payment.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/nutribox/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFvZ70_ehQoCl_pbfd-Qs4y5oLSoVDhfk&callback=initMap&libraries=marker&v=beta"></script>
    <script>
        let locations = <?php echo json_encode($locations); ?>;
        let map, markers = [];

        // 初始化 Google 地图
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: { lat: -27.4975, lng: 153.0137 },  // 默认 St Lucia 校区位置
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initMap();  // 初始化地图

            const campusSelect = document.getElementById('campus');
            const locationSelect = document.getElementById('location');

            // 校区改变时，更新地图中心点和标记配送点
            campusSelect.addEventListener('change', function () {
                const campus = this.value;
                locationSelect.innerHTML = '';  // 清空当前选项

                // 移除旧的标记
                markers.forEach(marker => marker.setMap(null));
                markers = [];

                // 获取校区第一个位置的坐标来设置地图中心
                if (locations[campus] && locations[campus].length > 0) {
                    const firstLocation = locations[campus][0];
                    map.setCenter({ lat: firstLocation.lat, lng: firstLocation.lng });
                    map.setZoom(14);  // 调整校区的缩放等级为14

                    // 根据校区显示配送点并在地图上标记
                    locations[campus].forEach(function (location) {
                        const option = document.createElement('option');
                        option.value = location.name;
                        option.textContent = location.name;
                        locationSelect.appendChild(option);

                        const marker = new google.maps.Marker({
                            position: { lat: location.lat, lng: location.lng },
                            map: map,
                            title: location.name,
                        });
                        markers.push(marker);
                    });
                }
            });

            // 位置改变时，放大并聚焦到具体的配送点
            locationSelect.addEventListener('change', function () {
                const selectedLocationName = this.value;

                // 在当前校区中查找具体位置
                const campus = campusSelect.value;
                if (locations[campus]) {
                    const selectedLocation = locations[campus].find(loc => loc.name === selectedLocationName);
                    if (selectedLocation) {
                        // 设置地图中心为所选位置，并放大地图
                        map.setCenter({ lat: selectedLocation.lat, lng: selectedLocation.lng });
                        map.setZoom(17);  // 放大到具体位置
                    }
                }
            });
        });
    </script>


</head>
<body onload="initMap()">
<?php include('../components/navbar.php'); ?>  <!-- 导航栏 -->

<div class="container mt-5">
    <h2>Checkout</h2>
    <form action="checkout.php" method="POST">

        <div class="mb-3 mt-3">
            <label for="pickup_time" class="form-label">Choose Your Pickup Time</label>
            <select class="form-select" id="pickup_time" name="pickup_time" required>
                <option value="" selected disabled>Select Pickup Time</option>
                <option value="8:00 AM">Breakfast 8:00 AM</option>
                <option value="8:30 AM">Breakfast 8:30 AM</option>
                <option value="9:00 AM">Breakfast 9:00 AM</option>
                <option value="11:30 AM">Lunch 11:30 AM</option>
                <option value="12:00 PM">Lunch 12:00 PM</option>
                <option value="12:30 PM">Lunch 12:30 PM</option>
                <option value="1:00 PM">Lunch 1:00 PM</option>
                <option value="5:00 PM">Dinner 5:00 PM</option>
                <option value="5:30 PM">Dinner 5:30 PM</option>
                <option value="6:00 PM">Dinner 6:00 PM</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="campus" class="form-label">Choose Your Campus</label>
            <select class="form-select" id="campus" name="campus" required>
                <option value="" selected disabled>Select Campus</option>
                <option value="St Lucia">St Lucia</option>
                <option value="Gatton">Gatton</option>
                <option value="Herston">Herston</option>
                <option value="Dutton Park">Dutton Park</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Choose Your Pickup Location</label>
            <select class="form-select" id="location" name="location" required>
                <!-- 动态显示配送点 -->
            </select>
        </div>
        <br>
        <br>
        <!-- 地图显示区域 -->
        <div id="map" style="height: 400px; width: 100%;"></div>
        <br>
        <br>
        <button type="submit" class="btn btn-primary">Pay now</button>
    </form>
</div>

<?php include('../components/footer.php'); ?>  <!-- 页脚 -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
