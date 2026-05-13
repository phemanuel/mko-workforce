-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 at 04:42 PM
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
-- Database: `mko-workforce`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `type`, `title`, `description`, `meta`, `created_at`, `updated_at`) VALUES
(1, 2, 'admin_review_application', 'Reviewed application', 'Admin opened application for Femi Akinyooye', '{\"user_id\":6}', '2026-05-12 11:57:55', '2026-05-12 11:57:55'),
(2, 2, 'admin_review_application', 'Reviewed application', 'Admin opened application for Femi Akinyooye', '{\"user_id\":6}', '2026-05-12 11:58:43', '2026-05-12 11:58:43'),
(3, 2, 'admin_review_application', 'Reviewed application', 'Admin opened application for Femi Akinyooye', '{\"user_id\":6}', '2026-05-12 12:01:32', '2026-05-12 12:01:32'),
(4, 2, 'document_approved', 'Document approved', 'System Admin - Passport has been approved by System Admin', '{\"user_id\":2}', '2026-05-12 12:01:50', '2026-05-12 12:01:50'),
(5, 2, 'document_approved', 'Document approved', 'System Admin - DBS has been approved by System Admin', '{\"user_id\":2}', '2026-05-12 12:01:55', '2026-05-12 12:01:55'),
(6, 2, 'document_approved', 'Document approved', 'System Admin - SIA Licence has been approved by System Admin', '{\"user_id\":2}', '2026-05-12 12:01:56', '2026-05-12 12:01:56'),
(7, 2, 'document_approved', 'Document approved', 'System Admin - Certificates has been approved by System Admin', '{\"user_id\":2}', '2026-05-12 12:01:58', '2026-05-12 12:01:58'),
(8, 2, 'application_approved', 'Application approved', 'Femi Akinyooye has been approved by System Admin', '{\"user_id\":6}', '2026-05-12 12:02:01', '2026-05-12 12:02:01'),
(9, 2, 'admin_review_application', 'Reviewed application', 'Admin opened application for Femi Akinyooye', '{\"user_id\":6}', '2026-05-12 12:02:01', '2026-05-12 12:02:01'),
(10, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:03:17', '2026-05-12 12:03:17'),
(11, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:05:14', '2026-05-12 12:05:14'),
(12, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:11:54', '2026-05-12 12:11:54'),
(13, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:14:17', '2026-05-12 12:14:17'),
(14, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:14:45', '2026-05-12 12:14:45'),
(15, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:17:30', '2026-05-12 12:17:30'),
(16, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:19:36', '2026-05-12 12:19:36'),
(17, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:21:27', '2026-05-12 12:21:27'),
(18, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:21:35', '2026-05-12 12:21:35'),
(19, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:21:49', '2026-05-12 12:21:49'),
(20, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:22:21', '2026-05-12 12:22:21'),
(21, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:22:26', '2026-05-12 12:22:26'),
(22, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:23:23', '2026-05-12 12:23:23'),
(23, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:23:26', '2026-05-12 12:23:26'),
(24, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:23:53', '2026-05-12 12:23:53'),
(25, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:28:46', '2026-05-12 12:28:46'),
(26, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:31:50', '2026-05-12 12:31:50'),
(27, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:36:53', '2026-05-12 12:36:53'),
(28, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:38:01', '2026-05-12 12:38:01'),
(29, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:39:03', '2026-05-12 12:39:03'),
(30, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:39:10', '2026-05-12 12:39:10'),
(31, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:39:25', '2026-05-12 12:39:25'),
(32, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:39:54', '2026-05-12 12:39:54'),
(33, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:41:43', '2026-05-12 12:41:43'),
(34, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:42:13', '2026-05-12 12:42:13'),
(35, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-12 12:45:39', '2026-05-12 12:45:39'),
(36, 2, 'employee_login', 'Employee login', 'System Admin-1 successfully login', '[]', '2026-05-13 10:08:04', '2026-05-13 10:08:04'),
(37, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:10:17', '2026-05-13 10:10:17'),
(38, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:19:33', '2026-05-13 10:19:33'),
(39, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:23:45', '2026-05-13 10:23:45'),
(40, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:23:59', '2026-05-13 10:23:59'),
(41, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:25:28', '2026-05-13 10:25:28'),
(42, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:25:43', '2026-05-13 10:25:43'),
(43, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:25:49', '2026-05-13 10:25:49'),
(44, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:32:50', '2026-05-13 10:32:50'),
(45, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:43:27', '2026-05-13 10:43:27'),
(46, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:47:41', '2026-05-13 10:47:41'),
(47, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:47:58', '2026-05-13 10:47:58'),
(48, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:54:37', '2026-05-13 10:54:37'),
(49, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:57:35', '2026-05-13 10:57:35'),
(50, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 10:57:56', '2026-05-13 10:57:56'),
(51, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 11:00:24', '2026-05-13 11:00:24'),
(52, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 11:16:01', '2026-05-13 11:16:01'),
(53, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 11:17:32', '2026-05-13 11:17:32'),
(54, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 11:17:54', '2026-05-13 11:17:54'),
(55, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 13:11:35', '2026-05-13 13:11:35'),
(56, 2, 'shift_viewed', 'Shifts viewed', 'Shift module was viewed by System Admin', '[]', '2026-05-13 13:13:07', '2026-05-13 13:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `check_in_time` datetime DEFAULT NULL,
  `check_out_time` datetime DEFAULT NULL,
  `check_in_lat` decimal(10,7) DEFAULT NULL,
  `check_in_lng` decimal(10,7) DEFAULT NULL,
  `check_out_lat` decimal(10,7) DEFAULT NULL,
  `check_out_lng` decimal(10,7) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'present',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) DEFAULT NULL,
  `employment_type` enum('Full-Time','Part-Time','Casual') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `primary_role` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `employment_type`, `start_date`, `primary_role`, `status`, `created_at`, `updated_at`) VALUES
(2, 6, NULL, 'Full-Time', '2026-07-01', 'SIA Security', 'Pending', '2026-05-07 09:00:05', '2026-05-07 09:41:03'),
(3, 4, NULL, 'Full-Time', '2026-06-01', 'Cleaning Services', 'Pending', '2026-05-07 15:27:03', '2026-05-07 15:29:53');

-- --------------------------------------------------------

--
-- Table structure for table `employee_documents`
--

CREATE TABLE `employee_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `verification_status` enum('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `verified_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_documents`
--

INSERT INTO `employee_documents` (`id`, `employee_id`, `document_type`, `file_path`, `expiry_date`, `verification_status`, `verified_by`, `created_at`, `updated_at`, `status`, `rejection_reason`, `verified_at`) VALUES
(1, 2, 'Passport', 'employee/documents/PhQtYbvuqebgIrCLQbQOXZAZVYttrOVEIiRQ6kAA.jpg', NULL, 'Pending', 2, '2026-05-07 10:29:06', '2026-05-12 12:01:50', 'approved', NULL, '2026-05-12'),
(2, 2, 'NI Proof', 'employee/documents/fthY7r1MTo4W2uOxiGDnsJSYRxfBF6WOHKct7UkF.pdf', NULL, 'Pending', 2, '2026-05-07 10:29:06', '2026-05-12 11:54:17', 'approved', NULL, '2026-05-12'),
(3, 2, 'DBS', 'employee/documents/X8ln8ls5hsnMLbJVeOQdtDPW3LW1FsyDhfIJx3g2.pdf', NULL, 'Pending', 2, '2026-05-07 10:29:06', '2026-05-12 12:01:55', 'approved', NULL, '2026-05-12'),
(4, 2, 'SIA Licence', 'employee/documents/SewA48luK9lP2qhrXsKrcPnNoQYIOP8HHGpxQm5Q.pdf', NULL, 'Pending', 2, '2026-05-07 10:29:06', '2026-05-12 12:01:56', 'approved', NULL, '2026-05-12'),
(5, 2, 'Certificates', 'employee/documents/eqwLVZg7mSIH2WgvPSPSTmMbgSpcyJuTj9eTKYv6.pdf', NULL, 'Pending', 2, '2026-05-07 10:29:07', '2026-05-12 12:01:58', 'approved', NULL, '2026-05-12'),
(6, 3, 'Passport', 'employee/documents/AYdx2qgnHWNG7m67fCTIJ5Hat4fZRSosuP1mfQ9f.jpg', NULL, 'Pending', NULL, '2026-05-10 19:08:20', '2026-05-10 19:08:20', 'pending', NULL, NULL),
(7, 3, 'NI Proof', 'employee/documents/AxDo8lgjM14ZR6gg85YXdqyQVOePwg6aAOTDy2Ki.pdf', NULL, 'Pending', NULL, '2026-05-10 19:08:20', '2026-05-10 19:08:20', 'pending', NULL, NULL),
(8, 3, 'DBS', 'employee/documents/078kQPrUyJNGb8upF8nGvtMGW4AL4pwnL8OyJ6Lc.pdf', NULL, 'Pending', NULL, '2026-05-10 19:08:20', '2026-05-10 19:08:20', 'pending', NULL, NULL),
(9, 3, 'SIA Licence', 'employee/documents/1FtGrMWuNQFJddN9b6EEG6xPxSQd6QGYoTMQLIKn.pdf', NULL, 'Pending', NULL, '2026-05-10 19:08:20', '2026-05-10 19:08:20', 'pending', NULL, NULL),
(10, 3, 'Certificates', 'employee/documents/lUoq7bwFthDp9RCQwI8RnSxf6Mebh0KcPirg0rc0.pdf', NULL, 'Pending', NULL, '2026-05-10 19:08:20', '2026-05-10 19:08:20', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_payrolls`
--

CREATE TABLE `employee_payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `sort_code` varchar(255) DEFAULT NULL,
  `utr` varchar(255) DEFAULT NULL,
  `payment_type` enum('PAYE','Self-Employed') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_payrolls`
--

INSERT INTO `employee_payrolls` (`id`, `employee_id`, `bank_name`, `account_number`, `sort_code`, `utr`, `payment_type`, `created_at`, `updated_at`) VALUES
(1, 2, 'HSBC', 'eyJpdiI6IjZJUXByNDh5Z3hLN08wNHJFMEgzQ2c9PSIsInZhbHVlIjoicCtsa01BOGxEOWhuSy9MWFlMOCtiUT09IiwibWFjIjoiNjNhYWQ2NGFmM2RiYzZiNmUzMWEzYjg3ZjhkZjdmNTllYzUyOGUxNzI3Yjg0OGVmOGM4ZDFlZjdlMWQzOGQxOSIsInRhZyI6IiJ9', 'eyJpdiI6IlV3UnI2Zi9XN3dVMU1zZFgrRTl4aEE9PSIsInZhbHVlIjoiY0tPNGlTNlJFbEpBdlJsM2RlNUJxQT09IiwibWFjIjoiMWIyODg0ZWQyMGQ2ZDkwZGIwZjNiYjY3MzM3YjQ3ZTkzMjQ1M2M1Y2U2YmJmNGY2MjVlNzgwMmNkOTkzYmMyYSIsInRhZyI6IiJ9', NULL, 'PAYE', '2026-05-07 10:10:55', '2026-05-07 10:10:55'),
(2, 3, 'HSBC', 'eyJpdiI6InU1c3JjNHcxR1NGUk9wSjNUalVES3c9PSIsInZhbHVlIjoiTGFyYmdCRkZVWFhXcytBaU43MlgvUT09IiwibWFjIjoiY2EwYTQ0NDg4YmNmOWQ1MTZlOWVkZGM4ZGE0MTZhMTEwMjA2NDY2ODBhMDg2OGRjNmM1YmExNGJlMGU2Y2FjMCIsInRhZyI6IiJ9', 'eyJpdiI6IkcxcjdEbW1GU3BoQjJDREsxbGFPNWc9PSIsInZhbHVlIjoiaVdSRzJ0YkIzY3ZKejRJSDM3Y1dZUT09IiwibWFjIjoiNDE2NDUwNTFhNjM1NTU0OGEyNTg1YmNlZmVmNjY0YmMxMmQ0NzUxZmYyMTJjNWVkODBmNzlkZDJjMDU4NjhlNyIsInRhZyI6IiJ9', 'eyJpdiI6IkdlTjhGVERIY0JDYTF4RlhaVXNnSGc9PSIsInZhbHVlIjoiRzQ4WE9XRHRzUENEa2Q5eEdEdXkxdz09IiwibWFjIjoiYzI0YmI2MzhhOGY4ZjQ2MTU2YjRlMDNlOTNiYjRlZDI4NTBmMzg2NThhNmQ2ZjQ5NTFlMWZiMTA2MGIwNDk2ZSIsInRhZyI6IiJ9', 'Self-Employed', '2026-05-10 19:07:06', '2026-05-10 19:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `employee_profiles`
--

CREATE TABLE `employee_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `ni_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `postcode` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_profiles`
--

INSERT INTO `employee_profiles` (`id`, `employee_id`, `date_of_birth`, `gender`, `nationality`, `ni_number`, `address`, `postcode`, `phone`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`) VALUES
(1, 2, '1987-12-31', 'Male', 'Nigeria', '23454', 'Ibadan', '234', '07032689329', 'Temiloluwa Akinyooye', '08053607899', '2026-05-07 09:34:11', '2026-05-07 09:34:11'),
(2, 3, '1999-01-05', 'Female', 'Egypt', '9494849292', 'Egypt', '203821', '0704578389292', 'Aishat Alli', '9300389292929', '2026-05-07 15:27:03', '2026-05-11 00:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `employee_role_details`
--

CREATE TABLE `employee_role_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `role_type` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `data1` longtext NOT NULL,
  `secondary_skills` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_role_details`
--

INSERT INTO `employee_role_details` (`id`, `employee_id`, `role_type`, `data`, `data1`, `secondary_skills`, `created_at`, `updated_at`) VALUES
(1, 2, 'SIA Security', '{\"sia_licence_number\":\"SIA23949\",\"licence_type\":\"Intermediate\",\"expiry_date\":\"2028-12-31\",\"first_aid\":\"Yes\",\"first_aid_expiry\":\"2026-12-31\",\"badge\":\"employee\\/badges\\/s92XtxHGfGXKboG1o8H0T89lSi9i4LiVSjJ7HsfA.jpg\"}', '', '', '2026-05-07 09:41:03', '2026-05-07 10:04:10'),
(2, 3, 'Cleaning Services', '[]', '[]', '[\"Cleaning Commercial\",\"Cleaning Residential\",\"Care Support Work\"]', '2026-05-07 15:33:52', '2026-05-11 00:24:06');

-- --------------------------------------------------------

--
-- Table structure for table `employee_skill`
--

CREATE TABLE `employee_skill` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `skill_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_work_eligibilities`
--

CREATE TABLE `employee_work_eligibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `work_status` enum('UK Citizen','BRP','Visa') DEFAULT NULL,
  `share_code` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_work_eligibilities`
--

INSERT INTO `employee_work_eligibilities` (`id`, `employee_id`, `work_status`, `share_code`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 2, 'BRP', 'SD848932', '2026-12-31', '2026-05-07 09:34:11', '2026-05-07 09:34:11'),
(2, 3, 'Visa', '93030289292', '2028-11-30', '2026-05-07 15:27:03', '2026-05-07 15:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_06_231927_create_roles_table', 1),
(5, '2026_05_06_232005_add_role_id_to_users_table', 1),
(6, '2026_05_06_235253_update_users_table_for_mko', 1),
(7, '2026_05_07_025421_add_phon_no_to_users_table', 1),
(8, '2026_05_07_033949_add_registration_step_to_users_table', 1),
(9, '2026_05_07_093313_create_employees_table', 1),
(10, '2026_05_07_093318_create_employee_profiles_table', 1),
(11, '2026_05_07_093331_create_employee_work_eligibilities_table', 1),
(12, '2026_05_07_093341_create_employee_payrolls_table', 1),
(13, '2026_05_07_093350_create_employee_role_details_table', 1),
(14, '2026_05_07_093359_create_skills_table', 1),
(15, '2026_05_07_093434_create_employee_skill_table', 1),
(17, '2026_05_07_111306_create_employee_documents_table', 2),
(18, '2026_05_07_120710_add_approval_status_to_users_table', 3),
(19, '2026_05_07_124528_add_new_fields_employee_documents_table', 1),
(20, '2026_05_10_210545_create_activities_table', 4),
(21, '2026_05_11_015120_create_shifts_table', 5),
(22, '2026_05_11_015134_create_shift_assignments_table', 5),
(23, '2026_05_13_142139_create_attendances_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2026-05-07 08:50:47', '2026-05-07 08:50:47'),
(2, 'supervisor', '2026-05-07 08:50:47', '2026-05-07 08:50:47'),
(3, 'staff', '2026-05-07 08:50:47', '2026-05-07 08:50:47');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('miqI4pov31nRgCThCENjHP579bqMAfmm4dsEx39i', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiS0ZCeHBSYmhZNUVyVGZyVDRpVEQ1TXBvMkxGWnZOeW5hQm12UE9XYSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3NoaWZ0cyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vYXR0ZW5kYW5jZSI7czo1OiJyb3V0ZSI7czoxNjoiYXR0ZW5kYW5jZS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1778683246);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `role_required` varchar(255) NOT NULL,
  `required_staff` int(11) NOT NULL DEFAULT 1,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `status` enum('Open','Assigned','Completed','Cancelled') NOT NULL DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `title`, `description`, `shift_date`, `start_time`, `end_time`, `location`, `role_required`, `required_staff`, `hourly_rate`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Election Campaign', 'Election Campaign', '2026-05-15', '10:00:00', '12:00:00', 'Virgina', 'SIA Security', 5, 50.00, 'Open', '2026-05-11 01:04:35', '2026-05-11 01:04:35'),
(2, 'College Prom', 'Security to maintain law and order throughout the party.', '2026-05-18', '12:00:00', '17:00:00', 'Manchester', 'SIA Security', 2, 30.00, 'Open', '2026-05-11 01:12:42', '2026-05-12 12:22:25'),
(3, 'School Cleaning', 'School Cleaning', '2026-05-18', '10:00:00', '18:00:00', 'Fulham', 'Cleaning Services', 5, 35.00, 'Open', '2026-05-11 01:32:21', '2026-05-12 12:22:17'),
(4, 'After Party Cleaning', 'After Party Cleaning', '2026-05-14', '16:00:00', '18:00:00', 'Virgina', 'Cleaning Services', 2, 35.00, 'Assigned', '2026-05-11 01:38:58', '2026-05-13 11:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `shift_assignments`
--

CREATE TABLE `shift_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Assigned','Accepted','Declined','Completed') NOT NULL DEFAULT 'Assigned',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shift_assignments`
--

INSERT INTO `shift_assignments` (`id`, `shift_id`, `employee_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 4, 3, 'Assigned', '2026-05-13 11:17:54', '2026-05-13 11:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `phone_no` varchar(255) DEFAULT NULL,
  `registration_step` varchar(255) DEFAULT NULL,
  `approval_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `status`, `phone_no`, `registration_step`, `approval_status`) VALUES
(2, 'System Admin', 'admin@mko.com', NULL, '$2y$12$y0Da.VDURl5R7ZxOulxlH.owLHIUYeeWjfE8oafQyJ0wbI.wkYYEW', NULL, '2026-05-07 08:50:47', '2026-05-07 08:50:47', 1, 'active', NULL, NULL, 'approved'),
(3, 'Supervisor One', 'supervisor@mko.com', NULL, '$2y$12$fva3OC0zxwhyOqZOTSKomOvE/ZFvFmQHg2KHWT0IkJjkpIEQU.tEG', NULL, '2026-05-07 08:50:47', '2026-05-07 08:50:47', 2, 'active', NULL, NULL, 'approved'),
(4, 'Yusuf Alli Dangote', 'staff@mko.com', '2026-05-07 15:25:11', '$2y$12$67eEjcGnwYU/F9/4Lo6uiOEoSTMSPOJCyqyJd97QQusGNDmNcu1kS', NULL, '2026-05-07 08:50:48', '2026-05-11 00:27:37', 3, 'active', NULL, '7', 'approved'),
(6, 'Femi Akinyooye', 'emmakinyooye@gmail.com', '2026-05-07 09:00:27', '$2y$12$hq3Kls6yKoyQccDBf3QmhOkrBpy.GnAr.ahuKyDn.TRgCeIr5TlVy', NULL, '2026-05-07 09:00:05', '2026-05-12 12:02:01', 3, 'active', NULL, '7', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_shift_id_foreign` (`shift_id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  ADD KEY `employees_user_id_foreign` (`user_id`);

--
-- Indexes for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_documents_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_payrolls`
--
ALTER TABLE `employee_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_payrolls_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_profiles_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_role_details`
--
ALTER TABLE `employee_role_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_role_details_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_skill`
--
ALTER TABLE `employee_skill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_skill_employee_id_foreign` (`employee_id`),
  ADD KEY `employee_skill_skill_id_foreign` (`skill_id`);

--
-- Indexes for table `employee_work_eligibilities`
--
ALTER TABLE `employee_work_eligibilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_work_eligibilities_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_assignments`
--
ALTER TABLE `shift_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_assignments_shift_id_foreign` (`shift_id`),
  ADD KEY `shift_assignments_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_documents`
--
ALTER TABLE `employee_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee_payrolls`
--
ALTER TABLE `employee_payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_role_details`
--
ALTER TABLE `employee_role_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee_skill`
--
ALTER TABLE `employee_skill`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_work_eligibilities`
--
ALTER TABLE `employee_work_eligibilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shift_assignments`
--
ALTER TABLE `shift_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD CONSTRAINT `employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_payrolls`
--
ALTER TABLE `employee_payrolls`
  ADD CONSTRAINT `employee_payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD CONSTRAINT `employee_profiles_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_role_details`
--
ALTER TABLE `employee_role_details`
  ADD CONSTRAINT `employee_role_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_skill`
--
ALTER TABLE `employee_skill`
  ADD CONSTRAINT `employee_skill_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_skill_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_work_eligibilities`
--
ALTER TABLE `employee_work_eligibilities`
  ADD CONSTRAINT `employee_work_eligibilities_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shift_assignments`
--
ALTER TABLE `shift_assignments`
  ADD CONSTRAINT `shift_assignments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shift_assignments_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
