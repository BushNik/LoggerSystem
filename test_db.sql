-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июл 30 2016 г., 14:55
-- Версия сервера: 10.1.9-MariaDB
-- Версия PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `log_table`
--

CREATE TABLE `log_table` (
  `id` int(11) NOT NULL,
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `label` char(16) NOT NULL,
  `message` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `log_table`
--

INSERT INTO `log_table` (`id`, `logtime`, `label`, `message`) VALUES
(13, '2016-07-30 08:17:26', 'ident', '''Log entry 0'''),
(14, '2016-07-30 08:17:26', 'ident', '''Log entry 1'''),
(15, '2016-07-30 08:17:26', 'ident', '''Log entry 2'''),
(16, '2016-07-30 08:17:26', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(17, '2016-07-30 08:17:26', 'ident', 'exception ''Exception'' with message ''Ð”ÐµÐ»ÐµÐ½Ð¸Ðµ Ð½Ð° Ð½Ð¾Ð»ÑŒ.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\sql.php:22\nStack trace:\n#0 {main}'),
(18, '2016-07-30 08:20:39', 'ident', '''Log entry 0'''),
(19, '2016-07-30 08:20:39', 'ident', '''Log entry 1'''),
(20, '2016-07-30 08:20:39', 'ident', '''Log entry 2'''),
(21, '2016-07-30 08:20:39', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(22, '2016-07-30 08:20:39', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\sql.php:23\nStack trace:\n#0 {main}'),
(23, '2016-07-30 08:21:44', 'ident', '''Log entry 0'''),
(24, '2016-07-30 08:21:44', 'ident', '''Log entry 1'''),
(25, '2016-07-30 08:21:44', 'ident', '''Log entry 2'''),
(26, '2016-07-30 08:21:44', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(27, '2016-07-30 08:21:44', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\sql.php:23\nStack trace:\n#0 {main}'),
(28, '2016-07-30 08:21:45', 'ident', 'ru\\f_technology\\logger\\SQL::__set_state(array(\n   ''sql'' => ''INSERT INTO log_table (logtime, label, message) VALUES(CURRENT_TIMESTAMP, ?, ?)'',\n   ''options'' => \n  array (\n    ''persistent'' => true,\n  ),\n'),
(29, '2016-07-30 08:32:15', 'ident', '''Log entry 0'''),
(30, '2016-07-30 08:32:15', 'ident', '''Log entry 1'''),
(31, '2016-07-30 08:32:15', 'ident', '''Log entry 2'''),
(32, '2016-07-30 08:32:15', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(33, '2016-07-30 08:32:15', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\sql.php:23\nStack trace:\n#0 {main}'),
(34, '2016-07-30 08:32:15', 'ident', 'Vegetable::__set_state(array(\n   ''edible'' => true,\n   ''color'' => ''blue'',\n))'),
(35, '2016-07-30 08:32:15', 'ident', 'Spinach::__set_state(array(\n   ''cooked'' => false,\n   ''edible'' => true,\n   ''color'' => ''green'',\n))'),
(36, '2016-07-30 11:36:44', 'ident', '''Log entry 0'''),
(37, '2016-07-30 11:36:44', 'ident', '''Log entry 1'''),
(38, '2016-07-30 11:36:44', 'ident', '''Log entry 2'''),
(39, '2016-07-30 11:36:44', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(40, '2016-07-30 11:36:44', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\combine.php:28\nStack trace:\n#0 {main}'),
(41, '2016-07-30 11:36:44', 'ident', 'Vegetable::__set_state(array(\n   ''edible'' => true,\n   ''color'' => ''blue'',\n))'),
(42, '2016-07-30 11:36:44', 'ident', 'Spinach::__set_state(array(\n   ''cooked'' => false,\n   ''edible'' => true,\n   ''color'' => ''green'',\n))'),
(43, '2016-07-30 11:36:55', 'ident', '''Log entry 0'''),
(44, '2016-07-30 11:36:55', 'ident', '''Log entry 1'''),
(45, '2016-07-30 11:36:55', 'ident', '''Log entry 2'''),
(46, '2016-07-30 11:36:55', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(47, '2016-07-30 11:36:55', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\combine.php:28\nStack trace:\n#0 {main}'),
(48, '2016-07-30 11:36:55', 'ident', 'Vegetable::__set_state(array(\n   ''edible'' => true,\n   ''color'' => ''blue'',\n))'),
(49, '2016-07-30 11:36:55', 'ident', 'Spinach::__set_state(array(\n   ''cooked'' => false,\n   ''edible'' => true,\n   ''color'' => ''green'',\n))'),
(50, '2016-07-30 11:37:43', 'ident', '''Log entry 0'''),
(51, '2016-07-30 11:37:43', 'ident', '''Log entry 1'''),
(52, '2016-07-30 11:37:43', 'ident', '''Log entry 2'''),
(53, '2016-07-30 11:37:43', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(54, '2016-07-30 11:37:43', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\combine.php:28\nStack trace:\n#0 {main}'),
(55, '2016-07-30 11:37:44', 'ident', 'Vegetable::__set_state(array(\n   ''edible'' => true,\n   ''color'' => ''blue'',\n))'),
(56, '2016-07-30 11:37:44', 'ident', 'Spinach::__set_state(array(\n   ''cooked'' => false,\n   ''edible'' => true,\n   ''color'' => ''green'',\n))'),
(57, '2016-07-30 11:39:24', 'ident', '''Log entry 0'''),
(58, '2016-07-30 11:39:24', 'ident', '''Log entry 1'''),
(59, '2016-07-30 11:39:24', 'ident', '''Log entry 2'''),
(60, '2016-07-30 11:39:24', 'ident', 'array (\n  0 => ''Volvo'',\n  1 => ''BMW'',\n  2 => ''Toyota'',\n)'),
(61, '2016-07-30 11:39:24', 'ident', 'exception ''Exception'' with message ''Деление на ноль.'' in C:\\xampp\\htdocs\\LogSystem\\tests\\combine.php:31\nStack trace:\n#0 {main}'),
(62, '2016-07-30 11:39:24', 'ident', 'Vegetable::__set_state(array(\n   ''edible'' => true,\n   ''color'' => ''blue'',\n))'),
(63, '2016-07-30 11:39:24', 'ident', 'Spinach::__set_state(array(\n   ''cooked'' => false,\n   ''edible'' => true,\n   ''color'' => ''green'',\n))');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `log_table`
--
ALTER TABLE `log_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `log_table`
--
ALTER TABLE `log_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
