-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2019 at 08:35 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookingservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profilephoto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `profilephoto`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'franklin', 'akpufranklin2@gmail.com', '$2y$10$9P7tXcCWZPwXy4rPHfIICOUVMW.8KVRY3hwDbMgTv8A...', NULL, 'sdeecde34224535554fvf', NULL, NULL),
(2, 'francesco', 'francesco@gmail.com', '$2y$10$y8CQKMMU5gxlMGlhsNmOA.sVatjsjQVaTRKrty.C5yaSB5Zkw6X6O', '5c8257ffa7c58_155204607920190308_IMG.jpg', NULL, NULL, '2019-03-08 10:54:39'),
(3, 'nnamdi', 'ddd@gmail.com', '$2y$10$ee8mmM5cZKMH1p.Hy1WVM.YFFAq2BAh/00AOXpeM2WjHmSkufx9Na', NULL, NULL, '2019-03-03 21:44:11', '2019-03-03 21:44:11'),
(5, 'nnamdi', 'ccc@gmail.com', '$2y$10$9AoSdx8Nr59TaYFn5F8AIO0gOguAjEgZeHm123NUMCBv7A73GuJWO', NULL, NULL, '2019-03-03 21:45:00', '2019-03-03 21:45:00'),
(7, 'nnamdi', 'fffg@gmail.com', '$2y$10$W6ghuAB3NJKFrbaWPLatKeNBOmukeWxztwt0Dslpk.q7FgL8z2g/O', NULL, NULL, '2019-03-03 21:46:07', '2019-03-03 21:46:07'),
(8, 'bala', 'akpufranklin444@gmail.com', '$2y$10$GCg6IhuS55eONy26CzHcxeLCP3sqg3SrYOsRge.iOboS58aRuslxS', NULL, NULL, '2019-03-08 12:43:40', '2019-03-08 12:43:40'),
(11, 'bala', 'akpufranklin445544@gmail.com', '$2y$10$kdF70SQq/5hiKHbsGU8e9ejCQhPCaSRrGT7kIV02oRsrijNN8.9Le', NULL, NULL, '2019-03-08 12:46:26', '2019-03-08 12:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `artisan_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` date NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_cost` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `scheduledate` date NOT NULL,
  `completedate` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `location`, `artisan_id`, `time`, `address`, `total_cost`, `status`, `scheduledate`, `completedate`, `created_at`, `updated_at`) VALUES
(1, 12, 21, 'ansu uli abiiiii oooooh', '21', '2019-03-03', 'what joker', '221', 0, '2019-03-03', '2019-03-03', '2019-03-08 13:57:26', '2019-03-09 13:47:04'),
(2, 12, 21, 'ansu uli', '21', '2019-03-03', 'uli', '221', 0, '2019-03-03', '2019-03-03', '2019-03-08 13:59:18', '2019-03-08 13:59:18'),
(3, 12, 21, 'ansu uli', '21', '2019-03-03', 'uli', '221', 0, '2019-03-03', '2019-03-03', '2019-03-09 13:45:52', '2019-03-09 13:45:52');

-- --------------------------------------------------------

--
-- Table structure for table `booking_service_options`
--

