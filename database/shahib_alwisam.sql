-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 10 يناير 2026 الساعة 23:55
-- إصدار الخادم: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shahib_alwisam`
--
CREATE DATABASE IF NOT EXISTS `shahib_alwisam` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shahib_alwisam`;

-- --------------------------------------------------------

--
-- بنية الجدول `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `content`, `image_path`, `active`, `meta_title`, `meta_description`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'ssssssssssss', 'ssssssssssss', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 'blog/1TtQYytxcN7w4FCKrkf3a0RdTNbj1gafEBZl9Tjx.png', 0, NULL, NULL, '2026-01-05 21:00:00', '2026-01-05 21:33:22', '2026-01-08 21:23:14'),
(2, 'ييييس', 'yyyysssssssssssssssss', 'ستم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 'blog/ry27ZQp3nryLMZntcK6Y4gq2HMQiIYYbCCkUTWeb.jpg', 0, NULL, NULL, '2026-01-05 21:00:00', '2026-01-05 22:15:19', '2026-01-08 21:23:03'),
(3, 'jvggd', 'jvggd', 'testimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialsv', 'blog/B251aRDPc76rPY0dr6FPdT6jmsTGem3R1weN10H5.png', 1, NULL, NULL, '2025-03-06 21:00:00', '2026-01-06 21:57:04', '2026-01-08 21:25:20'),
(4, 'تجربة', 'tgrb', 'مقبمبمممممممممممممممممممممممممممممممممممم', 'blog/tgrb-1768005209.webp', 1, NULL, NULL, '2026-01-09 21:00:00', '2026-01-09 21:31:26', '2026-01-09 21:33:29');

-- --------------------------------------------------------

--
-- بنية الجدول `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `failed_jobs`
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
-- بنية الجدول `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `image_path`, `project_id`, `active`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 'ييييييي', 'gallery/3Lzp8ZhY3vxN45fbHTnG7Ld7mcP7AkivJfvGouek.jpg', 1, 1, 2, '2026-01-05 22:14:29', '2026-01-06 23:53:50'),
(3, 'صبه', 'gallery/sbh-1768004431.webp', 3, 1, 1, '2026-01-06 21:58:52', '2026-01-09 21:20:31'),
(4, 'حفريات', 'gallery/hfryat-1768004498.webp', 2, 1, 5, '2026-01-09 21:21:38', '2026-01-09 21:21:38');

-- --------------------------------------------------------

--
-- بنية الجدول `jobs`
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
-- بنية الجدول `job_batches`
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
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `reply_content` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `reply_content`, `replied_at`, `created_at`, `updated_at`) VALUES
(1, 'Full Stack Development', 'amjed@gmail.com', '00967775226109', 'عرض سعر', 'ييييي', 1, 'سسسسسسسس', '2026-01-05 23:24:23', '2026-01-05 23:20:26', '2026-01-05 23:26:08'),
(2, 'Full Stack Development 2', 'admin@alwisam.com', '00967775226109', 'دعم', 'سسسسسس', 1, 'يييييييييييييييييييييييييييييييييييييييي', '2026-01-05 23:25:46', '2026-01-05 23:25:06', '2026-01-05 23:25:46'),
(3, 'Full Stack Development', 'amjed@gmail.com', '00967775226109', 'استشارة', 'يييييييييييييييي', 1, NULL, NULL, '2026-01-06 23:44:13', '2026-01-06 23:44:20'),
(4, 'Full Stack Development', 'alomisy03@gmail.com', '00967775226109', 'عرض سعر', 'وااااااااا يحييييييىىىىىىىى', 1, 'مااااااههههههههوووووووووو', '2026-01-07 08:35:56', '2026-01-07 08:35:24', '2026-01-07 08:35:56'),
(5, 'Laravel 12', 'alomisy03@gmail.com', '00967775226109', 'دعم', 'aaaaaaaaaa', 1, NULL, NULL, '2026-01-07 13:47:58', '2026-01-07 13:48:14');

-- --------------------------------------------------------

--
-- بنية الجدول `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(53, '0001_01_01_000000_create_users_table', 1),
(54, '0001_01_01_000001_create_cache_table', 1),
(55, '0001_01_01_000002_create_jobs_table', 1),
(56, '2026_01_05_221129_create_services_table', 1),
(57, '2026_01_05_221131_create_projects_table', 1),
(58, '2026_01_05_221132_create_gallery_images_table', 1),
(59, '2026_01_05_221133_create_blog_posts_table', 1),
(60, '2026_01_05_221134_create_skills_table', 1),
(61, '2026_01_05_221134_create_testimonials_table', 1),
(62, '2026_01_05_221135_create_settings_table', 1),
(63, '2026_01_05_224039_add_seo_fields_to_tables', 1),
(64, '2026_01_06_000000_create_messages_table', 2);

