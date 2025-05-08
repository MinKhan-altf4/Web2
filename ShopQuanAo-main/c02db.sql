-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 08, 2025 lúc 08:51 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `c02db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Bẫy `cart`
--
DELIMITER $$
CREATE TRIGGER `update_cart_total_price_insert` BEFORE INSERT ON `cart` FOR EACH ROW BEGIN
    DECLARE product_price DECIMAL(10,2);
    
    -- Lấy giá sản phẩm từ bảng products
    SELECT price INTO product_price FROM products WHERE product_id = NEW.product_id;
    
    -- Tính và đặt tổng giá
    SET NEW.total_price = NEW.quantity * product_price;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_cart_total_price_update` BEFORE UPDATE ON `cart` FOR EACH ROW BEGIN
    DECLARE product_price DECIMAL(10,2);
    
    -- Chỉ cập nhật total_price khi số lượng thay đổi
    IF NEW.quantity != OLD.quantity OR NEW.product_id != OLD.product_id THEN
        -- Lấy giá sản phẩm từ bảng products
        SELECT price INTO product_price FROM products WHERE product_id = NEW.product_id;
        
        -- Tính và đặt tổng giá
        SET NEW.total_price = NEW.quantity * product_price;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `checkout`
--

CREATE TABLE `checkout` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `shipping_fullname` varchar(100) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'Cash',
  `bank_reference` varchar(100) DEFAULT NULL,
  `card_last4` varchar(4) DEFAULT NULL,
  `card_brand` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_notes` text DEFAULT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `card_number` varchar(16) DEFAULT NULL,
  `card_holder` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `checkout`
--

INSERT INTO `checkout` (`order_id`, `user_id`, `order_date`, `shipping_fullname`, `shipping_phone`, `shipping_address`, `shipping_city`, `payment_method`, `bank_reference`, `card_last4`, `card_brand`, `total_amount`, `order_notes`, `order_status`, `created_at`, `card_number`, `card_holder`) VALUES
(128, 61, '2025-05-01 08:20:01', 'account1', '0123456789', '123 district1 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 2016.00, '', 'Pending', '2025-05-08 06:20:01', NULL, NULL),
(129, 61, '2025-05-02 08:20:17', 'account1', '0123456789', '123 district1 Viet Nam', 'Hanoi', 'transfer', '23423423', NULL, NULL, 88.00, '', 'Pending', '2025-05-08 06:20:17', NULL, NULL),
(130, 61, '2025-05-09 08:20:25', 'account1', '0123456789', '123 district1 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 115.00, '', 'Pending', '2025-05-08 06:20:25', NULL, NULL),
(131, 61, '2025-05-15 08:20:58', 'account1', '0123456789', '123 district1 Viet Nam', 'Hanoi', 'card', NULL, '1111', 'Visa/MasterCard', 77.00, '', 'Pending', '2025-05-08 06:20:58', NULL, NULL),
(132, 61, '2025-05-05 08:21:18', 'account1', '0123456789', '123 district1 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 288.00, '', 'Pending', '2025-05-08 06:21:18', NULL, NULL),
(133, 62, '2025-05-10 08:21:49', 'account2', '0123456789', '123 district2 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 376.00, '', 'cancelled', '2025-05-08 06:21:49', NULL, NULL),
(134, 62, '2025-05-24 08:22:08', 'account1', '123456789', '123 district1 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 700.00, '', 'cancelled', '2025-05-08 06:22:08', NULL, NULL),
(135, 62, '2025-05-21 08:22:24', 'account2', '0123456789', '123 district2 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 231.00, '', 'cancelled', '2025-05-08 06:22:24', NULL, NULL),
(136, 62, '2025-05-22 08:22:49', 'account2', '0123456789', '123 district2 Viet Nam', 'Hanoi', 'transfer', '1232131', NULL, NULL, 312.00, '', 'cancelled', '2025-05-08 06:22:49', NULL, NULL),
(137, 62, '2025-05-01 08:23:05', 'account2', '0123456789', '123 district2 Viet Nam', 'Hanoi', 'transfer', '12312', NULL, NULL, 65.00, '', 'cancelled', '2025-05-08 06:23:05', NULL, NULL),
(138, 63, '2025-05-09 08:24:42', 'account3', '0123456789', '123 district3 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 1669.00, '', 'delivered', '2025-05-08 06:24:42', NULL, NULL),
(139, 63, '2025-05-28 08:24:55', 'account3', '0123456789', '123 district3 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 155.00, '', 'delivered', '2025-05-08 06:24:55', NULL, NULL),
(140, 63, '2025-05-08 08:25:17', 'account3', '0123456789', '123 district3 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 54.00, '', 'delivered', '2025-05-08 06:25:17', NULL, NULL),
(141, 63, '2025-05-08 08:25:38', 'account3', '0123456789', '123 district3 Viet Nam', 'HoChiMinh', 'transfer', '34243242', NULL, NULL, 88.00, '', 'delivered', '2025-05-08 06:25:38', NULL, NULL),
(142, 63, '2025-05-08 08:26:03', 'account3', '0123456789', '123 district3 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 115.00, '', 'delivered', '2025-05-08 06:26:03', NULL, NULL),
(143, 64, '2025-05-08 08:27:35', 'account4', '0123456789', '123 district4 Viet Nam', 'HoChiMinh', 'transfer', '123123131', NULL, NULL, 504.00, '', 'delivered', '2025-05-08 06:27:35', NULL, NULL),
(144, 64, '2025-05-08 08:28:06', 'account4', '0123456789', '123 district4 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 198.00, '', 'delivered', '2025-05-08 06:28:06', NULL, NULL),
(145, 64, '2025-05-26 08:29:26', 'account4', '0123456789', '123 district4 Viet Nam', 'HoChiMinh', 'card', NULL, '1111', 'Visa/MasterCard', 88.00, '', 'delivered', '2025-05-08 06:29:26', NULL, NULL),
(146, 64, '2025-05-09 08:30:05', 'account4', '0123456789', '123 district4 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 610.00, '', 'delivered', '2025-05-08 06:30:05', NULL, NULL),
(147, 64, '2025-05-18 08:30:26', 'account4', '0123456789', '123 district4 Viet Nam', 'HoChiMinh', 'Cash', NULL, NULL, NULL, 1597.00, '', 'delivered', '2025-05-08 06:30:26', NULL, NULL),
(148, 65, '2025-05-20 08:31:01', 'account5', '0123456789', '123 district5 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 656.00, '', 'confirmed', '2025-05-08 06:31:01', NULL, NULL),
(149, 65, '2025-05-06 08:31:56', 'account5', '0123456789', '123 district5 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 130.00, '', 'confirmed', '2025-05-08 06:31:56', NULL, NULL),
(150, 65, '2025-05-05 08:32:54', 'account5', '0123456789', '123 district5 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 349.00, '', 'confirmed', '2025-05-08 06:32:54', NULL, NULL),
(151, 65, '2025-04-25 08:33:41', 'account5', '0123456789', '123 district5 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 493.00, '', 'confirmed', '2025-05-08 06:33:41', NULL, NULL),
(152, 65, '2025-05-23 08:34:09', 'account5', '0123456789', '123 district5 Viet Nam', 'Hanoi', 'transfer', '13213123', NULL, NULL, 79.00, '', 'confirmed', '2025-05-08 06:34:09', NULL, NULL),
(153, 66, '2025-06-04 08:34:55', 'account6', '0123456789', '123 district6 Viet Nam', 'Hanoi', 'card', NULL, '1111', 'Visa/MasterCard', 904.00, '', 'confirmed', '2025-05-08 06:34:55', NULL, NULL),
(154, 66, '2025-05-09 08:35:08', 'account6', '0123456789', '123 district6 Viet Nam', 'Hanoi', 'transfer', '1231', NULL, NULL, 203.00, '', 'confirmed', '2025-05-08 06:35:08', NULL, NULL),
(155, 66, '2025-04-17 08:35:30', 'account6', '0123456789', '123 district6 Viet Nam', 'Hanoi', 'card', NULL, '2211', 'Visa/MasterCard', 107.00, '', 'confirmed', '2025-05-08 06:35:30', NULL, NULL),
(156, 66, '2025-05-29 08:35:52', 'account6', '0123456789', '123 district6 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 276.00, '', 'confirmed', '2025-05-08 06:35:52', NULL, NULL),
(157, 66, '2025-05-30 08:36:01', 'account6', '0123456789', '123 district6 Viet Nam', 'Hanoi', 'Cash', NULL, NULL, NULL, 700.00, '', 'confirmed', '2025-05-08 06:36:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `checkout_items`
--

CREATE TABLE `checkout_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `checkout_items`
--

