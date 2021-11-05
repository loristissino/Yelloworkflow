-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 01, 2020 at 11:24 AM
-- Server version: 10.1.44-MariaDB-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ywf`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `code` varchar(40) DEFAULT NULL,
  `debits_header` varchar(60) NOT NULL,
  `credits_header` varchar(60) NOT NULL,
  `represents` char(1) NOT NULL DEFAULT 'R' COMMENT 'S: sale, D: donation, C: contribution, E: expense, R: real value',
  `enforced_balance` char(1) NOT NULL DEFAULT '-',
  `shown_in_ou_view` int(1) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `organizational_unit_id`, `rank`, `name`, `status`, `code`, `debits_header`, `credits_header`, `represents`, `enforced_balance`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 'Cassa Circolo', 1, '', 'Entrate', 'Uscite', 'R', 'D', 1587569563, 1588586449),
(2, NULL, 2, 'Carta Prepagata Circolo', 1, '', 'Entrate', 'Uscite', 'R', 'D', 1587569587, 1588586731),
(3, NULL, 3, 'Sede Nazionale', 1, '', 'Addebiti', 'Accrediti', 'R', '-', 1587569615, 1588329389),
(4, NULL, 4, 'Cassiere di Circolo', 1, '', 'Addebiti', 'Accrediti', 'R', '-', 1587569690, 1587570239),
(5, NULL, 5, 'Coordinatore di Circolo', 1, '', 'Addebiti', 'Accrediti', 'R', '-', 1587569792, 1587569792),
(6, NULL, 6, 'Altri soci Attivo Circolo', 1, '', 'Addebiti', 'Accrediti', 'R', '-', 1587570290, 1587570290),
(7, NULL, 11, 'Spese per organizzazione eventi', 1, '', 'Spese', 'Rettifiche', 'E', 'D', 1587657743, 1588586750),
(8, NULL, 12, 'Spese per gestione sede', 1, '', 'Spese', 'Rettifiche', 'E', 'D', 1587657763, 1588586758),
(9, NULL, 13, 'Imposte e tasse locali', 1, '', 'Spese', 'Rettifiche', 'E', 'D', 1587657808, 1588586765),
(10, NULL, 15, 'Spese generiche', 1, '', 'Spese', 'Rettifiche', 'E', 'D', 1587657835, 1588586772),
(11, NULL, 21, 'Rimborsi dalla Tesoreria nazionale', 1, '', 'Rettifiche', 'Incassi', 'D', 'C', 1587657863, 1588586778),
(12, NULL, 22, 'Vendita di libri', 1, '', 'Rettifiche', 'Incassi', 'S', 'C', 1587657889, 1588586842),
(13, NULL, 23, 'Vendita di gadget', 1, '', 'Rettifiche', 'Incassi', 'S', 'C', 1587657901, 1588586853),
(14, NULL, 24, 'Contributi al Circolo', 1, '', 'Rettifiche', 'Incassi', 'D', 'C', 1587657925, 1588586799),
(15, NULL, 25, 'Entrate diverse', 1, '', 'Rettifiche', 'Incassi', 'D', 'C', 1587658319, 1588586806),
(16, NULL, 14, 'Oneri bancari e postali', 1, '', 'Spese', 'Rettifiche', 'E', 'D', 1587657835, 1588586813);
-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `model` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `info` text NOT NULL,
  `authorization_id` int(11) DEFAULT NULL,
  `happened_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='log entries for the application';

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `activity_type`, `model`, `model_id`, `info`, `authorization_id`, `happened_at`) VALUES
(30, 4, 'updated', 'app\\models\\Affiliation', 16, '{\"user_id\":\"16\",\"organizational_unit_id\":\"6\",\"role_id\":\"5\",\"rank\":\"0\"}', 259, 1586859708),
(31, 4, 'created', 'app\\models\\Affiliation', 18, '{\"user_id\":\"16\",\"organizational_unit_id\":\"7\",\"role_id\":\"3\",\"rank\":1}', 259, 1586860184),
(32, 4, 'created', 'app\\models\\Affiliation', 19, '{\"user_id\":\"5\",\"organizational_unit_id\":\"3\",\"role_id\":\"1\",\"rank\":1}', 259, 1586860338),
(33, 4, 'created', 'app\\models\\Affiliation', 20, '{\"user_id\":\"13\",\"organizational_unit_id\":\"9\",\"role_id\":\"2\",\"rank\":1}', 259, 1586860510),
(34, 4, 'deleted', 'app\\models\\Affiliation', 20, '{\"user_id\":13,\"organizational_unit_id\":9,\"role_id\":2,\"rank\":1}', 259, 1586860639),
(35, 4, 'created', 'app\\models\\Authorization', 304, '{\"controller_id\":\"foo\",\"action_id\":\"bar\",\"method\":\"*\",\"type\":\"@\",\"user_id\":\"\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2099-12-31 00:00\",\"role_id\":\"\"}', 257, 1586861031),
(36, 4, 'deleted', 'app\\models\\Authorization', 304, '{\"controller_id\":\"foo\",\"action_id\":\"bar\",\"method\":\"*\",\"type\":\"@\",\"user_id\":null,\"begin_date\":\"2020-03-01 00:00:00\",\"end_date\":\"2099-12-31 00:00:00\",\"role_id\":null}', 257, 1586861038),
(37, 4, 'updated', 'app\\models\\Authorization', 257, '{\"controller_id\":\"authorizations\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":\"4\",\"begin_date\":\"2020-03-01 00:00:00\",\"end_date\":\"2099-12-31 00:00:00\",\"role_id\":\"8\"}', 257, 1586861380),
(38, 4, 'created', 'app\\models\\Authorization', 305, '{\"begin_date\":\"2020-04-14 11:04:39\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"backend\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 256, 1586862280),
(39, 4, 'created', 'app\\models\\Authorization', 306, '{\"begin_date\":\"2020-04-14 11:04:40\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"accounts\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 256, 1586862280),
(40, 4, 'created', 'app\\models\\Authorization', 307, '{\"begin_date\":\"2020-04-14 11:04:40\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"expense-types\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 256, 1586862280),
(41, 4, 'created', 'app\\models\\Authorization', 308, '{\"begin_date\":\"2020-04-14 11:04:40\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"transaction-templates\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 256, 1586862280),
(42, 4, 'updated', 'app\\models\\Role', 8, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"amministratore\",\"description\":\"Amministratore dell\'applicazione\",\"permissions\":\"activities, backend, accounts, expense-types, transaction-templates\",\"email\":\"\"}', 256, 1586862280),
(43, 4, 'created', 'app\\models\\Authorization', 309, '{\"begin_date\":\"2020-04-14 11:10:52\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"notification-templates\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 256, 1586862652),
(44, 4, 'updated', 'app\\models\\Role', 8, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"amministratore\",\"description\":\"Amministratore dell\'applicazione\",\"permissions\":\"activities, backend, accounts, expense-types, transaction-templates, notification-templates\",\"email\":\"\"}', 256, 1586862652),
(45, 4, 'created', 'app\\models\\Authorization', 310, '{\"begin_date\":\"2020-04-14 14:14:48\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":3}', 256, 1586873688),
(46, 4, 'created', 'app\\models\\Authorization', 311, '{\"begin_date\":\"2020-04-14 14:14:48\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"role_id\":3}', 256, 1586873688),
(47, 4, 'created', 'app\\models\\Authorization', 312, '{\"begin_date\":\"2020-04-14 14:14:48\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"role_id\":3}', 256, 1586873688),
(48, 4, 'updated', 'app\\models\\Role', 3, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"coordinatore_circolo\",\"description\":\"Coordinatore di Circolo\",\"permissions\":\"projects-submissions\",\"email\":\"\"}', 256, 1586873688),
(49, 4, 'created', 'app\\models\\Affiliation', 18, '{\"user_id\":\"4\",\"organizational_unit_id\":\"6\",\"role_id\":\"3\",\"rank\":1}', 259, 1586873739),
(50, 4, 'created', 'app\\models\\Authorization', 313, '{\"begin_date\":\"2020-04-14 14:15:49\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":3}', 255, 1586873749),
(51, 4, 'updated', 'app\\models\\Authorization', 310, '{\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"begin_date\":\"2020-04-14 14:14:48\",\"end_date\":\"2020-04-14 14:19:19\",\"role_id\":3}', 256, 1586873960),
(52, 4, 'updated', 'app\\models\\Authorization', 311, '{\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"begin_date\":\"2020-04-14 14:14:48\",\"end_date\":\"2020-04-14 14:19:19\",\"role_id\":3}', 256, 1586873960),
(53, 4, 'updated', 'app\\models\\Authorization', 312, '{\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"begin_date\":\"2020-04-14 14:14:48\",\"end_date\":\"2020-04-14 14:19:19\",\"role_id\":3}', 256, 1586873960),
(54, 4, 'updated', 'app\\models\\Authorization', 313, '{\"controller_id\":\"projects-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"begin_date\":\"2020-04-14 14:15:49\",\"end_date\":\"2020-04-14 14:19:19\",\"role_id\":3}', 256, 1586873960),
(55, 4, 'created', 'app\\models\\Authorization', 314, '{\"begin_date\":\"2020-04-14 14:19:20\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":3}', 256, 1586873960),
(56, 4, 'created', 'app\\models\\Authorization', 315, '{\"begin_date\":\"2020-04-14 14:19:20\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":3}', 256, 1586873960),
(57, 4, 'created', 'app\\models\\Authorization', 316, '{\"begin_date\":\"2020-04-14 14:19:20\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"role_id\":3}', 256, 1586873960),
(58, 4, 'created', 'app\\models\\Authorization', 317, '{\"begin_date\":\"2020-04-14 14:19:20\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"role_id\":3}', 256, 1586873960),
(59, 4, 'updated', 'app\\models\\Role', 3, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"coordinatore_circolo\",\"description\":\"Coordinatore di Circolo\",\"permissions\":\"project-submissions\",\"email\":\"\"}', 256, 1586873960),
(60, 4, 'created', 'app\\models\\Authorization', 318, '{\"begin_date\":\"2020-04-14 15:49:04\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":5,\"role_id\":5}', 256, 1586879344),
(61, 4, 'created', 'app\\models\\Authorization', 319, '{\"begin_date\":\"2020-04-14 15:49:04\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":16,\"role_id\":5}', 256, 1586879344),
(62, 4, 'updated', 'app\\models\\Role', 5, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"cassiere_circolo\",\"description\":\"Cassiere di Circolo\",\"permissions\":\"project-submissions\",\"email\":\"\"}', 256, 1586879344),
(63, 4, 'created', 'app\\models\\Affiliation', 19, '{\"user_id\":\"4\",\"organizational_unit_id\":\"7\",\"role_id\":\"16\",\"rank\":1}', 259, 1586882314),
(64, 4, 'updated', 'app\\models\\OrganizationalUnit', 7, '{\"rank\":\"12\",\"status\":\"0\",\"name\":\"Circolo di Ancona\",\"email\":\"\",\"url\":\"\"}', 258, 1586882323),
(65, 4, 'updated', 'app\\models\\OrganizationalUnit', 6, '{\"rank\":\"11\",\"status\":\"0\",\"name\":\"Circolo di Pordenone\",\"email\":\"pordenone@uaar.it\",\"url\":\"http:\\/\\/pordenone.uaar.it\"}', 258, 1586882642),
(66, 4, 'updated', 'app\\models\\OrganizationalUnit', 7, '{\"rank\":\"12\",\"status\":\"1\",\"name\":\"Circolo di Ancona\",\"email\":\"\",\"url\":\"\"}', 258, 1586883041),
(67, 4, 'updated', 'app\\models\\OrganizationalUnit', 6, '{\"rank\":\"11\",\"status\":\"1\",\"name\":\"Circolo di Pordenone\",\"email\":\"pordenone@uaar.it\",\"url\":\"http:\\/\\/pordenone.uaar.it\"}', 258, 1586883051),
(68, 4, 'updated', 'app\\models\\OrganizationalUnit', 7, '{\"rank\":\"12\",\"status\":\"0\",\"name\":\"Circolo di Ancona\",\"email\":\"\",\"url\":\"\"}', 258, 1586883087),
(69, 4, 'updated', 'app\\models\\OrganizationalUnit', 7, '{\"rank\":\"12\",\"status\":\"1\",\"name\":\"Circolo di Ancona\",\"email\":\"\",\"url\":\"\"}', 258, 1586883240),
(70, 4, 'deleted', 'app\\models\\Affiliation', 19, '{\"user_id\":4,\"organizational_unit_id\":7,\"role_id\":16,\"rank\":1}', 259, 1586883294),
(71, 4, 'created', 'app\\models\\Project', 1, '{\"title\":\"uno\",\"description\":\"gasdfg\",\"co_hosts\":\"zxczxcv\",\"partners\":\"zxcvzxcv\",\"period\":\"zxcvzxc\",\"place\":\"zcxvzxcv\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586943236),
(72, 4, 'created', 'app\\models\\Authorization', 320, '{\"controller_id\":\"organizational-units\",\"action_id\":\"view\",\"method\":\"GET\",\"type\":\"@\",\"user_id\":\"\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2099-12-31 00:00\",\"role_id\":\"\"}', 257, 1586943471),
(73, 10, 'created', 'app\\models\\Project', 2, '{\"title\":\"prova palermo\",\"description\":\"uno\",\"co_hosts\":\"du\",\"partners\":\"\",\"period\":\"fsdf\",\"place\":\"sadfsadf\",\"organizational_unit_id\":\"5\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 316, 1586943527),
(74, 4, 'created', 'app\\models\\Authorization', 321, '{\"controller_id\":\"users\",\"action_id\":\"view\",\"method\":\"GET\",\"type\":\"@\",\"user_id\":\"\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2099-12-31 00:00\",\"role_id\":\"\"}', 257, 1586943596),
(75, 4, 'updated', 'app\\models\\Project', 1, '{\"title\":\"Presentazione libro \\\"Vento tra i capelli\\\"\",\"description\":\"Videoconferenza con ... e con ...\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Pordenone\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1586950032),
(76, 4, 'updated', 'app\\models\\Project', 1, '{\"title\":\"Presentazione libro \\\"Vento tra i capelli\\\"\",\"description\":\"Videoconferenza con ... e con ...\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Pordenone\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1586950543),
(77, 4, 'created', 'app\\models\\Project', 3, '{\"title\":\"fdgh\",\"description\":\"vbncvb\",\"co_hosts\":\"cvbn\",\"partners\":\"\",\"period\":\"fxfb\",\"place\":\"vcxcbn\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586951463),
(78, 4, 'updated', 'app\\models\\Project', 3, '{\"title\":\"fdgh\",\"description\":\"vbncvb\",\"co_hosts\":\"cvbn\",\"partners\":\"\",\"period\":\"fxfb\",\"place\":\"vcxcbn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1586953451),
(79, 4, 'created', 'app\\models\\Project', 4, '{\"title\":\"Presentazione libro\",\"description\":\"Prova 1 presentazione libro\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Pordenone, Biblioteca Civica\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586957145),
(80, 4, 'updated', 'app\\models\\Project', 4, '{\"title\":\"Presentazione libro\",\"description\":\"Prova 1 presentazione libro\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Pordenone, Biblioteca Civica\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1586959471),
(81, 4, 'created', 'app\\models\\Project', 5, '{\"title\":\"dfhsfd\",\"description\":\"vbncvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbnvb\",\"place\":\"cvbncvb\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586959668),
(82, 4, 'updated', 'app\\models\\Project', 5, '{\"title\":\"dfhsfd\",\"description\":\"vbncvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbnvb\",\"place\":\"cvbncvb\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1586961441),
(83, 4, 'created', 'app\\models\\Project', 6, '{\"title\":\"fghfgh\",\"description\":\"bvncb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbnbvn\",\"place\":\"vbcvbn\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586961525),
(84, 4, 'updated', 'app\\models\\Project', 6, '{\"title\":\"fghfgh\",\"description\":\"bvncb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbnbvn\",\"place\":\"vbcvbn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1586961889),
(85, 4, 'created', 'app\\models\\Project', 7, '{\"title\":\"cbfsd\",\"description\":\"sdfghsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbncb\",\"place\":\"cbvcvbn\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586961933),
(86, 4, 'updated', 'app\\models\\Project', 7, '{\"title\":\"cbfsd\",\"description\":\"sdfghsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbncb\",\"place\":\"cbvcvbn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1586961935),
(87, 4, 'created', 'app\\models\\Project', 8, '{\"title\":\"sdgsdafg\",\"description\":\"cvbxcbv\",\"co_hosts\":\"x\",\"partners\":\"\",\"period\":\"cvbx\",\"place\":\"xcvb\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1586967743),
(88, 6, 'created', 'app\\models\\Project', 9, '{\"title\":\"sdfsdf\",\"description\":\"cxv\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"zxcv\",\"place\":\"xcbvzxc\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1586970970),
(89, 6, 'updated', 'app\\models\\Project', 9, '{\"title\":\"sdfsdf\",\"description\":\"cxv\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"zxcv\",\"place\":\"xcbvzxc\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1586970972),
(90, 4, 'created', 'app\\models\\Authorization', 322, '{\"begin_date\":\"2020-04-15 17:17:08\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-approvals\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":2}', 256, 1586971028),
(91, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"project-approvals\",\"email\":\"tesoriere@o.example.com\"}', 256, 1586971028),
(92, 4, 'updated', 'app\\models\\Authorization', 322, '{\"controller_id\":\"project-approvals\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"begin_date\":\"2020-04-15 17:17:08\",\"end_date\":\"2020-04-15 17:17:53\",\"role_id\":2}', 256, 1586971074),
(93, 4, 'created', 'app\\models\\Authorization', 323, '{\"begin_date\":\"2020-04-15 17:17:54\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-management\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":2}', 256, 1586971074),
(94, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management\",\"email\":\"tesoriere@o.example.com\"}', 256, 1586971074),
(95, 6, 'updated', 'app\\models\\Project', 9, '{\"title\":\"sdfsdf\",\"description\":\"cxv\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"zxcv\",\"place\":\"xcbvzxc\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":4}', 315, 1586971634),
(96, 6, 'created', 'app\\models\\Project', 10, '{\"title\":\"gfhdfg\",\"description\":\"fgh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"fgncgv\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1586971651),
(97, 6, 'updated', 'app\\models\\Project', 10, '{\"title\":\"gfhdfg\",\"description\":\"fgh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"fgncgv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1586971657),
(98, 6, 'updated', 'app\\models\\Project', 10, '{\"title\":\"gfhdfg\",\"description\":\"fgh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"fgncgv\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":4}', 315, 1586971770),
(99, 6, 'created', 'app\\models\\Project', 11, '{\"title\":\"jgfjgh\",\"description\":\"cvbn\",\"co_hosts\":\"\",\"partners\":\"cvbn\",\"period\":\"cvbncb\",\"place\":\"cvbn\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1586971784),
(100, 6, 'updated', 'app\\models\\Project', 11, '{\"title\":\"jgfjgh\",\"description\":\"cvbn\",\"co_hosts\":\"\",\"partners\":\"cvbn\",\"period\":\"cvbncb\",\"place\":\"cvbn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1586971787),
(101, 6, 'updated', 'app\\models\\Project', 11, '{\"title\":\"jgfjgh\",\"description\":\"cvbn\",\"co_hosts\":\"\",\"partners\":\"cvbn\",\"period\":\"cvbncb\",\"place\":\"cvbn\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":4}', 315, 1586971789),
(102, 6, 'created', 'app\\models\\Project', 12, '{\"title\":\"fghdfg\",\"description\":\"cvbncvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"567356\",\"place\":\"fghdfgh\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1586971802),
(103, 6, 'updated', 'app\\models\\Project', 12, '{\"title\":\"fghdfg\",\"description\":\"cvbncvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"567356\",\"place\":\"fghdfgh\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1586971804),
(104, 6, 'updated', 'app\\models\\Project', 12, '{\"title\":\"fghdfg\",\"description\":\"cvbncvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"567356\",\"place\":\"fghdfgh\",\"wf_status\":\"ProjectWorkflow\\/uncertain\",\"organizational_unit_id\":4}', 315, 1586973006),
(105, 6, 'created', 'app\\models\\Project', 13, '{\"title\":\"sdgasdg\",\"description\":\"xcvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvb\",\"place\":\"cvbnxcbv\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1586973714),
(106, 6, 'updated', 'app\\models\\Project', 13, '{\"title\":\"sdgasdg\",\"description\":\"xcvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvb\",\"place\":\"cvbnxcbv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1586973734),
(107, 6, 'updated', 'app\\models\\Project', 13, '{\"title\":\"sdgasdg\",\"description\":\"xcvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvb\",\"place\":\"cvbnxcbv\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1586973737),
(108, 11, 'created', 'app\\models\\Project', 14, '{\"title\":\"Progetto Ancona Laica\",\"description\":\"xcvx\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sadfsadf\",\"organizational_unit_id\":\"7\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 317, 1586974501),
(109, 11, 'updated', 'app\\models\\Project', 14, '{\"title\":\"Progetto Ancona Laica\",\"description\":\"xcvx\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sadfsadf\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":7}', 317, 1586974506),
(110, 6, 'updated', 'app\\models\\Project', 4, '{\"title\":\"Presentazione libro\",\"description\":\"Prova 1 presentazione libro\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Pordenone, Biblioteca Civica\",\"wf_status\":\"ProjectWorkflow\\/uncertain\",\"organizational_unit_id\":6}', 323, 1587050776),
(111, 6, 'updated', 'app\\models\\Project', 6, '{\"title\":\"fghfgh\",\"description\":\"bvncb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbnbvn\",\"place\":\"vbcvbn\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6}', 323, 1587050971),
(112, 6, 'updated', 'app\\models\\Project', 7, '{\"title\":\"cbfsd\",\"description\":\"sdfghsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbncb\",\"place\":\"cbvcvbn\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6}', 323, 1587051078),
(113, 6, 'updated', 'app\\models\\Project', 14, '{\"title\":\"Progetto Ancona Laica\",\"description\":\"xcvx\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sadfsadf\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":7}', 323, 1587052009),
(114, 6, 'created', 'app\\models\\Project', 15, '{\"title\":\"dfsf\",\"description\":\"xcvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbzxcbv\",\"place\":\"cxvbxbv\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587130179),
(115, 6, 'updated', 'app\\models\\Project', 15, '{\"title\":\"dfsf\",\"description\":\"xcvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbzxcbv\",\"place\":\"cxvbxbv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587130182),
(116, 6, 'updated', 'app\\models\\Project', 15, '{\"title\":\"dfsf\",\"description\":\"xcvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbzxcbv\",\"place\":\"cxvbxbv\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587130186),
(117, 6, 'created', 'app\\models\\Project', 16, '{\"title\":\"dfgs\",\"description\":\"cvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbv\",\"place\":\"xcvb\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587130291),
(118, 6, 'updated', 'app\\models\\Project', 16, '{\"title\":\"dfgs\",\"description\":\"cvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbv\",\"place\":\"xcvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587130293),
(119, 6, 'updated', 'app\\models\\Project', 16, '{\"title\":\"dfgs\",\"description\":\"cvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbv\",\"place\":\"xcvb\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587130294),
(120, 6, 'updated', 'app\\models\\Project', 16, '{\"title\":\"dfgs\",\"description\":\"cvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbv\",\"place\":\"xcvb\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587131248),
(121, 6, 'created', 'app\\models\\Project', 17, '{\"title\":\"sdfgasdfg\",\"description\":\"zxvb\",\"co_hosts\":\"xcvb\",\"partners\":\"\",\"period\":\"cxvb\",\"place\":\"cvb\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587131259),
(122, 6, 'updated', 'app\\models\\Project', 17, '{\"title\":\"sdfgasdfg\",\"description\":\"zxvb\",\"co_hosts\":\"xcvb\",\"partners\":\"\",\"period\":\"cxvb\",\"place\":\"cvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587131262),
(123, 6, 'updated', 'app\\models\\Project', 17, '{\"title\":\"sdfgasdfg\",\"description\":\"zxvb\",\"co_hosts\":\"xcvb\",\"partners\":\"\",\"period\":\"cxvb\",\"place\":\"cvb\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587131265),
(124, 6, 'updated', 'app\\models\\Project', 17, '{\"title\":\"sdfgasdfg\",\"description\":\"zxvb\",\"co_hosts\":\"xcvb\",\"partners\":\"\",\"period\":\"cxvb\",\"place\":\"cvb\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587131267),
(125, 6, 'created', 'app\\models\\Project', 18, '{\"title\":\"prova1\",\"description\":\"asd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"1\",\"place\":\"1\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587131343),
(126, 6, 'updated', 'app\\models\\Project', 18, '{\"title\":\"prova1\",\"description\":\"asd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"1\",\"place\":\"1\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587131347),
(127, 6, 'updated', 'app\\models\\Project', 18, '{\"title\":\"prova1\",\"description\":\"asd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"1\",\"place\":\"1\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587131348),
(128, 6, 'updated', 'app\\models\\Project', 18, '{\"title\":\"prova1\",\"description\":\"asd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"1\",\"place\":\"1\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587131350),
(129, 6, 'created', 'app\\models\\Project', 19, '{\"title\":\"ghfgdh\",\"description\":\"fghdfg\",\"co_hosts\":\"bncvbn\",\"partners\":\"\",\"period\":\"cvbn\",\"place\":\"vbn\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587131704),
(130, 6, 'updated', 'app\\models\\Project', 19, '{\"title\":\"ghfgdh\",\"description\":\"fghdfg\",\"co_hosts\":\"bncvbn\",\"partners\":\"\",\"period\":\"cvbn\",\"place\":\"vbn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587132013),
(131, 6, 'updated', 'app\\models\\Project', 19, '{\"title\":\"ghfgdh\",\"description\":\"fghdfg\",\"co_hosts\":\"bncvbn\",\"partners\":\"\",\"period\":\"cvbn\",\"place\":\"vbn\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587132015),
(132, 6, 'created', 'app\\models\\Project', 20, '{\"title\":\"sfdgasfdg\",\"description\":\"vbncvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"456\",\"place\":\"45645\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587132052),
(133, 6, 'updated', 'app\\models\\Project', 20, '{\"title\":\"sfdgasfdg\",\"description\":\"vbncvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"456\",\"place\":\"45645\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587132056),
(134, 6, 'updated', 'app\\models\\Project', 20, '{\"title\":\"sfdgasfdg\",\"description\":\"vbncvb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"456\",\"place\":\"45645\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587132059),
(135, 6, 'created', 'app\\models\\Project', 21, '{\"title\":\"fghdfg\",\"description\":\"dfghfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"dfgh\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587141246),
(136, 6, 'updated', 'app\\models\\Project', 21, '{\"title\":\"fghdfg\",\"description\":\"dfghfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"dfgh\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587141247),
(137, 6, 'updated', 'app\\models\\Project', 21, '{\"title\":\"fghdfg\",\"description\":\"dfghfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"dfgh\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587141249),
(138, 6, 'updated', 'app\\models\\Project', 21, '{\"title\":\"fghdfg\",\"description\":\"dfghfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgh\",\"place\":\"dfgh\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587141250),
(139, 6, 'created', 'app\\models\\Project', 22, '{\"title\":\"sdfhgfd\",\"description\":\"zvcb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"zxcvbxcvb\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587141262),
(140, 6, 'updated', 'app\\models\\Project', 22, '{\"title\":\"sdfhgfd\",\"description\":\"zvcb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"zxcvbxcvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587141335),
(141, 6, 'updated', 'app\\models\\Project', 22, '{\"title\":\"sdfhgfd\",\"description\":\"zvcb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"zxcvbxcvb\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587141337),
(142, 6, 'updated', 'app\\models\\Project', 22, '{\"title\":\"sdfhgfd\",\"description\":\"zvcb\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"zxcvbxcvb\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587141338),
(143, 6, 'created', 'app\\models\\Project', 23, '{\"title\":\"dfgjhdhg\",\"description\":\"ghjdgh\",\"co_hosts\":\"ghj\",\"partners\":\"\",\"period\":\"fgj\",\"place\":\"fghj\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587141347),
(144, 6, 'updated', 'app\\models\\Project', 23, '{\"title\":\"dfgjhdhg\",\"description\":\"ghjdgh\",\"co_hosts\":\"ghj\",\"partners\":\"\",\"period\":\"fgj\",\"place\":\"fghj\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587141350),
(145, 6, 'updated', 'app\\models\\Project', 23, '{\"title\":\"dfgjhdhg\",\"description\":\"ghjdgh\",\"co_hosts\":\"ghj\",\"partners\":\"\",\"period\":\"fgj\",\"place\":\"fghj\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587141374),
(146, 6, 'updated', 'app\\models\\Project', 23, '{\"title\":\"dfgjhdhg\",\"description\":\"ghjdgh\",\"co_hosts\":\"ghj\",\"partners\":\"\",\"period\":\"fgj\",\"place\":\"fghj\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587141476),
(147, 6, 'created', 'app\\models\\Project', 24, '{\"title\":\"ghjdfjh\",\"description\":\"bnm vbn m\",\"co_hosts\":\"     nbm\",\"partners\":\"\",\"period\":\"jdghj\",\"place\":\"ghfjh\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587141493),
(148, 6, 'updated', 'app\\models\\Project', 24, '{\"title\":\"ghjdfjh\",\"description\":\"bnm vbn m\",\"co_hosts\":\"     nbm\",\"partners\":\"\",\"period\":\"jdghj\",\"place\":\"ghfjh\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587141495),
(149, 6, 'updated', 'app\\models\\Project', 24, '{\"title\":\"ghjdfjh\",\"description\":\"bnm vbn m\",\"co_hosts\":\"     nbm\",\"partners\":\"\",\"period\":\"jdghj\",\"place\":\"ghfjh\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587141524),
(150, 6, 'created', 'app\\models\\Project', 25, '{\"title\":\"Creazione progetto\",\"description\":\"sdvzvf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"zxcv\",\"place\":\"zxcvz\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587141902),
(151, 6, 'created', 'app\\models\\Project', 26, '{\"title\":\"fghdfgh\",\"description\":\"ghjdghj\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghdfg\",\"place\":\"cvbncvbn\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587151342),
(152, 6, 'updated', 'app\\models\\Project', 26, '{\"title\":\"fghdfgh\",\"description\":\"ghjdghj\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghdfg\",\"place\":\"cvbncvbn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587151419),
(153, 6, 'updated', 'app\\models\\Project', 26, '{\"title\":\"fghdfgh\",\"description\":\"ghjdghj\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghdfg\",\"place\":\"cvbncvbn\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587151424),
(154, 6, 'updated', 'app\\models\\Project', 26, '{\"title\":\"fghdfgh\",\"description\":\"ghjdghj\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghdfg\",\"place\":\"cvbncvbn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587151428),
(155, 6, 'created', 'app\\models\\Project', 27, '{\"title\":\"fghdfgh\",\"description\":\"fghfg\",\"co_hosts\":\"dfgh\",\"partners\":\"dfgh\",\"period\":\"fghdfgh\",\"place\":\"fgh\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587151673),
(156, 6, 'updated', 'app\\models\\Project', 27, '{\"title\":\"fghdfgh\",\"description\":\"fghfg\",\"co_hosts\":\"dfgh\",\"partners\":\"dfgh\",\"period\":\"fghdfgh\",\"place\":\"fgh\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587151675),
(157, 6, 'ProjectWorkflow/submitted-ProjectWorkflow/approved', 'app\\models\\Project', 27, '{\"title\":\"fghdfgh\",\"description\":\"fghfg\",\"co_hosts\":\"dfgh\",\"partners\":\"dfgh\",\"period\":\"fghdfgh\",\"place\":\"fgh\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587151982),
(158, 6, 'updated', 'app\\models\\Project', 27, '{\"title\":\"fghdfgh\",\"description\":\"fghfg\",\"co_hosts\":\"dfgh\",\"partners\":\"dfgh\",\"period\":\"fghdfgh\",\"place\":\"fgh\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587151982),
(159, 6, 'created', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"organizational_unit_id\":\"1\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587152067),
(160, 6, 'ProjectWorkflow/draft-ProjectWorkflow/submitted', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":1}', 315, 1587152071),
(161, 6, 'updated', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":1}', 315, 1587152071),
(162, 6, 'ProjectWorkflow/submitted-ProjectWorkflow/approved', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":1}', 315, 1587152073),
(163, 6, 'updated', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":1}', 315, 1587152073),
(164, 6, 'ProjectWorkflow/approved-ProjectWorkflow/deleted', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":1}', 315, 1587152076),
(165, 6, 'updated', 'app\\models\\Project', 28, '{\"title\":\"uno\",\"description\":\"due\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":1}', 315, 1587152076),
(166, 6, 'created', 'app\\models\\Project', 30, '{\"title\":\"sdfgsdfg\",\"description\":\"dbxcbv\",\"co_hosts\":\"xcvbxcbv\",\"partners\":\"xcvb\",\"period\":\"xcvbn\",\"place\":\"cvbncvb\",\"organizational_unit_id\":\"1\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587152318),
(167, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 30, '{\"title\":\"sdfgsdfg\",\"description\":\"dbxcbv\",\"co_hosts\":\"xcvbxcbv\",\"partners\":\"xcvb\",\"period\":\"xcvbn\",\"place\":\"cvbncvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":1}', 315, 1587152321),
(168, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 29, '{\"title\":\"sdfgsdfgdfgsfg\",\"description\":\"dbxcbv\",\"co_hosts\":\"xcvbxcbv\",\"partners\":\"xcvb\",\"period\":\"xcvbn\",\"place\":\"cvbncvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":1}', 315, 1587152352),
(169, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 29, '{\"title\":\"sdfgsdfgdfgsfg\",\"description\":\"dbxcbv\",\"co_hosts\":\"xcvbxcbv\",\"partners\":\"xcvb\",\"period\":\"xcvbn\",\"place\":\"cvbncvb\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":1}', 315, 1587152356),
(170, 6, 'created', 'app\\models\\Project', 31, '{\"title\":\"fghdfg\",\"description\":\"dfghdfgh\",\"co_hosts\":\"dfghdfgh\",\"partners\":\"dfgh\",\"period\":\"bvnmcbvn\",\"place\":\"cvbncvbn\",\"organizational_unit_id\":\"1\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587154785),
(171, 6, 'created', 'app\\models\\Project', 32, '{\"title\":\"sdvbbxcvbxcghdghn er s dh\",\"description\":\"dgsdfhgsdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"436346t\",\"place\":\"ghdfgh\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587194329),
(172, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 32, '{\"title\":\"sdvbbxcvbxcghdghn er s dh\",\"description\":\"dgsdfhgsdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"436346t\",\"place\":\"ghdfgh\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587194331),
(173, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 32, '{\"title\":\"sdvbbxcvbxcghdghn er s dh\",\"description\":\"dgsdfhgsdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"436346t\",\"place\":\"ghdfgh\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587194442),
(174, 4, 'created', 'app\\models\\Project', 33, '{\"title\":\"hsdfghd\",\"description\":\"fdgh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghjfgh\",\"place\":\"gfhjfgj\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587197728),
(175, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 33, '{\"title\":\"hsdfghd\",\"description\":\"fdgh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghjfgh\",\"place\":\"gfhjfgj\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587197731),
(176, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 8, '{\"title\":\"sdgsdafg\",\"description\":\"cvbxcbv\",\"co_hosts\":\"x\",\"partners\":\"\",\"period\":\"cvbx\",\"place\":\"xcvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587197776),
(177, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 33, '{\"title\":\"hsdfghd\",\"description\":\"fdgh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fghjfgh\",\"place\":\"gfhjfgj\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587197833),
(178, 6, 'created', 'app\\models\\Project', 34, '{\"title\":\"ultimo\",\"description\":\"dfgdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"u\",\"place\":\"u\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587198025),
(179, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 34, '{\"title\":\"ultimo\",\"description\":\"dfgdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"u\",\"place\":\"u\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587198028),
(180, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 34, '{\"title\":\"ultimo\",\"description\":\"dfgdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"u\",\"place\":\"u\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587198032),
(181, 6, 'ProjectWorkflow/deleted', 'app\\models\\Project', 34, '{\"title\":\"ultimo\",\"description\":\"dfgdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"u\",\"place\":\"u\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":4}', 315, 1587198034),
(182, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 35, '{\"title\":\"microfono\",\"description\":\"dd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587198174),
(183, 4, 'created', 'app\\models\\Project', 36, '{\"title\":\"mouse\",\"description\":\"xcvxzc\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvzxc\",\"place\":\"xcv\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587198280),
(184, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 36, '{\"title\":\"mouse\",\"description\":\"xcvxzc\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvzxc\",\"place\":\"xcv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587198281),
(185, 4, 'created', 'app\\models\\Project', 37, '{\"title\":\"con spese?\",\"description\":\"ss\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"lss\",\"place\":\"ss\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587204068),
(186, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 37, '{\"title\":\"con spese?\",\"description\":\"ss\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"lss\",\"place\":\"ss\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587204072),
(187, 4, 'created', 'app\\models\\Project', 38, '{\"title\":\"sdfsdf\",\"description\":\"sdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvbxcv\",\"place\":\"xcvbxc\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587204334),
(188, 4, 'created', 'app\\models\\Authorization', 324, '{\"begin_date\":\"2020-04-18 10:25:17\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":3}', 256, 1587205517),
(189, 4, 'created', 'app\\models\\Authorization', 325, '{\"begin_date\":\"2020-04-18 10:25:17\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":3}', 256, 1587205517),
(190, 4, 'created', 'app\\models\\Authorization', 326, '{\"begin_date\":\"2020-04-18 10:25:17\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"role_id\":3}', 256, 1587205517),
(191, 4, 'created', 'app\\models\\Authorization', 327, '{\"begin_date\":\"2020-04-18 10:25:17\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"role_id\":3}', 256, 1587205517),
(192, 4, 'updated', 'app\\models\\Role', 3, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"coordinatore_circolo\",\"description\":\"Coordinatore di Circolo\",\"permissions\":\"project-submissions, planned-expenses\",\"email\":\"\"}', 256, 1587205517),
(193, 4, 'created', 'app\\models\\Authorization', 328, '{\"begin_date\":\"2020-04-18 10:25:48\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":5,\"role_id\":5}', 256, 1587205548),
(194, 4, 'created', 'app\\models\\Authorization', 329, '{\"begin_date\":\"2020-04-18 10:25:48\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":16,\"role_id\":5}', 256, 1587205548),
(195, 4, 'updated', 'app\\models\\Role', 5, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"cassiere_circolo\",\"description\":\"Cassiere di Circolo\",\"permissions\":\"project-submissions, planned-expenses\",\"email\":\"\"}', 256, 1587205548),
(196, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 38, '{\"title\":\"Progetto con spese\",\"description\":\"sdfss\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcvbxcv\",\"place\":\"xcvbxc\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587209202),
(197, 4, 'created', 'app\\models\\Project', 39, '{\"title\":\"Presentazione libro \\\"Winnie the Pooh\\\"\",\"description\":\"Winnie the Pooh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Pordenone, Biblioteca Civica\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587219958),
(198, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 39, '{\"title\":\"Presentazione libro \\\"Winnie the Pooh\\\"\",\"description\":\"Winnie the Pooh\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Pordenone, Biblioteca Civica\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587220704),
(199, 4, 'created', 'app\\models\\Project', 40, '{\"title\":\"darw\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dd\",\"place\":\"dd\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587228345),
(200, 4, 'created', 'app\\models\\Project', 41, '{\"title\":\"Presentazione libro\",\"description\":\"Des1\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Biblioteca\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587229502),
(201, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 41, '{\"title\":\"Presentazione libro\",\"description\":\"Des1\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Biblioteca\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6}', 314, 1587230951),
(202, 6, 'created', 'app\\models\\Project', 42, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"d\",\"place\":\"d\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587236803),
(203, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 42, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"d\",\"place\":\"d\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4}', 315, 1587236981),
(204, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 42, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"d\",\"place\":\"d\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":4}', 315, 1587237424),
(205, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 41, '{\"title\":\"Presentazione libro\",\"description\":\"Des1\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Biblioteca\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":10,\"project_id\":41,\"expense_type_id\":1,\"description\":\"Manifesti\",\"amount\":\"60.00\",\"notes\":\"kkkdd\"}]}}', 323, 1587238120),
(206, 4, 'created', 'app\\models\\Project', 43, '{\"title\":\"salvo\",\"description\":\"dd\",\"co_hosts\":\"ddd\",\"partners\":\"dd\",\"period\":\"dfgh\",\"place\":\"dddd\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587238337),
(207, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 43, '{\"title\":\"salvo\",\"description\":\"dd\",\"co_hosts\":\"ddd\",\"partners\":\"dd\",\"period\":\"dfgh\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":12,\"project_id\":43,\"expense_type_id\":1,\"description\":\"Manifesti\",\"amount\":\"567.00\",\"notes\":\"\"}]}}', 314, 1587238366),
(208, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 43, '{\"title\":\"salvo\",\"description\":\"dd\",\"co_hosts\":\"ddd\",\"partners\":\"dd\",\"period\":\"dfgh\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587238386),
(209, 6, 'created', 'app\\models\\Project', 44, '{\"title\":\"spese da modificare\",\"description\":\"dd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dd\",\"place\":\"dd\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587286279),
(210, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 44, '{\"title\":\"spese da modificare\",\"description\":\"dd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dd\",\"place\":\"dd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4,\"related\":{\"plannedExpenses\":[{\"id\":13,\"project_id\":44,\"expense_type_id\":2,\"description\":\"asdasd\",\"amount\":\"5.00\",\"notes\":\"\"}]}}', 315, 1587286382),
(211, 6, 'created', 'app\\models\\Project', 45, '{\"title\":\"fghdfgh\",\"description\":\"cvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbncvbn\",\"place\":\"cvbncvbn\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587286908),
(212, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 45, '{\"title\":\"fghdfgh\",\"description\":\"cvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbncvbn\",\"place\":\"cvbncvbn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":4,\"related\":{\"plannedExpenses\":[{\"id\":14,\"project_id\":45,\"expense_type_id\":5,\"description\":\"hdfghfgh\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 315, 1587286944);
INSERT INTO `activities` (`id`, `user_id`, `activity_type`, `model`, `model_id`, `info`, `authorization_id`, `happened_at`) VALUES
(213, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 45, '{\"title\":\"fghdfgh\",\"description\":\"cvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbncvbn\",\"place\":\"cvbncvbn\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":4}', 315, 1587286984),
(214, 4, 'created', 'app\\models\\Authorization', 330, '{\"controller_id\":\"notifications\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"@\",\"user_id\":\"\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2099-12-31 00:00\",\"role_id\":\"\"}', 257, 1587289886),
(215, 6, 'created', 'app\\models\\Project', 46, '{\"title\":\"nuovo in bozza\",\"description\":\"sdfgsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"cvbcvb\",\"place\":\"cvnvbncvbn\",\"organizational_unit_id\":\"4\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 315, 1587293477),
(216, 6, 'ProjectWorkflow/deleted', 'app\\models\\Project', 41, '{\"title\":\"Presentazione libro\",\"description\":\"Des1\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"Biblioteca\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 323, 1587293601),
(217, 6, 'ProjectWorkflow/deleted', 'app\\models\\Project', 43, '{\"title\":\"salvo\",\"description\":\"dd\",\"co_hosts\":\"ddd\",\"partners\":\"dd\",\"period\":\"dfgh\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 323, 1587293828),
(218, 4, 'created', 'app\\models\\Project', 47, '{\"title\":\"ddd\",\"description\":\"dddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587293892),
(219, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 47, '{\"title\":\"ddd\",\"description\":\"dddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":15,\"project_id\":47,\"expense_type_id\":1,\"description\":\"ddd\",\"amount\":\"45.00\",\"notes\":\"\"}]}}', 314, 1587293905),
(220, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 47, '{\"title\":\"ddd\",\"description\":\"dddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587293921),
(221, 6, 'ProjectWorkflow/deleted', 'app\\models\\Project', 47, '{\"title\":\"ddd\",\"description\":\"dddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 323, 1587293926),
(222, 4, 'created', 'app\\models\\Project', 48, '{\"title\":\"sdfsdf\",\"description\":\"xc\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbzxcb\",\"place\":\"zxcbv\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587296468),
(223, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 48, '{\"title\":\"sdfsdf\",\"description\":\"xc\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbzxcb\",\"place\":\"zxcbv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":16,\"project_id\":48,\"expense_type_id\":2,\"description\":\"zxczxc\",\"amount\":\"45.00\",\"notes\":\"xczxcv\"}]}}', 314, 1587296486),
(224, 4, 'created', 'app\\models\\Project', 49, '{\"title\":\"bnmvbnmvbnm\",\"description\":\"vbnm\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fgjghj\",\"place\":\"ghjghj\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587300172),
(225, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 49, '{\"title\":\"bnmvbnmvbnm\",\"description\":\"vbnm\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fgjghj\",\"place\":\"ghjghj\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":17,\"project_id\":49,\"expense_type_id\":4,\"description\":\"bnmvbnm\",\"amount\":\"78.00\",\"notes\":\"cjfghj\"}]}}', 314, 1587300193),
(226, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 48, '{\"title\":\"cambiato\",\"description\":\"xc\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"xcbzxcb\",\"place\":\"zxcbvdd\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587300604),
(227, 4, 'created', 'app\\models\\Project', 50, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587308319),
(228, 4, 'created', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587308361),
(229, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":18,\"project_id\":51,\"expense_type_id\":1,\"description\":\"fdgdfg\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587308409),
(230, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":18,\"project_id\":51,\"expense_type_id\":1,\"description\":\"fdgdfg\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587308448),
(231, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":18,\"project_id\":51,\"expense_type_id\":1,\"description\":\"fdgdfg\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587308493),
(232, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":18,\"project_id\":51,\"expense_type_id\":1,\"description\":\"fdgdfg\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587308623),
(233, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":18,\"project_id\":51,\"expense_type_id\":1,\"description\":\"fdgdfg\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587308646),
(234, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 50, '{\"title\":\"Microfono\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":19,\"project_id\":50,\"expense_type_id\":2,\"description\":\"Manifesti\",\"amount\":\"4.00\",\"notes\":\"\"}]}}', 314, 1587308741),
(235, 4, 'created', 'app\\models\\Project', 52, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"ddd\",\"partners\":\"\",\"period\":\"dddd\",\"place\":\"dddd\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587309575),
(236, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 52, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"ddd\",\"partners\":\"\",\"period\":\"dddd\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":20,\"project_id\":52,\"expense_type_id\":2,\"description\":\"ddd\",\"amount\":\"56.00\",\"notes\":\"\"}]}}', 314, 1587309586),
(237, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 52, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"ddd\",\"partners\":\"\",\"period\":\"dddd\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":20,\"project_id\":52,\"expense_type_id\":2,\"description\":\"ddd\",\"amount\":\"56.00\",\"notes\":\"\"}]}}', 314, 1587309611),
(238, 4, 'created', 'app\\models\\Project', 53, '{\"title\":\"solopn\",\"description\":\"vediamo\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"v1\",\"place\":\"v1\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587310341),
(239, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 53, '{\"title\":\"solopn\",\"description\":\"vediamo\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"v1\",\"place\":\"v1\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":21,\"project_id\":53,\"expense_type_id\":1,\"description\":\"dd\",\"amount\":\"45.00\",\"notes\":\"\"}]}}', 314, 1587310356),
(240, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 53, '{\"title\":\"solopn\",\"description\":\"vediamo\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"v1\",\"place\":\"v1\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":21,\"project_id\":53,\"expense_type_id\":1,\"description\":\"dd\",\"amount\":\"45.00\",\"notes\":\"\"}]}}', 314, 1587310404),
(241, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 53, '{\"title\":\"solopn\",\"description\":\"vediamo\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"v1\",\"place\":\"v1\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":21,\"project_id\":53,\"expense_type_id\":1,\"description\":\"dd\",\"amount\":\"45.00\",\"notes\":\"\"}]}}', 314, 1587310492),
(242, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 53, '{\"title\":\"solopn\",\"description\":\"vediamo\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"v1\",\"place\":\"v1\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587310935),
(243, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 52, '{\"title\":\"ddd\",\"description\":\"ddd\",\"co_hosts\":\"ddd\",\"partners\":\"\",\"period\":\"dddd\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6}', 323, 1587310989),
(244, 4, 'created', 'app\\models\\Project', 54, '{\"title\":\"dfgsdfg\",\"description\":\"sdfgsdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfsdfhgs\",\"place\":\"cvbxcvb\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587313472),
(245, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 54, '{\"title\":\"dfgsdfg\",\"description\":\"sdfgsdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfsdfhgs\",\"place\":\"cvbxcvb\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":22,\"project_id\":54,\"expense_type_id\":5,\"description\":\"fdghsfgdh\",\"amount\":\"78.00\",\"notes\":\"vcnmcvbn\"}]}}', 314, 1587313490),
(246, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 54, '{\"title\":\"dfgsdfg\",\"description\":\"sdfgsdfg\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfsdfhgs\",\"place\":\"cvbxcvb\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587313504),
(247, 11, 'created', 'app\\models\\Project', 55, '{\"title\":\"Presentazione libro\",\"description\":\"sdsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Ancona\",\"organizational_unit_id\":\"7\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 317, 1587313551),
(248, 11, 'ProjectWorkflow/submitted', 'app\\models\\Project', 55, '{\"title\":\"Presentazione libro\",\"description\":\"sdsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Ancona\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":7,\"related\":{\"plannedExpenses\":[{\"id\":23,\"project_id\":55,\"expense_type_id\":4,\"description\":\"Manifesti\",\"amount\":\"100.00\",\"notes\":\"\"}]}}', 317, 1587313570),
(249, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 55, '{\"title\":\"Presentazione libro\",\"description\":\"sdsd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Ancona\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":7}', 323, 1587314645),
(250, 4, 'created', 'app\\models\\Authorization', 331, '{\"begin_date\":\"2020-04-19 16:46:42\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":2}', 256, 1587314802),
(251, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587314949),
(252, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315397),
(253, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315461),
(254, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315542),
(255, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315554),
(256, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315626),
(257, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315646),
(258, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315843),
(259, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315864),
(260, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315886),
(261, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315909),
(262, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587315923),
(263, 4, 'updated', 'app\\models\\Role', 2, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"tesoriere\",\"description\":\"Tesoriere nazionale\",\"permissions\":\"projects-management, project-comments\",\"email\":\"tesoriere@o.example.com\"}', 256, 1587316050),
(264, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 50, '{\"title\":\"Microfono\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6,\"related\":{\"projectComments\":[{\"id\":1,\"project_id\":50,\"user_id\":6,\"comment\":\"No buono\",\"created_at\":1587316451,\"updated_at\":1587316898}]}}', 323, 1587316969),
(265, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 50, '{\"title\":\"Microfono\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6,\"related\":{\"projectComments\":[{\"id\":1,\"project_id\":50,\"user_id\":6,\"comment\":\"No buono\",\"created_at\":1587316451,\"updated_at\":1587316898}]}}', 323, 1587317022),
(266, 4, 'created', 'app\\models\\Authorization', 332, '{\"begin_date\":\"2020-04-19 17:28:36\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-management\",\"action_id\":\"index\",\"method\":\"*\",\"type\":\"-\",\"user_id\":7,\"role_id\":1}', 256, 1587317316),
(267, 4, 'created', 'app\\models\\Authorization', 333, '{\"begin_date\":\"2020-04-19 17:28:36\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"projects-management\",\"action_id\":\"view\",\"method\":\"*\",\"type\":\"-\",\"user_id\":7,\"role_id\":1}', 256, 1587317316),
(268, 4, 'created', 'app\\models\\Authorization', 334, '{\"begin_date\":\"2020-04-19 17:28:36\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":7,\"role_id\":1}', 256, 1587317316),
(269, 4, 'updated', 'app\\models\\Role', 1, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"segretario4655\",\"description\":\"Segretario nazionale\",\"permissions\":\"users\\/index, users\\/bulk\\/fixPermissions, projects-management\\/index,projects-management\\/view, project-comments\",\"email\":\"segretario@o.example.com\"}', 256, 1587317316),
(270, 4, 'created', 'app\\models\\Authorization', 335, '{\"begin_date\":\"2020-04-19 17:34:02\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":3}', 256, 1587317642),
(271, 4, 'created', 'app\\models\\Authorization', 336, '{\"begin_date\":\"2020-04-19 17:34:02\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":3}', 256, 1587317642),
(272, 4, 'created', 'app\\models\\Authorization', 337, '{\"begin_date\":\"2020-04-19 17:34:02\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"role_id\":3}', 256, 1587317642),
(273, 4, 'created', 'app\\models\\Authorization', 338, '{\"begin_date\":\"2020-04-19 17:34:02\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"role_id\":3}', 256, 1587317642),
(274, 4, 'updated', 'app\\models\\Role', 3, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"coordinatore_circolo\",\"description\":\"Coordinatore di Circolo\",\"permissions\":\"project-submissions, planned-expenses, project-comments\",\"email\":\"\"}', 256, 1587317642),
(275, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 51, '{\"title\":\"un progetto carino\",\"description\":\"ciao\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6,\"related\":{\"projectComments\":[{\"id\":2,\"project_id\":51,\"user_id\":6,\"comment\":\"Vorrei un chiarimento su una cosa\",\"created_at\":1587317203,\"updated_at\":1587317203},{\"id\":3,\"project_id\":51,\"user_id\":7,\"comment\":\"domanda di roberto\",\"created_at\":1587317515,\"updated_at\":1587317515},{\"id\":4,\"project_id\":51,\"user_id\":4,\"comment\":\"Tutto ok bro\",\"created_at\":1587317662,\"updated_at\":1587317730}]}}', 323, 1587317821),
(276, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 49, '{\"title\":\"bnmvbnmvbnm\",\"description\":\"vbnm\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fgjghj\",\"place\":\"ghjghj\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587399257),
(277, 4, 'created', 'app\\models\\Project', 56, '{\"title\":\"Telecomando\",\"description\":\"d\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"dddd\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587400362),
(278, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 56, '{\"title\":\"Telecomando\",\"description\":\"d\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":24,\"project_id\":56,\"expense_type_id\":2,\"description\":\"cxvbxcb\",\"amount\":\"67.00\",\"notes\":\"\"},{\"id\":25,\"project_id\":56,\"expense_type_id\":2,\"description\":\"Manifesti\",\"amount\":\"900.00\",\"notes\":\"\"}]}}', 314, 1587400437),
(279, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 56, '{\"title\":\"Telecomando\",\"description\":\"d\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"settembre 2020\",\"place\":\"dddd\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6,\"related\":{\"projectComments\":[{\"id\":9,\"project_id\":56,\"user_id\":4,\"comment\":\"Mi sono dimenticato di dire che...\",\"created_at\":1587400511,\"updated_at\":1587400511}]}}', 323, 1587400739),
(280, 4, 'created', 'app\\models\\Project', 57, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587400874),
(281, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 57, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":26,\"project_id\":57,\"expense_type_id\":2,\"description\":\"xcxc\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587400889),
(282, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 57, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":6,\"related\":{\"projectComments\":[{\"id\":10,\"project_id\":57,\"user_id\":4,\"comment\":\"sadasd\",\"created_at\":1587400896,\"updated_at\":1587400896},{\"id\":11,\"project_id\":57,\"user_id\":4,\"comment\":\"asdasdasd\",\"created_at\":1587400901,\"updated_at\":1587400901},{\"id\":12,\"project_id\":57,\"user_id\":6,\"comment\":\"Troppi soldi! lll\",\"created_at\":1587400935,\"updated_at\":1587400946}]}}', 323, 1587400952),
(283, 4, 'created', 'app\\models\\Project', 58, '{\"title\":\"Matematico napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587401063),
(284, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 58, '{\"title\":\"Matematico napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":27,\"project_id\":58,\"expense_type_id\":3,\"description\":\"xfgnxcn\",\"amount\":\"565.00\",\"notes\":\"\"}]}}', 314, 1587401078),
(285, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 58, '{\"title\":\"Matematico napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":6}', 323, 1587401097),
(286, 4, 'ProjectWorkflow/draft', 'app\\models\\Project', 58, '{\"title\":\"Matematico napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587401530),
(287, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":27,\"project_id\":58,\"expense_type_id\":3,\"description\":\"xfgnxcn\",\"amount\":\"565.00\",\"notes\":\"\"}]}}', 314, 1587401549),
(288, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":6}', 323, 1587401590),
(289, 6, 'ProjectWorkflow/submitted', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":27,\"project_id\":58,\"expense_type_id\":3,\"description\":\"xfgnxcn\",\"amount\":\"565.00\",\"notes\":\"\"}]}}', 323, 1587401672),
(290, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":6}', 323, 1587401688),
(291, 4, 'ProjectWorkflow/draft', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587402562),
(292, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":27,\"project_id\":58,\"expense_type_id\":3,\"description\":\"xfgnxcn\",\"amount\":\"565.00\",\"notes\":\"\"}]}}', 314, 1587402565),
(293, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":6}', 323, 1587402603),
(294, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":27,\"project_id\":58,\"expense_type_id\":3,\"description\":\"xfgnxcn\",\"amount\":\"565.00\",\"notes\":\"\"}]}}', 314, 1587404004),
(295, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587404365),
(296, 6, 'ProjectWorkflow/suspended', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/suspended\",\"organizational_unit_id\":6}', 323, 1587404371),
(297, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587404376),
(298, 4, 'ProjectWorkflow/ended', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/ended\",\"organizational_unit_id\":6}', 314, 1587404519),
(299, 6, 'ProjectWorkflow/reimbursed', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/reimbursed\",\"organizational_unit_id\":6}', 323, 1587404566),
(300, 6, 'ProjectWorkflow/archived', 'app\\models\\Project', 58, '{\"title\":\"Matematico Napoletano\",\"description\":\"xcbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"yrty\",\"place\":\"cxvbnxcv\",\"wf_status\":\"ProjectWorkflow\\/archived\",\"organizational_unit_id\":6}', 323, 1587404631),
(301, 4, 'created', 'app\\models\\Project', 59, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587407404),
(302, 4, 'created', 'app\\models\\Project', 60, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587407491),
(303, 4, 'created', 'app\\models\\Project', 61, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587407542),
(304, 4, 'created', 'app\\models\\Project', 62, '{\"title\":\"Telefono - (Copy)\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587407599),
(305, 4, 'created', 'app\\models\\Project', 63, '{\"title\":\"Telefono - (Copy) - (Copy)\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587407632),
(306, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 63, '{\"title\":\"Telefono - (Copy) - (Copy)\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587407688),
(307, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 62, '{\"title\":\"Telefono - (Copy)\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587407698),
(308, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 61, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587407707),
(309, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 60, '{\"title\":\"Telefono\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587407713),
(310, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 59, '{\"title\":\"Telefono2\",\"description\":\"dfgdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"dfgd\",\"place\":\"xcnxcn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587407720),
(311, 4, 'ProjectWorkflow/ended', 'app\\models\\Project', 49, '{\"title\":\"bnmvbnmvbnm\",\"description\":\"vbnm\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"fgjghj\",\"place\":\"ghjghj\",\"wf_status\":\"ProjectWorkflow\\/ended\",\"organizational_unit_id\":6}', 314, 1587407733),
(312, 4, 'created', 'app\\models\\User', 18, '{\"username\":\"ika\",\"first_name\":\"Ika\",\"last_name\":\"P\",\"email\":\"ika@example.com\",\"status\":\"1\"}', 255, 1587469442),
(313, 4, 'created', 'app\\models\\OrganizationalUnit', 10, '{\"rank\":\"11\",\"status\":\"1\",\"name\":\"Circolo di Poznan\",\"email\":\"poznan@o.example.com\",\"url\":\"\"}', 258, 1587469471),
(314, 4, 'created', 'app\\models\\Affiliation', 19, '{\"user_id\":\"18\",\"organizational_unit_id\":\"10\",\"role_id\":\"3\",\"rank\":1}', 259, 1587469492),
(315, 4, 'created', 'app\\models\\Authorization', 339, '{\"begin_date\":\"2020-04-21 11:47:38\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":18,\"role_id\":3}', 255, 1587469658),
(316, 4, 'created', 'app\\models\\Authorization', 340, '{\"begin_date\":\"2020-04-21 11:47:38\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"planned-expenses\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":18,\"role_id\":3}', 255, 1587469658),
(317, 4, 'created', 'app\\models\\Authorization', 341, '{\"begin_date\":\"2020-04-21 11:47:38\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"project-comments\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":18,\"role_id\":3}', 255, 1587469658),
(318, 18, 'created', 'app\\models\\Project', 64, '{\"title\":\"Conferenza\",\"description\":\"Conferenza incontro con una scrittrice polacca Olga Tokarczuk\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Pordenone\",\"period\":\"maggio 2020\",\"place\":\"Pordenone\",\"organizational_unit_id\":10,\"wf_status\":\"ProjectWorkflow\\/draft\"}', 339, 1587470366),
(319, 18, 'ProjectWorkflow/submitted', 'app\\models\\Project', 64, '{\"title\":\"Conferenza\",\"description\":\"Conferenza incontro con una scrittrice polacca Olga Tokarczuk\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Pordenone\",\"period\":\"maggio 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":10,\"related\":{\"plannedExpenses\":[{\"id\":34,\"project_id\":64,\"expense_type_id\":4,\"description\":\"2 notti presso Hotel Santin\",\"amount\":\"300.00\",\"notes\":\"\"}]}}', 339, 1587470606),
(320, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 64, '{\"title\":\"Conferenza\",\"description\":\"Conferenza incontro con una scrittrice polacca Olga Tokarczuk\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Pordenone\",\"period\":\"maggio 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":10}', 323, 1587470717),
(321, 18, 'created', 'app\\models\\Project', 65, '{\"title\":\"Conferenza - (Copy)\",\"description\":\"Conferenza incontro con una scrittrice polacca Olga Tokarczuk\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Pordenone\",\"period\":\"maggio 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":10}', 339, 1587470827),
(322, 18, 'ProjectWorkflow/submitted', 'app\\models\\Project', 65, '{\"title\":\"Conferenza - (Copy)\",\"description\":\"Conferenza incontro con una scrittrice polacca Wislawa Szymborska\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Sacile\",\"period\":\"settembre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":10,\"related\":{\"plannedExpenses\":[{\"id\":35,\"project_id\":65,\"expense_type_id\":4,\"description\":\"2 notti presso Hotel Santin\",\"amount\":\"300.00\",\"notes\":\"\"}]}}', 339, 1587470905),
(323, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 65, '{\"title\":\"Conferenza - (Copy)\",\"description\":\"Conferenza incontro con una scrittrice polacca Wislawa Szymborska\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Sacile\",\"period\":\"settembre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":10}', 323, 1587470948),
(324, 18, 'ProjectWorkflow/submitted', 'app\\models\\Project', 65, '{\"title\":\"Conferenza - (Copy)\",\"description\":\"Conferenza incontro con una scrittrice polacca Wislawa Szymborska\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Sacile\",\"period\":\"settembre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":10,\"related\":{\"plannedExpenses\":[{\"id\":35,\"project_id\":65,\"expense_type_id\":4,\"description\":\"2 notti presso Hotel Santin\",\"amount\":\"300.00\",\"notes\":\"\"}]}}', 339, 1587471059),
(325, 6, 'ProjectWorkflow/rejected', 'app\\models\\Project', 65, '{\"title\":\"Conferenza - (Copy)\",\"description\":\"Conferenza incontro con una scrittrice polacca Wislawa Szymborska\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Sacile\",\"period\":\"settembre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/rejected\",\"organizational_unit_id\":10,\"related\":{\"projectComments\":[{\"id\":17,\"project_id\":65,\"user_id\":6,\"comment\":\"Non mi \\u00e8 chiaro perch\\u00e9 servono cos\\u00ec tanto soldi. \",\"created_at\":1587470946,\"updated_at\":1587470946},{\"id\":18,\"project_id\":65,\"user_id\":18,\"comment\":\"Il prezzo di pernottamento \\u00e8 quello\",\"created_at\":1587471051,\"updated_at\":1587471051},{\"id\":19,\"project_id\":65,\"user_id\":6,\"comment\":\"Non mi convince\",\"created_at\":1587471143,\"updated_at\":1587471143}]}}', 323, 1587471148),
(326, 18, 'created', 'app\\models\\Project', 66, '{\"title\":\"Conferenza - (Copy) - (Copy)\",\"description\":\"Conferenza incontro con una scrittrice polacca Wislawa Szymborska\",\"co_hosts\":\"Chiara Sartori, ARCI\",\"partners\":\"Comune di Sacile\",\"period\":\"settembre 2020\",\"place\":\"Pordenone\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":10}', 339, 1587471221),
(327, 4, 'updated', 'app\\models\\Authorization', 259, '{\"controller_id\":\"affiliations\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":\"4\",\"begin_date\":\"2020-03-01 00:00:00\",\"end_date\":\"2099-12-31 00:00:00\",\"role_id\":\"8\"}', 257, 1587471852),
(328, 4, 'created', 'app\\models\\Project', 67, '{\"title\":\"Presentazione libro\",\"description\":\"sdfasdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sdf\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587490494),
(329, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 67, '{\"title\":\"Presentazione libro\",\"description\":\"sdfasdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sdf\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":37,\"project_id\":67,\"expense_type_id\":2,\"description\":\"sdfsdf\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587490517),
(330, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 67, '{\"title\":\"Presentazione libro\",\"description\":\"sdfasdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sdf\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":6}', 323, 1587490541),
(331, 4, 'ProjectWorkflow/draft', 'app\\models\\Project', 67, '{\"title\":\"Presentazione libro\",\"description\":\"sdfasdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sdf\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587492679),
(332, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 67, '{\"title\":\"Presentazione libro\",\"description\":\"sdfasdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"sdf\",\"place\":\"sdf\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587492682),
(333, 4, 'created', 'app\\models\\Project', 68, '{\"title\":\"Presentazione libro\",\"description\":\"vbnxvb\",\"co_hosts\":\"\",\"partners\":\"v\",\"period\":\"cvbn\",\"place\":\"vcbn\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587492917),
(334, 4, 'ProjectWorkflow/deleted', 'app\\models\\Project', 68, '{\"title\":\"Presentazione libro\",\"description\":\"vbnxvb\",\"co_hosts\":\"\",\"partners\":\"v\",\"period\":\"cvbn\",\"place\":\"vcbn\",\"wf_status\":\"ProjectWorkflow\\/deleted\",\"organizational_unit_id\":6}', 314, 1587493512),
(335, 4, 'created', 'app\\models\\Project', 69, '{\"title\":\"dd\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587493529),
(336, 4, 'created', 'app\\models\\Project', 70, '{\"title\":\"dd - (Copy)\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587493940),
(337, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 70, '{\"title\":\"sdsdgf\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":39,\"project_id\":70,\"expense_type_id\":2,\"description\":\"sdfsdf\",\"amount\":\"78.00\",\"notes\":\"cvxcb\"}]}}', 314, 1587494006),
(338, 4, 'created', 'app\\models\\Project', 71, '{\"title\":\"sdsdgf - (Copy)\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587494062),
(339, 4, 'updated', 'app\\models\\Role', 8, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"amministratore\",\"description\":\"Amministratore dell\'applicazione\",\"permissions\":\"activities, backend, accounts, expense-types, transaction-templates, notification-templates, periodical-reports\",\"email\":\"\"}', 256, 1587536536),
(340, 4, 'created', 'app\\models\\Authorization', 342, '{\"begin_date\":\"2020-04-22 06:22:26\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-reports\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 255, 1587536546),
(341, 4, 'updated', 'app\\models\\Affiliation', 2, '{\"user_id\":\"4\",\"organizational_unit_id\":\"9\",\"role_id\":\"8\",\"rank\":1}', 259, 1587565514),
(342, 4, 'updated', 'app\\models\\Affiliation', 2, '{\"user_id\":\"4\",\"organizational_unit_id\":\"8\",\"role_id\":\"8\",\"rank\":\"1\"}', 259, 1587565522),
(343, 4, 'updated', 'app\\models\\Authorization', 342, '{\"controller_id\":\"periodical-reports\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"begin_date\":\"2020-04-22 06:22:26\",\"end_date\":\"2020-04-22 14:53:51\",\"role_id\":8}', 256, 1587567232),
(344, 4, 'created', 'app\\models\\Authorization', 343, '{\"begin_date\":\"2020-04-22 14:53:52\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-reports-management\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":8}', 256, 1587567232),
(345, 4, 'updated', 'app\\models\\Role', 8, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"amministratore\",\"description\":\"Amministratore dell\'applicazione\",\"permissions\":\"activities, backend, accounts, expense-types, transaction-templates, notification-templates, periodical-reports-management\",\"email\":\"\"}', 256, 1587567232),
(346, 4, 'created', 'app\\models\\Project', 72, '{\"title\":\"dd - (Copy)\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/draft\",\"organizational_unit_id\":6}', 314, 1587572482),
(347, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 72, '{\"title\":\"Presentazione libro \\\"Filosofare con i bambini\\\"\",\"description\":\"Incontro con Rosanna Lavagna per la presentazione del suo libro\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Pordenone\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":41,\"project_id\":72,\"expense_type_id\":2,\"description\":\"Viaggio treno A\\/R da Savona\",\"amount\":\"90.00\",\"notes\":\"\"},{\"id\":42,\"project_id\":72,\"expense_type_id\":3,\"description\":\"Affitto sala Biblioteca\",\"amount\":\"60.00\",\"notes\":\"\"}]}}', 314, 1587572718),
(348, 4, 'created', 'app\\models\\Authorization', 344, '{\"begin_date\":\"2020-04-23 17:32:59\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-report-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":3}', 256, 1587663179),
(349, 4, 'created', 'app\\models\\Authorization', 345, '{\"begin_date\":\"2020-04-23 17:32:59\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-report-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":3}', 256, 1587663179),
(350, 4, 'created', 'app\\models\\Authorization', 346, '{\"begin_date\":\"2020-04-23 17:32:59\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-report-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"role_id\":3}', 256, 1587663179),
(351, 4, 'created', 'app\\models\\Authorization', 347, '{\"begin_date\":\"2020-04-23 17:32:59\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-report-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"role_id\":3}', 256, 1587663179),
(352, 4, 'created', 'app\\models\\Authorization', 348, '{\"begin_date\":\"2020-04-23 17:32:59\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"periodical-report-submissions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":18,\"role_id\":3}', 256, 1587663179),
(353, 4, 'updated', 'app\\models\\Role', 3, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"coordinatore_circolo\",\"description\":\"Coordinatore di Circolo\",\"permissions\":\"project-submissions, planned-expenses, project-comments, periodical-report-submissions\",\"email\":\"\"}', 256, 1587663179),
(354, 4, 'created', 'app\\models\\PeriodicalReport', 13, '{\"name\":\"Resconto di cassa marzo 2020\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2020-03-31\",\"organizational_unit_id\":\"6\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887087),
(355, 4, 'created', 'app\\models\\PeriodicalReport', 14, '{\"name\":\"Resconto di cassa marzo 2020\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2020-03-31\",\"organizational_unit_id\":\"7\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887087),
(356, 4, 'created', 'app\\models\\PeriodicalReport', 15, '{\"name\":\"Resconto di cassa marzo 2020\",\"begin_date\":\"2020-03-01\",\"end_date\":\"2020-03-31\",\"organizational_unit_id\":\"4\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887087),
(357, 4, 'created', 'app\\models\\PeriodicalReport', 16, '{\"name\":\"Resoconto di cassa aprile 2020\",\"begin_date\":\"2020-04-01\",\"end_date\":\"2020-04-30\",\"organizational_unit_id\":\"5\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887197),
(358, 4, 'created', 'app\\models\\PeriodicalReport', 17, '{\"name\":\"Resoconto di cassa aprile 2020\",\"begin_date\":\"2020-04-01\",\"end_date\":\"2020-04-30\",\"organizational_unit_id\":\"6\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887197),
(359, 4, 'created', 'app\\models\\PeriodicalReport', 18, '{\"name\":\"Resoconto di cassa aprile 2020\",\"begin_date\":\"2020-04-01\",\"end_date\":\"2020-04-30\",\"organizational_unit_id\":\"7\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887198),
(360, 4, 'created', 'app\\models\\PeriodicalReport', 19, '{\"name\":\"Resoconto di cassa aprile 2020\",\"begin_date\":\"2020-04-01\",\"end_date\":\"2020-04-30\",\"organizational_unit_id\":\"4\",\"wf_status\":\"PeriodicalReportWorkflow\\/draft\"}', 343, 1587887198),
(361, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 71, '{\"title\":\"sdsdgf - (Copy)\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":40,\"project_id\":71,\"expense_type_id\":2,\"description\":\"sdfsdf\",\"amount\":\"78.00\",\"notes\":\"cvxcb\"}]}}', 314, 1587891527),
(362, 4, 'created', 'app\\models\\Project', 73, '{\"title\":\"bncvbn\",\"description\":\"cvbncvbn\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"vcncvbnb\",\"place\":\"vbnbvn\",\"organizational_unit_id\":\"6\",\"wf_status\":\"ProjectWorkflow\\/draft\"}', 314, 1587891545);
INSERT INTO `activities` (`id`, `user_id`, `activity_type`, `model`, `model_id`, `info`, `authorization_id`, `happened_at`) VALUES
(363, 6, 'ProjectWorkflow/questioned', 'app\\models\\Project', 71, '{\"title\":\"sdsdgf - (Copy)\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/questioned\",\"organizational_unit_id\":6}', 323, 1587891578),
(364, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 71, '{\"title\":\"sdsdgf - (Copy)\",\"description\":\"ddd\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ddd\",\"place\":\"ddd\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":40,\"project_id\":71,\"expense_type_id\":2,\"description\":\"sdfsdf\",\"amount\":\"78.00\",\"notes\":\"cvxcb\"}]}}', 314, 1587891641),
(365, 4, 'created', 'app\\models\\Authorization', 349, '{\"begin_date\":\"2020-04-26 10:24:47\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"transactions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":4,\"role_id\":3}', 256, 1587896687),
(366, 4, 'created', 'app\\models\\Authorization', 350, '{\"begin_date\":\"2020-04-26 10:24:47\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"transactions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":6,\"role_id\":3}', 256, 1587896687),
(367, 4, 'created', 'app\\models\\Authorization', 351, '{\"begin_date\":\"2020-04-26 10:24:47\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"transactions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":10,\"role_id\":3}', 256, 1587896687),
(368, 4, 'created', 'app\\models\\Authorization', 352, '{\"begin_date\":\"2020-04-26 10:24:47\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"transactions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":11,\"role_id\":3}', 256, 1587896687),
(369, 4, 'created', 'app\\models\\Authorization', 353, '{\"begin_date\":\"2020-04-26 10:24:47\",\"end_date\":\"2099-12-31 23:59:59\",\"controller_id\":\"transactions\",\"action_id\":\"*\",\"method\":\"*\",\"type\":\"-\",\"user_id\":18,\"role_id\":3}', 256, 1587896687),
(370, 4, 'updated', 'app\\models\\Role', 3, '{\"rank\":\"0\",\"status\":\"1\",\"name\":\"coordinatore_circolo\",\"description\":\"Coordinatore di Circolo\",\"permissions\":\"project-submissions, planned-expenses, project-comments, periodical-report-submissions, transactions\",\"email\":\"\"}', 256, 1587896687),
(371, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 72, '{\"title\":\"Presentazione libro \\\"Filosofare con i bambini\\\"\",\"description\":\"Incontro con Rosanna Lavagna per la presentazione del suo libro\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"ottobre 2020\",\"place\":\"Biblioteca Civica di Pordenone\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587900252),
(372, 4, 'ProjectWorkflow/submitted', 'app\\models\\Project', 73, '{\"title\":\"Festa della laicit\\u00e0\",\"description\":\"sdsdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"vcncvbnb\",\"place\":\"vbnbvn\",\"wf_status\":\"ProjectWorkflow\\/submitted\",\"organizational_unit_id\":6,\"related\":{\"plannedExpenses\":[{\"id\":43,\"project_id\":73,\"expense_type_id\":2,\"description\":\"fghjfdg\",\"amount\":\"67.00\",\"notes\":\"\"}]}}', 314, 1587900440),
(373, 6, 'ProjectWorkflow/approved', 'app\\models\\Project', 73, '{\"title\":\"Festa della laicit\\u00e0\",\"description\":\"sdsdf\",\"co_hosts\":\"\",\"partners\":\"\",\"period\":\"vcncvbnb\",\"place\":\"vbnbvn\",\"wf_status\":\"ProjectWorkflow\\/approved\",\"organizational_unit_id\":6}', 323, 1587900467),
(374, 4, 'created', 'app\\models\\Transaction', 1, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-30\",\"description\":\"Rinnovo Gianni L.\",\"notes\":null,\"project_id\":null,\"event_id\":null,\"vat_number\":null,\"vendor\":null,\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1587911255),
(375, 4, 'created', 'app\\models\\Transaction', 2, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-22\",\"description\":\"Pannello espositivo\",\"notes\":null,\"project_id\":null,\"event_id\":null,\"vat_number\":null,\"vendor\":null,\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1587911714),
(376, 4, 'created', 'app\\models\\Transaction', 3, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-23\",\"description\":\"Vendita due libri\",\"notes\":\"\",\"project_id\":\"\",\"event_id\":\"\",\"vat_number\":\"\",\"vendor\":\"\",\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1587911957),
(377, 4, 'created', 'app\\models\\Transaction', 4, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-17\",\"description\":\"Pannello espositivo fgh dfg\",\"notes\":\"\",\"project_id\":\"72\",\"event_id\":\"72\",\"vat_number\":\"\",\"vendor\":\"\",\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1588007051),
(378, 4, 'created', 'app\\models\\Transaction', 6, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-23\",\"description\":\"ddd\",\"notes\":\"\",\"project_id\":\"\",\"event_id\":\"\",\"vat_number\":\"\",\"vendor\":\"\",\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1588322088),
(379, 4, 'created', 'app\\models\\Transaction', 7, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-24\",\"description\":\"cvbc\",\"notes\":\"\",\"project_id\":\"\",\"event_id\":\"\",\"vat_number\":\"\",\"vendor\":\"\",\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1588322136),
(380, 4, 'created', 'app\\models\\Transaction', 8, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-01\",\"description\":\"nuova corretta\",\"notes\":\"\",\"project_id\":\"\",\"event_id\":\"\",\"vat_number\":\"\",\"vendor\":\"\",\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1588322190),
(381, 4, 'created', 'app\\models\\Transaction', 9, '{\"periodical_report_id\":17,\"user_id\":4,\"date\":\"2020-04-23\",\"description\":\"Prelievo del giorno X\",\"notes\":\"\",\"project_id\":\"\",\"event_id\":\"\",\"vat_number\":\"\",\"vendor\":\"\",\"wf_status\":\"TransactionWorkflow\\/draft\"}', 349, 1588327711);

-- --------------------------------------------------------

--
-- Table structure for table `affiliations`
--

CREATE TABLE `affiliations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='link between user and organizational unit';

--
-- Dumping data for table `affiliations`
--

INSERT INTO `affiliations` (`id`, `user_id`, `organizational_unit_id`, `role_id`, `rank`) VALUES
(2, 4, 8, 8, 1),
(3, 5, 6, 5, 0),
(4, 6, 1, 2, 0),
(5, 6, 4, 3, 0),
(6, 7, 1, 1, 0),
(7, 8, 1, 6, 0),
(8, 9, 1, 11, 0),
(9, 10, 1, 13, 0),
(10, 10, 5, 3, 0),
(11, 11, 7, 3, 0),
(12, 11, 1, 12, 0),
(13, 12, 8, 9, 0),
(14, 14, 3, 14, 0),
(15, 15, 9, 10, 0),
(16, 16, 6, 5, 0),
(17, 17, 6, 16, 0),
(18, 4, 6, 3, 1),
(19, 18, 10, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `apikeys`
--

CREATE TABLE `apikeys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `app` varchar(100) NOT NULL,
  `value` varchar(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `itemId` int(11) NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `authorizations`
--

CREATE TABLE `authorizations` (
  `id` int(11) NOT NULL,
  `controller_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `action_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `method` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `type` char(1) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '-' COMMENT '*/@/?/-',
  `user_id` int(11) DEFAULT NULL,
  `begin_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authorizations`
--

INSERT INTO `authorizations` (`id`, `controller_id`, `action_id`, `method`, `type`, `user_id`, `begin_date`, `end_date`, `role_id`, `created_at`, `updated_at`) VALUES
(248, 'site', 'login', '*', '*', NULL, '2020-03-01 00:00:00', '2099-12-31 00:00:00', NULL, 1585426068, 1586420828),
(249, 'site', 'home', '*', '*', NULL, '2020-04-01 00:00:00', '2099-12-31 00:00:00', NULL, 1586420789, 1586420789),
(250, 'site', 'contact', '*', '*', NULL, '2020-04-01 00:00:00', '2099-12-31 00:00:00', NULL, 1586420902, 1586420902),
(251, 'site', 'index', '*', '*', NULL, '2020-04-01 00:00:00', '2099-12-31 00:00:00', NULL, 1586420961, 1586420961),
(255, 'users', '*', '*', '-', 4, '2020-04-01 00:00:00', '2099-12-31 00:00:00', 8, 1586422302, 1586422350),
(256, 'roles', '*', '*', '-', 4, '2020-04-01 00:00:00', '2099-12-31 00:00:00', 8, 1586422379, 1586422379),
(257, 'authorizations', '*', '*', '-', 4, '2020-03-01 00:00:00', '2099-12-31 00:00:00', 8, 1586628693, 1586861380),
(258, 'organizational-units', '*', '*', '-', 4, '2020-03-01 00:00:00', '2099-12-31 00:00:00', 8, 1586687841, 1586789525),
(259, 'affiliations', '*', '*', '-', 4, '2020-03-01 00:00:00', '2099-12-31 00:00:00', 8, 1586707084, 1587471852),
(287, 'accounting', '*', '*', '-', 4, '2020-04-13 14:54:04', '2020-04-13 14:54:44', 3, 1586789644, 1586789685),
(288, 'accounting', '*', '*', '-', 6, '2020-04-13 14:54:04', '2020-04-13 14:54:44', 3, 1586789644, 1586789685),
(289, 'accounting', '*', '*', '-', 10, '2020-04-13 14:54:04', '2020-04-13 14:54:44', 3, 1586789644, 1586789685),
(290, 'accounting', '*', '*', '-', 11, '2020-04-13 14:54:04', '2020-04-13 14:54:44', 3, 1586789644, 1586789685),
(291, 'foo', '*', '*', '-', 6, '2020-04-13 14:59:40', '2020-04-13 14:59:54', 2, 1586789980, 1586789995),
(292, 'bar', 'blip', '*', '-', 6, '2020-04-13 14:59:40', '2020-04-13 14:59:54', 2, 1586789980, 1586789995),
(293, 'activities', '*', '*', '-', 4, '2020-04-13 17:08:24', '2099-12-31 23:59:59', 8, 1586797704, 1586797704),
(294, 'users', 'index', '*', '-', 7, '2020-04-14 08:15:43', '2099-12-31 23:59:59', 1, 1586852143, 1586852143),
(295, 'users', 'process', 'boo', '-', 7, '2020-04-14 08:15:43', '2020-04-14 08:16:49', 1, 1586852143, 1586852210),
(296, 'users', 'process', '*', '-', 7, '2020-04-14 08:20:24', '2020-04-14 08:21:42', 1, 1586852424, 1586852503),
(297, 'users', 'process', '*', '-', 7, '2020-04-14 08:21:55', '2020-04-14 08:28:39', 1, 1586852515, 1586852920),
(298, 'users', 'bulk', 'GET', '-', 7, '2020-04-14 08:22:44', '2020-04-14 08:23:05', 1, 1586852564, 1586852586),
(299, 'users', 'bulk', 'ZUP', '-', 7, '2020-04-14 08:23:06', '2020-04-14 08:23:21', 1, 1586852586, 1586852602),
(300, 'users', 'bulk', 'fixPermissions', '-', 7, '2020-04-14 08:27:03', '2099-12-31 23:59:59', 1, 1586852823, 1586852823),
(301, 'user', 'process', '*', '-', 7, '2020-04-14 08:30:07', '2020-04-14 08:38:47', 1, 1586853007, 1586853528),
(302, 'site', '*', '*', '@', NULL, '2020-03-01 00:00:00', '2099-12-31 00:00:00', NULL, 1586860936, 1586860936),
(305, 'backend', '*', '*', '-', 4, '2020-04-14 11:04:39', '2099-12-31 23:59:59', 8, 1586862279, 1586862279),
(306, 'accounts', '*', '*', '-', 4, '2020-04-14 11:04:40', '2099-12-31 23:59:59', 8, 1586862280, 1586862280),
(307, 'expense-types', '*', '*', '-', 4, '2020-04-14 11:04:40', '2099-12-31 23:59:59', 8, 1586862280, 1586862280),
(308, 'transaction-templates', '*', '*', '-', 4, '2020-04-14 11:04:40', '2099-12-31 23:59:59', 8, 1586862280, 1586862280),
(309, 'notification-templates', '*', '*', '-', 4, '2020-04-14 11:10:52', '2099-12-31 23:59:59', 8, 1586862652, 1586862652),
(310, 'projects-submissions', '*', '*', '-', 6, '2020-04-14 14:14:48', '2020-04-14 14:19:19', 3, 1586873688, 1586873960),
(311, 'projects-submissions', '*', '*', '-', 10, '2020-04-14 14:14:48', '2020-04-14 14:19:19', 3, 1586873688, 1586873960),
(312, 'projects-submissions', '*', '*', '-', 11, '2020-04-14 14:14:48', '2020-04-14 14:19:19', 3, 1586873688, 1586873960),
(313, 'projects-submissions', '*', '*', '-', 4, '2020-04-14 14:15:49', '2020-04-14 14:19:19', 3, 1586873749, 1586873960),
(314, 'project-submissions', '*', '*', '-', 4, '2020-04-14 14:19:20', '2099-12-31 23:59:59', 3, 1586873960, 1586873960),
(315, 'project-submissions', '*', '*', '-', 6, '2020-04-14 14:19:20', '2099-12-31 23:59:59', 3, 1586873960, 1586873960),
(316, 'project-submissions', '*', '*', '-', 10, '2020-04-14 14:19:20', '2099-12-31 23:59:59', 3, 1586873960, 1586873960),
(317, 'project-submissions', '*', '*', '-', 11, '2020-04-14 14:19:20', '2099-12-31 23:59:59', 3, 1586873960, 1586873960),
(318, 'project-submissions', '*', '*', '-', 5, '2020-04-14 15:49:04', '2099-12-31 23:59:59', 5, 1586879344, 1586879344),
(319, 'project-submissions', '*', '*', '-', 16, '2020-04-14 15:49:04', '2099-12-31 23:59:59', 5, 1586879344, 1586879344),
(320, 'organizational-units', 'view', 'GET', '@', NULL, '2020-03-01 00:00:00', '2099-12-31 00:00:00', NULL, 1586943471, 1586943471),
(321, 'users', 'view', 'GET', '@', NULL, '2020-03-01 00:00:00', '2099-12-31 00:00:00', NULL, 1586943596, 1586943596),
(322, 'project-approvals', '*', '*', '-', 6, '2020-04-15 17:17:08', '2020-04-15 17:17:53', 2, 1586971028, 1586971074),
(323, 'projects-management', '*', '*', '-', 6, '2020-04-15 17:17:54', '2099-12-31 23:59:59', 2, 1586971074, 1586971074),
(324, 'planned-expenses', '*', '*', '-', 4, '2020-04-18 10:25:17', '2099-12-31 23:59:59', 3, 1587205517, 1587205517),
(325, 'planned-expenses', '*', '*', '-', 6, '2020-04-18 10:25:17', '2099-12-31 23:59:59', 3, 1587205517, 1587205517),
(326, 'planned-expenses', '*', '*', '-', 10, '2020-04-18 10:25:17', '2099-12-31 23:59:59', 3, 1587205517, 1587205517),
(327, 'planned-expenses', '*', '*', '-', 11, '2020-04-18 10:25:17', '2099-12-31 23:59:59', 3, 1587205517, 1587205517),
(328, 'planned-expenses', '*', '*', '-', 5, '2020-04-18 10:25:48', '2099-12-31 23:59:59', 5, 1587205548, 1587205548),
(329, 'planned-expenses', '*', '*', '-', 16, '2020-04-18 10:25:48', '2099-12-31 23:59:59', 5, 1587205548, 1587205548),
(330, 'notifications', '*', '*', '@', NULL, '2020-03-01 00:00:00', '2099-12-31 00:00:00', NULL, 1587289886, 1587289886),
(331, 'project-comments', '*', '*', '-', 6, '2020-04-19 16:46:42', '2099-12-31 23:59:59', 2, 1587314802, 1587314802),
(332, 'projects-management', 'index', '*', '-', 7, '2020-04-19 17:28:36', '2099-12-31 23:59:59', 1, 1587317316, 1587317316),
(333, 'projects-management', 'view', '*', '-', 7, '2020-04-19 17:28:36', '2099-12-31 23:59:59', 1, 1587317316, 1587317316),
(334, 'project-comments', '*', '*', '-', 7, '2020-04-19 17:28:36', '2099-12-31 23:59:59', 1, 1587317316, 1587317316),
(335, 'project-comments', '*', '*', '-', 4, '2020-04-19 17:34:02', '2099-12-31 23:59:59', 3, 1587317642, 1587317642),
(336, 'project-comments', '*', '*', '-', 6, '2020-04-19 17:34:02', '2099-12-31 23:59:59', 3, 1587317642, 1587317642),
(337, 'project-comments', '*', '*', '-', 10, '2020-04-19 17:34:02', '2099-12-31 23:59:59', 3, 1587317642, 1587317642),
(338, 'project-comments', '*', '*', '-', 11, '2020-04-19 17:34:02', '2099-12-31 23:59:59', 3, 1587317642, 1587317642),
(339, 'project-submissions', '*', '*', '-', 18, '2020-04-21 11:47:38', '2099-12-31 23:59:59', 3, 1587469658, 1587469658),
(340, 'planned-expenses', '*', '*', '-', 18, '2020-04-21 11:47:38', '2099-12-31 23:59:59', 3, 1587469658, 1587469658),
(341, 'project-comments', '*', '*', '-', 18, '2020-04-21 11:47:38', '2099-12-31 23:59:59', 3, 1587469658, 1587469658),
(342, 'periodical-reports', '*', '*', '-', 4, '2020-04-22 06:22:26', '2020-04-22 14:53:51', 8, 1587536546, 1587567232),
(343, 'periodical-reports-management', '*', '*', '-', 4, '2020-04-22 14:53:52', '2099-12-31 23:59:59', 8, 1587567232, 1587567232),
(344, 'periodical-report-submissions', '*', '*', '-', 4, '2020-04-23 17:32:59', '2099-12-31 23:59:59', 3, 1587663179, 1587663179),
(345, 'periodical-report-submissions', '*', '*', '-', 6, '2020-04-23 17:32:59', '2099-12-31 23:59:59', 3, 1587663179, 1587663179),
(346, 'periodical-report-submissions', '*', '*', '-', 10, '2020-04-23 17:32:59', '2099-12-31 23:59:59', 3, 1587663179, 1587663179),
(347, 'periodical-report-submissions', '*', '*', '-', 11, '2020-04-23 17:32:59', '2099-12-31 23:59:59', 3, 1587663179, 1587663179),
(348, 'periodical-report-submissions', '*', '*', '-', 18, '2020-04-23 17:32:59', '2099-12-31 23:59:59', 3, 1587663179, 1587663179),
(349, 'transactions', '*', '*', '-', 4, '2020-04-26 10:24:47', '2099-12-31 23:59:59', 3, 1587896687, 1587896687),
(350, 'transactions', '*', '*', '-', 6, '2020-04-26 10:24:47', '2099-12-31 23:59:59', 3, 1587896687, 1587896687),
(351, 'transactions', '*', '*', '-', 10, '2020-04-26 10:24:47', '2099-12-31 23:59:59', 3, 1587896687, 1587896687),
(352, 'transactions', '*', '*', '-', 11, '2020-04-26 10:24:47', '2099-12-31 23:59:59', 3, 1587896687, 1587896687),
(353, 'transactions', '*', '*', '-', 18, '2020-04-26 10:24:47', '2099-12-31 23:59:59', 3, 1587896687, 1587896687);

-- --------------------------------------------------------

--
-- Table structure for table `co_hosting`
--

CREATE TABLE `co_hosting` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'last updater',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='link between event and organizational unit';

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE `expense_types` (
  `id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `organizational_unit_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expense_types`
--

INSERT INTO `expense_types` (`id`, `rank`, `name`, `status`, `organizational_unit_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pubblicità', 1, NULL, 1587207175, 1587219752),
(2, 2, 'Rimborsi viaggio', 1, NULL, 1587207200, 1587207200),
(3, 5, 'Affitto sale', 1, NULL, 1587219777, 1587219838),
(4, 3, 'Rimborsi pernottamenti', 1, NULL, 1587219823, 1587219823),
(5, 3, 'Rimborsi pranzi / cene', 1, NULL, 1587219880, 1587219880);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `plaintext_body` text,
  `html_body` text,
  `created_at` int(11) NOT NULL,
  `seen_at` int(11) DEFAULT NULL,
  `sent_at` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `subject`, `plaintext_body`, `html_body`, `created_at`, `seen_at`, `sent_at`, `email`) VALUES
(1, 4, 'test1', 'prova1', NULL, 1587291900, NULL, NULL, NULL),
(2, 6, 'max', 'mnmn', NULL, 1587292234, 1587292351, NULL, NULL),
(3, 6, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(4, 4, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(5, 6, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(6, 10, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(7, 11, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(8, 5, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(9, 16, 'Progetto un progetto carino  ({organizational_unit})', 'È stato presentato il progetto un progetto carino da {organizational_unit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308646, NULL, NULL, NULL),
(10, 6, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308741, NULL, NULL, NULL),
(11, 4, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308741, NULL, NULL, NULL),
(12, 6, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308741, NULL, NULL, NULL),
(13, 10, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308741, NULL, NULL, NULL),
(14, 11, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308742, NULL, NULL, NULL),
(15, 5, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308742, NULL, NULL, NULL),
(16, 16, 'Progetto Microfono  (Circolo di Pordenone)', 'È stato presentato il progetto Microfono da Circolo di Pordenone.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', NULL, 1587308742, NULL, NULL, NULL),
(17, 6, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(18, 4, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(19, 6, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(20, 10, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(21, 11, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(22, 5, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(23, 16, 'Progetto ddd  (Circolo di Pordenone)', 'È stato presentato il progetto ddd da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587309611, NULL, NULL, NULL),
(24, 6, 'Progetto solopn  (Circolo di Pordenone)', 'È stato presentato il progetto «solopn» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=53\r\nulteriori informazioni.', NULL, 1587310356, NULL, NULL, NULL),
(25, 6, 'Progetto solopn  (Circolo di Pordenone)', 'È stato presentato il progetto «solopn» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=53\r\nulteriori informazioni.', NULL, 1587310404, NULL, NULL, NULL),
(26, 6, 'Progetto solopn  (Circolo di Pordenone)', 'È stato presentato il progetto «solopn» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=53\r\nulteriori informazioni.', NULL, 1587310492, NULL, NULL, NULL),
(27, 4, 'Progetto solopn  (Circolo di Pordenone)', 'È stato presentato il progetto «solopn» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=53\r\nulteriori informazioni.', NULL, 1587310492, NULL, NULL, NULL),
(28, 5, 'Progetto solopn  (Circolo di Pordenone)', 'È stato presentato il progetto «solopn» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=53\r\nulteriori informazioni.', NULL, 1587310492, NULL, NULL, NULL),
(29, 16, 'Progetto solopn  (Circolo di Pordenone)', 'È stato presentato il progetto «solopn» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=53\r\nulteriori informazioni.', NULL, 1587310492, NULL, NULL, NULL),
(30, 6, 'Approvazione progetto solopn', 'Il progetto «solopn» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=53\r\nulteriori informazioni.', NULL, 1587310935, NULL, NULL, NULL),
(31, 4, 'Approvazione progetto solopn', 'Il progetto «solopn» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=53\r\nulteriori informazioni.', NULL, 1587310935, NULL, NULL, NULL),
(32, 5, 'Approvazione progetto solopn', 'Il progetto «solopn» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=53\r\nulteriori informazioni.', NULL, 1587310935, NULL, NULL, NULL),
(33, 16, 'Approvazione progetto solopn', 'Il progetto «solopn» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=53\r\nulteriori informazioni.', NULL, 1587310935, NULL, NULL, NULL),
(34, 6, 'Rifiuto finanziamento progetto ddd', 'Il progetto «ddd» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=52\r\nulteriori informazioni.', NULL, 1587310989, NULL, NULL, NULL),
(35, 4, 'Rifiuto finanziamento progetto ddd', 'Il progetto «ddd» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587310989, NULL, NULL, NULL),
(36, 5, 'Rifiuto finanziamento progetto ddd', 'Il progetto «ddd» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587310989, NULL, NULL, NULL),
(37, 16, 'Rifiuto finanziamento progetto ddd', 'Il progetto «ddd» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=52\r\nulteriori informazioni.', NULL, 1587310989, NULL, NULL, NULL),
(38, 6, 'Progetto dfgsdfg  (Circolo di Pordenone)', 'È stato presentato il progetto «dfgsdfg» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=54\r\nulteriori informazioni.', NULL, 1587313490, NULL, NULL, NULL),
(39, 4, 'Progetto dfgsdfg  (Circolo di Pordenone)', 'È stato presentato il progetto «dfgsdfg» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=54\r\nulteriori informazioni.', NULL, 1587313490, NULL, NULL, NULL),
(40, 5, 'Progetto dfgsdfg  (Circolo di Pordenone)', 'È stato presentato il progetto «dfgsdfg» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=54\r\nulteriori informazioni.', NULL, 1587313490, NULL, NULL, NULL),
(41, 16, 'Progetto dfgsdfg  (Circolo di Pordenone)', 'È stato presentato il progetto «dfgsdfg» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=54\r\nulteriori informazioni.', NULL, 1587313490, NULL, NULL, NULL),
(42, 6, 'Approvazione progetto dfgsdfg', 'Il progetto «dfgsdfg» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=54\r\nulteriori informazioni.', NULL, 1587313504, NULL, NULL, NULL),
(43, 4, 'Approvazione progetto dfgsdfg', 'Il progetto «dfgsdfg» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=54\r\nulteriori informazioni.', NULL, 1587313504, NULL, NULL, NULL),
(44, 5, 'Approvazione progetto dfgsdfg', 'Il progetto «dfgsdfg» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=54\r\nulteriori informazioni.', NULL, 1587313504, NULL, NULL, NULL),
(45, 16, 'Approvazione progetto dfgsdfg', 'Il progetto «dfgsdfg» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=54\r\nulteriori informazioni.', NULL, 1587313504, NULL, NULL, NULL),
(46, 6, 'Progetto Presentazione libro  (Circolo di Ancona)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Ancona.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=55\r\nulteriori informazioni.', NULL, 1587313570, NULL, NULL, NULL),
(47, 11, 'Progetto Presentazione libro  (Circolo di Ancona)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Ancona.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=55\r\nulteriori informazioni.', NULL, 1587313570, NULL, NULL, NULL),
(48, 6, 'Rifiuto finanziamento progetto Presentazione libro', 'Il progetto «Presentazione libro» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=55\r\nulteriori informazioni.', NULL, 1587314645, NULL, NULL, NULL),
(49, 11, 'Rifiuto finanziamento progetto Presentazione libro', 'Il progetto «Presentazione libro» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=55\r\nulteriori informazioni.', NULL, 1587314645, NULL, NULL, NULL),
(50, 6, 'Rifiuto finanziamento progetto Microfono', 'Il progetto «Microfono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=50\r\nulteriori informazioni.', NULL, 1587317022, NULL, NULL, NULL),
(51, 4, 'Rifiuto finanziamento progetto Microfono', 'Il progetto «Microfono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=50\r\nulteriori informazioni.', NULL, 1587317023, NULL, NULL, NULL),
(52, 5, 'Rifiuto finanziamento progetto Microfono', 'Il progetto «Microfono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=50\r\nulteriori informazioni.', NULL, 1587317023, NULL, NULL, NULL),
(53, 16, 'Rifiuto finanziamento progetto Microfono', 'Il progetto «Microfono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=50\r\nulteriori informazioni.', NULL, 1587317023, NULL, NULL, NULL),
(54, 6, 'Rifiuto finanziamento progetto un progetto carino', 'Il progetto «un progetto carino» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=51\r\nulteriori informazioni.', NULL, 1587317821, NULL, NULL, NULL),
(55, 7, 'Rifiuto finanziamento progetto un progetto carino', 'Il progetto «un progetto carino» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=51\r\nulteriori informazioni.', NULL, 1587317821, NULL, NULL, NULL),
(56, 4, 'Rifiuto finanziamento progetto un progetto carino', 'Il progetto «un progetto carino» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=51\r\nulteriori informazioni.', NULL, 1587317821, NULL, NULL, NULL),
(57, 5, 'Rifiuto finanziamento progetto un progetto carino', 'Il progetto «un progetto carino» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=51\r\nulteriori informazioni.', NULL, 1587317821, NULL, NULL, NULL),
(58, 16, 'Rifiuto finanziamento progetto un progetto carino', 'Il progetto «un progetto carino» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=51\r\nulteriori informazioni.', NULL, 1587317821, NULL, NULL, NULL),
(59, 6, 'Approvazione progetto bnmvbnmvbnm', 'Il progetto «bnmvbnmvbnm» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=49\r\nulteriori informazioni.', NULL, 1587399257, NULL, NULL, NULL),
(60, 7, 'Approvazione progetto bnmvbnmvbnm', 'Il progetto «bnmvbnmvbnm» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=49\r\nulteriori informazioni.', NULL, 1587399257, NULL, NULL, NULL),
(61, 4, 'Approvazione progetto bnmvbnmvbnm', 'Il progetto «bnmvbnmvbnm» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=49\r\nulteriori informazioni.', NULL, 1587399257, NULL, NULL, NULL),
(62, 5, 'Approvazione progetto bnmvbnmvbnm', 'Il progetto «bnmvbnmvbnm» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=49\r\nulteriori informazioni.', NULL, 1587399257, NULL, NULL, NULL),
(63, 16, 'Approvazione progetto bnmvbnmvbnm', 'Il progetto «bnmvbnmvbnm» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=49\r\nulteriori informazioni.', NULL, 1587399257, NULL, NULL, NULL),
(64, 6, 'Progetto Telecomando  (Circolo di Pordenone)', 'È stato presentato il progetto «Telecomando» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=56\r\nulteriori informazioni.', NULL, 1587400437, NULL, NULL, NULL),
(65, 7, 'Progetto Telecomando  (Circolo di Pordenone)', 'È stato presentato il progetto «Telecomando» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=56\r\nulteriori informazioni.', NULL, 1587400437, NULL, NULL, NULL),
(66, 4, 'Progetto Telecomando  (Circolo di Pordenone)', 'È stato presentato il progetto «Telecomando» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=56\r\nulteriori informazioni.', NULL, 1587400437, NULL, NULL, NULL),
(67, 5, 'Progetto Telecomando  (Circolo di Pordenone)', 'È stato presentato il progetto «Telecomando» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=56\r\nulteriori informazioni.', NULL, 1587400437, NULL, NULL, NULL),
(68, 16, 'Progetto Telecomando  (Circolo di Pordenone)', 'È stato presentato il progetto «Telecomando» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=56\r\nulteriori informazioni.', NULL, 1587400437, NULL, NULL, NULL),
(69, 6, 'Rifiuto finanziamento progetto Telecomando', 'Il progetto «Telecomando» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=56\r\nulteriori informazioni.', NULL, 1587400739, NULL, NULL, NULL),
(70, 7, 'Rifiuto finanziamento progetto Telecomando', 'Il progetto «Telecomando» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=56\r\nulteriori informazioni.', NULL, 1587400739, NULL, NULL, NULL),
(71, 4, 'Rifiuto finanziamento progetto Telecomando', 'Il progetto «Telecomando» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=56\r\nulteriori informazioni.', NULL, 1587400739, NULL, NULL, NULL),
(72, 5, 'Rifiuto finanziamento progetto Telecomando', 'Il progetto «Telecomando» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=56\r\nulteriori informazioni.', NULL, 1587400739, NULL, NULL, NULL),
(73, 16, 'Rifiuto finanziamento progetto Telecomando', 'Il progetto «Telecomando» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=56\r\nulteriori informazioni.', NULL, 1587400739, NULL, NULL, NULL),
(74, 6, 'Progetto Telefono  (Circolo di Pordenone)', 'È stato presentato il progetto «Telefono» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=57\r\nulteriori informazioni.', NULL, 1587400889, NULL, NULL, NULL),
(75, 7, 'Progetto Telefono  (Circolo di Pordenone)', 'È stato presentato il progetto «Telefono» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=57\r\nulteriori informazioni.', NULL, 1587400889, NULL, NULL, NULL),
(76, 4, 'Progetto Telefono  (Circolo di Pordenone)', 'È stato presentato il progetto «Telefono» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=57\r\nulteriori informazioni.', NULL, 1587400889, NULL, NULL, NULL),
(77, 5, 'Progetto Telefono  (Circolo di Pordenone)', 'È stato presentato il progetto «Telefono» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=57\r\nulteriori informazioni.', NULL, 1587400889, NULL, NULL, NULL),
(78, 16, 'Progetto Telefono  (Circolo di Pordenone)', 'È stato presentato il progetto «Telefono» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=57\r\nulteriori informazioni.', NULL, 1587400889, NULL, NULL, NULL),
(79, 6, 'Rifiuto finanziamento progetto Telefono', 'Il progetto «Telefono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=57\r\nulteriori informazioni.', NULL, 1587400952, NULL, NULL, NULL),
(80, 7, 'Rifiuto finanziamento progetto Telefono', 'Il progetto «Telefono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=57\r\nulteriori informazioni.', NULL, 1587400952, NULL, NULL, NULL),
(81, 4, 'Rifiuto finanziamento progetto Telefono', 'Il progetto «Telefono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=57\r\nulteriori informazioni.', NULL, 1587400952, NULL, NULL, NULL),
(82, 5, 'Rifiuto finanziamento progetto Telefono', 'Il progetto «Telefono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=57\r\nulteriori informazioni.', NULL, 1587400952, NULL, NULL, NULL),
(83, 16, 'Rifiuto finanziamento progetto Telefono', 'Il progetto «Telefono» presentato da {organizational_unit} non è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=57\r\nulteriori informazioni.', NULL, 1587400952, NULL, NULL, NULL),
(84, 6, 'Progetto Matematico napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587401078, NULL, NULL, NULL),
(85, 7, 'Progetto Matematico napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587401078, NULL, NULL, NULL),
(86, 4, 'Progetto Matematico napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401078, NULL, NULL, NULL),
(87, 5, 'Progetto Matematico napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401078, NULL, NULL, NULL),
(88, 16, 'Progetto Matematico napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401078, NULL, NULL, NULL),
(89, 6, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587401549, NULL, NULL, NULL),
(90, 7, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587401549, NULL, NULL, NULL),
(91, 4, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401549, NULL, NULL, NULL),
(92, 5, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401549, NULL, NULL, NULL),
(93, 16, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401549, NULL, NULL, NULL),
(94, 6, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587401672, NULL, NULL, NULL),
(95, 7, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587401672, NULL, NULL, NULL),
(96, 4, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401672, NULL, NULL, NULL),
(97, 5, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401672, NULL, NULL, NULL),
(98, 16, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587401672, NULL, NULL, NULL),
(99, 6, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587402565, NULL, NULL, NULL),
(100, 7, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587402565, NULL, NULL, NULL),
(101, 4, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587402565, NULL, NULL, NULL),
(102, 5, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587402565, NULL, NULL, NULL),
(103, 16, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587402565, NULL, NULL, NULL),
(104, 6, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587404004, NULL, NULL, NULL),
(105, 7, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587404004, NULL, NULL, NULL),
(106, 4, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404004, NULL, NULL, NULL),
(107, 5, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404004, NULL, NULL, NULL),
(108, 16, 'Progetto Matematico Napoletano  (Circolo di Pordenone)', 'È stato presentato il progetto «Matematico Napoletano» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404004, NULL, NULL, NULL),
(109, 6, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587404365, NULL, NULL, NULL),
(110, 7, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587404365, NULL, NULL, NULL),
(111, 4, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404365, NULL, NULL, NULL),
(112, 5, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404365, NULL, NULL, NULL),
(113, 16, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404365, NULL, NULL, NULL),
(114, 6, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587404376, NULL, NULL, NULL),
(115, 7, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=58\r\nulteriori informazioni.', NULL, 1587404376, NULL, NULL, NULL),
(116, 4, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404376, NULL, NULL, NULL),
(117, 5, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404376, NULL, NULL, NULL),
(118, 16, 'Approvazione progetto Matematico Napoletano', 'Il progetto «Matematico Napoletano» presentato da {organizational_unit} è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=58\r\nulteriori informazioni.', NULL, 1587404376, NULL, NULL, NULL),
(119, 6, 'Progetto Conferenza  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=64\r\nulteriori informazioni.', NULL, 1587470606, NULL, NULL, NULL),
(120, 7, 'Progetto Conferenza  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=64\r\nulteriori informazioni.', NULL, 1587470606, NULL, NULL, NULL),
(121, 18, 'Progetto Conferenza  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=64\r\nulteriori informazioni.', NULL, 1587470606, NULL, NULL, NULL),
(122, 6, 'Approvazione progetto Conferenza', 'Il progetto «Conferenza» presentato da Circolo di Poznan è stato approvato.\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/projects-management/view?id=64\r\nulteriori informazioni.', NULL, 1587470717, NULL, NULL, NULL),
(123, 7, 'Approvazione progetto Conferenza', 'Il progetto «Conferenza» presentato da Circolo di Poznan è stato approvato.\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/projects-management/view?id=64\r\nulteriori informazioni.', NULL, 1587470717, NULL, NULL, NULL),
(124, 18, 'Approvazione progetto Conferenza', 'Il progetto «Conferenza» presentato da Circolo di Poznan è stato approvato.\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/project-submissions/view?id=64\r\nulteriori informazioni.', NULL, 1587470717, NULL, NULL, NULL),
(125, 6, 'Progetto Conferenza - (Copy)  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza - (Copy)» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587470905, NULL, NULL, NULL),
(126, 7, 'Progetto Conferenza - (Copy)  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza - (Copy)» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587470905, NULL, NULL, NULL),
(127, 18, 'Progetto Conferenza - (Copy)  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza - (Copy)» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=65\r\nulteriori informazioni.', NULL, 1587470905, NULL, NULL, NULL),
(128, 6, 'Progetto Conferenza - (Copy) (Circolo di Poznan): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Conferenza - (Copy)».\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587470948, NULL, NULL, NULL),
(129, 7, 'Progetto Conferenza - (Copy) (Circolo di Poznan): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Conferenza - (Copy)».\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587470948, NULL, NULL, NULL),
(130, 18, 'Progetto Conferenza - (Copy) (Circolo di Poznan): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Conferenza - (Copy)».\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/project-submissions/view?id=65\r\nulteriori informazioni.', NULL, 1587470948, 1587470974, NULL, NULL),
(131, 6, 'Progetto Conferenza - (Copy)  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza - (Copy)» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587471059, NULL, NULL, NULL),
(132, 7, 'Progetto Conferenza - (Copy)  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza - (Copy)» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587471059, NULL, NULL, NULL),
(133, 18, 'Progetto Conferenza - (Copy)  (Circolo di Poznan)', 'È stato presentato il progetto «Conferenza - (Copy)» da Circolo di Poznan.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=65\r\nulteriori informazioni.', NULL, 1587471059, NULL, NULL, NULL),
(134, 6, 'Rifiuto finanziamento progetto Conferenza - (Copy)', 'Il progetto «Conferenza - (Copy)» presentato da Circolo di Poznan non è stato approvato.\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587471148, NULL, NULL, NULL),
(135, 7, 'Rifiuto finanziamento progetto Conferenza - (Copy)', 'Il progetto «Conferenza - (Copy)» presentato da Circolo di Poznan non è stato approvato.\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/projects-management/view?id=65\r\nulteriori informazioni.', NULL, 1587471148, NULL, NULL, NULL),
(136, 18, 'Rifiuto finanziamento progetto Conferenza - (Copy)', 'Il progetto «Conferenza - (Copy)» presentato da Circolo di Poznan non è stato approvato.\r\n\r\nQui\r\nhttp://192.168.1.14:8000/index.php/project-submissions/view?id=65\r\nulteriori informazioni.', NULL, 1587471148, NULL, NULL, NULL),
(137, 6, 'Progetto Presentazione libro  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=67\r\nulteriori informazioni.', NULL, 1587490517, NULL, NULL, NULL),
(138, 7, 'Progetto Presentazione libro  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=67\r\nulteriori informazioni.', NULL, 1587490517, NULL, NULL, NULL),
(139, 4, 'Progetto Presentazione libro  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=67\r\nulteriori informazioni.', NULL, 1587490517, NULL, NULL, NULL),
(140, 5, 'Progetto Presentazione libro  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=67\r\nulteriori informazioni.', NULL, 1587490517, NULL, NULL, NULL),
(141, 16, 'Progetto Presentazione libro  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=67\r\nulteriori informazioni.', NULL, 1587490517, NULL, NULL, NULL),
(142, 6, 'Progetto Presentazione libro (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Presentazione libro».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=67\r\nulteriori informazioni.', NULL, 1587490541, NULL, NULL, NULL),
(143, 7, 'Progetto Presentazione libro (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Presentazione libro».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=67\r\nulteriori informazioni.', NULL, 1587490541, NULL, NULL, NULL),
(144, 4, 'Progetto Presentazione libro (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Presentazione libro».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=67\r\nulteriori informazioni.', NULL, 1587490541, NULL, NULL, NULL),
(145, 5, 'Progetto Presentazione libro (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Presentazione libro».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=67\r\nulteriori informazioni.', NULL, 1587490541, NULL, NULL, NULL),
(146, 16, 'Progetto Presentazione libro (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «Presentazione libro».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=67\r\nulteriori informazioni.', NULL, 1587490541, NULL, NULL, NULL),
(147, 6, 'Progetto sdsdgf  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=70\r\nulteriori informazioni.', NULL, 1587494006, NULL, NULL, NULL),
(148, 7, 'Progetto sdsdgf  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=70\r\nulteriori informazioni.', NULL, 1587494006, NULL, NULL, NULL),
(149, 4, 'Progetto sdsdgf  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=70\r\nulteriori informazioni.', NULL, 1587494006, NULL, NULL, NULL),
(150, 5, 'Progetto sdsdgf  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=70\r\nulteriori informazioni.', NULL, 1587494006, NULL, NULL, NULL),
(151, 16, 'Progetto sdsdgf  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=70\r\nulteriori informazioni.', NULL, 1587494006, NULL, NULL, NULL),
(152, 6, 'Progetto Presentazione libro \"Filosofare con i bambini\"  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro \"Filosofare con i bambini\"» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=72\r\nulteriori informazioni.', NULL, 1587572718, NULL, NULL, NULL),
(153, 7, 'Progetto Presentazione libro \"Filosofare con i bambini\"  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro \"Filosofare con i bambini\"» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=72\r\nulteriori informazioni.', NULL, 1587572718, NULL, NULL, NULL),
(154, 4, 'Progetto Presentazione libro \"Filosofare con i bambini\"  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro \"Filosofare con i bambini\"» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=72\r\nulteriori informazioni.', NULL, 1587572718, NULL, NULL, NULL),
(155, 5, 'Progetto Presentazione libro \"Filosofare con i bambini\"  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro \"Filosofare con i bambini\"» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=72\r\nulteriori informazioni.', NULL, 1587572718, NULL, NULL, NULL),
(156, 16, 'Progetto Presentazione libro \"Filosofare con i bambini\"  (Circolo di Pordenone)', 'È stato presentato il progetto «Presentazione libro \"Filosofare con i bambini\"» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=72\r\nulteriori informazioni.', NULL, 1587572718, NULL, NULL, NULL),
(157, 6, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=71\r\nulteriori informazioni.', NULL, 1587891527, NULL, NULL, NULL),
(158, 7, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=71\r\nulteriori informazioni.', NULL, 1587891527, NULL, NULL, NULL),
(159, 4, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891527, NULL, NULL, NULL),
(160, 5, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891527, NULL, NULL, NULL),
(161, 16, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891527, NULL, NULL, NULL),
(162, 6, 'Progetto sdsdgf - (Copy) (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «sdsdgf - (Copy)».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=71\r\nulteriori informazioni.', NULL, 1587891578, NULL, NULL, NULL),
(163, 7, 'Progetto sdsdgf - (Copy) (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «sdsdgf - (Copy)».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=71\r\nulteriori informazioni.', NULL, 1587891578, NULL, NULL, NULL),
(164, 4, 'Progetto sdsdgf - (Copy) (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «sdsdgf - (Copy)».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891578, NULL, NULL, NULL),
(165, 5, 'Progetto sdsdgf - (Copy) (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «sdsdgf - (Copy)».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891578, NULL, NULL, NULL),
(166, 16, 'Progetto sdsdgf - (Copy) (Circolo di Pordenone): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «sdsdgf - (Copy)».\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891578, NULL, NULL, NULL),
(167, 6, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=71\r\nulteriori informazioni.', NULL, 1587891641, NULL, NULL, NULL),
(168, 7, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=71\r\nulteriori informazioni.', NULL, 1587891641, NULL, NULL, NULL),
(169, 4, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891641, NULL, NULL, NULL),
(170, 5, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891641, NULL, NULL, NULL),
(171, 16, 'Progetto sdsdgf - (Copy)  (Circolo di Pordenone)', 'È stato presentato il progetto «sdsdgf - (Copy)» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=71\r\nulteriori informazioni.', NULL, 1587891641, NULL, NULL, NULL),
(172, 6, 'Approvazione progetto Presentazione libro \"Filosofare con i bambini\"', 'Il progetto «Presentazione libro \"Filosofare con i bambini\"» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=72\r\nulteriori informazioni.', NULL, 1587900252, NULL, NULL, NULL),
(173, 7, 'Approvazione progetto Presentazione libro \"Filosofare con i bambini\"', 'Il progetto «Presentazione libro \"Filosofare con i bambini\"» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=72\r\nulteriori informazioni.', NULL, 1587900252, NULL, NULL, NULL),
(174, 4, 'Approvazione progetto Presentazione libro \"Filosofare con i bambini\"', 'Il progetto «Presentazione libro \"Filosofare con i bambini\"» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=72\r\nulteriori informazioni.', NULL, 1587900252, NULL, NULL, NULL),
(175, 5, 'Approvazione progetto Presentazione libro \"Filosofare con i bambini\"', 'Il progetto «Presentazione libro \"Filosofare con i bambini\"» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=72\r\nulteriori informazioni.', NULL, 1587900252, NULL, NULL, NULL),
(176, 16, 'Approvazione progetto Presentazione libro \"Filosofare con i bambini\"', 'Il progetto «Presentazione libro \"Filosofare con i bambini\"» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=72\r\nulteriori informazioni.', NULL, 1587900252, NULL, NULL, NULL),
(177, 6, 'Progetto Festa della laicità  (Circolo di Pordenone)', 'È stato presentato il progetto «Festa della laicità» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=73\r\nulteriori informazioni.', NULL, 1587900440, NULL, NULL, NULL),
(178, 7, 'Progetto Festa della laicità  (Circolo di Pordenone)', 'È stato presentato il progetto «Festa della laicità» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=73\r\nulteriori informazioni.', NULL, 1587900440, NULL, NULL, NULL),
(179, 4, 'Progetto Festa della laicità  (Circolo di Pordenone)', 'È stato presentato il progetto «Festa della laicità» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=73\r\nulteriori informazioni.', NULL, 1587900440, NULL, NULL, NULL),
(180, 5, 'Progetto Festa della laicità  (Circolo di Pordenone)', 'È stato presentato il progetto «Festa della laicità» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=73\r\nulteriori informazioni.', NULL, 1587900440, NULL, NULL, NULL),
(181, 16, 'Progetto Festa della laicità  (Circolo di Pordenone)', 'È stato presentato il progetto «Festa della laicità» da Circolo di Pordenone.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=73\r\nulteriori informazioni.', NULL, 1587900440, NULL, NULL, NULL);
INSERT INTO `notifications` (`id`, `user_id`, `subject`, `plaintext_body`, `html_body`, `created_at`, `seen_at`, `sent_at`, `email`) VALUES
(182, 6, 'Approvazione progetto Festa della laicità', 'Il progetto «Festa della laicità» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=73\r\nulteriori informazioni.', NULL, 1587900467, NULL, NULL, NULL),
(183, 7, 'Approvazione progetto Festa della laicità', 'Il progetto «Festa della laicità» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/projects-management/view?id=73\r\nulteriori informazioni.', NULL, 1587900467, NULL, NULL, NULL),
(184, 4, 'Approvazione progetto Festa della laicità', 'Il progetto «Festa della laicità» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=73\r\nulteriori informazioni.', NULL, 1587900467, NULL, NULL, NULL),
(185, 5, 'Approvazione progetto Festa della laicità', 'Il progetto «Festa della laicità» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=73\r\nulteriori informazioni.', NULL, 1587900467, NULL, NULL, NULL),
(186, 16, 'Approvazione progetto Festa della laicità', 'Il progetto «Festa della laicità» presentato da Circolo di Pordenone è stato approvato.\r\n\r\nQui\r\nhttp://127.0.0.1:8000/index.php/project-submissions/view?id=73\r\nulteriori informazioni.', NULL, 1587900467, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` int(11) NOT NULL,
  `code` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `title` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `plaintext_body` text NOT NULL,
  `html_body` text,
  `md_body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `code`, `title`, `subject`, `plaintext_body`, `html_body`, `md_body`) VALUES
(1, 'ProjectWorkflow/submitted', 'Presentazione progetto', 'Progetto {title}  ({organizationalUnit})', 'È stato presentato il progetto «{title}» da {organizationalUnit}.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', '', ''),
(2, 'ProjectWorkflow/approved', 'Approvazione progetto', 'Approvazione progetto {title}', 'Il progetto «{title}» presentato da {organizationalUnit} è stato approvato.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', '', ''),
(3, 'ProjectWorkflow/rejected', 'Rifuto finanziamento progetto', 'Rifiuto finanziamento progetto {title}', 'Il progetto «{title}» presentato da {organizationalUnit} non è stato approvato.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', '', ''),
(4, 'ProjectWorkflow/questioned', 'Richiesta informazioni progetto', 'Progetto {title} ({organizationalUnit}): richiesta informazioni', 'Sono state richieste informazioni in merito al progetto «{title}».\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', '', ''),
(5, 'ProjectWorkflow/suspended', 'Sospensione progetto', 'Progetto {title} ({organizationalUnit}): sospensione', 'Il progetto «{title}» è stato sospeso.\r\n\r\nQui\r\n{url}\r\nulteriori informazioni.', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `organizational_units`
--

CREATE TABLE `organizational_units` (
  `id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ceiling_amount` decimal(10,2) DEFAULT NULL,
  `possible_actions` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organizational_units`
--

INSERT INTO `organizational_units` (`id`, `rank`, `status`, `name`, `email`, `url`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Comitato di Coordinamento', '', '', 0, 1586859175),
(2, 3, 1, 'Casa editrice Nessun Dogma', '', 'https://www.nessundogma.it/', 0, 1586688911),
(3, 4, 1, 'Rivista Nessun Dogma', '', 'https://rivista.nessundogma.it/', 0, 1586688933),
(4, 13, 1, 'Circolo di Ragusa', '', 'https://uaar.it/ragusa', 0, 1586689332),
(5, 11, 1, 'Circolo di Palermo', '', 'https://palermo.uaar.it', 0, 1586689304),
(6, 11, 1, 'Circolo di Pordenone', 'pordenone@uaar.it', 'http://pordenone.uaar.it', 0, 1586883051),
(7, 12, 1, 'Circolo di Ancona', '', '', 0, 1586883240),
(8, 6, 1, 'Servizio informatico', '', '', 0, 1586688865),
(9, 2, 1, 'Sede', '', '', 0, 1586688903),
(10, 11, 1, 'Circolo di Poznan', 'poznan@o.example.com', '', 1587469471, 1587469471);

-- --------------------------------------------------------

--
-- Table structure for table `periodical_reports`
--

CREATE TABLE `periodical_reports` (
  `id` int(11) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `periodical_reports`
--

INSERT INTO `periodical_reports` (`id`, `organizational_unit_id`, `name`, `begin_date`, `end_date`, `wf_status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Resoconto di cassa febbraio 2020', '2020-02-01', '2020-02-29', 'PeriodicalReportWorkflow/approved', 1587543789, 1587543789),
(2, 5, 'Resoconto di cassa febbraio 2020', '2020-02-01', '2020-02-29', 'PeriodicalReportWorkflow/approved', 1587543789, 1587543789),
(3, 6, 'Resoconto di cassa febbraio 2020', '2020-02-01', '2020-02-29', 'PeriodicalReportWorkflow/approved', 1587543789, 1587543789),
(4, 7, 'Resoconto di cassa febbraio 2020', '2020-02-01', '2020-02-29', 'PeriodicalReportWorkflow/approved', 1587543789, 1587543789),
(5, 10, 'Resoconto di cassa febbraio 2020', '2020-02-01', '2020-02-29', 'PeriodicalReportWorkflow/approved', 1587543789, 1587543789),
(13, 6, 'Resoconto di cassa marzo 2020', '2020-03-01', '2020-03-31', 'PeriodicalReportWorkflow/approved', 1587887087, 1587887087),
(14, 7, 'Resconto di cassa marzo 2020', '2020-03-01', '2020-03-31', 'PeriodicalReportWorkflow/approved', 1587887087, 1587887087),
(15, 4, 'Resconto di cassa marzo 2020', '2020-03-01', '2020-03-31', 'PeriodicalReportWorkflow/approved', 1587887087, 1587887087),
(16, 5, 'Resoconto di cassa aprile 2020', '2020-04-01', '2020-04-30', 'PeriodicalReportWorkflow/approved', 1587887197, 1587887197),
(17, 6, 'Resoconto di cassa aprile 2020', '2020-04-01', '2020-04-30', 'PeriodicalReportWorkflow/draft', 1587887197, 1587887197),
(18, 7, 'Resoconto di cassa aprile 2020', '2020-04-01', '2020-04-30', 'PeriodicalReportWorkflow/approved', 1587887197, 1587887197),
(19, 4, 'Resoconto di cassa aprile 2020', '2020-04-01', '2020-04-30', 'PeriodicalReportWorkflow/approved', 1587887198, 1587887198);

-- --------------------------------------------------------

--
-- Table structure for table `planned_expenses`
--

CREATE TABLE `planned_expenses` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `expense_type_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `planned_expenses`
--

INSERT INTO `planned_expenses` (`id`, `project_id`, `expense_type_id`, `description`, `amount`, `notes`) VALUES
(34, 64, 4, '2 notti presso Hotel Santin', '300.00', ''),
(35, 65, 4, '2 notti presso Hotel Santin', '300.00', ''),
(36, 66, 4, '2 notti presso Hotel Santin', '300.00', ''),
(37, 67, 2, 'sdfsdf', '67.00', ''),
(38, 69, 2, 'sdfsdf', '78.00', 'cvxcb'),
(39, 70, 2, 'sdfsdf', '78.00', 'cvxcb'),
(40, 71, 2, 'sdfsdf', '78.00', 'cvxcb'),
(41, 72, 2, 'Viaggio treno A/R da Savona', '90.00', ''),
(42, 72, 3, 'Affitto sala Biblioteca', '60.00', ''),
(43, 73, 2, 'fghjfdg', '67.00', '');

-- --------------------------------------------------------

--
-- Table structure for table `postings`
--

CREATE TABLE `postings` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `co_hosts` text,
  `partners` text,
  `period` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `co_hosts`, `partners`, `period`, `place`, `wf_status`, `organizational_unit_id`, `created_at`, `updated_at`) VALUES
(64, 'Conferenza', 'Conferenza incontro con una scrittrice polacca Olga Tokarczuk', 'Chiara Sartori, ARCI', 'Comune di Pordenone', 'maggio 2020', 'Pordenone', 'ProjectWorkflow/approved', 10, 1587470366, 1587470717),
(65, 'Conferenza - (Copy)', 'Conferenza incontro con una scrittrice polacca Wislawa Szymborska', 'Chiara Sartori, ARCI', 'Comune di Sacile', 'settembre 2020', 'Pordenone', 'ProjectWorkflow/rejected', 10, 1587470827, 1587471148),
(66, 'World Scout Jamboree', 'hzshshs', 'Chiara Sartori, ARCI', 'Comune di Sacile', 'settembre 2020', 'Pordenone', 'ProjectWorkflow/draft', 10, 1587471221, 1587471348),
(67, 'Presentazione libro', 'sdfasdf', '', '', 'sdf', 'sdf', 'ProjectWorkflow/deleted', 6, 1587490494, 1587492682),
(68, 'Presentazione libro', 'vbnxvb', '', 'v', 'cvbn', 'vcbn', 'ProjectWorkflow/deleted', 6, 1587492917, 1587493512),
(69, 'dd', 'ddd', '', '', 'ddd', 'ddd', 'ProjectWorkflow/draft', 6, 1587493529, 1587493529),
(70, 'sdsdgf', 'ddd', '', '', 'ddd', 'ddd', 'ProjectWorkflow/submitted', 6, 1587493940, 1587494006),
(71, 'sdsdgf - (Copy)', 'ddd', '', '', 'ddd', 'ddd', 'ProjectWorkflow/submitted', 6, 1587494062, 1587891641),
(72, 'Presentazione libro \"Filosofare con i bambini\"', 'Incontro con Rosanna Lavagna per la presentazione del suo libro', '', '', 'ottobre 2020', 'Biblioteca Civica di Pordenone', 'ProjectWorkflow/approved', 6, 1587572482, 1587900252),
(73, 'Festa della laicità', 'sdsdf', '', '', 'vcncvbnb', 'vbnbvn', 'ProjectWorkflow/approved', 6, 1587891545, 1587900467);

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_comments`
--

INSERT INTO `project_comments` (`id`, `project_id`, `user_id`, `comment`, `created_at`, `updated_at`) VALUES
(17, 65, 6, 'Non mi è chiaro perché servono così tanto soldi. ', 1587470946, 1587470946),
(18, 65, 18, 'Il prezzo di pernottamento è quello', 1587471051, 1587471051),
(19, 65, 6, 'Non mi convince', 1587471143, 1587471143),
(20, 67, 6, 'asasdasd', 1587490539, 1587490539),
(21, 67, 6, 'sdfgsdfg', 1587490930, 1587490930),
(22, 67, 6, 'fgdfgh', 1587490937, 1587490937),
(23, 67, 4, 'gcvncvbn', 1587491309, 1587491309),
(24, 67, 4, '> fgdfgh\r\ncxbcbv\r\n', 1587491981, 1587491981),
(25, 67, 4, '> fgdfgh\r\n\r\nxcvb\r\nxcvb\r\nxcb\r\n', 1587491992, 1587491992),
(26, 70, 4, 'bnmcbvmcbvcvbmnc\r\nvbmvbm\r\nvbnm\r\nv\r\nbnm', 1587494077, 1587494077),
(27, 71, 6, 'fdghdfghdfgh', 1587891573, 1587891573),
(28, 71, 4, '> fdghdfghdfgh\r\n\r\ncbxc\r\nn\r\ncn\r\nxbvn', 1587891634, 1587891634);

-- --------------------------------------------------------

--
-- Table structure for table `reimbursements`
--

CREATE TABLE `reimbursements` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `requested_amount` decimal(10,2) NOT NULL,
  `request_description` varchar(255) DEFAULT NULL,
  `reimbursed_amount` decimal(10,2) NOT NULL,
  `reimbursement_description` varchar(255) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `permissions` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `rank`, `status`, `name`, `description`, `permissions`, `email`) VALUES
(1, 0, 1, 'segretario4655', 'Segretario nazionale', 'users/index, users/bulk/fixPermissions, projects-management/index,projects-management/view, project-comments', 'segretario@o.example.com'),
(2, 0, 1, 'tesoriere', 'Tesoriere nazionale', 'projects-management, project-comments', 'tesoriere@o.example.com'),
(3, 0, 1, 'coordinatore_circolo', 'Coordinatore di Circolo', 'project-submissions, planned-expenses, project-comments, periodical-report-submissions, transactions', ''),
(4, 0, 1, 'referente_provinciale', 'Referente provinciale2', '', ''),
(5, 0, 1, 'cassiere_circolo', 'Cassiere di Circolo', 'project-submissions, planned-expenses', ''),
(6, 0, 1, 'responsabile_circoli', 'Responsabile nazionale circoli', NULL, 'circoli@o.example.com'),
(7, 0, 1, 'cc', 'Membro del Comitato di Coordinamento', NULL, NULL),
(8, 0, 1, 'amministratore', 'Amministratore dell\'applicazione', 'activities, backend, accounts, expense-types, transaction-templates, notification-templates, periodical-reports-management', ''),
(9, 0, 1, 'webmaster', 'Webmaster sito nazionale', NULL, 'webmaster@o.example.com'),
(10, 0, 1, 'contab', 'Addetto alla contabilità nazionale', NULL, 'contabilita@o.example.com'),
(11, 0, 1, 'responsabile_com_int', 'Responsabile comunicazione interna', NULL, 'infointerne@o.example.com'),
(12, 0, 1, 'responsabile_relaz_interass', 'Responsabile relazioni interassociative', NULL, 'relazioniassociative@o.example.com'),
(13, 0, 1, 'responsabile_eventi', 'Responsabile eventi', NULL, 'eventi@o.example.com'),
(14, 0, 1, 'curatore_riv_nd', 'Curatore rivista Nessun Dogma', NULL, NULL),
(15, 0, 1, 'vice_coordinatore_circolo', 'Vice Coordinatore di Circolo', NULL, NULL),
(16, 0, 1, 'attivo_circolo', 'Membro dell\'Attivo di Circolo', NULL, NULL),
(17, 0, 1, 'Wow', 'lll', 'ciao', 'ddd@ddd.com'),
(18, 12, 1, 'sdfsfd', 'sdfgsdvzxcv', '', ''),
(19, 0, 1, 'nuovissimoaaa', 'Ruolo inventato apposta per fare danni', 'xcvbxcbvxcvb', ''),
(20, 66, 1, 'cvbxcbv', 'xcvbxcvb', '23423', '23423'),
(21, 4, 0, 'funzionante', 'zxcbzxcb', 'cxvbxcvb', 'xcvbxcbv'),
(22, 67, 1, 'funzionantedavvero', 'zfgfb', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `periodical_report_id` int(11) NOT NULL,
  `transaction_template_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `vat_number` varchar(20) DEFAULT NULL,
  `vendor` varchar(100) DEFAULT NULL,
  `invoice` varchar(60) DEFAULT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'last updater',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




-- --------------------------------------------------------

--
-- Table structure for table `transaction_templates`
--

CREATE TABLE `transaction_templates` (
  `id` int(11) NOT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `rank` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `description` varchar(255) NOT NULL,
  `o_title` varchar(60) NOT NULL,
  `o_description` varchar(255) NOT NULL,
  `needs_attachment` int(1) NOT NULL DEFAULT '1',
  `needs_project` int(1) NOT NULL DEFAULT '1',
  `needs_vendor` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_templates`
--

INSERT INTO `transaction_templates` (`id`, `organizational_unit_id`, `status`, `rank`, `title`, `description`, `needs_attachment`, `needs_project`, `needs_vendor`) VALUES
(1, NULL, 1, 1, 'Iscrizione / Rinnovo', 'Versamento quota sociale in contanti presso la cassa del Circolo', 1, 0, 0),
(2, NULL, 1, 2, 'Vendita libri', 'Vendita libri e altri articoli con esclusione IVA ex art. 74 DPR 633/72', 1, 0, 0),
(3, NULL, 1, 3, 'Vendita gadget', 'Vendita di materiali imponibili IVA 22%', 1, 0, 0),
(4, NULL, 1, 4, 'Incasso donazioni', 'Donazioni e liberalità ricevute dal Circolo', 1, 0, 0),
(5, NULL, 1, 11, 'Spese per eventi (cassa)', 'Spese varie per organizzazione eventi, pagate con la cassa del Circolo', 1, 1, 1),
(6, NULL, 1, 12, 'Spese per eventi (carta prepagata)', 'Spese varie per organizzazione eventi, pagate con la carta prepagata del Circolo', 1, 1, 1),
(7, NULL, 1, 21, 'Prelievo contanti', 'Prelievo contanti da sportello PostePay', 0, 0, 0),
(8, NULL, 1, 13, 'Spese per eventi (cassiere)', 'Spese varie per organizzazione eventi, anticipate dal Cassiere del Circolo', 1, 1, 1),
(9, NULL, 1, 14, 'Spese per eventi (coordinatore)', 'Spese varie per organizzazione eventi, anticipate dal Coordinatore del Circolo', 1, 1, 1),
(10, NULL, 1, 15, 'Spese per eventi (attivo)', 'Spese varie per organizzazione eventi, anticipate dal componenti dell\'Attivo di Circolo', 1, 1, 1),
(11, NULL, 1, 31, 'Bonifico a sede nazionale (cassiere)', 'Bonifico a sede nazionale fatto dal Cassiere del Circolo', 1, 0, 0),
(12, NULL, 1, 32, 'Bonifico a sede nazionale (coordinatore)', 'Bonifico a sede nazionale fatto dal Coordinatore del Circolo', 1, 0, 0),
(13, NULL, 1, 41, 'Rimborso da Tesoriere nazionale (carta)', 'Ricezione rimborso da Tesoriere nazionale con ricarica carta prepagata', 0, 0, 0),
(14, NULL, 1, 42, 'Rimborso da Tesoriere nazionale (storno)', 'Ricezione rimborso da Tesoriere nazionale con storno del debito verso la sede nazionale', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_template_postings`
--

CREATE TABLE `transaction_template_postings` (
  `id` int(11) NOT NULL,
  `transaction_template_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `dc` char(1) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'D=debit, C=credit, $=amount',
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_template_postings`
--

INSERT INTO `transaction_template_postings` (`id`, `transaction_template_id`, `rank`, `account_id`, `dc`, `amount`) VALUES
(1, 1, 1, 1, 'D', NULL),
(2, 1, 2, 3, 'C', NULL),
(3, 2, 1, 1, 'D', NULL),
(4, 2, 2, 12, 'C', NULL),
(5, 3, 1, 1, 'D', NULL),
(6, 3, 2, 13, 'C', NULL),
(7, 4, 1, 1, 'D', NULL),
(8, 4, 2, 14, 'C', NULL),
(9, 5, 1, 7, 'D', NULL),
(10, 5, 2, 1, 'C', NULL),
(11, 6, 1, 7, 'D', NULL),
(12, 6, 2, 2, 'C', NULL),
(13, 7, 1, 1, 'D', NULL),
(14, 7, 2, 2, 'C', NULL),
(15, 7, 3, 16, '$', '1.00'),
(16, 7, 4, 2, '$', '-1.00'),
(17, 8, 1, 7, 'D', NULL),
(18, 8, 2, 4, 'C', NULL),
(19, 9, 1, 7, 'D', NULL),
(20, 9, 2, 5, 'C', NULL),
(21, 10, 1, 7, 'D', NULL),
(22, 10, 2, 5, 'C', NULL),
(23, 11, 1, 3, 'D', NULL),
(24, 11, 2, 4, 'C', NULL),
(25, 12, 1, 3, 'D', NULL),
(26, 12, 2, 5, 'C', NULL),
(27, 13, 1, 2, 'D', NULL),
(28, 13, 2, 11, 'C', NULL),
(29, 14, 1, 3, 'D', NULL),
(30, 13, 2, 11, 'C', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `auth_key` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `access_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `otp_secret` varchar(128) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email`, `auth_key`, `access_token`, `otp_secret`, `status`, `created_at`, `updated_at`) VALUES
(4, 'loris', 'Loris', 'T', 'loris@example.com', '$2y$13$PSipfVZYcf4izn5qpfY9GOoq1MvPUq5L72bOmYB2KQS3CbWvzbObK', '4', '', 1, 1585778400, 1586859542),
(5, 'marta', 'Marta', 'A', 'marta@example.com', '$2y$13$iTxB/Yp9nRWuqtQYuuHSFe8TnlSZi5FEEQZhGTThOB8CePKxoRbNS', '5', '', 1, 1585778400, 1586682254),
(6, 'massimo', 'Massimo', 'M', 'massimo@example.com', '$2y$13$bbeYohPjl3YVwTWcApqKyODnVL08n4c00FyDOC2ubi7LrZLK4FFqW', '6', '', 1, 1585778400, 1586683452),
(7, 'roberto', 'Roberto', 'G', 'roberto@example.com', '$2y$13$TCrb450MzMc0/bTca7B3Cuw1LkLIHKWROHVl9tD7MgaDR6CjhYhGi', '7', '', 1, 1585778400, 1585778400),
(8, 'cinzia', 'Cinzia', 'V', 'cinzia@example.com', '$2y$13$u1NtOm3lZUHUWMkq0XkbmO4n/VVjSmeV3rCA2viO84gGMMxC1Aa5u', '8', '', 1, 1585778400, 1585778400),
(9, 'manuel', 'Manuel', 'B', 'manuel@example.com', '$2y$13$vJk/7BbgGBPiu.P2otRjX.pd5RID2UxFg9QKPQLd2lIETHSi3TiEq', '9', '', 1, 1585778400, 1585778400),
(10, 'giorgio', 'Giorgio', 'M', 'giorgio@example.com', '$2y$13$WT53DruxbiqzJnDqXwnm8OzsfveNe/M709GomEigaQxpauBlxMOo.', '10', '', 1, 1585778400, 1585778400),
(11, 'paul', 'Paul', 'M', 'paul@example.com', '$2y$13$j0irfkl9th/nHoAJvGCOduCJpijXcCKI5qn9Yg6Nl7Db5HkQn8SqK', '11', '', 1, 1585778400, 1585778400),
(12, 'david', 'David', 'S', 'david@example.com', '$2y$13$Dyt9grHzJcd5QokC6B1/Ae0tU3GRcn6QG5Tq6YMUCBNpoJDsLwxHO', '12', '', 1, 1585778400, 1585778400),
(13, 'emanuele', 'Emanuele', 'A', 'emanuele@example.com', '$2y$13$76ypRPkLpBTz3Q5Bad.4Gez6OiQb9/QHwYdTEX2Y7uV7eeCw6/s1S', '13', '', 1, 1585778400, 1585778400),
(14, 'raffaele', 'Raffaele', 'C', 'raffaele@example.com', '$2y$13$0/Fbk.9k3sQyiG8yYKUAde/h4.9IHO7dCTNMSo1S5CKyoFDWyJIA.', '14', '', 1, 1585778400, 1585778400),
(15, 'patrizia', 'Patrizia', 'P', 'patrizia@example.com', '$2y$13$gKiCJfXp0Ec8dBYwzOS.wevZZ2IEyOQ/vN.UU/H0j8dSrpRRSAWpG', '99', '', 1, 1585778400, 1585778400),
(16, 'anna', 'Anna', 'P', 'anna@example.com', '$2y$13$qexLKmo1VzxZrphufkJ9zeIKR766xSoo3826hT/TjTdu35wc4XKf2', '999', '', 1, NULL, NULL),
(17, 'diego', 'Diego', 'M', 'diego@example.com', '$2y$13$JUPptUBKwZK7wX0SQ7fK1.X/e38LfX0jV7svdng/9VLqmD4RbbKvO', '9999', NULL, 1, NULL, NULL),
(18, 'ika', 'Ika', 'P', 'ika@example.com', '$2y$13$JUPptUBKwZK7wX0SQ7fK1.X/e38LfX0jV7svdng/9VLqmD4RbbKvO', 'ddd', 'ddd', 1, 1587469442, 1587469442);

-- --------------------------------------------------------

--
-- Table structure for table `user_agents`
--

CREATE TABLE `user_agents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hash` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `info` text NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `code` (`code`),
  ADD KEY `rank` (`rank`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `model` (`model`),
  ADD KEY `created_at` (`happened_at`),
  ADD KEY `activity_type` (`activity_type`),
  ADD KEY `authorization_id` (`authorization_id`);

--
-- Indexes for table `affiliations`
--
ALTER TABLE `affiliations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `univocal_affiliation` (`user_id`,`organizational_unit_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `rank` (`rank`);

--
-- Indexes for table `apikeys`
--
ALTER TABLE `apikeys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`) USING BTREE,
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_model` (`model`),
  ADD KEY `file_item_id` (`itemId`);

--
-- Indexes for table `authorizations`
--
ALTER TABLE `authorizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `begin_date` (`begin_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `controller_id` (`controller_id`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `method` (`method`),
  ADD KEY `type` (`type`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `co_hosting`
--
ALTER TABLE `co_hosting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `status` (`wf_status`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`(191)),
  ADD KEY `status` (`wf_status`),
  ADD KEY `begin_date` (`begin_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `status` (`status`),
  ADD KEY `organizationa_unit_id` (`organizational_unit_id`),
  ADD KEY `rank` (`rank`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `seen_at` (`seen_at`),
  ADD KEY `sent_at` (`sent_at`),
  ADD KEY `email` (`email`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `organizational_units`
--
ALTER TABLE `organizational_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `rank` (`rank`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `periodical_reports`
--
ALTER TABLE `periodical_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `name` (`name`),
  ADD KEY `begin_date` (`begin_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `status` (`wf_status`);

--
-- Indexes for table `planned_expenses`
--
ALTER TABLE `planned_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `expense_type_id` (`expense_type_id`),
  ADD KEY `amount` (`amount`);

--
-- Indexes for table `postings`
--
ALTER TABLE `postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `amount` (`amount`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `organizationa_unit_id` (`organizational_unit_id`),
  ADD KEY `status` (`wf_status`);

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reimbursements`
--
ALTER TABLE `reimbursements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `requested_amount` (`requested_amount`),
  ADD KEY `reimbursed_amount` (`reimbursed_amount`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `rank` (`rank`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `periodical_report_id` (`periodical_report_id`),
  ADD KEY `date` (`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`wf_status`),
  ADD KEY `vat_number` (`vat_number`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `transaction_template_id` (`transaction_template_id`);

--
-- Indexes for table `transaction_templates`
--
ALTER TABLE `transaction_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `title` (`title`),
  ADD KEY `status` (`status`),
  ADD KEY `rank` (`rank`),
  ADD KEY `needs_project` (`needs_project`),
  ADD KEY `needs_vendor` (`needs_vendor`);

--
-- Indexes for table `transaction_template_postings`
--
ALTER TABLE `transaction_template_postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_template_id` (`transaction_template_id`),
  ADD KEY `rank` (`rank`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `user_agents`
--
ALTER TABLE `user_agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hash` (`hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=382;
--
-- AUTO_INCREMENT for table `affiliations`
--
ALTER TABLE `affiliations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `apikeys`
--
ALTER TABLE `apikeys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `authorizations`
--
ALTER TABLE `authorizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;
--
-- AUTO_INCREMENT for table `co_hosting`
--
ALTER TABLE `co_hosting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;
--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `organizational_units`
--
ALTER TABLE `organizational_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `periodical_reports`
--
ALTER TABLE `periodical_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `planned_expenses`
--
ALTER TABLE `planned_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `postings`
--
ALTER TABLE `postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `reimbursements`
--
ALTER TABLE `reimbursements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `transaction_templates`
--
ALTER TABLE `transaction_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `transaction_template_postings`
--
ALTER TABLE `transaction_template_postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `user_agents`
--
ALTER TABLE `user_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`authorization_id`) REFERENCES `authorizations` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `affiliations`
--
ALTER TABLE `affiliations`
  ADD CONSTRAINT `affiliations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `affiliations_ibfk_2` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`),
  ADD CONSTRAINT `affiliations_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `apikeys`
--
ALTER TABLE `apikeys`
  ADD CONSTRAINT `apikeys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `authorizations`
--
ALTER TABLE `authorizations`
  ADD CONSTRAINT `authorizations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `authorizations_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD CONSTRAINT `expense_types_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `periodical_reports`
--
ALTER TABLE `periodical_reports`
  ADD CONSTRAINT `periodical_reports_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`);

--
-- Constraints for table `planned_expenses`
--
ALTER TABLE `planned_expenses`
  ADD CONSTRAINT `planned_expenses_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `planned_expenses_ibfk_2` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `postings`
--
ALTER TABLE `postings`
  ADD CONSTRAINT `postings_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `postings_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD CONSTRAINT `project_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `project_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`periodical_report_id`) REFERENCES `periodical_reports` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`transaction_template_id`) REFERENCES `transaction_templates` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transaction_templates`
--
ALTER TABLE `transaction_templates`
  ADD CONSTRAINT `transaction_templates_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transaction_template_postings`
--
ALTER TABLE `transaction_template_postings`
  ADD CONSTRAINT `transaction_template_postings_ibfk_1` FOREIGN KEY (`transaction_template_id`) REFERENCES `transaction_templates` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_template_postings_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `user_agents`
--
ALTER TABLE `user_agents`
  ADD CONSTRAINT `user_agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
