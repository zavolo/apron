-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 12 2022 г., 20:31
-- Версия сервера: 5.7.37-log
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `apron`
--

-- --------------------------------------------------------

--
-- Структура таблицы `devices`
--

CREATE TABLE `devices` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'Системмный ID',
  `name` varchar(255) NOT NULL COMMENT 'Название',
  `number` varchar(255) NOT NULL COMMENT 'Номер устройства',
  `about` varchar(255) NOT NULL COMMENT 'Краткое описание',
  `owner` varchar(255) NOT NULL COMMENT 'Владелец',
  `date` varchar(255) NOT NULL COMMENT 'Дата добавления в apron'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Устройства';

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'Айди пользователя',
  `login` varchar(255) NOT NULL COMMENT 'Логин пользователя',
  `password` varchar(255) NOT NULL COMMENT 'Пароль пользователя',
  `name` varchar(255) NOT NULL COMMENT 'ФИО пользователя',
  `status` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Роль (0 - user, 1 - admin, 2 - support)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Пользователи';

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `status`) VALUES
(1, 'apron', 'apron', 'Админ', '1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Системмный ID';

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Айди пользователя', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
