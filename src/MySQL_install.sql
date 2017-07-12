CREATE DATABASE IF NOT EXISTS `app` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `app`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `privileges` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `users` (`id`, `username`, `password`, `privileges`, `email`, `first_name`, `last_name`) VALUES
(0, 'test', '$2y$12$JDJhJDA3JFQzNVQ0UFBMMOpKqNzl5qJWOSPBgKNWXpzCnxwTVClv2', 1, NULL, NULL, NULL),
(1, 'test2', '$2y$12$JDJhJDA3JFQzNVQ0UFBMMOpKqNzl5qJWOSPBgKNWXpzCnxwTVClv2', 1, NULL, NULL, NULL);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
