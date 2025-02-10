-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.2
-- Время создания: Фев 10 2025 г., 22:57
-- Версия сервера: 8.2.0
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `db_application`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Class`
--

CREATE TABLE `Class` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `teacherId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Class`
--

INSERT INTO `Class` (`id`, `name`, `teacherId`) VALUES
(1, '1Г', 1),
(2, '1А', 2),
(3, '2А', 1),
(4, '2Б', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `ClassList`
--

CREATE TABLE `ClassList` (
  `id` int NOT NULL,
  `classId` int NOT NULL,
  `studentId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ClassList`
--

INSERT INTO `ClassList` (`id`, `classId`, `studentId`) VALUES
(2, 1, 2),
(3, 1, 3),
(12, 1, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `Student`
--

CREATE TABLE `Student` (
  `id` int NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `patronymic` text NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Student`
--

INSERT INTO `Student` (`id`, `surname`, `name`, `patronymic`, `birthday`) VALUES
(2, 'Ушкин', 'Матвей', 'Побрикушкович', '2010-10-20'),
(3, 'Горшев', 'Алексей', 'Павлович', '2000-10-10'),
(12, 'А', 'А', 'А', '2000-12-12');

-- --------------------------------------------------------

--
-- Структура таблицы `Teacher`
--

CREATE TABLE `Teacher` (
  `id` int NOT NULL,
  `surname` text NOT NULL,
  `name` text NOT NULL,
  `patronymic` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Teacher`
--

INSERT INTO `Teacher` (`id`, `surname`, `name`, `patronymic`) VALUES
(1, 'Липов', 'Антон', 'Геннадьевич'),
(2, 'Васюков', 'Павел', 'Анатольевич');

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`id`, `login`, `password`) VALUES
(1, 'admin', 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Class`
--
ALTER TABLE `Class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacherId` (`teacherId`);

--
-- Индексы таблицы `ClassList`
--
ALTER TABLE `ClassList`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classId` (`classId`),
  ADD KEY `studentId` (`studentId`);

--
-- Индексы таблицы `Student`
--
ALTER TABLE `Student`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Teacher`
--
ALTER TABLE `Teacher`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Class`
--
ALTER TABLE `Class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `ClassList`
--
ALTER TABLE `ClassList`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `Student`
--
ALTER TABLE `Student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `Teacher`
--
ALTER TABLE `Teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Class`
--
ALTER TABLE `Class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `Teacher` (`id`);

--
-- Ограничения внешнего ключа таблицы `ClassList`
--
ALTER TABLE `ClassList`
  ADD CONSTRAINT `classlist_ibfk_1` FOREIGN KEY (`classId`) REFERENCES `Class` (`id`),
  ADD CONSTRAINT `classlist_ibfk_2` FOREIGN KEY (`studentId`) REFERENCES `Student` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