INSERT INTO `checkout_items` (`item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(167, 128, 227, 7, 288.00),
(168, 129, 228, 1, 88.00),
(169, 130, 229, 1, 115.00),
(170, 131, 230, 1, 77.00),
(171, 132, 227, 1, 288.00),
(172, 133, 227, 1, 288.00),
(173, 133, 228, 1, 88.00),
(174, 134, 194, 1, 700.00),
(175, 135, 224, 1, 154.00),
(176, 135, 230, 1, 77.00),
(177, 136, 227, 1, 288.00),
(178, 136, 237, 1, 24.00),
(179, 137, 226, 1, 65.00),
(180, 138, 194, 2, 700.00),
(181, 138, 229, 1, 115.00),
(182, 138, 232, 2, 77.00),
(183, 139, 217, 1, 155.00),
(184, 140, 216, 1, 54.00),
(185, 141, 228, 1, 88.00),
(186, 142, 222, 1, 115.00),
(187, 143, 225, 6, 84.00),
(188, 144, 212, 1, 43.00),
(189, 144, 213, 1, 43.00),
(190, 144, 214, 1, 50.00),
(191, 144, 215, 1, 62.00),
(192, 145, 228, 1, 88.00),
(193, 146, 209, 1, 300.00),
(194, 146, 217, 2, 155.00),
(195, 147, 210, 1, 42.00),
(196, 147, 227, 5, 288.00),
(197, 147, 229, 1, 115.00),
(198, 148, 227, 1, 288.00),
(199, 148, 228, 2, 88.00),
(200, 148, 229, 1, 115.00),
(201, 148, 232, 1, 77.00),
(202, 149, 237, 1, 24.00),
(203, 149, 238, 1, 38.00),
(204, 149, 239, 1, 30.00),
(205, 149, 241, 1, 38.00),
(206, 150, 211, 2, 37.00),
(207, 150, 212, 1, 43.00),
(208, 150, 213, 1, 43.00),
(209, 150, 214, 1, 50.00),
(210, 150, 215, 1, 62.00),
(211, 150, 232, 1, 77.00),
(212, 151, 216, 2, 54.00),
(213, 151, 232, 2, 77.00),
(214, 151, 234, 1, 77.00),
(215, 151, 235, 2, 77.00),
(216, 152, 208, 1, 79.00),
(217, 153, 224, 4, 154.00),
(218, 153, 227, 1, 288.00),
(219, 154, 228, 1, 88.00),
(220, 154, 229, 1, 115.00),
(221, 155, 235, 1, 77.00),
(222, 155, 243, 1, 30.00),
(223, 156, 221, 1, 77.00),
(224, 156, 222, 1, 115.00),
(225, 156, 225, 1, 84.00),
(226, 157, 194, 1, 700.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_number` varchar(20) NOT NULL,
  `invoice_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(50) NOT NULL DEFAULT 'Unpaid',
  `payment_date` datetime DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `order_id`, `invoice_number`, `invoice_date`, `payment_status`, `payment_date`, `subtotal`, `tax_amount`, `shipping_fee`, `discount_amount`, `total_amount`, `notes`, `created_at`, `updated_at`) VALUES
(112, 128, 'INV-1746685201-8084', '2025-05-08 13:20:01', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 2016.00, NULL, '2025-05-08 06:20:01', '2025-05-08 06:20:01'),
(113, 129, 'INV-1746685217-3278', '2025-05-08 13:20:17', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 88.00, NULL, '2025-05-08 06:20:17', '2025-05-08 06:20:17'),
(114, 130, 'INV-1746685225-7599', '2025-05-08 13:20:25', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 115.00, NULL, '2025-05-08 06:20:25', '2025-05-08 06:20:25'),
(115, 131, 'INV-1746685258-7950', '2025-05-08 13:20:58', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 77.00, NULL, '2025-05-08 06:20:58', '2025-05-08 06:20:58'),
(116, 132, 'INV-1746685278-7684', '2025-05-08 13:21:18', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 288.00, NULL, '2025-05-08 06:21:18', '2025-05-08 06:21:18'),
(117, 133, 'INV-1746685309-4991', '2025-05-08 13:21:49', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 376.00, NULL, '2025-05-08 06:21:49', '2025-05-08 06:21:49'),
(118, 134, 'INV-1746685328-1536', '2025-05-08 13:22:08', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 700.00, NULL, '2025-05-08 06:22:08', '2025-05-08 06:22:08'),
(119, 135, 'INV-1746685345-4125', '2025-05-08 13:22:25', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 231.00, NULL, '2025-05-08 06:22:25', '2025-05-08 06:22:25'),
(120, 136, 'INV-1746685369-2350', '2025-05-08 13:22:49', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 312.00, NULL, '2025-05-08 06:22:49', '2025-05-08 06:22:49'),
(121, 137, 'INV-1746685385-8014', '2025-05-08 13:23:05', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 65.00, NULL, '2025-05-08 06:23:05', '2025-05-08 06:23:05'),
(122, 138, 'INV-1746685482-9062', '2025-05-08 13:24:42', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 1669.00, NULL, '2025-05-08 06:24:42', '2025-05-08 06:44:35'),
(123, 139, 'INV-1746685495-3607', '2025-05-08 13:24:55', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 155.00, NULL, '2025-05-08 06:24:55', '2025-05-08 06:44:31'),
(124, 140, 'INV-1746685517-6804', '2025-05-08 13:25:17', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 54.00, NULL, '2025-05-08 06:25:17', '2025-05-08 06:44:25'),
(125, 141, 'INV-1746685538-6544', '2025-05-08 13:25:38', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 88.00, NULL, '2025-05-08 06:25:38', '2025-05-08 06:25:38'),
(126, 142, 'INV-1746685563-4593', '2025-05-08 13:26:03', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 115.00, NULL, '2025-05-08 06:26:03', '2025-05-08 06:44:16'),
(127, 143, 'INV-1746685655-3402', '2025-05-08 13:27:35', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 504.00, NULL, '2025-05-08 06:27:35', '2025-05-08 06:27:35'),
(128, 144, 'INV-1746685686-4205', '2025-05-08 13:28:06', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 198.00, NULL, '2025-05-08 06:28:06', '2025-05-08 06:44:06'),
(129, 145, 'INV-1746685766-5526', '2025-05-08 13:29:26', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 88.00, NULL, '2025-05-08 06:29:26', '2025-05-08 06:29:26'),
(130, 146, 'INV-1746685805-8752', '2025-05-08 13:30:05', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 610.00, NULL, '2025-05-08 06:30:05', '2025-05-08 06:43:57'),
(131, 147, 'INV-1746685826-2757', '2025-05-08 13:30:26', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 1597.00, NULL, '2025-05-08 06:30:26', '2025-05-08 06:43:46'),
(132, 148, 'INV-1746685861-5407', '2025-05-08 13:31:01', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 656.00, NULL, '2025-05-08 06:31:01', '2025-05-08 06:31:01'),
(133, 149, 'INV-1746685916-8131', '2025-05-08 13:31:56', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 130.00, NULL, '2025-05-08 06:31:56', '2025-05-08 06:31:56'),
(134, 150, 'INV-1746685974-2835', '2025-05-08 13:32:54', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 349.00, NULL, '2025-05-08 06:32:54', '2025-05-08 06:32:54'),
(135, 151, 'INV-1746686021-3593', '2025-05-08 13:33:41', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 493.00, NULL, '2025-05-08 06:33:41', '2025-05-08 06:33:41'),
(136, 152, 'INV-1746686049-2860', '2025-05-08 13:34:09', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 79.00, NULL, '2025-05-08 06:34:09', '2025-05-08 06:34:09'),
(137, 153, 'INV-1746686095-3104', '2025-05-08 13:34:55', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 904.00, NULL, '2025-05-08 06:34:55', '2025-05-08 06:34:55'),
(138, 154, 'INV-1746686108-3046', '2025-05-08 13:35:08', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 203.00, NULL, '2025-05-08 06:35:08', '2025-05-08 06:35:08'),
(139, 155, 'INV-1746686130-8870', '2025-05-08 13:35:30', 'Paid', NULL, 0.00, 0.00, 0.00, 0.00, 107.00, NULL, '2025-05-08 06:35:30', '2025-05-08 06:35:30'),
(140, 156, 'INV-1746686152-4093', '2025-05-08 13:35:52', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 276.00, NULL, '2025-05-08 06:35:52', '2025-05-08 06:35:52'),
(141, 157, 'INV-1746686161-7844', '2025-05-08 13:36:01', 'Unpaid', NULL, 0.00, 0.00, 0.00, 0.00, 700.00, NULL, '2025-05-08 06:36:01', '2025-05-08 06:36:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `description`, `tag`, `image`, `created_at`, `is_deleted`, `is_visible`, `type_id`) VALUES
(194, 'Zara Men Jacket', 700, '', '0', 'product-2.jpg', '2025-05-07 15:35:12', 0, 1, 2),
(208, 'Diagonal Textured', 79, '', '0', 'product-4.jpg', '2025-05-07 17:04:09', 0, 1, 2),
(209, 'SHORT FLOWER POPLIN SHIRT', 300, '', '0', '03652340330-e1.jpg', '2025-05-07 17:09:52', 0, 1, 2),
(210, 'POPLIN SHIRT WITH PEARL', 42, '', '0', '02136534441-e1.jpg', '2025-05-07 17:15:12', 0, 0, 2),
(211, 'POPLIN PLAID SHIRT', 37, '', '0', '02262115062-e1.jpg', '2025-05-07 17:17:44', 0, 1, 2),
(212, 'OXFORD SHIRT WITH BUTTONS AT WAIST', 43, '', '0', '02190772406-e1.jpg', '2025-05-07 17:19:32', 0, 1, 2),
(213, 'POPLIN SHIRT WITH PLAID PATTERN AND BUTTONS', 43, '', '0', '02238832406-e1.jpg', '2025-05-07 17:20:46', 0, 1, 2),
(214, 'COTTON - LINEN KNITTED POLO SHIRT', 50, '', '0', '02893424712-e1.jpg', '2025-05-07 17:23:07', 0, 1, 2),
(215, 'JACQUARD COLORFUL POLO SHIRT', 62, '', '0', '01023412120-e1.jpg', '2025-05-07 17:24:07', 0, 1, 2),
(216, 'BOXY FIT POLO SHIRT WITH EMBROIDERY', 54, '', '0', '06917417500-e1.jpg', '2025-05-07 17:26:05', 0, 1, 2),
(217, 'LEATHER BAG SANDALS', 155, '', '0', '12488520800-e1.jpg', '2025-05-07 17:28:19', 0, 1, 3),
(218, 'LEATHER MOCCASIN SHOES CASUAL LIMITED EDITION', 115, '', '0', '12693520700-e1.jpg', '2025-05-07 17:30:33', 0, 1, 3),
(219, 'CASUAL STYLE LEATHER MOCCASIN SHOES', 65, '', '0', '12653520400-e1.jpg', '2025-05-07 17:31:29', 0, 0, 3),
(220, 'DOUBLE STRAPS', 46, '', '0', '12754520800-e1.jpg', '2025-05-07 17:33:36', 0, 1, 3),
(221, 'CASUAL STYLE LEATHER MOCCASIN SHOES', 77, '', '0', '12615620800-e1.jpg', '2025-05-07 17:35:24', 0, 1, 3),
(222, 'EMBOSSED LEATHER SHOES', 115, '', '0', '12421520800-e1.jpg', '2025-05-07 17:37:48', 0, 1, 3),
(223, 'GLOSSY LEATHER MOCCASIN SHOES WITH HORIZONTAL STRAP', 73, '', '0', '12608520800-e1.jpg', '2025-05-07 17:43:36', 0, 1, 3),
(224, 'LEATHER BOAT SHOES', 154, '', '0', '12496520022-e1.jpg', '2025-05-07 17:46:17', 0, 1, 3),
(225, 'CHELSEA LEATHER BOOTS', 84, '', '0', '12018420114-e1.jpg', '2025-05-07 17:47:40', 0, 1, 3),
(226, 'THICK SOLE SHOES WITH BLOCK-UP STRAP', 65, '', '0', 'poster.jpg', '2025-05-07 17:49:10', 0, 1, 3),
(227, 'LIMITED EDITION LEATHER Clutch Bag', 288, '', '0', '13190520700-e1.jpg', '2025-05-07 17:51:24', 0, 0, 1),
(228, 'LEATHER BAG FOR PERSONAL ITEMS', 88, '', '0', '13711520700-e1.jpg', '2025-05-07 17:52:19', 0, 1, 1),
(229, 'TECHNICAL FABRIC TRAVEL BAG', 115, '', '0', '13320520032-e7.jpg', '2025-05-07 17:53:36', 0, 1, 1),
(230, 'Shopper bag with fabric and leather', 77, '', '0', '13323520107-e1.jpg', '2025-05-07 17:55:20', 0, 1, 1),
(231, 'TECHNICAL FABRIC BACKPACK', 77, '', '0', '13223520032-e1.jpg', '2025-05-07 17:56:48', 0, 1, 1),
(232, 'TEXTILE BACKPACK', 77, '', '0', '13229420800-e1.jpg', '2025-05-07 17:58:01', 0, 1, 1),
(233, 'RUBBER COATED BACKPACK WITH LID', 77, '', '0', '13230420802-e1.jpg', '2025-05-07 17:59:11', 0, 1, 1),
(234, 'BRAITED SHOULDER BAG', 77, '', '0', '13175520002-e1.jpg', '2025-05-07 18:00:17', 0, 1, 1),
(235, 'MACRAME BRAIDED SHOPPER BAG', 77, '', '0', '13391520002-e1.jpg', '2025-05-07 18:01:08', 0, 0, 1),
(236, 'MULTI-FUNCTIONAL SPORTS BAG', 77, '', '0', '13165520800-e1.jpg', '2025-05-07 18:03:50', 0, 1, 1),
(237, 'RUBBER COATED CROSS-BODY BAG', 24, '', '0', '13902520800-e1.jpg', '2025-05-07 18:08:19', 0, 1, 4),
(238, 'XL LEATHER WALLET', 38, '', '0', '13804520700-e1.jpg', '2025-05-07 18:10:03', 0, 1, 4),
(239, 'LEATHER CARD CASE', 30, '', '0', '13919520700-e1.jpg', '2025-05-07 18:11:35', 0, 1, 4),
(240, 'LEATHER PASSPORT WALLET', 38, '', '0', '13821520800-e1.jpg', '2025-05-07 18:13:12', 0, 1, 4),
(241, 'TEXTURED CARD CASE', 38, '', '0', '13818520800-e1.jpg', '2025-05-07 18:14:22', 0, 1, 4),
(242, 'RUBBER COATED WALLET WITH ZIPPER', 20, '', '0', '13816520800-e1.jpg', '2025-05-07 18:15:16', 0, 1, 4),
(243, 'CROSS-BODY WALLET', 30, '', '0', '13809520700-e1.jpg', '2025-05-07 18:17:11', 0, 1, 4);

--
-- Bẫy `products`
--
DELIMITER $$
CREATE TRIGGER `update_cart_on_product_price_change` AFTER UPDATE ON `products` FOR EACH ROW BEGIN
    -- Nếu giá sản phẩm thay đổi, cập nhật tổng giá trong giỏ hàng
    IF NEW.price != OLD.price THEN
        UPDATE cart 
        SET total_price = quantity * NEW.price
        WHERE product_id = NEW.product_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_types`
--

CREATE TABLE `product_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_types`
--

INSERT INTO `product_types` (`type_id`, `type_name`) VALUES
(1, 'Bags'),
(2, 'Clothing'),
(3, 'Shoes'),
(4, 'Accessories');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `original_password` varchar(255) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `original_password`, `fullname`, `phone`, `address`, `city`, `gender`, `email`, `role`, `status`) VALUES
(36, 'admin1', '$2y$10$EGL2.zrv.R2RSQ5.WdALF.2u5FhpRMT.oBMqGWBu88iQd9g2FPU4a', NULL, 'Admin System 1', '0123456789', 'Ha Noi', NULL, 'Male', 'admin1@gmail.com', 'admin', '1'),
(37, 'admin2', '$2y$10$fgunpBNT6FRXLcim3p3OL.xCZ634TqrOiVlPIYHRxiVQSfE08sRJ.', NULL, 'Admin System 2', '0123456788', 'Ha Noi', NULL, 'Male', 'admin2@gmail.com', 'admin', '1'),
(38, 'admin3', '$2y$10$/4b4xE.980Z7eGtO764xd.yEeFfQdmkGU.BPcw4N7Tteq80SaolHe', NULL, 'Admin System 3', '0123456787', 'Ha Noi', NULL, 'Male', 'admin3@gmail.com', 'admin', '1'),
(61, 'account1', '$2y$10$JEoC/Vm3BVvabpzuuP35LOpFpNLt6WO9aBpBvvabwUnEfpwgSnf9.', '123456', 'account1', '0123456789', '123 district1 Viet Nam', 'Hanoi', 'male', 'account1@gmail.com', 'customer', '1'),
(62, 'account2', '$2y$10$N2FEwpfTsNUPi.nbRRNn9.xhXOlPznxky9TNgLD1b0sjvkEnlzSHi', '123456', 'account2', '0123456789', '123 district2 Viet Nam', 'Hanoi', 'male', 'account2@gmail.com', 'customer', '1'),
(63, 'account3', '$2y$10$mL.i/l8y9Nd11qaIGVuMyO.HlMbgWcA5Iyxl2WR6MK1kg8Idq7BEW', '123456', 'account3', '0123456789', '123 district3 Viet Nam', 'HoChiMinh', 'male', 'account3@gmail.com', 'customer', '1'),
(64, 'account4', '$2y$10$.ZeBBQImccXMDLu1Ckvar.WeWZIUiefEP80vYsmNkFYUQF01y17hS', '123456', 'account4', '0123456789', '123 district4 Viet Nam', 'HoChiMinh', 'female', 'account4@gmail.com', 'customer', '1'),
(65, 'account5', '$2y$10$ZBlKu7SNBSaKXGuxoheHD.RZu7nrLkjMzsJDmfftmB.xWd3BY6RaC', '123456', 'account5', '0123456789', '123 district5 Viet Nam', 'Hanoi', 'female', 'account5@gmail.com', 'customer', '1'),
(66, 'account6', '$2y$10$l3uEO1fi0tsLLwTXAq1BEOx4fOaN6nLEuYuFFjT.7hSkETyVDOHhq', '123456', 'account6', '0123456789', '123 district6 Viet Nam', 'Hanoi', 'female', 'account6@gmail.com', 'customer', '1'),
(67, 'account7', '$2y$10$ZVVpxSeQZAr7Y99JEJxq6.U1bna.jT.fHhiW4yXwVFx9imahvS/ny', '123456', 'account7', '0123456789', '123 district7 Viet Nam', 'Hanoi', 'male', 'account7@gmail.com', 'customer', '1'),
(68, 'account8', '$2y$10$DvIp44WWqwJK6n1Z29VxnOTV6gl0m87UkN6gXUNZW1g6S/cGhAZWi', '123456', 'account8', '0123456789', '123 district8 Viet Nam', 'Hanoi', 'male', 'account8@gmail.com', 'customer', '0'),
(69, 'account9', '$2y$10$Z9UJUlkJI5.K.ZOpIOAEVOOYtC1qGUD5RO2ymv/6E/GHfTe4osKEm', '123456', 'account9', '0123456789', '123 district9 Viet Nam', 'Hanoi', 'female', 'account9@gmail.com', 'customer', '1'),
(70, 'account10', '$2y$10$w3UYj6rCc2dkiUhtqqbOI.DD1Tmq31vbXkryVBU9OkHZlmp36MIyi', '123456', 'account10', '0123456789', '123 district10 Viet Nam', 'Hanoi', 'male', 'account10@gmail.com', 'customer', '1');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Chỉ mục cho bảng `checkout`
--
ALTER TABLE `checkout`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_checkout_user` (`user_id`);

--
-- Chỉ mục cho bảng `checkout_items`
--
ALTER TABLE `checkout_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_checkout_items_order` (`order_id`),
  ADD KEY `fk_checkout_items_product` (`product_id`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD UNIQUE KEY `invoice_number_2` (`invoice_number`),
  ADD KEY `fk_invoice_order` (`order_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_product_type` (`type_id`);

--
-- Chỉ mục cho bảng `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `checkout`
--
ALTER TABLE `checkout`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT cho bảng `checkout_items`
--
ALTER TABLE `checkout_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT cho bảng `product_types`
--
ALTER TABLE `product_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoice_order` FOREIGN KEY (`order_id`) REFERENCES `checkout` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_type` FOREIGN KEY (`type_id`) REFERENCES `product_types` (`type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
