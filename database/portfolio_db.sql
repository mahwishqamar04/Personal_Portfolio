-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 19, 2026 at 10:48 AM
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
-- Database: `portfolio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `organization` varchar(150) DEFAULT NULL,
  `certificate_date` date DEFAULT NULL,
  `certificate_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `headline` varchar(200) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `education` varchar(200) DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `upwork_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `full_name`, `headline`, `bio`, `education`, `experience`, `profile_image`, `linkedin_url`, `github_url`, `upwork_url`, `created_at`) VALUES
(1, 'Mehwish Qamar', 'Data Analyst & AI Web Developer', 'I am a B.Com graduate from the University of Karachi, developing skills in data analytics, business intelligence and AI web development.', 'B.Com - University of Karachi', '1 year experience as Quality Controller (QC) at Shan Foods.', NULL, 'https://www.linkedin.com/in/mehwish-qamar-133230375', 'https://github.com/mahwishqamar04', 'https://www.upwork.com/freelancers/~0160585e54d9a38131', '2026-09-19 08:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `category`, `image`, `project_url`, `github_url`, `created_at`) VALUES
(1, 'Quetta Services Hub', 'A home services booking platform developed using Core PHP, MySQL, HTML, CSS and Bootstrap.', 'AI Web Development', 'assets/images/quetta-services-hub.png', 'https://quettaserviceshub1.rf.gd', 'https://github.com/mahwishqamar04/quetta_serviceshub1', '2026-09-19 08:35:32'),
(2, 'BizGuard AI', 'AI-based web development practice project.', 'AI Web Development', 'assets/images/bizguard-ai.png', NULL, NULL, '2026-09-19 08:35:32'),
(3, 'Mil Gaya AI', 'AI web development practice project.', 'AI Web Development', 'assets/images/mil-gaya-ai.png', NULL, NULL, '2026-09-19 08:35:32'),
(4, 'Power BI Sales Dashboard', 'Interactive sales dashboard created for data analytics practice.', 'Data Analytics', 'assets/images/powerbi-dashboard.png', NULL, NULL, '2026-09-19 08:35:32');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `icon`, `created_at`) VALUES
(1, 'Data Analytics & Dashboards', 'Data analysis, reporting and interactive Power BI dashboard development.', 'fas fa-chart-line', '2026-09-19 08:35:33'),
(2, 'AI Web Development', 'Development of AI-enabled web applications and projects.', 'fas fa-robot', '2026-09-19 08:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `skill_category` varchar(100) DEFAULT NULL,
  `proficiency` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `skill_name`, `skill_category`, `proficiency`, `created_at`) VALUES
(1, 'Data Analysis', 'Data Analytics', 85, '2026-09-19 08:35:33'),
(2, 'Power BI', 'Data Analytics', 85, '2026-09-19 08:35:33'),
(3, 'SQL', 'Data Analytics', 80, '2026-09-19 08:35:33'),
(4, 'Core PHP', 'Web Development', 80, '2026-09-19 08:35:33'),
(5, 'MySQL', 'Database', 80, '2026-09-19 08:35:33'),
(6, 'HTML', 'Web Development', 90, '2026-09-19 08:35:33'),
(7, 'CSS', 'Web Development', 85, '2026-09-19 08:35:33'),
(8, 'Bootstrap', 'Web Development', 85, '2026-09-19 08:35:33'),
(9, 'JavaScript', 'Web Development', 75, '2026-09-19 08:35:33'),
(10, 'AI Web Development', 'AI Development', 75, '2026-09-19 08:35:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
