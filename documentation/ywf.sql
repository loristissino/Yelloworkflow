-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Ago 21, 2025 alle 14:39
-- Versione del server: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- Versione PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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
-- Struttura della tabella `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `reversed_name` varchar(100) NOT NULL DEFAULT '',
  `status` int(1) NOT NULL DEFAULT 1,
  `code` varchar(40) DEFAULT NULL,
  `debits_header` varchar(60) NOT NULL,
  `credits_header` varchar(60) NOT NULL,
  `represents` char(1) NOT NULL DEFAULT 'R' COMMENT 'S: sale, D: donation, C: contribution, E: expense, R: real value',
  `enforced_balance` char(1) NOT NULL DEFAULT '-',
  `shown_in_ou_view` int(1) NOT NULL DEFAULT 0,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `model` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `info` text NOT NULL DEFAULT '',
  `authorization_id` int(11) DEFAULT NULL,
  `happened_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='log entries for the application';

-- --------------------------------------------------------

--
-- Struttura della tabella `affiliations`
--

CREATE TABLE `affiliations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='link between user and organizational unit';

-- --------------------------------------------------------

--
-- Struttura della tabella `apikeys`
--

CREATE TABLE `apikeys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `app` varchar(100) NOT NULL,
  `value` varchar(32) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `model` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `itemId` int(11) NOT NULL,
  `hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `mime` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `authorizations`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `co_hosting`
--

CREATE TABLE `co_hosting` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'last updater',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='link between event and organizational unit';

-- --------------------------------------------------------

--
-- Struttura della tabella `events`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `expense_types`
--

CREATE TABLE `expense_types` (
  `id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(1) NOT NULL DEFAULT 1,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `plaintext_body` text DEFAULT NULL,
  `html_body` text DEFAULT NULL,
  `headers` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `sent_at` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `addressee` varchar(255) DEFAULT NULL,
  `apikey` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `plaintext_body` text DEFAULT NULL,
  `html_body` text DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `seen_at` int(11) DEFAULT NULL,
  `sent_at` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` int(11) NOT NULL,
  `code` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `title` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `plaintext_body` text NOT NULL,
  `html_body` text DEFAULT NULL,
  `md_body` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `organizational_units`
--

CREATE TABLE `organizational_units` (
  `id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `last_designation_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `ceiling_amount` decimal(10,2) DEFAULT NULL,
  `possible_actions` int(11) NOT NULL DEFAULT 0,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `periodical_reports`
--

CREATE TABLE `periodical_reports` (
  `id` int(11) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `required_attachments` text DEFAULT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `periodical_report_comments`
--

CREATE TABLE `periodical_report_comments` (
  `id` int(11) NOT NULL,
  `periodical_report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `petitions`
--

