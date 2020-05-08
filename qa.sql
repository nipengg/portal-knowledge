-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2020 at 09:30 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qa`
--

-- --------------------------------------------------------

--
-- Table structure for table `article_has_files`
--

CREATE TABLE `article_has_files` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `filename_article` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_has_files`
--

INSERT INTO `article_has_files` (`article_id`, `filename_article`, `created_at`, `updated_at`) VALUES
(1, 'unnamed.jpg', NULL, NULL),
(2, 'git.JPG', NULL, NULL),
(3, 'git.JPG', NULL, NULL),
(4, 'ig.JPG', NULL, NULL),
(5, 'git.JPG', NULL, NULL),
(6, 'WhatsApp Image 2020-02-07 at 3.18.23 PM.jpeg', NULL, NULL),
(7, 'unnamed.jpg', NULL, NULL),
(8, 'WhatsApp Image 2020-02-07 at 3.18.23 PM.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `article_has_sumbers`
--

CREATE TABLE `article_has_sumbers` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `sumber_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_has_sumbers`
--

INSERT INTO `article_has_sumbers` (`article_id`, `sumber_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 1, NULL, NULL),
(3, 3, NULL, NULL),
(4, 1, NULL, NULL),
(5, 3, NULL, NULL),
(6, 1, NULL, NULL),
(7, 2, NULL, NULL),
(8, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `article_has_tags`
--

CREATE TABLE `article_has_tags` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_has_tags`
--

INSERT INTO `article_has_tags` (`article_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL),
(2, 2, NULL, NULL),
(3, 2, NULL, NULL),
(4, 1, NULL, NULL),
(5, 2, NULL, NULL),
(6, 2, NULL, NULL),
(7, 2, NULL, NULL),
(8, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `article_inisiatives`
--

CREATE TABLE `article_inisiatives` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `security` enum('konfidensial','sharing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `article_inisiatives`
--

INSERT INTO `article_inisiatives` (`id`, `title`, `content`, `summary`, `security`, `user_id`, `theme_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lman', 'Lman Enak Karena Mengandung Banyak Gizi', 'Lmen Di Campur Banana', 'konfidensial', 1, 1, 1, '2020-05-04 22:51:58', '2020-05-04 22:51:58'),
(2, 'Maron', 'Maron 5', 'Maron', 'sharing', 1, 1, 1, '2020-05-07 00:58:39', '2020-05-07 00:58:39'),
(3, 'c-53', 'C-53', 'C-53', 'sharing', 3, 3, 1, '2020-05-07 00:59:37', '2020-05-07 00:59:37'),
(4, 'History', 'History', 'History', 'sharing', 1, 1, 1, '2020-05-07 18:52:58', '2020-05-07 18:52:58'),
(5, 'Covid-19', 'Covid', 'Covid-19', 'sharing', 1, 1, 1, '2020-05-07 18:53:37', '2020-05-07 18:53:37'),
(6, 'Lman', 'Lman', 'Lman produk baru', 'sharing', 1, 3, 1, '2020-05-07 18:54:06', '2020-05-07 18:54:06'),
(7, 'Choco', 'Choco', 'Choco', 'sharing', 1, 1, 1, '2020-05-07 18:54:31', '2020-05-07 18:54:31'),
(8, 'banana', 'banana', 'banana', 'konfidensial', 3, 2, 2, '2020-05-07 19:27:45', '2020-05-07 19:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'LEFO', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00'),
(2, 'Review', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00'),
(3, 'Q&A', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `votes` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `department_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'RKA', 'R&D packaging and service', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(2, 'RPD', 'R&D product powder diary', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(3, 'RPP', 'R&D product powder non-diary', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(4, 'RPE', 'R&D product Export', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(5, 'RPN', 'R&D product non powder', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(6, 'REA', 'R&Dproduct Enginering & process', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(7, 'RSL', 'R&D Laboratory', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `qa_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, '2017_05_02_021831_create_questions_table', 1),
(4, '2017_05_02_032034_create_posts_table', 1),
(5, '2017_05_02_032505_create_comments_table', 1),
(6, '2017_05_02_033223_create_tags_table', 1),
(7, '2017_05_02_035304_create_question_has_tags_table', 1),
(8, '2017_05_02_035345_create_user_voted_posts_table', 1),
(9, '2017_05_02_035414_create_user_voted_comments_table', 1),
(10, '2017_05_02_063232_apply_foreign_keys', 1),
(11, '2020_01_28_064959_create_categories_table', 1),
(12, '2020_01_29_083219_create_sumbers_table', 1),
(13, '2020_02_05_020239_create_departments_table', 1),
(14, '2020_02_12_080814_create_tr_article_inisiative_table', 1),
(15, '2020_02_17_010422_create_post_has_sumbers_table', 1),
(16, '2020_02_17_010617_create_article_has_sumbers_table', 1),
(17, '2020_02_17_010727_create_article_has_tags_table', 1),
(18, '2020_02_21_010110_create_themes_table', 1),
(19, '2020_02_25_081236_create_question_has_files_table', 1),
(20, '2020_02_26_040446_create_article_has_files_table', 1),
(21, '2020_02_27_031647_create_qas_table', 1),
(22, '2020_02_27_031851_create_messages_table', 1),
(23, '2020_02_27_041704_create_qa_has_tags_table', 1),
(24, '2020_02_27_042530_create_qa_has_files_table', 1),
(25, '2020_03_05_070310_create_post_issues_table', 1),
(26, '2020_04_02_061124_create_tagged_user_articles', 1),
(27, '2020_04_05_055203_create_tagged_user_questions_table', 1),
(28, '2020_04_29_071806_create_topics_table', 1),
(29, '2020_05_01_074421_create_tagged_department_article_table', 1),
(30, '2020_05_05_052806_create_tagged_department_questions_table', 1);

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `votes` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `post_content`, `votes`, `user_id`, `question_id`, `created_at`, `updated_at`) VALUES
(3, 'Enak Ga?', 0, 2, 3, '2020-05-04 23:33:54', '2020-05-04 23:33:54'),
(4, 'Emang Enak', 0, 1, 3, '2020-05-04 23:38:04', '2020-05-04 23:38:04'),
(5, 'Choco Resep C-53', 0, 2, 4, '2020-05-07 03:09:47', '2020-05-07 03:09:47'),
(6, 'Susu + Banana + Milo', 0, 1, 4, '2020-05-07 03:17:53', '2020-05-07 03:44:07'),
(7, 'Leman', 0, 2, 5, '2020-05-07 19:35:01', '2020-05-07 19:35:01'),
(8, 'Enak bnagte', 0, 1, 5, '2020-05-07 19:36:09', '2020-05-07 19:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `post_has_sumbers`
--

CREATE TABLE `post_has_sumbers` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `sumber_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_has_sumbers`
--

INSERT INTO `post_has_sumbers` (`post_id`, `sumber_id`, `created_at`, `updated_at`) VALUES
(4, 1, NULL, NULL),
(6, 1, NULL, NULL),
(6, 2, NULL, NULL),
(8, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_issues`
--

CREATE TABLE `post_issues` (
  `issue_id` int(11) NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `issue` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_issues`
--

INSERT INTO `post_issues` (`issue_id`, `question_id`, `issue`, `created_at`, `updated_at`) VALUES
(1, 3, '-', NULL, NULL),
(2, 4, '-', NULL, NULL),
(3, 4, 'Kurang Bahan Sepertinya', NULL, NULL),
(4, 5, '-', NULL, NULL),
(5, 5, 'Kurang Lengkap', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `qas`
--

CREATE TABLE `qas` (
  `id` int(10) UNSIGNED NOT NULL,
  `qa_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `security` enum('konfidensial','sharing') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_request_id` int(10) UNSIGNED NOT NULL,
  `accepted_qa_id` int(10) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qa_has_files`
--

CREATE TABLE `qa_has_files` (
  `qa_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qa_has_tags`
--

CREATE TABLE `qa_has_tags` (
  `qa_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary_question` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_request_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meeting` datetime DEFAULT NULL,
  `estimated_time` datetime NOT NULL,
  `estimated_time_updated` datetime NOT NULL,
  `theme_id` int(11) NOT NULL,
  `post_rating` double(8,2) NOT NULL DEFAULT '0.00',
  `security` enum('konfidensial','sharing') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sharing',
  `accepted_answer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_title`, `summary_question`, `user_id`, `user_request_id`, `category_name`, `meeting`, `estimated_time`, `estimated_time_updated`, `theme_id`, `post_rating`, `security`, `accepted_answer_id`, `created_at`, `updated_at`) VALUES
(3, 'Lmen + Banana', 'Lmen Di Campur Banana', 2, 1, 'Review', NULL, '2020-05-27 12:12:00', '2020-05-27 12:12:00', 3, 4.00, 'konfidensial', 1, '2020-05-04 23:33:54', '2020-05-04 23:38:22'),
(4, 'Choco Enak', 'Choco C-53', 2, 1, 'LEFO', '2020-05-30 12:00:00', '2020-03-07 10:10:00', '2020-03-07 10:10:00', 2, 3.00, 'sharing', 1, '2020-05-07 03:09:47', '2020-05-07 04:28:40'),
(5, 'Lmen + Banana', 'Lmen Di Campur Banana', 2, 1, 'Review', NULL, '2020-05-30 12:00:00', '2020-05-30 12:00:00', 3, 4.00, 'sharing', 1, '2020-05-07 19:35:01', '2020-05-07 19:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `question_has_files`
--

CREATE TABLE `question_has_files` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_has_files`
--

INSERT INTO `question_has_files` (`post_id`, `filename`, `created_at`, `updated_at`) VALUES
(4, 'unnamed.jpg', NULL, NULL),
(8, 'WhatsApp Image 2020-02-07 at 3.18.23 PM.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_has_tags`
--

CREATE TABLE `question_has_tags` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_has_tags`
--

INSERT INTO `question_has_tags` (`question_id`, `tag_id`, `category_name`, `created_at`, `updated_at`) VALUES
(3, 1, 'Review', NULL, NULL),
(3, 2, 'Review', NULL, NULL),
(4, 2, 'LEFO', NULL, NULL),
(5, 1, 'Review', NULL, NULL),
(5, 2, 'Review', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sumbers`
--

CREATE TABLE `sumbers` (
  `id` int(10) UNSIGNED NOT NULL,
  `sumber_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sumbers`
--

INSERT INTO `sumbers` (`id`, `sumber_name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bedah Buku', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(2, 'Free Topic', 1, '2020-05-04 22:46:59', '2020-05-04 22:46:59'),
(3, 'Specific project', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `tagged_department_article`
--

CREATE TABLE `tagged_department_article` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagged_department_article`
--

INSERT INTO `tagged_department_article` (`article_id`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL),
(8, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tagged_department_questions`
--

CREATE TABLE `tagged_department_questions` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagged_department_questions`
--

INSERT INTO `tagged_department_questions` (`question_id`, `department_id`, `created_at`, `updated_at`) VALUES
(3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tagged_user_articles`
--

CREATE TABLE `tagged_user_articles` (
  `article_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagged_user_articles`
--

INSERT INTO `tagged_user_articles` (`article_id`, `user_id`, `created_at`, `updated_at`) VALUES
(8, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tagged_user_questions`
--

CREATE TABLE `tagged_user_questions` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tagged_user_questions`
--

INSERT INTO `tagged_user_questions` (`question_id`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Lmen', 1, NULL, NULL),
(2, 'Choco', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` int(10) UNSIGNED NOT NULL,
  `theme` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `theme`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Machine', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00'),
(2, 'Makanan', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00'),
(3, 'Minuman', 1, '2020-05-04 22:47:00', '2020-05-04 22:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(10) UNSIGNED NOT NULL,
  `topic` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `topic`, `created_at`, `updated_at`) VALUES
(1, 'Covid', '2020-05-07 06:06:00', '2020-05-07 06:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` int(10) UNSIGNED NOT NULL,
  `is_approved` enum('sending','active','reject','inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `department_id`, `is_approved`, `is_admin`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'neville', 'nevillecorneliustj@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 1, 'active', 2, 1, NULL, '2020-05-04 22:47:44', '2020-05-04 22:47:44'),
(2, 'geges', 'gege@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 2, 'active', 0, 1, NULL, '2020-05-04 22:54:35', '2020-05-04 22:54:35'),
(3, 'joshua', 'joshu@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 5, 'active', 1, 1, NULL, '2020-05-07 00:32:03', '2020-05-07 00:32:03'),
(4, 'amanek', 'amanek@gmail.com', '7c222fb2927d828af22f592134e8932480637c0d', 4, 'active', 3, 1, NULL, '2020-05-07 00:46:08', '2020-05-07 00:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_voted_comments`
--

CREATE TABLE `user_voted_comments` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_voted_posts`
--

CREATE TABLE `user_voted_posts` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `article_has_files`
--
ALTER TABLE `article_has_files`
  ADD UNIQUE KEY `article_has_files_article_id_filename_article_unique` (`article_id`,`filename_article`);

--
-- Indexes for table `article_has_sumbers`
--
ALTER TABLE `article_has_sumbers`
  ADD UNIQUE KEY `article_has_sumbers_article_id_sumber_id_unique` (`article_id`,`sumber_id`);

--
-- Indexes for table `article_has_tags`
--
ALTER TABLE `article_has_tags`
  ADD UNIQUE KEY `article_has_tags_article_id_tag_id_unique` (`article_id`,`tag_id`);

--
-- Indexes for table `article_inisiatives`
--
ALTER TABLE `article_inisiatives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_post_id_foreign` (`post_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_question_id_foreign` (`question_id`);

--
-- Indexes for table `post_has_sumbers`
--
ALTER TABLE `post_has_sumbers`
  ADD UNIQUE KEY `post_has_sumbers_post_id_sumber_id_unique` (`post_id`,`sumber_id`);

--
-- Indexes for table `post_issues`
--
ALTER TABLE `post_issues`
  ADD PRIMARY KEY (`issue_id`),
  ADD UNIQUE KEY `post_issues_question_id_issue_unique` (`question_id`,`issue`);

--
-- Indexes for table `qas`
--
ALTER TABLE `qas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qa_has_files`
--
ALTER TABLE `qa_has_files`
  ADD UNIQUE KEY `qa_has_files_qa_id_filename_unique` (`qa_id`,`filename`);

--
-- Indexes for table `qa_has_tags`
--
ALTER TABLE `qa_has_tags`
  ADD UNIQUE KEY `qa_has_tags_qa_id_tag_id_unique` (`qa_id`,`tag_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_user_id_foreign` (`user_id`);

--
-- Indexes for table `question_has_files`
--
ALTER TABLE `question_has_files`
  ADD UNIQUE KEY `question_has_files_post_id_filename_unique` (`post_id`,`filename`);

--
-- Indexes for table `question_has_tags`
--
ALTER TABLE `question_has_tags`
  ADD UNIQUE KEY `question_has_tags_question_id_tag_id_unique` (`question_id`,`tag_id`),
  ADD KEY `question_has_tags_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `sumbers`
--
ALTER TABLE `sumbers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tagged_department_article`
--
ALTER TABLE `tagged_department_article`
  ADD UNIQUE KEY `tagged_department_article_article_id_department_id_unique` (`article_id`,`department_id`);

--
-- Indexes for table `tagged_department_questions`
--
ALTER TABLE `tagged_department_questions`
  ADD UNIQUE KEY `tagged_department_questions_question_id_department_id_unique` (`question_id`,`department_id`);

--
-- Indexes for table `tagged_user_articles`
--
ALTER TABLE `tagged_user_articles`
  ADD UNIQUE KEY `tagged_user_articles_article_id_user_id_unique` (`article_id`,`user_id`);

--
-- Indexes for table `tagged_user_questions`
--
ALTER TABLE `tagged_user_questions`
  ADD UNIQUE KEY `tagged_user_questions_question_id_user_id_unique` (`question_id`,`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tags_tag_unique` (`tag`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_voted_comments`
--
ALTER TABLE `user_voted_comments`
  ADD UNIQUE KEY `user_voted_comments_user_id_comment_id_unique` (`user_id`,`comment_id`),
  ADD KEY `user_voted_comments_comment_id_foreign` (`comment_id`);

--
-- Indexes for table `user_voted_posts`
--
ALTER TABLE `user_voted_posts`
  ADD UNIQUE KEY `user_voted_posts_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `user_voted_posts_post_id_foreign` (`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article_inisiatives`
--
ALTER TABLE `article_inisiatives`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post_issues`
--
ALTER TABLE `post_issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `qas`
--
ALTER TABLE `qas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sumbers`
--
ALTER TABLE `sumbers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question_has_tags`
--
ALTER TABLE `question_has_tags`
  ADD CONSTRAINT `question_has_tags_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `question_has_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_voted_comments`
--
ALTER TABLE `user_voted_comments`
  ADD CONSTRAINT `user_voted_comments_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_voted_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_voted_posts`
--
ALTER TABLE `user_voted_posts`
  ADD CONSTRAINT `user_voted_posts_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_voted_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