-- --------------------------------------------------------

--
-- بنية الجدول `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `projects`
--

INSERT INTO `projects` (`id`, `title`, `slug`, `description`, `location`, `scope`, `duration`, `main_image`, `active`, `meta_title`, `meta_description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, '', 'moss', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 'الباب', 'صنعاء', '2023/5/10', 'projects/moss-1768006836.webp', 1, NULL, NULL, 1, '2026-01-05 21:14:01', '2026-01-09 22:00:36'),
(2, 'امجد العميسي', 'amgd-alaamysy', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 'الحثيلي', 'صنعاء', 'شهرين', 'projects/amgd-alaamysy-1768004676.webp', 1, NULL, NULL, 3, '2026-01-06 21:18:35', '2026-01-09 21:24:36'),
(3, 'محمد', 'mhmd', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 'الحثيلي', 'صنعاء', 'شهرين', 'projects/mhmd-1768006891.webp', 1, NULL, NULL, 2, '2026-01-06 21:19:43', '2026-01-09 22:01:31'),
(4, 'صبيات', 'sbyat', 'بسبسسسسسسسسسسسسسسسسسسسسبييييييييييييييييييييييييييييييييييييييييييييييييييييييييييييييي\r\nببببببببببببببببببببببببببببب', 'جدة', NULL, NULL, 'projects/sbyat-1768006862.webp', 1, NULL, NULL, 4, '2026-01-09 20:19:48', '2026-01-09 22:01:02');

-- --------------------------------------------------------

