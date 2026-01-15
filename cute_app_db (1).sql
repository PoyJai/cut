-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2026 at 06:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cute_app_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `tag` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `image`, `tag`, `description`, `created_at`) VALUES
(1, 'เสื้อไหมพรมเกาหลี', 390.00, 'T-Shirt', 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=500&q=80', 'Best Seller', 'เสื้อไหมพรมเนื้อนุ่ม สไตล์มินิมอล นำเข้าจากเกาหลี', '2026-01-15 03:27:54'),
(2, 'nigga\r\n', 550.00, 'Dress', 'https://images.unsplash.com/photo-1572804013307-5975c037c5ff?w=500&q=80', 'New Arrival', 'ชุดเดรสยาวพริ้วไหว สีชมพูพาสเทล', '2026-01-15 03:27:54'),
(3, 'เสื้อยืด Oversize ลายหมี', 250.00, 'T-Shirt', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=500&q=80', 'Sale', 'เสื้อยืดทรงหลวม Cotton 100%', '2026-01-15 03:27:54'),
(4, 'กางเกงขาสั้นผ้าลินิน', 320.00, 'Bottoms', 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500&q=80', '', 'กางเกงขาสั้นผ้าลินินแท้ ทรงสวย', '2026-01-15 03:27:54'),
(5, 'เดรสชีฟองลายดอกไม้', 450.00, 'Dress', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=500', 'NEW', 'เดรสยาวผ้าชีฟอง พลิ้วไหว ใส่สบาย เหมาะกับวันไปเที่ยวทะเล', '2026-01-15 04:11:30'),
(6, 'เสื้อไหมพรมคอเต่า', 320.00, 'T-Shirt', 'https://images.unsplash.com/photo-1574015974293-817f0ebebb74?w=500', 'HOT', 'เสื้อไหมพรมเนื้อนุ่ม สีพาสเทล ให้ความอบอุ่นได้ดี', '2026-01-15 04:11:30'),
(7, 'กางเกงยีนส์เอวสูง', 590.00, 'Bottoms', 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=500', 'SALE', 'กางเกงยีนส์ทรงกระบอกเล็ก ทรงสวยเก็บพุง', '2026-01-15 04:11:30'),
(8, 'เสื้อยืด Oversize', 290.00, 'T-Shirt', 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=500', '', 'เสื้อยืดผ้า Cotton 100% ลายกราฟิกสุดชิค', '2026-01-15 04:11:30'),
(9, 'กระโปรงเทนนิส', 350.00, 'Bottoms', 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=500', 'TREND', 'กระโปรงมีซับใน กางเกงข้างใน ใส่แล้วดูขายาว', '2026-01-15 04:11:30'),
(10, 'เสื้อคลุมคาร์ดิแกน', 390.00, 'T-Shirt', 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=500', '', 'เสื้อคลุมกันหนาว สีเอิร์ธโทน แมตช์ง่าย', '2026-01-15 04:11:30'),
(11, 'หมวกเบเร่ต์แฟชั่น', 190.00, 'Accessories', 'https://images.unsplash.com/photo-1576828831022-ae41d4565076?w=500', 'ACCESSORY', 'หมวกทรงเบเร่ต์ เพิ่มลุคคุณหนูให้กับชุดของคุณ', '2026-01-15 04:11:30'),
(12, 'กระเป๋าถือใบเล็ก', 750.00, 'Accessories', 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=500', 'PREMIUM', 'กระเป๋าหนังเทียมสีขาว เรียบหรูดูแพง', '2026-01-15 04:11:30'),
(13, 'รองเท้าผ้าใบสีพาสเทล', 890.00, 'Accessories', 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?w=500', 'SALE', 'รองเท้าผ้าใบน้ำหนักเบา ใส่เดินได้ทั้งวันไม่เจ็บเท้า', '2026-01-15 04:11:30'),
(14, 'เสื้อสายเดี่ยวลูกไม้', 250.00, 'T-Shirt', 'https://images.unsplash.com/photo-1515347619252-60a4bdad882c?w=500', '', 'เสื้อสายเดี่ยวแต่งลูกไม้สุดเซ็กซี่ ปนความน่ารัก', '2026-01-15 04:11:30'),
(15, 'กางเกงขาสั้นผ้าลินิน', 280.00, 'Bottoms', 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=500', 'NEW', 'กางเกงขาสั้นใส่รับหน้าร้อน ผ้าโปร่งสบาย', '2026-01-15 04:11:30'),
(16, 'เสื้อเชิ้ตลายทาง', 380.00, 'T-Shirt', 'https://images.unsplash.com/photo-1598033129183-c4f50c7176c8?w=500', '', 'เสื้อเชิ้ตทรงทำงาน ใส่ไปเรียนหรือไปทำงานก็ดูดี', '2026-01-15 04:11:30'),
(17, 'ผ้าพันคอผ้าไหม', 150.00, 'Accessories', 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=500', '', 'ผ้าพันคอผืนยาว ลายพิมพ์คลาสสิก', '2026-01-15 04:11:30'),
(18, 'ต่างหูมุกแฟชั่น', 120.00, 'Accessories', 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=500', '', 'ต่างหูมุกน้ำจืด ดีไซน์ทันสมัย', '2026-01-15 04:11:30'),
(19, 'แว่นกันแดดทรง Cat Eye', 450.00, 'T-Shirt', 'https://images.unsplash.com/photo-1511499767390-a73923f61942?w=500', 'HOT', 'แว่นกันแดดป้องกัน UV400 เสริมความมั่นใจ', '2026-01-15 04:11:30'),
(20, 'ชุดเซต 2 ชิ้น', 690.00, 'Dress', 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=500', 'LIMIT', 'เสื้อครอปและกระโปรงสั้น เข้าเซตสีชมพูหวา่น', '2026-01-15 04:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `display_name`, `created_at`) VALUES
(1, 'cute_user', '123456', 'น้องหมีนำโชค', '2026-01-15 02:52:14'),
(2, 'hee', '$2y$10$SMwL2Z7F6QPBdIi426khre/CBbRzCUiUOgkSchDYWGRFS8MYA/V1a', NULL, '2026-01-15 03:15:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
