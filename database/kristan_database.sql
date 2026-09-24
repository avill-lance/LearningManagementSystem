-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2026 at 11:37 PM
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
-- Database: `enrollment_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(10) UNSIGNED NOT NULL,
  `posted_by` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `posted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `instructions` text DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `max_score` decimal(6,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `attendance_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Late','Absent','Excused') NOT NULL,
  `logged_by` int(10) UNSIGNED NOT NULL,
  `logged_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `section_id` int(10) UNSIGNED NOT NULL,
  `strand_id` int(10) UNSIGNED NOT NULL,
  `adviser_id` int(10) UNSIGNED DEFAULT NULL,
  `grade_level` enum('11','12') NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `max_slots` int(11) NOT NULL,
  `status` enum('Open','Closed','Cancelled') NOT NULL DEFAULT 'Open',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `enrollment_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `school_year` varchar(9) NOT NULL,
  `school_year_id` int(10) UNSIGNED NOT NULL,
  `semester` enum('1st Semester','2nd Semester') NOT NULL,
  `date_enrolled` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Enrolled','Dropped','Pending') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `final_grades`
--

CREATE TABLE `final_grades` (
  `final_grade_id` int(10) UNSIGNED NOT NULL,
  `enrollment_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `final_rating` decimal(5,2) NOT NULL,
  `remarks` enum('Passed','Failed','Incomplete') DEFAULT NULL,
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `computed_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_components`
--

CREATE TABLE `grade_components` (
  `component_id` int(10) UNSIGNED NOT NULL,
  `enrollment_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `component_type` enum('written_work','performance_task','exam') NOT NULL,
  `source_type` enum('submission','quiz_attempt','manual') DEFAULT NULL,
  `source_id` int(10) UNSIGNED DEFAULT NULL,
  `raw_score` decimal(6,2) NOT NULL,
  `max_score` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grading_templates`
--

CREATE TABLE `grading_templates` (
  `template_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `written_work_weight` decimal(4,2) NOT NULL,
  `performance_task_weight` decimal(4,2) NOT NULL,
  `exam_weight` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guardians`
--

CREATE TABLE `guardians` (
  `guardian_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guidance_records`
--

CREATE TABLE `guidance_records` (
  `record_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `logged_by` int(10) UNSIGNED NOT NULL,
  `category` varchar(50) NOT NULL,
  `notes` text NOT NULL,
  `is_restricted` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `material_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_url` varchar(500) NOT NULL,
  `status` enum('Draft','Published','Archived') NOT NULL DEFAULT 'Draft',
  `uploaded_by` int(10) UNSIGNED NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `read_at` datetime DEFAULT NULL
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
(1, '2026_09_20_000001_create_users_table', 1),
(2, '2026_09_20_000002_create_tracks_table', 1),
(3, '2026_09_20_000003_create_strands_table', 1),
(4, '2026_09_20_000004_create_guardians_table', 1),
(5, '2026_09_20_000005_create_teachers_table', 1),
(6, '2026_09_20_000006_create_students_table', 1),
(7, '2026_09_20_000007_create_school_years_table', 1),
(8, '2026_09_20_000008_create_semesters_table', 1),
(9, '2026_09_20_000009_create_rooms_table', 1),
(10, '2026_09_20_000010_create_class_sections_table', 1),
(11, '2026_09_20_000011_create_subjects_table', 1),
(12, '2026_09_20_000012_create_schedules_table', 1),
(13, '2026_09_20_000013_create_enrollments_table', 1),
(14, '2026_09_20_000014_create_assignments_table', 1),
(15, '2026_09_20_000015_create_quizzes_table', 1),
(16, '2026_09_20_000016_create_quiz_questions_table', 1),
(17, '2026_09_20_000017_create_quiz_attempts_table', 1),
(18, '2026_09_20_000018_create_submissions_table', 1),
(19, '2026_09_20_000019_create_attendance_records_table', 1),
(20, '2026_09_20_000020_create_grading_templates_table', 1),
(21, '2026_09_20_000021_create_final_grades_table', 1),
(22, '2026_09_20_000022_create_grade_components_table', 1),
(23, '2026_09_20_000023_create_subject_grading_templates_table', 1),
(24, '2026_09_20_000024_create_learning_materials_table', 1),
(25, '2026_09_20_000025_create_announcements_table', 1),
(26, '2026_09_20_000026_create_audit_logs_table', 1),
(27, '2026_09_20_000027_create_guidance_records_table', 1),
(28, '2026_09_20_000028_create_messages_table', 1),
(29, '2026_09_20_000029_create_notifications_table', 1),
(30, '2026_09_20_000030_create_schedule_events_table', 1),
(31, '2026_09_20_000031_create_student_guardians_table', 1),
(32, '2026_09_22_000032_change_teachers_specialization_to_enum', 1),
(33, '2026_09_22_064143_change_specialization_to_string_on_teachers_table', 1),
(34, '2026_09_22_064824_add_updated_at_to_teachers_table', 1),
(35, '2026_09_23_145926_create_sessions_table', 1),
(36, '2026_09_23_031213_create_sessions_table', 2),
(37, '2026_09_23_100000_add_teacher_id_to_subjects_table', 3),
(38, '2026_09_23_182114_add_must_change_password_to_users_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` varchar(500) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `time_limit_minutes` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `attempt_id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `started_at` datetime NOT NULL DEFAULT current_timestamp(),
  `submitted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `question_id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','short_answer') NOT NULL,
  `options` longtext DEFAULT NULL,
  `correct_answer` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(10) UNSIGNED NOT NULL,
  `room_name` varchar(50) NOT NULL,
  `building` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(10) UNSIGNED DEFAULT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_events`
--

CREATE TABLE `schedule_events` (
  `event_id` int(10) UNSIGNED NOT NULL,
  `created_by_role` enum('Student','Teacher') NOT NULL,
  `created_by_id` int(10) UNSIGNED NOT NULL,
  `section_id` int(10) UNSIGNED DEFAULT NULL,
  `subject_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('Personal','Quiz','Review','Announcement') NOT NULL DEFAULT 'Personal',
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `status` enum('Scheduled','Cancelled','Done') NOT NULL DEFAULT 'Scheduled',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `school_year_id` int(10) UNSIGNED NOT NULL,
  `year` varchar(9) NOT NULL,
  `status` enum('active','closed') NOT NULL DEFAULT 'closed',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(10) UNSIGNED NOT NULL,
  `school_year_id` int(10) UNSIGNED NOT NULL,
  `semester_label` enum('1st Semester','2nd Semester') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_grading_locked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('3wDZRG4gC7kt9zYXYF2jo3asBF8cGu3HPBR4Tpnq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVXI0SHNLWUswakcyN2ExcGtOTWY3NVJwS25KS2dUV1lrWWtzZ1pmVCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQvYXNzaWdubWVudHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196155),
('47JDdbEaTgfOyXHI1eedSVGKEmtycj2s0HmGw41x', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGpxWlY2VnBYOHF0MFVRZTFHQVRIR0tMVGtRbVVtZ3k5UjZ0SEIyZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2NDoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL3N0dWRlbnQvcXVpenplcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU0OiJodHRwOi8vbG9jYWxob3N0L0xlYXJuaW5nTWFuYWdlbWVudFN5c3RlbS9wdWJsaWMvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790195592),
('60Th4ML4AlY0euqdmTUO5Lbx1HKckFphc1KOxVFe', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoialFJNEJURnBZRUNlMVE5VnBUS2x2ekY0NHAxMjNGaUVLdXAyR2JZSiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NjoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL3N0dWRlbnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NDoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790195584),
('71NPzHGVub7u3OirGvzxz65ZldIMNqM691DomVjQ', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUZ0dWNaNFJPZEJmZGllQVJOODBNV1kzNm5QMlFRRGludTJJSVFGeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sb2NhbGhvc3QvTGVhcm5pbmdNYW5hZ2VtZW50U3lzdGVtL3B1YmxpYyI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790195584),
('7lvBAhjKSzWka0fNCVyLO0YVrql65PrUU6hcDTS8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmVoMUxsNDZiWk5xTE9GQVdzNWFoVDBGd0lSMm5mUk5pVlk3bFU0VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790196530),
('9aE1aHJGmbegJyaGSOLgU1AQecXF9QIAL5wK2nif', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYVJSTjRpRmQ5MmVLWFNrN3c2MFUzOEx5OUNQanR4UGo1ZnJ3NkVJbyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2NhbGVuZGFyIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790196156),
('AI79X8Blp4WFXVuzGmM3qepMbX2YpQyriBHOb57Y', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVk5NcUp5T3FHaWVkQ1o2TzNFYVVlcmJHQ3VNYk5mSGVsOFZoa0RRVSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Fubm91bmNlbWVudHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196374),
('c90oK5zi1W5xxL6uNY85iHFcNe0FNz0BZipHNbCx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjFzc1F5S3YwVTA2azdtWEtwdnl2dGlxb3dWMlNzdUVYVDZkSWZwVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196148),
('cBtVKbeLq1jcU3PH0UJdGMEyeLN2VOcGCKAV9Hbx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZmNJczlYRlBYdUppMEJnRFNMMTFCTlRQT1Yxd01zQWNFYkFaakJUMCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196373),
('foDhIoxhypCT0TUuufj2BxUwo3bqghHNIg5iv8JI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUo1YWJrS2ZpZ2p1Z3poWEFaR1d5MlBMeGl3UWdBRVlRUFVtVXpoVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790196374),
('HplserTLZfaAMITtUvxFhulPhTr1IAuWrN66sEd8', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOXBLYk9QOVRaU0NQS01SSGJLd2VDaml6S1YzdEZoV3F0S1ozMVBpTyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NjoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL3N0dWRlbnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NDoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790195591),
('J5Re3jJTyDPChv3Yb4T303I5hFSphYoG7ZeRbdKB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnV6b28wM1I2dEJCclE1djNncm9SNGFZUzhReVNka0t5dU02RDFyOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790196490),
('Ke2KXzLYqRxCSDYvWjOKzdGlq7XBdtnmKLta26Bq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjBnQ1p3MEdLbHJOVHZ5RWtQUUNOdlVXZk11a0ZPQndOdjB3bXN5TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196374),
('n8XwRtlSJ54utW1OX3fp84bjQFNMp0iBdXdwAmLS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT2xTazlSTmhtNEF0VkhMQzJRalBoZ1VqOG5ZMUZtdXA1bGJVaUM4RyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196373),
('nZyLhIoOEGiLxKdRX51dRE6f5EW32dTS4IzEho6A', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRlFQY21IMEI0R29pMmg3bzhLWHMzZ0h5MjN0dnZKT1I3OE9URFg2ZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2NhbGVuZGFyIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790196373),
('OyeTqzFXGKjuuI77jPznysObM51kDoRIgodi4NTX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWRXalR2eGY0VXc4WjRndHFESXJUODJVZ3JTakNBMnlpUGdDUG9vbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790196524),
('QEOkpSWxQDznlwnJE7zy07CY2Po6YvWSxkCZXkm3', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTFVZR0xzTW53QkxuT25ITWZzbjdFVTFLRWRBU01xb2NydlRMM1d0ZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjI6e2k6MDtzOjY6ImVycm9ycyI7aToxO3M6MTA6Il9vbGRfaW5wdXQiO31zOjM6Im5ldyI7YTowOnt9fXM6NjoiZXJyb3JzIjtPOjMxOiJJbGx1bWluYXRlXFN1cHBvcnRcVmlld0Vycm9yQmFnIjoxOntzOjc6IgAqAGJhZ3MiO2E6MTp7czo3OiJkZWZhdWx0IjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcTWVzc2FnZUJhZyI6Mjp7czoxMToiACoAbWVzc2FnZXMiO2E6MTp7czoxMDoiaWRlbnRpZmllciI7YToxOntpOjA7czozOToiVGhlIHByb3ZpZGVkIGNyZWRlbnRpYWxzIGFyZSBpbmNvcnJlY3QuIjt9fXM6OToiACoAZm9ybWF0IjtzOjg6IjptZXNzYWdlIjt9fX1zOjEwOiJfb2xkX2lucHV0IjthOjE6e3M6MTA6ImlkZW50aWZpZXIiO3M6MjU6Imp1YW4uZGVsYWNydXpAZXhhbXBsZS5jb20iO319', 1790196519),
('qhDpwWu85jWeft2PVgKjBNIO58QmRsCb2y1P9585', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibGpCQUthVnJ3cG01Z1o1dkdYSUFlTmhxemcwMDUzRmloSHBHZ1FlVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sb2NhbGhvc3QvTGVhcm5pbmdNYW5hZ2VtZW50U3lzdGVtL3B1YmxpYyI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790195577),
('S18uNJ2947iMSYBRNcQTqmsquEPfcHjKZarDtCc0', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia211ZVRDaFNJZjdzRGtOWlZtRlJhaWh1SVM4em5IeTV5SmNsYWE3VCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1NzoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL2NhbGVuZGFyIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly9sb2NhbGhvc3QvTGVhcm5pbmdNYW5hZ2VtZW50U3lzdGVtL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790195592),
('TDGrfSzeFSP152NUxjwEDRZvQ5ZY9Q8U6vmBkyhF', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicWJvd1RLTG9hTjZCMnU4YXFpckZERmVVZ1FZeWUxYk1UR0VjYkhUMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hbm5vdW5jZW1lbnRzIjtzOjU6InJvdXRlIjtzOjE5OiJhbm5vdW5jZW1lbnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', 1790196998),
('tHafE1bjlcWpuuvM4eQ8kisHqUh4k6xZT2Tw3A4Q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekRpbUNrVzBBRzVmTnZ3cnFMYzdodEZNQ0dBWG9tdXpXVW5SOFBBUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo3OiJsYW5kaW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196154),
('WdZ4FsvtH7gr95sAalwAuntI8DgcTw4YKy684HCG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieExiTHlxWTZSeW90RTRwY0theXVYTjVTNkZ3eVZoMTJmcmRkMjhPMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjI6e2k6MDtzOjY6ImVycm9ycyI7aToxO3M6MTA6Il9vbGRfaW5wdXQiO31zOjM6Im5ldyI7YTowOnt9fXM6NjoiZXJyb3JzIjtPOjMxOiJJbGx1bWluYXRlXFN1cHBvcnRcVmlld0Vycm9yQmFnIjoxOntzOjc6IgAqAGJhZ3MiO2E6MTp7czo3OiJkZWZhdWx0IjtPOjI5OiJJbGx1bWluYXRlXFN1cHBvcnRcTWVzc2FnZUJhZyI6Mjp7czoxMToiACoAbWVzc2FnZXMiO2E6MTp7czoxMDoiaWRlbnRpZmllciI7YToxOntpOjA7czozOToiVGhlIHByb3ZpZGVkIGNyZWRlbnRpYWxzIGFyZSBpbmNvcnJlY3QuIjt9fXM6OToiACoAZm9ybWF0IjtzOjg6IjptZXNzYWdlIjt9fX1zOjEwOiJfb2xkX2lucHV0IjthOjE6e3M6MTA6ImlkZW50aWZpZXIiO3M6MjU6Imp1YW4uZGVsYWNydXpAZXhhbXBsZS5jb20iO319', 1790196512),
('WKrr8nLf4BHioRnCaH0mz5i23ouCGP0YBh6PIwQK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaEpGU2E0R3VqbGJ4Z3htaGVFUUhmazZERGtmMEJpSHBRVVZ2VEJ0UCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196155),
('xA17xBZEMMIRWgUR6iVa2emlYZeVIDFTpGEa1SRI', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHJGQVFuWHhldm1DbG51ZkNFYXFobVpRUmRscHdjaTI3eEEwVXJ3MiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly9sb2NhbGhvc3QvTGVhcm5pbmdNYW5hZ2VtZW50U3lzdGVtL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790195612),
('xLPaSmSQkYRd0fn7Do023fAv9vYPwHthKmnSyxm1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDV5YUtpdUhjV0VMdk5YMXJlYWJic293TGRRQjhoaXQ5bW80TUdkcyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQvcXVpenplcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790196155),
('y47ATLN56t3DYUGn7YflOcM3XWKDf8J1iJmKrXgt', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSldncnBucjNDTWptWXVQM2FOU3BOb3NEQjltd1VhSVNEdmVnTWk0aSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hbm5vdW5jZW1lbnRzIjtzOjU6InJvdXRlIjtzOjE5OiJhbm5vdW5jZW1lbnRzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTM7fQ==', 1790196545),
('z960HgwjJYXEXBiHAFvF7rd2PT4LURy3kcM3mtZk', 30, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiam5WeEJtRkZ1ZnFqNGJqaFRnRWRlOU9nSTc5SU5WbDBDM0xtcTlHRiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc3R1ZGVudC9hc3NpZ25tZW50cyI7czo1OiJyb3V0ZSI7czoyNToic3R1ZGVudC5hc3NpZ25tZW50cy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMwO30=', 1790197092),
('zdUP3CaiXTh5EVFQN3qYjN32DiFv3ZeYqgd4K2X3', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR0Q3eTNYMGZxTzVlWFV4S0JkS3NKMkI0MjljcXRVajdFbWZ2bFlxOSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2MjoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL2Fubm91bmNlbWVudHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NDoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790195592),
('ZHh4fd679qczSrIWJUZ5krrk2nZPjgAw4xdJwZzf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibWFPdDBoSnNJWUd5ZVhEb1oyY3VqUzJqUDEyMWVHZm1ORzZlWnBITCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Fubm91bmNlbWVudHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790196156),
('zIfmfngJG2PKedX0iE1YksfvunX4dCiIy8gZtLcC', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9444', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSzZDaHAxblBkYUFPZmRCYzZxbjFtVFc5MWE5S1RtUjJaQkNHWHQxNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo2ODoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL3N0dWRlbnQvYXNzaWdubWVudHMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NDoiaHR0cDovL2xvY2FsaG9zdC9MZWFybmluZ01hbmFnZW1lbnRTeXN0ZW0vcHVibGljL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790195591);

-- --------------------------------------------------------

--
-- Table structure for table `strands`
--

CREATE TABLE `strands` (
  `strand_id` int(10) UNSIGNED NOT NULL,
  `track_id` int(10) UNSIGNED NOT NULL,
  `strand_code` varchar(20) NOT NULL,
  `strand_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `lrn` varchar(12) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `grade_level` enum('11','12') NOT NULL,
  `strand_id` int(10) UNSIGNED DEFAULT NULL,
  `guardian_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `lrn`, `student_number`, `grade_level`, `strand_id`, `guardian_id`, `created_at`, `updated_at`) VALUES
(1, 2, '000000000002', 'TEMP-000002', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(2, 8, '000000000008', 'TEMP-000008', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(3, 13, '000000000013', 'TEMP-000013', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(4, 14, '000000000014', 'TEMP-000014', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(5, 15, '000000000015', 'TEMP-000015', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(6, 16, '000000000016', 'TEMP-000016', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(7, 17, '000000000017', 'TEMP-000017', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(8, 18, '000000000018', 'TEMP-000018', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(9, 19, '000000000019', 'TEMP-000019', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(10, 20, '000000000020', 'TEMP-000020', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(11, 21, '000000000021', 'TEMP-000021', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(12, 22, '000000000022', 'TEMP-000022', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(13, 24, '000000000024', 'TEMP-000024', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27'),
(14, 30, '000000000030', 'TEMP-000030', '11', NULL, NULL, '2026-09-23 20:56:27', '2026-09-23 20:56:27');

-- --------------------------------------------------------

--
-- Table structure for table `student_guardians`
--

CREATE TABLE `student_guardians` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `guardian_id` int(10) UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(10) UNSIGNED NOT NULL,
  `strand_id` int(10) UNSIGNED DEFAULT NULL,
  `teacher_id` int(10) UNSIGNED DEFAULT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(150) NOT NULL,
  `subject_type` enum('Core','Applied','Specialized') NOT NULL DEFAULT 'Core',
  `grade_level` enum('11','12') NOT NULL,
  `semester` enum('1st Semester','2nd Semester') NOT NULL,
  `units` decimal(3,1) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_grading_templates`
--

CREATE TABLE `subject_grading_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(10) UNSIGNED NOT NULL,
  `template_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `submission_id` int(10) UNSIGNED NOT NULL,
  `assignment_id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `file_url` varchar(500) DEFAULT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `status` enum('Pending','Submitted','Late','Graded') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `teacher_number` varchar(20) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `track_id` int(10) UNSIGNED NOT NULL,
  `track_code` varchar(10) NOT NULL,
  `track_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL,
  `role` enum('Admin','Staff','Registrar','Accounting','Teacher','Student','Guardian') NOT NULL DEFAULT 'Student',
  `status` enum('Active','Inactive','Suspended','Locked') NOT NULL DEFAULT 'Active',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `middle_name`, `password`, `must_change_password`, `email`, `role`, `status`, `is_deleted`, `contact_number`, `address`, `birthdate`, `gender`, `created_at`, `updated_at`) VALUES
(1, 'jason', 'begornia', 'verzosa', '$2y$12$6gyO7WtSZUc5ZODOQwu6FevbhJs7Ly7eYlyMoVMg4KOTWjAzEe8jq', 0, 'jasonbegornia57@gmail.com', 'Admin', 'Active', 0, '09945646355', 'blk 34 lot 3 cluster 4 bella vista, general trias cavite', '2002-11-11', 'Male', '2026-09-21 00:37:16', '2026-09-23 17:46:05'),
(2, 'Tung ', 'Tung', 'Sahur', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'sahur26f@gmail.com', 'Student', 'Active', 0, '09945646355', 'blk 35 lot 3 cluster 4 bella vista, general trias cavite', '2003-11-11', 'Male', '2026-09-21 00:37:16', '2026-09-21 09:53:05'),
(3, 'Bombardino', 'Crocodilo', 'Tung', 'Admin123', 0, 'bombardino.crocodilo@example.com', 'Teacher', 'Inactive', 0, '09191234569', 'Brainrot Street, Pasig', '1998-07-10', 'Male', '2026-09-21 10:04:49', '2026-09-21 14:27:28'),
(4, 'Ballerina', 'Cappuccina', 'Tralala', 'password123', 0, 'ballerina.cappuccina@example.com', 'Teacher', 'Active', 0, '09201234570', 'Latte Road, Makati', '1999-11-05', 'Female', '2026-09-21 10:04:49', '2026-09-21 12:59:49'),
(5, 'Cappuccino', 'Assassino', 'Sigma', 'password123', 0, 'cappuccino.assassino@example.com', 'Teacher', 'Suspended', 0, '09211234571', 'Espresso Street, Taguig', '1997-05-18', 'Male', '2026-09-21 10:04:49', '2026-09-21 10:04:49'),
(6, 'Trippi', 'Troppi', 'Tung', 'password123', 0, 'trippi.troppi@example.com', 'Student', 'Active', 1, '09221234572', 'Brainrot Village, Imus', '2005-09-12', 'Other', '2026-09-21 10:04:49', '2026-09-21 10:28:16'),
(7, 'Frigo', 'Camelo', 'Bombardino', 'password123', 0, 'frigo.camelo@example.com', 'Teacher', 'Active', 0, '09231234573', 'Camel Road, Dasmarinas', '1985-02-28', 'Male', '2026-09-21 10:04:49', '2026-09-21 12:59:27'),
(8, 'Chimpanzini', 'Bananini', 'Tralala', 'password123', 0, 'chimpanzini.bananini@example.com', 'Student', 'Locked', 0, '09241234574', 'Banana Street, Cavite', '2006-06-30', 'Male', '2026-09-21 10:04:49', '2026-09-21 10:04:49'),
(9, 'Brr Brr', 'Patapim', 'Cappuccina', 'password123', 0, 'brr.patapim@example.com', 'Teacher', 'Active', 0, '09251234575', 'Patapim Road, Bacoor', '1995-12-20', 'Female', '2026-09-21 10:04:49', '2026-09-21 10:04:49'),
(10, 'Lirili', 'Larila', 'Bananini', 'password123', 0, 'lirili.larila@example.com', 'Teacher', 'Inactive', 0, '09261234576', 'Lalala Street, General Trias', '2002-08-08', 'Female', '2026-09-21 10:04:49', '2026-09-21 12:58:44'),
(11, 'blud', 'Sahur', 'Sigma', 'password123', 0, 'tung.sahur@example.com', 'Admin', 'Active', 0, '09171234567', 'Ohio Street, Manila', '2000-01-15', 'Male', '2026-09-21 10:04:49', '2026-09-21 10:04:49'),
(12, 'Tralalero', 'Tralala', 'Bombardino', 'password123', 0, 'tralalero.tralala@example.com', 'Student', 'Active', 1, '09181234568', 'Skibidi Avenue, Quezon City', '2001-03-22', 'Male', '2026-09-21 10:04:49', '2026-09-22 08:46:47'),
(13, 'Juan', 'Dela Cruz', 'Santos', '$2y$12$aCI4QbDn0YMyMGWCzwpxHuJovy6QB22l/dHzyuQMXO7Ilr5wvgWS.', 0, 'juan.delacruz@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2023-06-10 09:00:00', '2026-09-23 20:48:55'),
(14, 'Maria', 'Clara', 'Rizal', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'maria.clara@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2023-06-10 09:15:00', '2026-09-21 12:57:43'),
(15, 'Andres', 'Bonifacio', 'Castro', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'andres.b@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2024-06-11 10:00:00', '2026-09-21 12:57:43'),
(16, 'Jose', 'Rizal', 'Mercado', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'jose.rizal@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2024-06-11 10:30:00', '2026-09-21 12:57:43'),
(17, 'Emilio', 'Aguinaldo', 'Famy', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'emilio.a@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2025-06-12 11:00:00', '2026-09-21 12:57:43'),
(18, 'Apolinario', 'Mabini', 'Maranan', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'apolinario.m@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2025-06-12 11:20:00', '2026-09-21 12:57:43'),
(19, 'Melchora', 'Aquino', 'Ramos', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'melchora.a@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2026-06-15 08:30:00', '2026-09-21 12:57:43'),
(20, 'Gabriela', 'Silang', 'Cariño', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'gabriela.s@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2026-06-15 09:00:00', '2026-09-21 12:57:43'),
(21, 'Antonio', 'Luna', 'Novicio', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'antonio.luna@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2027-06-10 08:00:00', '2026-09-21 12:57:43'),
(22, 'Marcelo', 'Del Pilar', 'Hilario', '$2y$12$kx57NeLjHA0uhu2G.ttXuuORwPnTp8LyqNRCX4lWO9GRsn6l8Hrxu', 0, 'marcelo.delpilar@example.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2027-06-10 08:30:00', '2026-09-21 12:57:43'),
(24, 'Jack', 'Nelson', 'Wing Hubbard', '$2y$12$IDXpnr6zLQlvV9rphgtkvet6YggzpTG4oBmwII8vJpkyVunoEn6rm', 0, 'jivecituc@mailinator.com', 'Student', 'Active', 0, '248', 'Officia harum dolore', '1998-10-01', 'Male', '2026-09-22 06:52:58', '2026-09-22 08:46:37'),
(27, 'Herman', 'Ford', 'Alfonso Anderson', '$2y$12$RCIrKp2RmR1iMMH2AqDDkeJ2iqi7C/Bw9BBw/JTAfjq8pUw444kLK', 0, 'vubybiqud@mailinator.com', 'Admin', 'Suspended', 0, '542', 'Consequuntur aut cul', '2013-06-12', 'Other', '2026-09-22 07:14:40', '2026-09-22 07:14:40'),
(30, 'Student', 'Test', NULL, '$2y$12$OrQwKmZrP7.J3FBgseTM3.bLsM5a8.8NqJaj3qTLsJKcABgXt9sYe', 0, 'student@school.com', 'Student', 'Active', 0, NULL, NULL, NULL, NULL, '2026-09-23 18:50:18', '2026-09-23 18:51:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `announcements_posted_by_foreign` (`posted_by`),
  ADD KEY `announcements_section_id_foreign` (`section_id`);

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `assignments_schedule_id_foreign` (`schedule_id`);

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`attendance_id`),
  ADD UNIQUE KEY `uq_attendance_once` (`schedule_id`,`student_id`,`attendance_date`),
  ADD KEY `attendance_records_student_id_foreign` (`student_id`),
  ADD KEY `attendance_records_logged_by_foreign` (`logged_by`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_audit_entity` (`entity_type`,`entity_id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`section_id`),
  ADD KEY `class_sections_strand_id_foreign` (`strand_id`),
  ADD KEY `class_sections_adviser_id_foreign` (`adviser_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `enrollments_student_id_foreign` (`student_id`),
  ADD KEY `enrollments_section_id_foreign` (`section_id`),
  ADD KEY `enrollments_school_year_id_foreign` (`school_year_id`);

--
-- Indexes for table `final_grades`
--
ALTER TABLE `final_grades`
  ADD PRIMARY KEY (`final_grade_id`),
  ADD UNIQUE KEY `uq_final_grade` (`enrollment_id`,`subject_id`),
  ADD KEY `idx_final_grade_locked` (`is_locked`),
  ADD KEY `final_grades_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `grade_components`
--
ALTER TABLE `grade_components`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `grade_components_enrollment_id_foreign` (`enrollment_id`),
  ADD KEY `grade_components_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `grading_templates`
--
ALTER TABLE `grading_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `guardians`
--
ALTER TABLE `guardians`
  ADD PRIMARY KEY (`guardian_id`),
  ADD UNIQUE KEY `guardians_user_id_unique` (`user_id`);

--
-- Indexes for table `guidance_records`
--
ALTER TABLE `guidance_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `guidance_records_student_id_foreign` (`student_id`),
  ADD KEY `guidance_records_logged_by_foreign` (`logged_by`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `learning_materials_schedule_id_foreign` (`schedule_id`),
  ADD KEY `learning_materials_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_message_receiver_unread` (`receiver_id`,`read_at`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_notification_unread` (`user_id`,`is_read`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `quizzes_schedule_id_foreign` (`schedule_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`attempt_id`),
  ADD UNIQUE KEY `uq_attempt_once` (`quiz_id`,`student_id`),
  ADD KEY `quiz_attempts_student_id_foreign` (`student_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `quiz_questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `rooms_room_name_unique` (`room_name`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `schedules_section_id_foreign` (`section_id`),
  ADD KEY `schedules_subject_id_foreign` (`subject_id`),
  ADD KEY `schedules_teacher_id_foreign` (`teacher_id`),
  ADD KEY `schedules_room_id_foreign` (`room_id`);

--
-- Indexes for table `schedule_events`
--
ALTER TABLE `schedule_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`school_year_id`),
  ADD UNIQUE KEY `school_years_year_unique` (`year`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`),
  ADD UNIQUE KEY `uq_semester_per_year` (`school_year_id`,`semester_label`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `strands`
--
ALTER TABLE `strands`
  ADD PRIMARY KEY (`strand_id`),
  ADD UNIQUE KEY `strands_strand_code_unique` (`strand_code`),
  ADD KEY `strands_track_id_foreign` (`track_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `students_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `students_lrn_unique` (`lrn`),
  ADD UNIQUE KEY `students_student_number_unique` (`student_number`),
  ADD KEY `students_strand_id_foreign` (`strand_id`),
  ADD KEY `students_guardian_id_foreign` (`guardian_id`);

--
-- Indexes for table `student_guardians`
--
ALTER TABLE `student_guardians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_guardian` (`student_id`,`guardian_id`),
  ADD KEY `student_guardians_guardian_id_foreign` (`guardian_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subjects_subject_code_unique` (`subject_code`),
  ADD KEY `subjects_strand_id_foreign` (`strand_id`),
  ADD KEY `subjects_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `subject_grading_templates`
--
ALTER TABLE `subject_grading_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subject_template` (`subject_id`),
  ADD KEY `subject_grading_templates_template_id_foreign` (`template_id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD UNIQUE KEY `uq_submission_once` (`assignment_id`,`student_id`),
  ADD KEY `idx_submission_status` (`status`),
  ADD KEY `submissions_student_id_foreign` (`student_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `teachers_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `teachers_teacher_number_unique` (`teacher_number`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`track_id`),
  ADD UNIQUE KEY `tracks_track_code_unique` (`track_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `attendance_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `section_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `enrollment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `final_grades`
--
ALTER TABLE `final_grades`
  MODIFY `final_grade_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade_components`
--
ALTER TABLE `grade_components`
  MODIFY `component_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grading_templates`
--
ALTER TABLE `grading_templates`
  MODIFY `template_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guardians`
--
ALTER TABLE `guardians`
  MODIFY `guardian_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guidance_records`
--
ALTER TABLE `guidance_records`
  MODIFY `record_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `material_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `attempt_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `question_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_events`
--
ALTER TABLE `schedule_events`
  MODIFY `event_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `school_year_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `strands`
--
ALTER TABLE `strands`
  MODIFY `strand_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `student_guardians`
--
ALTER TABLE `student_guardians`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_grading_templates`
--
ALTER TABLE `subject_grading_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submission_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `track_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcements_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`section_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_logged_by_foreign` FOREIGN KEY (`logged_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_records_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attendance_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD CONSTRAINT `class_sections_adviser_id_foreign` FOREIGN KEY (`adviser_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `class_sections_strand_id_foreign` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`strand_id`) ON UPDATE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`school_year_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollments_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`section_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON UPDATE CASCADE;

--
-- Constraints for table `final_grades`
--
ALTER TABLE `final_grades`
  ADD CONSTRAINT `final_grades_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `final_grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON UPDATE CASCADE;

--
-- Constraints for table `grade_components`
--
ALTER TABLE `grade_components`
  ADD CONSTRAINT `grade_components_enrollment_id_foreign` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`enrollment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grade_components_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON UPDATE CASCADE;

--
-- Constraints for table `guardians`
--
ALTER TABLE `guardians`
  ADD CONSTRAINT `guardians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `guidance_records`
--
ALTER TABLE `guidance_records`
  ADD CONSTRAINT `guidance_records_logged_by_foreign` FOREIGN KEY (`logged_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `guidance_records_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD CONSTRAINT `learning_materials_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `learning_materials_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quiz_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`quiz_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`section_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`school_year_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `strands`
--
ALTER TABLE `strands`
  ADD CONSTRAINT `strands_track_id_foreign` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`track_id`) ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`guardian_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_strand_id_foreign` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`strand_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_guardians`
--
ALTER TABLE `student_guardians`
  ADD CONSTRAINT `student_guardians_guardian_id_foreign` FOREIGN KEY (`guardian_id`) REFERENCES `guardians` (`guardian_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_guardians_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_strand_id_foreign` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`strand_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `subject_grading_templates`
--
ALTER TABLE `subject_grading_templates`
  ADD CONSTRAINT `subject_grading_templates_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_grading_templates_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `grading_templates` (`template_id`) ON UPDATE CASCADE;

--
-- Constraints for table `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
