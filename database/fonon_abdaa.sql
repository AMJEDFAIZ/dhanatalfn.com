-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 20 يناير 2026 الساعة 03:34
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
-- Database: `fonon_abdaa`
--

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
(1, 'كيف تختار الوان مناسبة', 'kyf-tkhtar-aloan-mnasb', 'كيف تختار الوان مناسبة', 'blog/kyf-tkhtar-aloan-mnasb-1768683659.webp', 1, NULL, NULL, '2026-01-16 21:00:00', '2026-01-17 18:01:00', '2026-01-17 18:01:00');

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
(1, 'دهانات', 'gallery/dhanat-1768871633.webp', 2, 1, 0, '2026-01-19 22:13:53', '2026-01-19 22:13:53');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_05_221129_create_services_table', 1),
(5, '2026_01_05_221131_create_projects_table', 1),
(6, '2026_01_05_221132_create_gallery_images_table', 1),
(7, '2026_01_05_221133_create_blog_posts_table', 1),
(8, '2026_01_05_221134_create_skills_table', 1),
(9, '2026_01_05_221134_create_testimonials_table', 1),
(10, '2026_01_05_221135_create_settings_table', 1),
(11, '2026_01_05_224039_add_seo_fields_to_tables', 1),
(12, '2026_01_06_000000_create_messages_table', 1);

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
(2, 'hggg', 'hggg', ';;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm;;;;;;;;;;;;;;;;;;;;;;;mmmmmmmmmmmmmmmmmmmmmmmmm', 'eee', 'll', '5', 'projects/hggg-1768425670.webp', 1, NULL, NULL, 1, '2026-01-14 18:21:10', '2026-01-17 18:23:25');

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
(1, 'دهانات داخلية', 'dhanat-dakhly', 'دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخليةدهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية دهانات داخلية', NULL, 'services/dhanat-dakhly-1768526823.webp', 1, NULL, NULL, 0, '2026-01-15 22:27:03', '2026-01-19 21:35:18');

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
('EAeurFuOIb7tKzer3Qiy5t1oXJLs0Eo86gPJZwi6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmV4UFZIbUYxVUxXc1B3OWRjbHdId0VQVE11a25mU0VFcXQycVZvViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768866611),
('L7C5n3crAvW8h8v10bl8HQl3XZX6esKn24ct6qUw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZEM2djhqMkw0QWZadndxWWhqWFQ5ckdPVXl2NE1mazFZNmRLZGVZNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1768874464),
('rwZZEcxouAr5UbpJDRUuqUAw8yBU1XQOhBd6sZ1X', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXVIMjRPRFJ0VGxENFNiVWNKbVZ6RFY3YVYyaVNwZEFIczQxZlQwYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ibG9nL2t5Zi10a2h0YXItYWxvYW4tbW5hc2IiO3M6NToicm91dGUiO3M6OToiYmxvZy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1768876486);

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
(1, 'site_name', 'الفـن الحديث', '2026-01-13 21:15:27', '2026-01-18 21:54:35'),
(2, 'site_description', 'الفـن الحديث للدهانات والديكور', '2026-01-13 21:15:27', '2026-01-18 21:54:35'),
(3, 'phone', '0532791522', '2026-01-13 21:15:27', '2026-01-18 22:06:11'),
(4, 'email', 'alomisy03@gmail.com', '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(5, 'address', 'المملكة العربية السعودية \"جدة\"', '2026-01-13 21:15:27', '2026-01-18 22:06:11'),
(6, 'facebook', 'https://www.facebook.com/a8xxf', '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(7, 'twitter', 'https://twitter.com/a8xxe', '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(8, 'instagram', 'https://www.instagram.com/a8xxf', '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(9, 'linkedin', 'https://www.instagram.com/a8xxf', '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(10, 'whatsapp', 'https://wa.me/+967775226109', '2026-01-13 21:15:27', '2026-01-16 00:34:02'),
(11, 'services_meta_title', NULL, '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(12, 'services_meta_description', NULL, '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(13, 'projects_meta_title', NULL, '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(14, 'projects_meta_description', NULL, '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(15, 'blog_meta_title', NULL, '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(16, 'blog_meta_description', NULL, '2026-01-13 21:15:27', '2026-01-13 21:15:27'),
(17, 'site_logo', 'settings/U9CqkAeyrlt58s6jt80cSWx88iuLyO1ffCYgkuzL.png', '2026-01-13 21:15:27', '2026-01-15 23:10:57'),
(18, 'site_favicon', 'settings/xZYChESesXLzM4Rmg9tPI2zu9nig6TK0Mc4g6OjP.png', '2026-01-13 21:15:27', '2026-01-16 23:58:37');

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
(1, 'شركة الاحسان', 'شركة برمجية', 'الصراحة العمل ماشاء الله تحفه \r\nمراااا وااااو', 'testimonials/shrk-alahsan-1768426530.webp', 5, 1, 0, '2026-01-14 18:35:31', '2026-01-14 18:35:31'),
(2, 'amjed', 'developer', 'جودة شغلهم 100% كما أنصح بأ أحمد لأنة أفضل معلم دهانات وديكور تعاملت معه،', 'testimonials/amjed-1768872689.webp', 5, 1, 0, '2026-01-19 22:30:29', '2026-01-19 22:31:29');

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
(1, 'Admin', 'admin@alwisam.com', '2026-01-13 21:14:02', '$2y$12$Pd0gywNVIvdTdoSRHX9JAetlHwaEFiJV8cYMAIBWYb5E5kDOhSNyy', NULL, '2026-01-13 21:14:02', '2026-01-13 21:14:02'),
(2, 'Ahmed', 'admin@alfan.com', '2026-01-19 23:32:20', '$2y$12$WJkxm2ELreaVWioJ1AVtq.ikstrJFuaFfWMkjBk3n2z5SapsUJJua', NULL, '2026-01-19 23:32:20', '2026-01-19 23:32:20');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