CREATE TABLE `petitions` (
  `id` int(11) NOT NULL,
  `slug` varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `title` varchar(256) NOT NULL,
  `target` text NOT NULL,
  `introduction` text DEFAULT NULL,
  `picture_url` varchar(256) CHARACTER SET ascii COLLATE ascii_bin DEFAULT NULL,
  `request` text DEFAULT NULL,
  `updates` text DEFAULT NULL,
  `promoted_by` varchar(255) DEFAULT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `launched_at` int(11) DEFAULT NULL,
  `expired_at` int(11) DEFAULT NULL,
  `goal` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `petition_signatures`
--

CREATE TABLE `petition_signatures` (
  `id` int(11) NOT NULL,
  `petition_id` int(11) NOT NULL,
  `email` varchar(120) NOT NULL,
  `first_name` varchar(120) NOT NULL,
  `last_name` varchar(120) NOT NULL,
  `yob` int(4) DEFAULT NULL,
  `district` varchar(3) DEFAULT NULL,
  `gender` varchar(3) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `accepted_terms` varchar(100) NOT NULL,
  `confirmation_code` varchar(10) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `reminded_at` int(11) DEFAULT NULL,
  `validated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `planned_expenses`
--

CREATE TABLE `planned_expenses` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `expense_type_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `postings`
--

CREATE TABLE `postings` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `bond` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `co_hosts` text DEFAULT NULL,
  `partners` text DEFAULT NULL,
  `period` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `project_comments`
--

CREATE TABLE `project_comments` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `questionnaires`
--

CREATE TABLE `questionnaires` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `definition` mediumtext DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `questionnaire_responses`
--

CREATE TABLE `questionnaire_responses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `questionnaire_id` int(11) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `content` mediumtext DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reimbursements`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 1,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `permissions` varchar(511) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
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
  `handling` varchar(255) DEFAULT NULL,
  `vat_number` varchar(20) DEFAULT NULL,
  `vendor` varchar(100) DEFAULT NULL,
  `invoice` varchar(60) DEFAULT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'last updater',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transaction_templates`
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
  `request` varchar(255) DEFAULT NULL,
  `needs_attachment` int(1) NOT NULL DEFAULT 1,
  `needs_project` int(1) NOT NULL DEFAULT 1,
  `needs_vendor` int(1) NOT NULL DEFAULT 1,
  `is_sealable` int(1) NOT NULL DEFAULT 0,
  `office` int(1) NOT NULL DEFAULT 0,
  `extra` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transaction_template_postings`
--

CREATE TABLE `transaction_template_postings` (
  `id` int(11) NOT NULL,
  `transaction_template_id` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `dc` char(1) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'D=debit, C=credit, $=amount',
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
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
  `status` int(11) NOT NULL DEFAULT 1,
  `external_id` int(11) DEFAULT NULL,
  `last_renewal` int(4) DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `last_action_at` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `user_agents`
--

CREATE TABLE `user_agents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hash` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `info` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `viewed_ou_main_activities`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `viewed_ou_main_activities` (
`id` int(11)
,`happened_at` int(11)
,`activity_type` varchar(100)
,`user_id` int(11)
,`first_name` varchar(40)
,`last_name` varchar(40)
,`organizational_unit_id` int(11)
,`name` varchar(100)
,`role_id` int(11)
,`role_description` varchar(255)
);

-- --------------------------------------------------------

--
-- Struttura per vista `viewed_ou_main_activities`
--
DROP TABLE IF EXISTS `viewed_ou_main_activities`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewed_ou_main_activities`  AS SELECT `subquery`.`id` AS `id`, `subquery`.`happened_at` AS `happened_at`, `subquery`.`activity_type` AS `activity_type`, `subquery`.`user_id` AS `user_id`, `subquery`.`first_name` AS `first_name`, `subquery`.`last_name` AS `last_name`, `subquery`.`organizational_unit_id` AS `organizational_unit_id`, `subquery`.`name` AS `name`, `subquery`.`role_id` AS `role_id`, `subquery`.`role_description` AS `role_description` FROM (select `activities`.`id` AS `id`,`activities`.`happened_at` AS `happened_at`,`activities`.`activity_type` AS `activity_type`,`users`.`id` AS `user_id`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`organizational_units`.`id` AS `organizational_unit_id`,`organizational_units`.`name` AS `name`,`roles`.`id` AS `role_id`,`roles`.`description` AS `role_description` from (((((`activities` join `authorizations` on(`activities`.`authorization_id` = `authorizations`.`id`)) join `roles` on(`authorizations`.`role_id` = `roles`.`id`)) join `users` on(`activities`.`user_id` = `users`.`id`)) join `periodical_reports` on(`activities`.`model_id` = `periodical_reports`.`id`)) join `organizational_units` on(`periodical_reports`.`organizational_unit_id` = `organizational_units`.`id`)) where `activities`.`activity_type` in ('PeriodicalReportWorkflow/submitted','PeriodicalReportWorkflow/submitted-empty') union select `activities`.`id` AS `id`,`activities`.`happened_at` AS `happened_at`,`activities`.`activity_type` AS `activity_type`,`users`.`id` AS `user_id`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`organizational_units`.`id` AS `organizational_unit_id`,`organizational_units`.`name` AS `name`,`roles`.`id` AS `role_id`,`roles`.`description` AS `role_description` from (((((`activities` join `authorizations` on(`activities`.`authorization_id` = `authorizations`.`id`)) join `roles` on(`authorizations`.`role_id` = `roles`.`id`)) join `users` on(`activities`.`user_id` = `users`.`id`)) join `projects` on(`activities`.`model_id` = `projects`.`id`)) join `organizational_units` on(`projects`.`organizational_unit_id` = `organizational_units`.`id`)) where `activities`.`activity_type` = 'ProjectWorkflow/submitted') AS `subquery` ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `code` (`code`),
  ADD KEY `rank` (`rank`),
  ADD KEY `name` (`name`),
  ADD KEY `shown_in_ou_view` (`shown_in_ou_view`),
  ADD KEY `reversed_name` (`reversed_name`);

--
-- Indici per le tabelle `activities`
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
-- Indici per le tabelle `affiliations`
--
ALTER TABLE `affiliations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `univocal_affiliation` (`user_id`,`organizational_unit_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `rank` (`rank`),
  ADD KEY `email` (`email`);

--
-- Indici per le tabelle `apikeys`
--
ALTER TABLE `apikeys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`) USING BTREE,
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_model` (`model`),
  ADD KEY `file_item_id` (`itemId`);

--
-- Indici per le tabelle `authorizations`
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
-- Indici per le tabelle `co_hosting`
--
ALTER TABLE `co_hosting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `status` (`wf_status`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`(191)),
  ADD KEY `status` (`wf_status`),
  ADD KEY `begin_date` (`begin_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `status` (`status`),
  ADD KEY `organizationa_unit_id` (`organizational_unit_id`),
  ADD KEY `rank` (`rank`);

--
-- Indici per le tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sent_at` (`sent_at`),
  ADD KEY `email` (`email`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `addressee` (`addressee`(191));

--
-- Indici per le tabelle `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `seen_at` (`seen_at`),
  ADD KEY `sent_at` (`sent_at`),
  ADD KEY `email` (`email`),
  ADD KEY `created_at` (`created_at`);

--
-- Indici per le tabelle `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indici per le tabelle `organizational_units`
--
ALTER TABLE `organizational_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `rank` (`rank`),
  ADD KEY `status` (`status`),
  ADD KEY `last_designation_date` (`last_designation_date`);

--
-- Indici per le tabelle `periodical_reports`
--
ALTER TABLE `periodical_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `name` (`name`),
  ADD KEY `begin_date` (`begin_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `status` (`wf_status`),
  ADD KEY `due_date` (`due_date`);

--
-- Indici per le tabelle `periodical_report_comments`
--
ALTER TABLE `periodical_report_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `periodical_report_id` (`periodical_report_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `petitions`
--
ALTER TABLE `petitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `title` (`title`(191)),
  ADD KEY `picture_url` (`picture_url`),
  ADD KEY `wf_status` (`wf_status`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `launched_at` (`launched_at`),
  ADD KEY `expired_at` (`expired_at`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `promoted_by` (`promoted_by`(191));

--
-- Indici per le tabelle `petition_signatures`
--
ALTER TABLE `petition_signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `petition_id_email` (`petition_id`,`email`) USING BTREE,
  ADD KEY `petition_id` (`petition_id`),
  ADD KEY `email` (`email`),
  ADD KEY `name` (`first_name`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `confirmed_at` (`confirmed_at`),
  ADD KEY `validated_at` (`validated_at`) USING BTREE,
  ADD KEY `yob` (`yob`),
  ADD KEY `district` (`district`),
  ADD KEY `gender` (`gender`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `reminded_at` (`reminded_at`);

--
-- Indici per le tabelle `planned_expenses`
--
ALTER TABLE `planned_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `expense_type_id` (`expense_type_id`),
  ADD KEY `amount` (`amount`);

--
-- Indici per le tabelle `postings`
--
ALTER TABLE `postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `amount` (`amount`);

--
-- Indici per le tabelle `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `organizationa_unit_id` (`organizational_unit_id`),
  ADD KEY `status` (`wf_status`);

--
-- Indici per le tabelle `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `questionnaires`
--
ALTER TABLE `questionnaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`);

--
-- Indici per le tabelle `questionnaire_responses`
--
ALTER TABLE `questionnaire_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `questionnaire_id` (`questionnaire_id`),
  ADD KEY `wf_status` (`wf_status`);

--
-- Indici per le tabelle `reimbursements`
--
ALTER TABLE `reimbursements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `requested_amount` (`requested_amount`),
  ADD KEY `reimbursed_amount` (`reimbursed_amount`);

--
-- Indici per le tabelle `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `email` (`email`),
  ADD KEY `rank` (`rank`);

--
-- Indici per le tabelle `transactions`
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
-- Indici per le tabelle `transaction_templates`
--
ALTER TABLE `transaction_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `title` (`title`),
  ADD KEY `status` (`status`),
  ADD KEY `rank` (`rank`),
  ADD KEY `needs_project` (`needs_project`),
  ADD KEY `needs_vendor` (`needs_vendor`),
  ADD KEY `o_title` (`o_title`),
  ADD KEY `is_sealable` (`is_sealable`),
  ADD KEY `office` (`office`),
  ADD KEY `request` (`request`(191)),
  ADD KEY `extra` (`extra`);

--
-- Indici per le tabelle `transaction_template_postings`
--
ALTER TABLE `transaction_template_postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_template_id` (`transaction_template_id`),
  ADD KEY `rank` (`rank`),
  ADD KEY `account_id` (`account_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `external_id` (`external_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `status` (`status`),
  ADD KEY `last_renewal` (`last_renewal`),
  ADD KEY `last_action_at` (`last_action_at`);

--
-- Indici per le tabelle `user_agents`
--
ALTER TABLE `user_agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hash` (`hash`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `affiliations`
--
ALTER TABLE `affiliations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `apikeys`
--
ALTER TABLE `apikeys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `authorizations`
--
ALTER TABLE `authorizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `co_hosting`
--
ALTER TABLE `co_hosting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `organizational_units`
--
ALTER TABLE `organizational_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `periodical_reports`
--
ALTER TABLE `periodical_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `periodical_report_comments`
--
ALTER TABLE `periodical_report_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `petitions`
--
ALTER TABLE `petitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `petition_signatures`
--
ALTER TABLE `petition_signatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `planned_expenses`
--
ALTER TABLE `planned_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `postings`
--
ALTER TABLE `postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `questionnaires`
--
ALTER TABLE `questionnaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `questionnaire_responses`
--
ALTER TABLE `questionnaire_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reimbursements`
--
ALTER TABLE `reimbursements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `transaction_templates`
--
ALTER TABLE `transaction_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `transaction_template_postings`
--
ALTER TABLE `transaction_template_postings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `user_agents`
--
ALTER TABLE `user_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`authorization_id`) REFERENCES `authorizations` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `affiliations`
--
ALTER TABLE `affiliations`
  ADD CONSTRAINT `affiliations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `affiliations_ibfk_2` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`),
  ADD CONSTRAINT `affiliations_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Limiti per la tabella `apikeys`
--
ALTER TABLE `apikeys`
  ADD CONSTRAINT `apikeys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `authorizations`
--
ALTER TABLE `authorizations`
  ADD CONSTRAINT `authorizations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `authorizations_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `expense_types`
--
ALTER TABLE `expense_types`
  ADD CONSTRAINT `expense_types_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limiti per la tabella `periodical_reports`
--
ALTER TABLE `periodical_reports`
  ADD CONSTRAINT `periodical_reports_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`);

--
-- Limiti per la tabella `periodical_report_comments`
--
ALTER TABLE `periodical_report_comments`
  ADD CONSTRAINT `periodical_report_comments_ibfk_1` FOREIGN KEY (`periodical_report_id`) REFERENCES `periodical_reports` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `periodical_report_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `petition_signatures`
--
ALTER TABLE `petition_signatures`
  ADD CONSTRAINT `petition_signatures_ibfk_1` FOREIGN KEY (`petition_id`) REFERENCES `petitions` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `planned_expenses`
--
ALTER TABLE `planned_expenses`
  ADD CONSTRAINT `planned_expenses_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `planned_expenses_ibfk_2` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `postings`
--
ALTER TABLE `postings`
  ADD CONSTRAINT `postings_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `postings_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `project_comments`
--
ALTER TABLE `project_comments`
  ADD CONSTRAINT `project_comments_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `project_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `questionnaire_responses`
--
ALTER TABLE `questionnaire_responses`
  ADD CONSTRAINT `questionnaire_responses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `questionnaire_responses_ibfk_2` FOREIGN KEY (`questionnaire_id`) REFERENCES `questionnaires` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`periodical_report_id`) REFERENCES `periodical_reports` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`transaction_template_id`) REFERENCES `transaction_templates` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `transaction_templates`
--
ALTER TABLE `transaction_templates`
  ADD CONSTRAINT `transaction_templates_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `transaction_template_postings`
--
ALTER TABLE `transaction_template_postings`
  ADD CONSTRAINT `transaction_template_postings_ibfk_1` FOREIGN KEY (`transaction_template_id`) REFERENCES `transaction_templates` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_template_postings_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Limiti per la tabella `user_agents`
--
ALTER TABLE `user_agents`
  ADD CONSTRAINT `user_agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
