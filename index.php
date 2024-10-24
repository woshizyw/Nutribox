<?php
include('includes/db.php');
// 启动 session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriBox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/nutribox/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
    <style>
        /* 背景图样式 */
        .hero-section {
            background-image: url('/nutribox/assets/images/index_background.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 80px;
        }

        .hero-text {
            color: white;
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            z-index: 2;  /* 确保文字出现在背景图之上 */
            position: relative;  /* 保持文字在背景图上正常显示 */
        }

        /* Why NutriBox 样式 */
        .why-nutribox {
            padding: 40px 0;
        }

        .why-nutribox img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .why-nutribox .btn {
            margin-top: 20px;
        }

        /* 横向滚动区域 */
        .mealkit-carousel {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 20px 0;
        }

        .mealkit-carousel img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        /* 广告图片 */
        .ad-section img {
            width: 100%;
            height: auto;
        }

        /* 谷歌地图样式 */
        #map {
            width: 100%;
            height: 400px;
        }

        /* 改变四个 campus 按钮的样式 */
        .btn-campus {
            background-color: black;  /* 背景色为黑色 */
            color: white;  /* 文字颜色为白色 */
            border: none;  /* 去掉边框 */
            padding: 10px 20px;  /* 增加按钮的内边距 */
            font-size: 16px;  /* 调整文字大小 */
            border-radius: 5px;  /* 给按钮圆角效果 */
            cursor: pointer;  /* 鼠标悬停时为手型 */
            transition: background-color 0.3s ease;  /* 增加过渡效果 */
        }

        /* 鼠标悬停效果 */
        .btn-campus:hover {
            background-color: white;  /* 鼠标悬停时背景色变为白色 */
            color: black;  /* 鼠标悬停时文字变为黑色 */
            border: 1px solid black;  /* 增加黑色边框 */
        }
    </style>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFvZ70_ehQoCl_pbfd-Qs4y5oLSoVDhfk&callback=initMap&libraries=marker&v=beta<?php echo time(); ?>"></script>
</head>
<body>

<?php include('components/navbar.php'); ?> <!-- 导航栏 -->

<!-- 背景图 -->
<div class="hero-section">
    <div class="hero-text">
    Welcome to NutriBox, a new way to order food at UQ
    </div>
</div>

<!-- Why NutriBox -->
<div class="container why-nutribox text-center">
    <h2>Why NutriBox?</h2>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <img src="/nutribox/assets/images/homePhoto1.png" alt="Dietary diversification">
            <h5>Dietary diversification</h5>
            <p>Select your dietary preferences and price ranges. The degree of processing can also vary, from fresh, unprocessed ingredients to pre-cut ones.</p>
        </div>
        <div class="col-md-4">
            <img src="/nutribox/assets/images/homePhoto2.png" alt="Delivery visualization">
            <h5>Delivery visualization</h5>
            <p>We have multiple fixed pick-up points at all UQ campuses, so you can easily find a pick-up point nearby.</p>
        </div>
        <div class="col-md-4">
            <img src="/nutribox/assets/images/homePhoto3.png" alt="Make money">
            <h5>Want to make money?</h5>
            <p>NutriBox estimates that all users can upload their own MealKit, prepare meals and deliver them to earn money. We also encourage you to share your own MealKit or recommend your favorite MealKit in our community.</p>
        </div>
    </div>
    <a href="/nutribox/pages/menu.php" class="btn btn-success">Let's order</a>
</div>

<!-- 横向滚动展示区域 -->
<div class="container">
    <h2 class="text-center">Our MealKits</h2>
    <div class="mealkit-carousel">
        <?php
        // 从数据库中获取 mealkit 的图片和其他信息
        $sql_mealkits = "SELECT name, image_path, price FROM mealkits ORDER BY created_at DESC";
        $result_mealkits = $conn->query($sql_mealkits);

        // 循环展示 mealkits
        while ($mealkit = $result_mealkits->fetch_assoc()) {
            // 确保只获取文件名，避免路径重复
            $image_file = basename($mealkit['image_path']);
            echo '<div class="mealkit-item">';
            echo '<img src="/nutribox/uploads/' . htmlspecialchars($image_file) . '" alt="' . htmlspecialchars($mealkit['name']) . '">';
            echo '<p>' . htmlspecialchars($mealkit['name']) . '</p>';
            echo '<p>$' . number_format($mealkit['price'], 2) . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</div>


<!-- 广告图片 -->
<div class="container ad-section my-4">
    <img src="/nutribox/assets/images/ad_photo.png" alt="NutriBox Community">
</div>

<!-- Google 地图 -->
<div class="container">
    <h2 class="text-center">Pick up point near me</h2>
    <br>
    <div class="d-flex justify-content-center mb-4">
        <button class="btn btn-campus mx-2" onclick="changeCampus('St Lucia')">St Lucia</button>
        <button class="btn btn-campus mx-2" onclick="changeCampus('Gatton')">Gatton</button>
        <button class="btn btn-campus mx-2" onclick="changeCampus('Herston')">Herston</button>
        <button class="btn btn-campus mx-2" onclick="changeCampus('Dutton Park')">Dutton Park</button>
    </div>
    <div id="map"></div>
</div>

<script>
    let locations = {
        'St Lucia': [
            { name: 'UQ Canteen', lat: -27.498993827243297, lng: 153.0120756270213 },
            { name: 'UQ Food Court', lat: -27.49691419082293, lng: 153.01601429908672 },
            { name: 'Advanced Engineering Building (49)', lat: -27.499279913869415, lng: 153.01548048831032 },
            { name: 'Central Library hall', lat: -27.496031823711636, lng: 153.01384432502783 },
            { name: 'UQ Business School', lat: -27.494672680223793, lng: 153.01422495150456 }
        ],
        'Gatton': [
            { name: 'Dining Hall', lat: -27.555039279624808, lng: 152.33587914616442 },
            { name: 'UQU Lawes Club', lat: -27.54958783559103, lng: 152.33904460935062 }
        ],
        'Herston': [
            { name: 'Cafe Dose', lat: -27.448082201601544, lng: 153.02434269462177 }
        ],
        'Dutton Park': [
            { name: 'Pacemaker Cafe', lat: -27.500196007668098, lng: 153.03082954034338 }
        ]
    };

    let map;
    let markers = [];

    function initMap() {
        // 默认地图初始化
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -27.4975, lng: 153.0137 },
            zoom: 14
        });

        // 添加默认校区的图钉
        setMarkers('St Lucia');
    }

    // 校区切换函数
    function changeCampus(campus) {
        map.setCenter({ lat: locations[campus][0].lat, lng: locations[campus][0].lng });
        map.setZoom(14);

        // 清除现有的图钉
        clearMarkers();

        // 设置新校区的图钉
        setMarkers(campus);
    }

    function setMarkers(campus) {
        locations[campus].forEach(function(location) {
            const marker = new google.maps.Marker({
                position: { lat: location.lat, lng: location.lng },
                map: map,
                title: location.name
            });
            markers.push(marker);
        });
    }

    function clearMarkers() {
        markers.forEach(marker => marker.setMap(null));
        markers = [];
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include('components/footer.php'); ?> <!-- 页脚 -->
</body>
</html>

