-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2024-10-18 04:13:48
-- 服务器版本： 8.3.0
-- PHP 版本： 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `nutribox_db`
--

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `post_id` int DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `comment`, `created_at`) VALUES
(1, 1, 1, 'i like it!!!!', '2024-10-16 03:39:54'),
(2, 2, 1, 'The price is too high！！！', '2024-10-16 03:41:23'),
(3, 1, 2, 'Can\'t Wait for this :)))', '2024-10-17 18:25:31'),
(4, 2, 2, 'Looks good', '2024-10-17 18:44:56'),
(5, 2, 2, 'this is so good!!!!!!!', '2024-10-18 00:18:39'),
(6, 2, 3, 'I like it !!!!!!!', '2024-10-18 02:59:02');

-- --------------------------------------------------------

--
-- 表的结构 `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `post_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`post_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(39, 2, 4, '2024-10-18 02:59:38'),
(38, 2, 1, '2024-10-18 02:58:47'),
(37, 2, 2, '2024-10-18 02:58:39'),
(34, 1, 2, '2024-10-17 22:30:00'),
(35, 1, 3, '2024-10-17 23:18:54');

-- --------------------------------------------------------

--
-- 表的结构 `mealkits`
--

DROP TABLE IF EXISTS `mealkits`;
CREATE TABLE IF NOT EXISTS `mealkits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text,
  `likes` int DEFAULT '0',
  `image_path` varchar(255) DEFAULT NULL,
  `sales` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `mealkits`
--

INSERT INTO `mealkits` (`id`, `user_id`, `name`, `price`, `description`, `likes`, `image_path`, `sales`, `created_at`) VALUES
(22, 1, 'Chicken', 17.00, 'good', 0, '/nutribox/uploads/1729202173_c1fc1da57bd9f6944956d94e8322b5c.png', 0, '2024-10-17 21:56:13'),
(14, 1, 'Pumpkin Pie', 14.00, 'qer', 0, '/nutribox/uploads/1729146176_dish5.png', 0, '2024-10-17 06:22:56'),
(10, 1, 'MaPo Tofu', 17.99, '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\r\n^^&&&&&&&&&&&\r\n@@@@@@@@@@@@@@@@@@@', 0, '/nutribox/uploads/1729143912_pexels-change-c-c-974768353-20943933.jpg', 0, '2024-10-17 05:45:12'),
(11, 1, 'Pasta', 12.00, 'goodgoodgood', 0, '/nutribox/uploads/1729144028_dish1.png', 0, '2024-10-17 05:47:08'),
(12, 1, 'Kimchi Pancake', 13.00, 'good', 0, '/nutribox/uploads/1729144038_dish4.png', 0, '2024-10-17 05:47:18'),
(13, 1, 'Salted Tofu', 15.00, 'good', 0, '/nutribox/uploads/1729144051_dish2.png', 0, '2024-10-17 05:47:31'),
(19, 1, 'Bao', 6.00, 'goodgood', 0, '/nutribox/uploads/1729201804_01c66b33828ea7d845bd74f923a4885.png', 0, '2024-10-17 21:50:04'),
(21, 1, 'BaconBagel', 8.00, 'good', 0, '/nutribox/uploads/1729201991_0ef3b898d5187a3814a752470692e40.png', 0, '2024-10-17 21:53:11'),
(20, 1, 'Sandwich', 5.50, 'good', 0, '/nutribox/uploads/1729201893_bae5a2a6fa1b1ff4ed09923e145515a.png', 0, '2024-10-17 21:51:33'),
(23, 2, 'jipai', 16.00, 'haochi', 0, '/nutribox/uploads/1729208230_c1fc1da57bd9f6944956d94e8322b5c.png', 0, '2024-10-17 23:37:10'),
(24, 2, 'dumpling', 11.99, 'good food', 0, '/nutribox/uploads/1729220442_dish1.png', 0, '2024-10-18 03:00:42');

-- --------------------------------------------------------

--
-- 表的结构 `mealkit_tags`
--

