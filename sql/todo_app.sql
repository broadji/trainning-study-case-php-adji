-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 12, 2023 at 06:10 PM
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
-- Database: `todo_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `attachment`, `created_at`, `updated_at`) VALUES
(6, 'title', 'desckik', 'uploads/Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-09 10:48:23', '2023-11-12 04:58:11'),
(11, 'wkwkw', 'lol', 'uploads/Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-09 15:12:14', '2023-11-12 05:55:10'),
(13, '1', '2', 'Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-09 15:24:38', '2023-11-09 15:24:38'),
(14, '1', '2', 'Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-09 15:25:30', '2023-11-09 15:25:30'),
(15, '1', '2', 'uploads/Kartu Pelanggan GasKita Pintar-01.jpg', '2023-11-09 15:33:38', '2023-11-09 15:33:38'),
(16, '1', '2', 'uploads/Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-09 15:39:51', '2023-11-09 15:39:51'),
(25, 'test_create', 'masuk', 'uploads/Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-12 16:43:09', '2023-11-12 16:43:09'),
(27, 'test_edit', 'Update Description', 'uploads/Kartu Pelanggan GasKita Pintar-02.jpg', '2023-11-12 16:44:30', '2023-11-12 16:45:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(2, 'test', 'test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
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
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
