-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 28 Gru 2020, 08:28
-- Wersja serwera: 10.1.38-MariaDB
-- Wersja PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `rss`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `articles_ks`
--

CREATE TABLE `articles_ks` (
  `id` int(11) NOT NULL,
  `date_publication` datetime NOT NULL,
  `date_add` datetime NOT NULL,
  `title` text COLLATE utf8_polish_ci NOT NULL,
  `contents` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `articles_rmf24`
--

CREATE TABLE `articles_rmf24` (
  `id` int(11) NOT NULL,
  `date_publication` datetime NOT NULL,
  `date_add` datetime NOT NULL,
  `title` text COLLATE utf8_polish_ci NOT NULL,
  `contents` text COLLATE utf8_polish_ci NOT NULL,
  `guid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `articles_ks`
--
ALTER TABLE `articles_ks`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `articles_rmf24`
--
ALTER TABLE `articles_rmf24`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `articles_ks`
--
ALTER TABLE `articles_ks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `articles_rmf24`
--
ALTER TABLE `articles_rmf24`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
