-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2026 at 04:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `8032db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(500) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_thai_520_w2;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `description`, `price`, `stock`, `image_url`, `status`, `created_at`) VALUES
(1, 1, 'Liverpool FC Home Jersey 2025/26', 'เสื้อเหย้า Liverpool FC สำหรับแฟนบอล', 2890.00, 25, '1.jpg', 'active', '2026-08-08 11:09:03'),
(2, 1, 'Liverpool FC Walk On T-Shirt', 'เสื้อยืด Liverpool FC ลาย Walk On', 990.00, 40, '2.webp', 'active', '2026-08-08 11:09:03'),
(3, 1, 'Liverpool FC Fan T-Shirt Red', 'เสื้อยืดสีแดงสำหรับแฟน Liverpool FC', 890.00, 35, '3.webp', 'active', '2026-08-08 11:09:03'),
(4, 2, 'Liverpool FC Official Scarf', 'ผ้าพันคอ Liverpool FC สีแดง พร้อมตราสโมสร', 790.00, 30, 'https://store.liverpoolfc.com/media/catalog/product/cache/1/image/1000x/040ec09b1e35df139433887a97daa66f/l/f/lfc_epl_champions_24_25_scarf_red_1.jpg', 'active', '2026-08-08 11:09:03'),
(5, 2, 'Liverpool FC Champions Scarf', 'ผ้าพันคอที่ระลึก Liverpool FC Champions', 890.00, 20, 'https://store.liverpoolfc.com/media/catalog/product/cache/1/image/1000x/040ec09b1e35df139433887a97daa66f/l/f/lfc_epl_champions_24_25_scarf_red_1.jpg', 'active', '2026-08-08 11:09:03'),
(6, 3, 'Liverpool FC Training Shorts', 'กางเกงฝึกซ้อม Liverpool FC', 1290.00, 20, 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?auto=format&fit=crop&w=800&q=80', 'active', '2026-08-08 11:09:03'),
(7, 3, 'Liverpool FC Football Shorts', 'กางเกงฟุตบอลสีแดงสำหรับแฟน Liverpool FC', 1190.00, 25, 'https://images.unsplash.com/photo-1553778263-73a83bab9b0c?auto=format&fit=crop&w=800&q=80', 'active', '2026-08-08 11:09:03'),
(8, 4, 'Liverpool FC Water Bottle 750ml', 'ขวดน้ำ Liverpool FC ขนาด 750 มิลลิลิตร', 590.00, 50, 'https://files.ekmcdn.com/515a32/images/team-merchandise-aluminium-750ml-24042-p.jpeg', 'active', '2026-08-08 11:09:03'),
(9, 4, 'Liverpool FC Champions Water Bottle', 'ขวดน้ำ Liverpool FC รุ่น Champions', 690.00, 35, '4.jpg', 'active', '2026-08-08 11:09:03'),
(10, 5, 'Liverpool FC Football', 'ลูกฟุตบอลที่ระลึก Liverpool FC', 1290.00, 15, 'https://www.3qsports.co.uk/images/option_value/source/LI07814%20-%20Copy.jpg?t=1727308885', 'active', '2026-08-08 11:09:03'),
(11, 5, 'Liverpool FC Fan Gift Set', 'ชุดของที่ระลึกสำหรับแฟน Liverpool FC', 1590.00, 10, 'https://www.3qsports.co.uk/images/option_value/source/LI07814%20-%20Copy.jpg?t=1727308885', 'active', '2026-08-08 11:09:03'),
(12, 5, 'Liverpool FC Collectible Figure', 'ฟิกเกอร์สะสม Liverpool FC', 990.00, 18, 'https://www.merseymerch.com/cdn/shop/files/Mersey_Merch_1.png?v=1757955427&width=1200', 'active', '2026-08-08 11:09:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
