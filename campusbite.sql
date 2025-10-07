-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 09:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `campusbite`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `canteen_name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `food_name`, `canteen_name`, `image_path`, `uploaded_at`) VALUES
(1, 'Veg Biryani', 'Student cafe', 'assets/images/6833d82fcc507.jpg', '2025-05-26 08:25:43'),
(2, 'Veg Biryani', 'Student cafe', 'assets/images/img_6836809d991445.71614851.jpg', '2025-05-28 08:48:53'),
(3, 'Veg Biryani', 'Student cafe', 'assets/images/img_683680b82da3e8.96631281.jpg', '2025-05-28 08:49:20'),
(4, 'Chapathi', 'Main Cafeteria', '\"C:\\xampp\\htdocs\\campusbite\\assets\\images\\chapathi.jpeg\"', '2025-09-11 19:56:16'),
(5, 'burger', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\burger.jpg', '2025-09-11 20:16:49'),
(7, 'parotta', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\Paratha.jpg', '2025-09-11 20:17:55'),
(8, 'Poori', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\poori.jpeg', '2025-09-11 20:22:10'),
(9, 'Idly', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\idly.jpg', '2025-09-11 20:23:01'),
(10, 'Chicken Biryani', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\chickenbiryani.jpg', '2025-09-11 20:23:48'),
(11, 'Sambar Rice', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\sambar.jpeg', '2025-09-11 20:28:06'),
(12, 'falooda', 'Main Cafeteria', 'C:\\xampp\\htdocs\\campusbite\\assets\\images\\falooda.jpeg', '2025-09-11 20:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--

CREATE TABLE `auth` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`user_id`, `username`, `email`, `phone_number`, `password`, `created_at`, `role`) VALUES
(6, 'aditi', 'shreya123@gmail.com', '9843711740', 'ts123', '2025-07-29 12:51:41', 'vendor'),
(7, 'shrey', 'shivkumar.ksa@gmail.com', '9842802551', 'ts1224', '2025-07-29 12:59:18', 'user'),
(8, 'dhana', 'dhana123@gmail.com', '8248989512', 'dhana123', '2025-08-07 03:44:51', 'vendor'),
(9, 'Allen', 'allen123@gmail.com', '7871405219', 'allen005', '2025-08-11 04:47:20', 'vendor'),
(10, 'akash', 'ak@gmail.com', '123456789', 'ak123', '2025-08-11 04:48:05', 'vendor'),
(11, 'admin', 'admin123@gmail.com', '123456789', 'welcome', '2025-09-02 14:23:50', 'admin'),
(18, 'Manjula', 's.shreyaaditi11a@gmail.com', '8248989512', 'm123', '2025-09-07 06:40:59', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `canteen_name`
--

CREATE TABLE `canteen_name` (
  `canteen_id` int(11) NOT NULL,
  `canteen_name` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `canteen_name`
--

INSERT INTO `canteen_name` (`canteen_id`, `canteen_name`, `email`) VALUES
(1, 'Main Cafeteria', 'ak@gmail.com'),
(2, 'Student cafe', 'allen123@gmail.com'),
(3, 'ENGINEERING CAFE', 'shreya123@gmail.com'),
(4, 'SS cafe', 'dhana123@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (`price` * `quantity`) STORED,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `food_name`, `quantity`, `price`, `added_at`) VALUES
(31, 7, 'parotta', 1, 45.00, '2025-09-09 15:28:16'),
(32, 7, 'burger', 3, 160.00, '2025-09-10 05:55:14'),
(33, 7, 'Veg Biryani', 2, 70.00, '2025-09-09 16:28:55'),
(34, 7, 'Chapathi', 4, 45.00, '2025-09-10 03:48:57');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `issue_type` varchar(100) NOT NULL,
  `issue_details` text DEFAULT NULL,
  `status` enum('Filed','Under Review','Verified','Resolved') DEFAULT 'Filed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `canteen_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `transaction_id`, `issue_type`, `issue_details`, `status`, `created_at`, `updated_at`, `canteen_name`, `user_id`) VALUES
(9, '1213455', 'double payment', 'need refund', '', '2025-09-02 09:43:55', '2025-09-05 13:48:11', 'Student cafe', 6),
(11, '123456', 'double payment', 'need refund', 'Filed', '2025-09-03 08:32:55', '2025-09-03 08:32:55', 'Main cafeteria', 7),
(12, '12352627', 'Refund not received', 'refund', 'Filed', '2025-09-10 07:17:47', '2025-09-10 07:17:47', 'SS Cafe', 7);

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE `disputes` (
  `dispute_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `issue_summary` text NOT NULL,
  `decision` enum('pending','solved','denied') DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disputes`
--

INSERT INTO `disputes` (`dispute_id`, `user_id`, `vendor_id`, `issue_summary`, `decision`, `reason`, `admin_id`, `resolved_at`, `created_at`) VALUES
(1, 10, 20, 'Test issue summary', 'denied', 'no valid reason', 101, '2025-09-05 13:29:32', '2025-09-01 03:54:22');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `image` longtext NOT NULL,
  `food_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `discount` decimal(5,2) DEFAULT NULL,
  `available` int(11) DEFAULT NULL,
  `canteen_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`image`, `food_name`, `price`, `discount`, `available`, `canteen_name`) VALUES
