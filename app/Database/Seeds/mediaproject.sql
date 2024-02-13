-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2024 at 11:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mediaproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `audios`
--

CREATE TABLE `audios` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  `path` varchar(500) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `note` varchar(5000) DEFAULT NULL,
  `duration` time NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audios`
--

INSERT INTO `audios` (`id`, `name`, `type`, `uploaded_at`, `path`, `caption`, `updated_at`, `note`, `duration`, `user_id`) VALUES
(121, 'George Michael - Careless Whisper (Official Video) [izGwDsrQ1eQ].webm', 'video/webm', '2024-01-25 14:03:12', 'C:\\xampp\\htdocs\\mediaProject\\public/audio/George Michael - Careless Whisper (Official Video) [izGwDsrQ1eQ].webm', 'George Michael - Careless Whisper (Official Video) [izGwDsrQ1eQ].webm', '2024-01-25 14:03:12', '', '00:05:00', 1),
(122, 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].webm', 'video/webm', '2024-01-29 12:35:29', 'C:\\xampp\\htdocs\\mediaProject\\public/audio/Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY]1.webm', 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY]1.webm', '2024-01-29 12:35:29', '', '00:03:18', 13),
(125, 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].webm', 'video/webm', '2024-02-02 12:49:48', 'C:\\xampp\\htdocs\\mediaProject\\public/audio/Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].webm', 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].webm', '2024-02-02 12:49:48', '', '00:03:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  `path` varchar(500) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `note` varchar(5000) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `name`, `type`, `uploaded_at`, `path`, `caption`, `updated_at`, `note`, `user_id`) VALUES
(285, 'Behind the Hollywood Sign', 'image/jpeg', '2023-12-23 08:54:47', 'C:\\xampp\\htdocs\\mediaProject\\public/images/20230610_085716.jpg', '20230610_085716.jpg', '2023-12-23 09:13:34', 'This is behind the Hollywood sign', 1),
(286, '20230610_085953.jpg', 'image/jpeg', '2023-12-23 08:54:47', 'C:\\xampp\\htdocs\\mediaProject\\public/images/20230610_085953.jpg', '20230610_085953.jpg', '2023-12-23 08:54:47', '', 1),
(288, '20230610_085851_HDR.jpg', 'image/jpeg', '2023-12-23 08:54:47', 'C:\\xampp\\htdocs\\mediaProject\\public/images/20230610_085851_HDR.jpg', '20230610_085851_HDR.jpg', '2024-02-02 08:18:54', 'this is a photo', 1),
(295, 'Screenshot 2024-01-11 092648.png', 'image/png', '2024-01-26 09:36:03', 'C:\\xampp\\htdocs\\mediaProject\\public/images/Screenshot 2024-01-11 092648.png', 'Screenshot 2024-01-11 092648.png', '2024-02-06 14:22:12', '', 1),
(297, 'Screenshot 2024-01-14 200026.png', 'image/png', '2024-01-26 09:36:03', 'C:\\xampp\\htdocs\\mediaProject\\public/images/Screenshot 2024-01-14 200026.png', 'Screenshot 2024-01-14 200026.png', '2024-01-26 09:36:03', '', 1),
(301, 'Screenshot 2023-12-31 092947.png', 'image/png', '2024-01-26 16:50:13', 'C:\\xampp\\htdocs\\mediaProject\\public/images/Screenshot 2023-12-31 092947.png', 'Screenshot 2023-12-31 092947.png', '2024-01-26 16:50:13', '', 3),
(302, 'RL Craft', 'image/png', '2024-01-28 10:48:47', 'C:\\xampp\\htdocs\\mediaProject\\public/images/Screenshot 2023-12-31 092957.png', 'Screenshot 2023-12-31 092957.png', '2024-02-02 10:47:20', 'This is a screenshot of my RL Craft solo world.', 1),
(303, 'Acer_Wallpaper_02_3840x2400.jpg', 'image/jpeg', '2024-01-31 10:34:01', 'C:\\xampp\\htdocs\\mediaProject\\public/images/Acer_Wallpaper_02_3840x2400.jpg', 'Acer_Wallpaper_02_3840x2400.jpg', '2024-01-31 10:34:01', '', 3),
(306, 'Coordinates in cave in Minecraft', 'image/png', '2024-02-02 10:51:24', 'C:\\xampp\\htdocs\\mediaProject\\public/images/Screenshot 2023-12-17 162121.png', 'Screenshot 2023-12-17 162121.png', '2024-02-06 13:53:23', 'This is a screenshot of the world we are currently doing. \r\nHere I am taking a screenshot of the \r\n<strong>coordinates of a cave.</strong>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shared_audios`
--