--
-- بنية الجدول `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `services`
--

INSERT INTO `services` (`id`, `title`, `slug`, `description`, `icon`, `image_path`, `active`, `meta_title`, `meta_description`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'امجد', 'lyaaaaallrrr', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 'fa-soild fa-tools', 'services/amgd-1768005632.webp', 1, NULL, 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', 1, '2026-01-05 21:29:59', '2026-01-09 21:40:32'),
(2, 'خرسانه', 'khrsanh', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', NULL, 'services/khrsanh-1768005523.webp', 1, NULL, NULL, 2, '2026-01-06 21:20:50', '2026-01-09 21:38:44'),
(3, 'جديدوو', 'gdydoo', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', NULL, 'services/gdydoo-1768005439.webp', 1, NULL, NULL, 1, '2026-01-08 14:17:57', '2026-01-09 21:37:19');

-- --------------------------------------------------------

--
-- بنية الجدول `sessions`
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
-- إرجاع أو استيراد بيانات الجدول `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1sXRWp7j1PmPvF5F0EdJhadC6E5ceY3vxps7KKpK', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicGhHbzZxdFpQT1J6T2pKaENObDQya3RxNXpTVmtzaGNmcVpBVmZlUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9za2lsbHMiO3M6NToicm91dGUiO3M6MTg6ImFkbWluLnNraWxscy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1768084246),
('kjlUDoJ8rNlfWqjthEg2cawPSD9vtstILvmUNyn1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibERDT0NFMjFQZzZJR0xxTFNOdU9WeTVWd0VCcjB1Q1ZPSUpKd1NRYSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vc2V0dGluZ3MiO3M6NToicm91dGUiO3M6MjA6ImFkbWluLnNldHRpbmdzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1768008983),
('RuCJQuyHgPQTyw9sjVtLgtQ8xXJxHnysEDPaYBbK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0JubkRPcFBVTnZJbXhydkh0RHIyNlZtZGVuaHA1eUZrN0lrUFpWQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9qZWN0cy9taG1kIjtzOjU6InJvdXRlIjtzOjEzOiJwcm9qZWN0cy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768006523);

-- --------------------------------------------------------

--
-- بنية الجدول `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'الفـن الـحـديـث', '2026-01-05 20:27:14', '2026-01-07 00:01:01'),
(2, 'site_description', 'مراااا وااااو', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(3, 'phone', '775226109', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(4, 'email', 'alomisy03@gmail.com', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(5, 'address', 'الحثيلي', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(6, 'facebook', 'https://www.facebook.com/a8xxf', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(7, 'twitter', 'https://twitter.com/a8xxe', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(8, 'instagram', 'https://www.instagram.com/a8xxf', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(9, 'linkedin', 'https://www.instagram.com/a8xxf', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(10, 'whatsapp', 'https://wa.me/+967775226109', '2026-01-05 20:27:14', '2026-01-05 22:23:22'),
(11, 'site_logo', 'settings/ok1SX6rbV0DSu02jeVJW6slSeUWyCrJIYGGndrSb.png', '2026-01-05 20:27:14', '2026-01-05 20:27:14'),
(12, 'site_favicon', 'settings/JguAadzCukW9D1IfGtUfZnphRUQJTGEtwj7gZTRN.png', '2026-01-05 20:27:14', '2026-01-07 00:01:45'),
(13, 'services_meta_title', 'خدماتنا', '2026-01-06 20:21:33', '2026-01-06 22:19:22'),
(14, 'services_meta_description', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', '2026-01-06 20:21:33', '2026-01-06 20:21:33'),
(15, 'projects_meta_title', 'مشاريعنا', '2026-01-06 20:21:33', '2026-01-06 22:26:19'),
(16, 'projects_meta_description', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', '2026-01-06 20:21:33', '2026-01-06 20:21:33'),
(17, 'blog_meta_title', 'المدونة', '2026-01-06 20:21:33', '2026-01-06 22:26:19'),
(18, 'blog_meta_description', 'تم تصميم وتنفيذ هذا المشروع السكني ليكون تحفة معمارية تجمع بين الأصالة والحداثة. ركزنا في هذا المشروع على استغلال المساحات بشكل أمثل وتوفير بيئة سكنية مريحة وفاخرة للعائلة.\r\n\r\nشمل العمل جميع المراحل بدءاً من التصميم المعماري والإنشائي، مروراً بأعمال الحفر والأساسات، وصولاً إلى التشطيبات النهائية والديكور الداخلي وتنسيق الحدائق.', '2026-01-06 20:21:33', '2026-01-06 20:21:33');

-- --------------------------------------------------------

--
-- بنية الجدول `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `skills`
--

INSERT INTO `skills` (`id`, `name`, `percentage`, `active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Full Stack Development', 80, 1, 1, '2026-01-05 20:25:37', '2026-01-05 20:25:37');

-- --------------------------------------------------------

--
-- بنية الجدول `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `testimonials`
--

INSERT INTO `testimonials` (`id`, `client_name`, `position`, `content`, `image_path`, `rating`, `active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'امجد', 'مهندس', 'صرااااااااااااااااااااااااااااااااااااااااااااححححححححححححححححححححححححححححححههههههههههههههههههههههه ممممممممممممممممممررررررررررررررررررررااااااااااااااااااااا ووووووووووووووووواااااااااااااااااااااااوووووووووووووووووو', 'testimonials/h3PJreJaMX1zLrmee9oyNa3iKGK187NTz9orul4A.jpg', 1, 1, 1, '2026-01-05 22:17:27', '2026-01-06 17:38:58'),
(4, 'dd', 'مهندس', 'testimonials', 'testimonials/egdR8Dmpk9QyQOACUPgH6TLVnKWzpJjiKC2yvUl8.jpg', 5, 1, 2, '2026-01-06 21:51:36', '2026-01-06 21:51:36'),
(5, 'dd', 'مهندس', 'testimonials', 'testimonials/VOHFIqu14zO6Om4eASidfE2pZktyi9EoHHo25eWl.jpg', 3, 1, 3, '2026-01-06 21:51:55', '2026-01-06 21:51:55'),
(6, 'امجد', 'مهندس', 'testimonialstestimonialstestimonials', 'testimonials/9vMZpYe7JsJnGyXhTqiN3wfqysyRd6iZxnJgqnop.jpg', 4, 1, 4, '2026-01-06 21:52:54', '2026-01-06 21:52:54'),
(7, 'lpl]', 'مهندس', 'testimonialstestimonialstestimonialstestimonials', 'testimonials/LLCIapyH22XgD9uQDrEv8lSO5CFJKRLhgPy1Vvf5.jpg', 2, 1, 5, '2026-01-06 21:53:16', '2026-01-06 21:53:16'),
(8, 'moh', 'sa', 'testimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonialstestimonials', 'testimonials/ukOEN7x0INUuUvoW8QF8wRF3kh9DwuQwuMDr4jSp.jpg', 1, 1, 6, '2026-01-06 21:54:17', '2026-01-06 21:54:47'),
(9, 'امجددوووو', NULL, 'بببببببببببببببببببب', 'testimonials/amgddoooo-1768006202.webp', 5, 1, 0, '2026-01-09 21:45:07', '2026-01-09 21:50:02');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@alwisam.com', '2026-01-05 19:49:57', '$2y$12$l.RLjOh1Dg6nbIiKyOkope2DrNsy2NhohaW.2JZoqwj7kO0xAAN7K', NULL, '2026-01-05 19:49:57', '2026-01-05 19:49:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_posts_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_images_project_id_foreign` (`project_id`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_slug_unique` (`slug`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD CONSTRAINT `gallery_images_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