('assets/images/Vegbiriyani.jpg', 'Veg Biryani', 70.00, 5.00, 10, 'Student Cafe'),
('assets/images/coffee.jpg', 'Cold Coffee', 40.00, 0.00, 20, 'Student Cafe'),
('assets/images/vegburger.jpg', 'Veg Burger', 60.00, 0.00, 15, 'Student Cafe'),
('assets/images/pizza.jpg', 'PIZZA', 210.00, 20.00, 15, 'MAIN CAFETERIA'),
('assets/images/pizza.jpg', 'PIZZA', 210.00, 20.00, 15, 'MAIN CAFETERIA'),
('assets/images/coffee.jpg', 'COFFEE', 15.00, 0.00, 20, 'ENGINEERING CAFE'),
('assets/images/coffee.jpg', 'COFFEE', 15.00, 0.00, 20, 'ENGINEERING CAFE'),
('', 'burger', 160.00, NULL, NULL, 'Student Cafe'),
('', 'ka', 1.00, NULL, NULL, 'Student cafe'),
('', 'burger', 160.00, NULL, NULL, 'Main Cafeteria'),
('', 'parotta', 45.00, NULL, NULL, 'Main Cafeteria'),
('', 'Poori', 40.00, NULL, NULL, 'Main Cafeteria'),
('', 'Idly', 35.00, NULL, NULL, 'Main Cafeteria'),
('', 'Chicken Biryani', 130.00, NULL, NULL, 'Main Cafeteria'),
('', 'Chapathi', 45.00, NULL, NULL, 'Main Cafeteria'),
('', 'Curd Rice', 40.00, NULL, NULL, 'Main Cafeteria'),
('', 'Sambar Rice', 40.00, NULL, NULL, 'Main Cafeteria'),
('', 'falooda', 95.00, NULL, NULL, 'Main Cafeteria'),
('', 'Pizza', 140.00, NULL, NULL, 'Student cafe'),
('', 'Dosa', 45.00, NULL, NULL, 'Student cafe');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `order_status` varchar(50) DEFAULT 'Pending',
  `pickup_time` time NOT NULL,
  `canteen_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `food_name`, `quantity`, `total_price`, `order_date`, `order_status`, `pickup_time`, `canteen_name`) VALUES
(5, 7, 'TEA', 2, 20.00, '2025-07-24 13:34:19', 'Accepted', '13:34:19', 'STUDENT CAFE'),
(13, 6, 'nachos', 2, 600.00, '2025-07-30 15:24:27', 'pending', '13:00:00', ''),
(14, 6, 'nachos', 2, 600.00, '2025-07-30 15:29:50', 'pending', '13:00:00', ''),
(15, 6, 'Biryani', 2, 600.00, '2025-07-31 09:30:29', 'pending', '13:00:00', ''),
(16, 6, 'coffee', 2, 35.00, '2025-07-31 09:48:59', 'pending', '13:00:00', 'student cafe'),
(17, 6, 'coffee', 2, 35.00, '2025-08-28 09:23:09', 'pending', '13:00:00', 'student cafe'),
(18, 6, 'coffee', 2, 35.00, '2025-08-28 09:32:49', 'pending', '13:00:00', 'student cafe');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `expires_at`) VALUES
('s.shreyaaditi11a@gmail.com', '284376', '2025-09-07 09:34:47'),
('shivkumar.ksa@gmail.com', '199298', '2025-09-06 15:18:40'),
('shreya@gmail.com', 'ce938e78cceb2ff40d4af377447bcf66', '2025-05-26 06:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `razorpay_order_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('created','paid','failed') DEFAULT 'created',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `canteen_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `review` text DEFAULT NULL,
  `review_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `food_name`, `canteen_name`, `rating`, `review`, `review_date`) VALUES
(1, 'Veg Biryani', 'Student cafe', 4, 'great tast and fresh', '2025-05-26 08:41:33'),
(2, 'nachos', 'Engineering Cafe', 4, 'had a good taste', '2025-07-30 10:19:47'),
(3, 'Chicken Biryani (with 65)', 'SS Cafe', 4, 'had a good taste', '2025-09-10 09:23:58'),
(4, 'Sample Food', 'ss cafe', 5, 'super', '2025-09-10 09:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone_number`, `password`, `role`, `created_at`) VALUES
(1, 'Allen', 'allen123@gmail.com', '7871405219', 'allen005', 'vendor', '2025-08-07 09:29:26'),
(2, 'shrey', 'shrey@gmail.com', '123456789', 'shre', 'user', '2025-08-11 10:12:30'),
(3, 'akash', 'akash@gmail.com', '123456789', 'ak', 'vendor', '2025-08-11 10:13:08');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `item_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `canteen_name` varchar(100) NOT NULL,
  `username` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`item_id`, `vendor_id`, `canteen_name`, `username`) VALUES
(4, 3, 'student cafe', 'Allen'),
(5, 4, 'Engineering Cafe', 'akash');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `canteen_name`
--
ALTER TABLE `canteen_name`
  ADD PRIMARY KEY (`canteen_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disputes`
--
ALTER TABLE `disputes`
  ADD PRIMARY KEY (`dispute_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`,`token`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `canteen_name`
--
ALTER TABLE `canteen_name`
  MODIFY `canteen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `disputes`
--
ALTER TABLE `disputes`
  MODIFY `dispute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `auth` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
