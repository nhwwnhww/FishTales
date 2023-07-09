-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2023-07-10 00:36:56
-- 服务器版本： 10.4.24-MariaDB
-- PHP 版本： 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `fishtales`
--

-- --------------------------------------------------------

--
-- 表的结构 `markers`
--

CREATE TABLE `markers` (
  `markerID` int(20) NOT NULL,
  `userID` int(20) NOT NULL,
  `fishName` varchar(50) NOT NULL,
  `Latitude` float NOT NULL,
  `Longitude` float NOT NULL,
  `date` varchar(15) NOT NULL,
  `comment` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `markers`
--

INSERT INTO `markers` (`markerID`, `userID`, `fishName`, `Latitude`, `Longitude`, `date`, `comment`) VALUES
(1, 1, 'Prionace glauca', -27.6464, 152.913, '01/01/2023', 'AMD YES'),
(2, 1, 'Prionace glauca', -27.6465, 152.935, '2023-07-07', 'sdf'),
(9, 1, 'Prionace glauca', -27.6442, 152.934, '2023-07-05', 'ez'),
(10, 1, 'Prionace glauca', -27.6475, 152.936, '2023-07-13', 'asd'),
(11, 1, 'Tandanus tandanus', -27.6458, 152.937, '2023-06-28', 'nice  catch'),
(12, 1, 'Sphyraena barracuda', -27.6868, 152.919, '2023-07-14', 'ss'),
(14, 0, 'Tandanus tandanus', -27.6465, 152.936, '2023-07-06', 'a guest'),
(15, 21, 'Macquaria novemaculeata', -27.6466, 152.938, '2023-07-14', 'nice one'),
(16, 1, 'Prionace glauca', -27.6854, 152.913, '2023-07-14', '124');

-- --------------------------------------------------------

--
-- 表的结构 `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `name` varchar(11) NOT NULL,
  `lag` int(11) NOT NULL,
  `lng` int(11) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `test`
--

INSERT INTO `test` (`id`, `name`, `lag`, `lng`, `date`) VALUES
(1, 'wei', 12, 23, 2023),
(2, 'AMD', 14, 24, 2023),
(3, 'Inter', 22, 44, 2022);

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `userID` int(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`userID`, `email`, `username`, `password`) VALUES
(0, 'guest', 'guest', 'guest'),
(1, 'nhwwnhww@gmail.com', 'wei', '123'),
(21, '12345@email.com', 'Funnyface', '123'),
(22, '516119587@qq.com', 'miko', '123');

--
-- 转储表的索引
--

--
-- 表的索引 `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`markerID`),
  ADD KEY `User_id` (`userID`);

--
-- 表的索引 `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `markers`
--
ALTER TABLE `markers`
  MODIFY `markerID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 使用表AUTO_INCREMENT `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- 限制导出的表
--

--
-- 限制表 `markers`
--
ALTER TABLE `markers`
  ADD CONSTRAINT `User_id` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
