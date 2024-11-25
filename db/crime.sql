-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2024 at 11:23 AM
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
-- Database: `crime`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'lowelljaybrosoto1998@gmail.com', '2c78e8b645c18e44605bcb24eefe7ced0e7cb3eb51326cf7e938dbb165e2e4cf', '2024-09-09 04:48:31', '2024-09-09 01:48:31'),
(2, 'lowelljaybrosoto1998@gmail.com', '8020fe413220f734f88dc59bb37e4a2c356432ddc5cfd4bba2f92e4369fb227e', '2024-09-09 04:49:04', '2024-09-09 01:49:04'),
(3, 'lowelljaybrosoto1998@gmail.com', '79e3db3c1de6f9ce6132ea0f850770f449f270a266823495231d110bc811fde8', '2024-09-09 04:49:17', '2024-09-09 01:49:17'),
(4, 'test@gmail.com', 'd3749740ef59403d708c475222c25a3cc61ce59d4971c80109247da4716b8177', '2024-09-09 06:20:31', '2024-09-09 03:20:31'),
(5, 'test1@gmail.com', 'e1c30f3ecc61f1d0252825534b9b6801456fc06e3ae763dedac0733d2a06aac7', '2024-09-09 07:08:25', '2024-09-09 04:08:25'),
(6, 'test2@gmail.com', '99ecd21feb076ba854d619f4a24ee9665ce4c02fbda80e2eca2b0db7e7b1b0c6', '2024-09-09 07:09:48', '2024-09-09 04:09:48'),
(7, 'test3@gmail.com', 'a0f960b5204d860d65ce0d5acb9143b42f1f4961135b564bca8af0840fe7bf56', '2024-09-09 07:11:15', '2024-09-09 04:11:15'),
(8, 'test4@gmail.com', '9c9c7ca67343e3642b9d244a9b80784fa20e5c2ecf2edd29c9e3f902a64d5ad6', '2024-09-09 07:14:08', '2024-09-09 04:14:08'),
(9, 'test5@gmail.com', '007552ec08ea061003e9ebbdc651f6754cdb2ddf6b4532c56ca9334c327219de', '2024-09-09 07:14:44', '2024-09-09 04:14:44'),
(10, 'test6@gmail.com', '66357425f65bbfbc9064cf145bc7d71998a0e64f6d16491f711646d30ef23bbb', '2024-09-09 07:15:41', '2024-09-09 04:15:41'),
(11, 'test@gmail.com', '09d891bbb6ce5bb1b713d3d23ae62c95da262d29534939f9af0cfc9e96fe0ff9', '2024-09-09 10:44:36', '2024-09-09 07:44:36'),
(12, 'test1@gmail.com', 'fe8c56ed14f507e0212e31d1d1527c7d2c1e8cf91228b92407c813b9eb036985', '2024-09-09 10:45:20', '2024-09-09 07:45:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `dateOfBirth` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin','registrar') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `address`, `dateOfBirth`, `gender`, `phone_number`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Lowell Jay Godoyo Brosoto', '#19 Neptune St. Santana Subd Taytay Rizal', '2024-09-09', 'Male', '09498679047', 'admin@gmail.com', '$2y$10$Y/R.eITYsso2Ed0/IPlwXO.UsQfGTqH/9dFZMhWNHrE7aY63ZgdCS', 'user', '2024-09-09 07:42:38', '2024-09-09 07:42:38'),
(2, 'test test', 'Bagong Tubig, San Luis, Batangas', '1998-03-12', 'Male', '091231231234', 'test@gmail.com', '$2y$10$ZzrLIO/TBtJuBFi00GjmDeKmt7SNRd8Lo3dOUVCswZu7hWxNAndDK', 'user', '2024-09-09 07:44:36', '2024-09-09 07:44:36'),
(3, 'test1 test1', 'Bagong Tubig, San Luis, Batangas', '1998-03-12', 'Male', '091231231234', 'test1@gmail.com', '$2y$10$ptKjlFruSl9MzYkDAb0Eze8L9qnqBMaEYiFvm8OXutp7p.Qcp6NEy', 'user', '2024-09-09 07:45:20', '2024-09-09 07:45:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_email` (`email`);

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
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
