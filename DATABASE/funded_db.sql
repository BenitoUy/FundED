-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 01:50 PM
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
-- Database: `funded_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `studentId` varchar(50) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `goal` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `goal_amount` decimal(10,2) DEFAULT 0.00,
  `raised_amount` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `user_id`, `name`, `email`, `studentId`, `title`, `goal`, `category`, `description`, `image`, `created_at`, `goal_amount`, `raised_amount`) VALUES
(6, 4, '', '', NULL, 'Scholarship', 4246.00, NULL, '142424242', 'istockphoto-517188688-612x612.jpg', '2025-11-02 12:16:29', 0.00, 0.00),
(7, 6, '', '', NULL, 'Thesis Printing and Binding Costs', 7000.00, NULL, 'a 4th-year BS Psychology student at the University of the Philippines. I’m currently completing my thesis, but I’m struggling to afford the printing, binding, and submission costs. Any support, big or small, will help me finish strong and finally graduate. Thank you for helping make my dream come true! ❤️', '326-3265737_order-online-book-bind-thesis.jpg', '2025-11-02 12:39:02', 0.00, 0.00),
(9, 6, '', '', NULL, '🎓 Keep Me in School – Tuition Assistance for 2nd Semester', 10000.00, NULL, 'Computer Engineering student at Batangas State University. Due to financial challenges, my family can’t cover my tuition for the upcoming semester. I’m working part-time, but it’s not enough. I’m humbly asking for support to continue my studies and pursue my dream of becoming an engineer.', 'GettyImages-1338373232.png', '2025-11-02 12:41:48', 0.00, 0.00),
(10, 4, '', '', NULL, 'Support My Review for the Nursing Board Exam ', 15000.00, NULL, 'a BS Nursing graduate preparing for the July 2025 Nursing Licensure Examination. My family has limited income, and I need help covering the review center fee and materials. Your donation will help me reach my dream of becoming a registered nurse and giving back to my community through healthcare.', 'images (1).jpg', '2025-11-02 12:43:43', 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default.png',
  `bio` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `profile_image`, `bio`, `phone`, `address`, `created_at`, `location`) VALUES
(4, 'Benito Rossi Uy', 'ben@gmail.com', '$2y$10$mlYlm4BE0hWdXtRWZSnj0.uHkvVyPT1rs/XpspqDZ0uq7T3tYmiHq', 'pngtree-businessman-user-avatar-wearing-suit-with-red-tie-png-image_5809521.png', 'Hi! I’m a hardworking college student passionate about learning and achieving my dreams despite financial challenges. I believe education is the key to changing lives, and I’m doing my best to make mine count', '09563996842', '14 madankay', '2025-11-02 07:08:11', 'Makati, Philippines'),
(6, 'John Doe', 'br@gmail.com', '$2y$10$X8jf0aikfplcgEDgwODPw.7cC4MvZWGFU8MnSSzbfK0IClykakJde', 'images (2).jpg', 'A consistent honor student dedicated to academic excellence and community service. I’m grateful for every opportunity to learn, grow, and inspire others who also dream of finishing their education despite financial difficulties.', '095639535', 'makati', '2025-11-02 07:57:06', 'Manila, Philippines');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_campaign_user` (`user_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `fk_campaign_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