CREATE TABLE `shared_audios` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `shared_at` datetime DEFAULT current_timestamp(),
  `audio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shared_audios`
--

INSERT INTO `shared_audios` (`id`, `sender_id`, `receiver_id`, `shared_at`, `audio_id`) VALUES
(31, 13, 1, '2024-01-29 12:35:35', 122),
(32, 1, 3, '2024-02-02 13:20:46', 121),
(33, 1, 13, '2024-02-04 15:58:12', 121),
(34, 1, 14, '2024-02-05 10:17:54', 125);

-- --------------------------------------------------------

--
-- Table structure for table `shared_images`
--

CREATE TABLE `shared_images` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `shared_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shared_images`
--

INSERT INTO `shared_images` (`id`, `sender_id`, `receiver_id`, `image_id`, `shared_at`) VALUES
(64, 3, 1, 301, '2024-01-26 16:50:18'),
(65, 1, 3, 302, '2024-01-30 15:07:14'),
(66, 3, 1, 303, '2024-01-31 11:01:51'),
(68, 1, 3, 285, '2024-02-02 12:08:02'),
(69, 1, 13, 301, '2024-02-04 15:55:50'),
(70, 1, 14, 297, '2024-02-06 14:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `shared_videos`
--

CREATE TABLE `shared_videos` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  `shared_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shared_videos`
--

INSERT INTO `shared_videos` (`id`, `sender_id`, `receiver_id`, `video_id`, `shared_at`) VALUES
(22, 13, 1, 100, '2024-01-29 12:51:30'),
(23, 18, 1, 101, '2024-01-31 17:00:57'),
(24, 1, 3, 102, '2024-02-02 14:28:52'),
(25, 1, 13, 103, '2024-02-04 15:57:09'),
(26, 1, 13, 102, '2024-02-04 16:08:48'),
(27, 1, 14, 102, '2024-02-05 10:18:36'),
(28, 1, 18, 107, '2024-02-06 19:20:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `created_at`) VALUES
(1, 'Emmanuel', 'Gonzalez', 'eg2895@gmail.com', '$2y$10$gUA1deHYWtXdxGDV9v0PMu2jkUzJnReFqDZYW5Zg2rlse4IAS6KSe', '2023-05-19 12:57:20'),
(3, 'Emmanuel', 'Gonzalez', 'egonza100@calstatela.edu', '$2y$10$UtNjlOXkupbhGWc5zlmTS.OVroZ10fl2R12SgtkvxFHtoz8zY0Fra', '2023-05-31 09:11:39'),
(13, 'fake', 'User', 'email@gmail.com', '$2y$10$WaOceERLM9EitahqPDjV5ukPTg2RGb40VElRzTL0LZPrMEoY0VWIq', '2023-06-22 08:51:00'),
(14, 'Goat', 'James', 'goatjames23.25@gmail.com', '$2y$10$bL7gAtIfF/5Kq8trGsMOueOQdZIbSujDlyDxxPg6l0EJyRBmBFB9a', '2024-01-28 20:06:44'),
(18, 'Andrew', 'Gonzalez', 'andrewsucks@gmail.com', '$2y$10$4dPgcvorND5AI1sypT0zSeRpxipwLUlzJ95JH9EOhAZ1GC5qUCZT2', '2024-01-31 16:12:28');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  `path` varchar(500) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `updated_at` datetime NOT NULL,
  `note` varchar(5000) DEFAULT NULL,
  `duration` time NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `name`, `type`, `uploaded_at`, `path`, `caption`, `updated_at`, `note`, `duration`, `user_id`) VALUES
(100, 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].mp4', 'video/mp4', '2024-01-29 12:51:19', 'C:\\xampp\\htdocs\\mediaProject\\public/video/Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY]1.mp4', 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY]1.mp4', '2024-01-29 12:51:19', '', '00:03:18', 13),
(101, 'That \'70s Show 1x1 (1998).mp4', 'video/mp4', '2024-01-31 16:51:38', 'C:\\xampp\\htdocs\\mediaProject\\public/video/That \'70s Show 1x1 (1998).mp4', 'That \'70s Show 1x1 (1998).mp4', '2024-01-31 16:51:38', '', '00:22:33', 18),
(102, 'George Michael - Careless Whisper (Official Video)', 'video/mp4', '2024-02-02 14:23:22', 'C:\\xampp\\htdocs\\mediaProject\\public/video/George Michael - Careless Whisper (Official Video) [izGwDsrQ1eQ].mp4', 'George Michael - Careless Whisper (Official Video) [izGwDsrQ1eQ].mp4', '2024-02-07 09:52:34', 'I feel so unsure\r\nAs I take your hand and lead you to the dance floor\r\nAs the music dies, something in your eyes\r\nCalls to mind a silver screen\r\nAnd all its sad good-byes\r\n\r\nI\'m never gonna dance again\r\nGuilty feet have got no rhythm\r\nThough it\'s easy to pretend\r\nI know you\'re not a fool\r\n\r\nI should\'ve known better than to cheat a friend\r\nAnd waste the chance that I\'d been given\r\nSo I\'m never gonna dance again\r\nThe way I danced with you, oh\r\n\r\nTime can never mend\r\nThe careless whispers of a good friend\r\nTo the heart and mind\r\nIgnorance is kind\r\nThere\'s no comfort in the truth\r\nPain is all you\'ll find\r\n\r\nI\'m never gonna dance again\r\nGuilty feet have got no rhythm\r\nThough it\'s easy to pretend\r\nI know you\'re not a fool\r\n\r\nI should\'ve known better than to cheat a friend (should\'ve known better, yeah)\r\nAnd waste the chance that I\'d been given\r\nSo I\'m never gonna dance again\r\nThe way I danced with you, oh\r\n\r\nNever without your love\r\n\r\nTonight the music seems so loud\r\nI wish that we could lose this crowd\r\nMaybe it\'s better this way\r\nWe\'d hurt each other with the things we\'d want to say\r\n\r\nWe could have been so good together\r\nWe could have lived this dance forever\r\nBut now, who\'s gonna dance with me?\r\nPlease stay\r\n\r\nAnd I\'m never gonna dance again\r\nGuilty feet have got no rhythm\r\nThough it\'s easy to pretend\r\nI know you\'re not a fool\r\n\r\nI should\'ve known better than to cheat a friend\r\nAnd waste the chance that I\'d been given\r\nSo I\'m never gonna dance again\r\nThe way I danced with you, oh\r\n\r\nnow that you\'re gone\r\n(Now that you\'re gone) was what I did so wrong, so wrong\r\nThat you had to leave me alone?', '00:03:18', 1),
(103, 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].mp4', 'video/mp4', '2024-02-02 14:23:23', 'C:\\xampp\\htdocs\\mediaProject\\public/video/Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].mp4', 'Lost [Official Music Video] - Linkin Park [7NK_JOkuSVY].mp4', '2024-02-05 10:09:46', '', '00:05:00', 1),
(107, 'That \'70s Show 1x2 (1998).mp4', 'video/mp4', '2024-02-06 14:32:37', 'C:\\xampp\\htdocs\\mediaProject\\public/video/That \'70s Show 1x2 (1998).mp4', 'That \'70s Show 1x2 (1998).mp4', '2024-02-06 14:32:37', '', '00:22:34', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audios`
--
ALTER TABLE `audios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `shared_audios`
--
ALTER TABLE `shared_audios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `audio_id` (`audio_id`);

--
-- Indexes for table `shared_images`
--
ALTER TABLE `shared_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `shared_videos`
--
ALTER TABLE `shared_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `video_id` (`video_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audios`
--
ALTER TABLE `audios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `shared_audios`
--
ALTER TABLE `shared_audios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `shared_images`
--
ALTER TABLE `shared_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `shared_videos`
--
ALTER TABLE `shared_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audios`
--
ALTER TABLE `audios`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `shared_audios`
--
ALTER TABLE `shared_audios`
  ADD CONSTRAINT `shared_audios_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shared_audios_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shared_audios_ibfk_3` FOREIGN KEY (`audio_id`) REFERENCES `audios` (`id`);

--
-- Constraints for table `shared_images`
--
ALTER TABLE `shared_images`
  ADD CONSTRAINT `shared_images_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shared_images_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shared_images_ibfk_3` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`);

--
-- Constraints for table `shared_videos`
--
ALTER TABLE `shared_videos`
  ADD CONSTRAINT `shared_videos_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shared_videos_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `shared_videos_ibfk_3` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`);

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
