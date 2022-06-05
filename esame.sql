-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 03, 2022 alle 19:04
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
-- Database: `esame`
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
-- Struttura della tabella `usercontent`
--

CREATE TABLE `usercontent` (
  `id` int(11) NOT NULL,
  `type` varchar(15) NOT NULL,
  `creationDate` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `notes` varchar(350) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `userid` int(11) NOT NULL
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
  `visibility` tinyint(1) NOT NULL DEFAULT 1,
  `avatarUri` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `friendrequests`
--
ALTER TABLE `friendrequests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userida` (`userida`),
  ADD KEY `useridb` (`useridb`);

--
-- Indici per le tabelle `friends`
--
ALTER TABLE `friends`
  ADD KEY `userida` (`userida`,`useridb`),
  ADD KEY `useridb` (`useridb`,`userida`);

--
-- Indici per le tabelle `usercontent`
--
ALTER TABLE `usercontent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_key_userid` (`userid`);

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
  ADD CONSTRAINT `userida` FOREIGN KEY (`userida`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `useridb` FOREIGN KEY (`useridb`) REFERENCES `users` (`id`);

--
-- Limiti per la tabella `usercontent`
--
ALTER TABLE `usercontent`
  ADD CONSTRAINT `usercontent_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
