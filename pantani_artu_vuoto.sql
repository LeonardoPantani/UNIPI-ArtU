-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 12, 2022 alle 20:17
-- Versione del server: 10.4.21-MariaDB
-- Versione PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pantani_artu`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `friendrequests`
--

CREATE TABLE `friendrequests` (
  `id` int(11) NOT NULL,
  `userida` int(11) NOT NULL,
  `useridb` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `friends`
--

CREATE TABLE `friends` (
  `userida` int(11) NOT NULL,
  `useridb` int(11) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `pages`
--

CREATE TABLE `pages` (
  `userid` int(11) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `editDate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `page_ratings`
--

CREATE TABLE `page_ratings` (
  `userid` int(11) NOT NULL,
  `userpageid` int(11) NOT NULL,
  `value` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `usercontent`
--

CREATE TABLE `usercontent` (
  `id` int(11) NOT NULL,
  `type` varchar(15) NOT NULL,
  `creationDate` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `tags` varchar(700) NOT NULL,
  `notes` varchar(3500) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL,
  `contentExtension` varchar(255) NOT NULL,
  `thumbnailExtension` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `usercontent_comments`
--

CREATE TABLE `usercontent_comments` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `contentid` int(11) NOT NULL,
  `text` varchar(550) NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `usercontent_ratings`
--

CREATE TABLE `usercontent_ratings` (
  `userid` int(11) NOT NULL,
  `contentid` int(11) NOT NULL,
  `value` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `creationDate` int(255) NOT NULL,
  `avatarUri` varchar(255) DEFAULT NULL,
  `setting_visibility` tinyint(1) NOT NULL DEFAULT 1,
  `setting_numElemsPerPage` int(11) NOT NULL DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `friendrequests`
--
ALTER TABLE `friendrequests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userida_foreignkey_friendrequests` (`userida`),
  ADD KEY `useridb_foreignkey_friendrequests` (`useridb`);

--
-- Indici per le tabelle `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`userida`,`useridb`),
  ADD KEY `userida` (`userida`,`useridb`),
  ADD KEY `useridb` (`useridb`,`userida`);

--
-- Indici per le tabelle `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`userid`);

--
-- Indici per le tabelle `page_ratings`
--
ALTER TABLE `page_ratings`
  ADD PRIMARY KEY (`userid`,`userpageid`),
  ADD KEY `userid` (`userid`,`userpageid`);

--
-- Indici per le tabelle `usercontent`
--
ALTER TABLE `usercontent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid_foreignkey_usercontent` (`userid`);

--
-- Indici per le tabelle `usercontent_comments`
--
ALTER TABLE `usercontent_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usercontent_comments_ibfk_1` (`userid`);

--
-- Indici per le tabelle `usercontent_ratings`
--
ALTER TABLE `usercontent_ratings`
  ADD PRIMARY KEY (`userid`,`contentid`),
  ADD KEY `contentid` (`contentid`,`userid`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `friendrequests`
--
ALTER TABLE `friendrequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `usercontent`
--
ALTER TABLE `usercontent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `usercontent_comments`
--
ALTER TABLE `usercontent_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `friendrequests`
--
ALTER TABLE `friendrequests`
  ADD CONSTRAINT `userida_foreignkey_friendrequests` FOREIGN KEY (`userida`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `useridb_foreignkey_friendrequests` FOREIGN KEY (`useridb`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `userida_foreignkey_friends` FOREIGN KEY (`userida`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `useridb_foreignkey_friends` FOREIGN KEY (`useridb`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `userid_foreignkey_pages` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `page_ratings`
--
ALTER TABLE `page_ratings`
  ADD CONSTRAINT `userid_foreignkey_page_ratings` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `usercontent`
--
ALTER TABLE `usercontent`
  ADD CONSTRAINT `userid_foreignkey_usercontent` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `usercontent_comments`
--
ALTER TABLE `usercontent_comments`
  ADD CONSTRAINT `userid_foreignkey_usercontent_comments` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `usercontent_ratings`
--
ALTER TABLE `usercontent_ratings`
  ADD CONSTRAINT `userid_foreignkey_usercontent_ratings` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