DROP TABLE IF EXISTS `mealkit_tags`;
CREATE TABLE IF NOT EXISTS `mealkit_tags` (
  `mealkit_id` int NOT NULL,
  `tag_id` int NOT NULL,
  PRIMARY KEY (`mealkit_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `mealkit_tags`
--

INSERT INTO `mealkit_tags` (`mealkit_id`, `tag_id`) VALUES
(10, 54),
(11, 43),
(11, 45),
(11, 51),
(11, 56),
(11, 57),
(12, 43),
(12, 45),
(12, 46),
(12, 50),
(12, 58),
(12, 61),
(13, 43),
(14, 43),
(19, 59),
(20, 58),
(21, 61),
(22, 44),
(23, 43),
(23, 44),
(23, 51),
(23, 56),
(23, 62),
(24, 43),
(24, 45),
(24, 46),
(24, 56),
(24, 57),
(24, 59);

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `mealkit_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pickup_location` varchar(255) DEFAULT NULL,
  `status` enum('Accepted','Preparing','Ready','Delivered') DEFAULT 'Accepted',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `mealkit_id` (`mealkit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `mealkit_id`, `quantity`, `total_price`, `order_date`, `pickup_location`, `status`) VALUES
(21, 1, 1, 2, 25.00, '2024-10-17 12:29:48', 'Pacemaker Cafe', 'Accepted'),
(22, 2, 1, 2, 25.00, '2024-10-17 13:37:57', 'UQU Lawes Club', 'Accepted'),
(23, 2, 1, 2, 25.00, '2024-10-17 14:09:03', 'UQU Lawes Club', 'Accepted'),
(24, 2, 1, 2, 25.00, '2024-10-17 16:58:28', 'UQU Lawes Club', 'Accepted');

-- --------------------------------------------------------

--
-- 表的结构 `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `mealkit_id` int DEFAULT NULL,
  `mealkit_image` varchar(255) DEFAULT NULL,
  `mealkit_description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `mealkit_id` (`mealkit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `mealkit_id`, `mealkit_image`, `mealkit_description`, `price`, `created_at`) VALUES
(1, 1, NULL, '/nutribox/uploads/1729049977_dish3.png', '1242', 11.00, '2024-10-16 03:39:37'),
(3, 2, NULL, '/nutribox/uploads/1729190657_dish2.png', 'This is a traditional street food called fried tofu. If you want to order this dish, please let me know in the comments. If 3 people are willing to order it, I will put this MealKit on the shelves.', 15.00, '2024-10-17 18:44:17');

-- --------------------------------------------------------

--
-- 表的结构 `shopping_cart`
--

DROP TABLE IF EXISTS `shopping_cart`;
CREATE TABLE IF NOT EXISTS `shopping_cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `mealkit_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `fries` tinyint(1) DEFAULT '0',
  `cola` tinyint(1) DEFAULT '0',
  `gravy` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `mealkit_id` (`mealkit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `shopping_cart`
--

INSERT INTO `shopping_cart` (`id`, `user_id`, `mealkit_id`, `quantity`, `fries`, `cola`, `gravy`) VALUES
(1, 1, 2, 5, 0, 0, 0),
(2, 1, 9, 7, 0, 0, 0),
(3, 1, 1, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(63, 'Italian food'),
(53, 'Japanese'),
(62, 'BBQ'),
(43, 'Vegetarian'),
(44, 'Fast food'),
(45, 'Healthy'),
(46, 'Low calorie'),
(48, 'Spicy'),
(50, 'Asian'),
(51, 'Spanish'),
(55, 'High protein'),
(56, 'Seafood'),
(57, 'Carbohydrates'),
(58, 'Gluten'),
(59, 'Chinese'),
(60, 'Salad'),
(61, 'Grains'),
(64, 'Mexico');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'DefaultUser.png',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `password`, `avatar`, `created_at`) VALUES
(1, 'ZijianShuaiiiii', '1336304352@qq.com', '$2y$10$H9SwEB5RyYLSB5c294SsyuCyBue4INkES9oNsngsgQ3I2FHXP4PWC', '/nutribox/uploads/17287091522728590210049.png', '2024-10-10 19:55:14'),
(2, 'SongHeeeee', 'szj1336304352@qq.com', '$2y$10$le8aB9oVOaOOEBHxchYzw.h0.4I9bZLOqpVjpZTaUDEWUIV0TY3V.', '/nutribox/uploads/1729050062icon.png', '2024-10-16 03:40:43');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