CREATE TABLE `booking_service_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` int(11) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selected` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `image`, `created_at`, `updated_at`) VALUES
(1, 'waste', '5c83ce3e915a8_155214188620190309_ICO.jpg', '2019-03-09 13:31:26', '2019-03-09 13:31:26'),
(2, 'waste', '5c83d23286039_155214289820190309_ICO.jpg', '2019-03-09 13:48:18', '2019-03-09 13:48:18');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isdeleted` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `city`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, '1', 'we are heading home', '1', '2019-03-15 08:51:38', '2019-03-15 08:54:49'),
(2, '1', 'kola bus stop', '0', '2019-03-15 08:51:56', '2019-03-15 08:51:56'),
(3, '1', 'we are heading for africa', '0', '2019-03-15 08:55:49', '2019-03-15 08:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, 'uganda', 1, '2019-03-14 17:12:27', '2019-03-14 17:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_03_03_011419_create_admins_table', 1),
(4, '2019_03_03_014754_create_roles_table', 2),
(5, '2019_03_05_032859_create_categories_table', 3),
(6, '2019_03_05_044114_create_services_table', 4),
(7, '2019_03_07_032416_create_service_form_options_table', 5),
(8, '2019_03_07_045104_create_bookings_table', 6),
(9, '2019_03_07_055927_create_booking_service_options_table', 7),
(10, '2019_03_09_024655_create_sub_categories_table', 8),
(11, '2019_03_09_034740_create_skills_table', 9),
(12, '2019_03_09_113028_create_user_skills_table', 10),
(13, '2019_03_14_163727_create_countries_table', 11),
(14, '2019_03_14_163849_create_states_table', 11),
(15, '2019_03_14_163940_create_cities_table', 11),
(16, '2019_03_14_223026_create_reviews_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `artisan_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `review` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `customer_id`, `artisan_id`, `service_id`, `booking_id`, `review`, `rating`, `review_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 3, 'coolest review ever', '5 star', '2/02/2019', '2019-03-15 09:56:10', '2019-03-15 09:56:10'),
(2, 1, 2, 2, 3, 'craziest programmer ever', 'step up your game', '2/02/2019', '2019-03-15 09:57:10', '2019-03-15 09:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rolename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isdefault` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `rolename`, `isdefault`, `created_at`, `updated_at`) VALUES
(3, 'madness', '0', '2019-03-08 11:15:32', '2019-03-08 11:15:32');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service`, `category_id`, `isdeleted`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'hello world', 4, 1, 'best service ever', '5c83b3a4ac690_155213507620190309_SERVICES.jpg', '2019-03-05 05:07:56', '2019-03-09 11:43:33'),
(2, 'mathias', 4, 0, 'dddddddd', '5c7e126b915bc_155176612320190305_SERVICES.jpg', '2019-03-05 05:08:43', '2019-03-05 05:08:43'),
(3, 'mathias', 4, 0, 'dddddddd', '5c7e127d81e20_155176614120190305_SERVICES.jpg', '2019-03-05 05:09:01', '2019-03-05 05:09:01'),
(4, 'hello world', 4, 0, 'best service ever', '5c83b3e24fb4d_155213513820190309_SERVICES.jpg', '2019-03-09 11:38:58', '2019-03-09 11:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `service_form_options`
--

CREATE TABLE `service_form_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ispublic` tinyint(1) NOT NULL,
  `price` int(11) NOT NULL,
  `options` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selected` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `skill` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `category_id`, `sub_category_id`, `skill`, `created_at`, `updated_at`) VALUES
(2, 4, 10, 'cooking', '2019-03-09 14:56:49', '2019-03-09 14:56:49');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` int(11) NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `state`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, 2, 'anambra', 1, '2019-03-15 00:47:13', '2019-03-15 08:34:27');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `sub_category`, `image`, `created_at`, `updated_at`) VALUES
(2, '4', 'boss test and see', '5c83b6c04b46e_155213587220190309_ICO.png', '2019-03-09 11:51:12', '2019-03-09 11:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cityid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roleid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profilephoto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `phone`, `cityid`, `roleid`, `profilephoto`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(9, 'nnamdi', 'chukwu', 'chukwu@gmail.com', '$2y$10$BmOzMBZrkXnnRPhKYr7tt.Ocan33eT5qp1Z68huIlbrVUDiG49GqO', '332222223', 'dds', 'dffd', '5c7cb8297d7ba_155167748120190304_IMG.JPG', 0, NULL, '2019-03-04 03:45:10', '2019-03-08 11:36:48'),
(10, 'basdeedde', 'costanino', 'akpufranklin2@gmail.com', '$2y$10$EwtbpaORkfcNc72bFS2dE.kT/sp6KzW8sn/J//hp2tkNfy4/5BtOG', '3344', 'dhfj34', 'dfgg', NULL, 0, NULL, '2019-03-08 12:50:46', '2019-03-08 13:17:49'),
(11, 'kosi', 'kakashi', 'akpufranklin444@gmail.com', '$2y$10$Bs5NEimIGn8olHZVnR5Y3O5CWh8nhg0okSJ4OspO.NwIjzE0sMMdG', '070103455554', 'sd3432', 'de3434', NULL, 0, NULL, '2019-03-08 12:50:58', '2019-03-08 12:50:58'),
(13, 'kosi', 'kakashi', 'akpufranklin8744@gmail.com', '$2y$10$ayWPswzHdLvRfkhn9.FaPOMSy1uBl6dTVmD7m3zSqt8zcwpee5aMC', '070103455554', 'sd3432', 'de3434', NULL, 0, NULL, '2019-03-08 12:54:07', '2019-03-08 12:54:07'),
(14, 'kosi', 'kakashi', 'akpufranklin87443333@gmail.com', '$2y$10$jLh9wNiIGC79/d.2SmKSgO5IXGZF5owCUdktMpV25tabFyEBjx14W', '070103455554', 'sd3432', 'de3434', NULL, 0, NULL, '2019-03-08 12:55:22', '2019-03-08 12:55:22'),
(15, 'bask', 'costa', 'mailer@gmail.com', '$2y$10$aDNdlsTJsWMHxz3JiQazc.WRi.rYLc30dHsG8xdeeYFvyB2iNbJre', '3344', 'dhfj34', 'dfgg', '5c827dff0400b_155205580720190308_IMG.jpg', 0, NULL, '2019-03-08 12:59:50', '2019-03-08 13:36:47'),
(16, 'bask', 'costa', 'mailsser@gmail.com', '$2y$10$5DMJBUzRZjlxPh7CCiILge/CF/QsErlNY1wWhi44qfbGqbcGPUt/e', '3344', 'dhfj34', 'dfgg', NULL, 0, NULL, '2019-03-08 13:07:19', '2019-03-08 13:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `years_of_experience` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isdeleted` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `skill_id`, `rating`, `years_of_experience`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, 23, 2, '5', '10023', 1, '2019-03-09 15:27:29', '2019-03-09 15:39:57'),
(2, 23, 2, '5', '100', 0, '2019-03-09 15:28:26', '2019-03-09 15:28:26'),
(3, 23, 2, '5', '10023', 0, '2019-03-09 15:29:07', '2019-03-09 15:29:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_service_options`
--
ALTER TABLE `booking_service_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_form_options`
--
ALTER TABLE `service_form_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_service_options`
--
ALTER TABLE `booking_service_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_form_options`
--
ALTER TABLE `service_form_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
