--
-- Product name: `melody.proto`
-- (c) 2021 SuperSonic(https://github.com/supersonictw)
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `posts` (
  `uuid` varchar(36) NOT NULL,
  `author` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `created_time` int(11) NOT NULL,
  `modified_time` int(11) DEFAULT NULL,
  `parent` varchar(36) DEFAULT NULL,
  `link` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `users` (
  `identity` varchar(64) NOT NULL,
  `display_name` varchar(30) NOT NULL,
  `device` text NOT NULL,
  `ip_addr` varchar(128) NOT NULL,
  `created_time` int(11) NOT NULL,
  `access_token` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `posts`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `parent` (`parent`),
  ADD KEY `posts_ibfk_1` (`author`),
  ADD KEY `link` (`link`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`identity`);

ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`identity`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`parent`) REFERENCES `posts` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`link`) REFERENCES `posts` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
