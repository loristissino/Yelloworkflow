-- SQL Dump

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
  `parent_id` int(11) DEFAULT NULL,
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
-- Struttura della tabella `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `first_name` varchar(40) DEFAULT NULL,
  `last_name` varchar(40) DEFAULT NULL,
  `alias` varchar(40) DEFAULT NULL,
  `is_natural_person` tinyint(1) NOT NULL DEFAULT 1,
  `date_of_birth` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `district` char(2) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `tax_code` varchar(16) DEFAULT NULL,
  `occupation` varchar(64) DEFAULT NULL,
  `membership_card_number` int(11) DEFAULT NULL,
  `notes` mediumtext DEFAULT NULL,
  `card_type` tinyint(4) DEFAULT NULL,
  `main_organizational_unit_id` int(11) DEFAULT NULL,
  `allowed_contacts` int(11) NOT NULL DEFAULT 65535,
  `lead_source` varchar(40) DEFAULT NULL,
  `auth_key` varchar(100) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `otp_secret` varchar(128) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contacts_ous_junction`
--

CREATE TABLE `contacts_ous_junction` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `expired_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contacts_tags_junction`
--

CREATE TABLE `contacts_tags_junction` (
  `id` int(11) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `ord` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `expired_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contact_methods`
--

CREATE TABLE `contact_methods` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `contact_method_type_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contact_method_types`
--

CREATE TABLE `contact_method_types` (
  `id` int(11) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `regexp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `contact_tags`
--

CREATE TABLE `contact_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
-- Struttura della tabella `digital_receipts`
--

CREATE TABLE `digital_receipts` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `wf_status` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `digital_receipt_type_id` int(11) NOT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `total_amount` decimal(10,2) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `sent_at` int(11) DEFAULT NULL,
  `processed_at` int(11) DEFAULT NULL,
  `voided_at` int(11) DEFAULT NULL,
  `expo_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `client_id` uuid NOT NULL,
  `assigned_id` varchar(50) DEFAULT NULL,
  `voiding_receipt_assigned_id` varchar(50) DEFAULT NULL,
  `voided_receipt_assigned_id` varchar(50) DEFAULT NULL,
  `document_number` varchar(50) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `sequential_number` int(11) DEFAULT NULL,
  `cash_payment_amount` decimal(10,2) DEFAULT NULL,
  `electronic_payment_amount` decimal(10,2) DEFAULT NULL,
  `api_request` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`api_request`)),
  `api_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`api_response`)),
  `api_callback` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`api_callback`)),
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notes`)),
  `issuer_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`issuer_data`)),
  `receipt_year` int(11) GENERATED ALWAYS AS (year(`date`)) STORED,
  `payment_method` varchar(20) GENERATED ALWAYS AS (case when coalesce(`cash_payment_amount`,0) > 0 and coalesce(`electronic_payment_amount`,0) = 0 then 'cash' when coalesce(`electronic_payment_amount`,0) > 0 and coalesce(`cash_payment_amount`,0) = 0 then 'electronic' when coalesce(`cash_payment_amount`,0) > 0 and coalesce(`electronic_payment_amount`,0) > 0 then 'mixed' else 'unknown' end) VIRTUAL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `digital_receipt_lines`
--

CREATE TABLE `digital_receipt_lines` (
  `id` int(11) NOT NULL,
  `digital_receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `item_assigned_id` varchar(10) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `quantity_returned` int(11) NOT NULL DEFAULT 0,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(10,2) NOT NULL,
  `vat_rate_code` varchar(5) NOT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `line_type` enum('SALE','RETURN','VOIDING') NOT NULL DEFAULT 'SALE',
  `signed_quantity` int(11) GENERATED ALWAYS AS (case when `line_type` = 'SALE' then `quantity` when `line_type` in ('RETURN','VOIDING') then -`quantity` end) STORED,
  `unit_discount` decimal(10,2) GENERATED ALWAYS AS (`discount` / `quantity`) VIRTUAL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `digital_receipt_types`
--

CREATE TABLE `digital_receipt_types` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `label` varchar(50) NOT NULL,
  `explanation` varchar(50) NOT NULL,
  `issued_text` varchar(50) DEFAULT NULL,
  `voiding_text` varchar(50) DEFAULT NULL,
  `return_text` varchar(50) DEFAULT NULL,
  `color` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `sequential_number_code` varchar(5) NOT NULL DEFAULT 'S',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `amount_soft_limit` decimal(10,2) NOT NULL DEFAULT 99999.00,
  `amount_hard_limit` decimal(10,2) NOT NULL DEFAULT 999999.00,
  `validator` varchar(100) NOT NULL,
  `environment` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `coeff` int(11) NOT NULL DEFAULT 1,
  `status` int(1) NOT NULL DEFAULT 1,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `expos`
--

CREATE TABLE `expos` (
  `id` int(11) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `periodical_report_id` int(11) DEFAULT NULL,
  `wf_status` varchar(50) NOT NULL,
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
  `code` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
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
  `phone` varchar(20) DEFAULT NULL,
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
-- Struttura stand-in per le viste `processable_digital_receipts`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `processable_digital_receipts` (
`date` date
,`wf_status` varchar(50)
,`organizational_unit_id` int(11)
,`digital_receipt_type_id` int(11)
,`payment_method` varchar(20)
,`notes` varchar(500)
,`digital_receipt_ids` mediumtext
,`number_of_receipts` bigint(21)
,`sum_of_amounts` decimal(32,2)
,`sum_of_line_amounts` decimal(32,2)
,`sum_of_amounts_22` decimal(32,2)
,`sum_of_amounts_n2` decimal(32,2)
,`sum_of_discounts` decimal(32,2)
,`sum_of_discounts_22` decimal(32,2)
,`sum_of_discounts_n2` decimal(32,2)
,`vat_rate_codes` mediumtext
);

-- --------------------------------------------------------

--
-- Struttura della tabella `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `digital_receipt_type_id` int(11) NOT NULL,
  `organizational_unit_id` int(11) DEFAULT NULL,
  `sku` varchar(100) NOT NULL,
  `ecommerce_code` varchar(100) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `isbn` varchar(13) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `long_description` varchar(500) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `standard_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `internal_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `vat_rate_code` varchar(5) NOT NULL,
  `notes` mediumtext DEFAULT NULL,
  `extra_info_required` varchar(100) DEFAULT NULL,
  `rank` int(11) NOT NULL,
  `requires_sealing` int(1) NOT NULL DEFAULT 0,
  `sales_account_id` int(11) DEFAULT NULL,
  `returns_account_id` int(11) DEFAULT NULL,
  `discounts_account_id` int(11) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Struttura della tabella `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `bond` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `co_hosts` text DEFAULT NULL,
  `partners` text DEFAULT NULL,
  `period` varchar(255) NOT NULL,
  `place` varchar(255) NOT NULL,
  `wf_status` varchar(40) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `organizational_unit_id` int(11) NOT NULL,
  `periodical_report_id` int(11) DEFAULT NULL,
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
-- Struttura della tabella `subcriptions`
--

CREATE TABLE `subcriptions` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `subscription_type_id` int(11) DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `copies` int(11) NOT NULL DEFAULT 1,
  `notes` mediumtext DEFAULT NULL,
  `external_id` int(11) DEFAULT NULL,
  `external_username` varchar(255) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `subscription_types`
--

CREATE TABLE `subscription_types` (
  `id` int(11) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `code` varchar(10) NOT NULL,
  `default_begin_date` date DEFAULT NULL,
  `default_end_date` date DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `content` blob DEFAULT NULL,
  `config` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='memberships; magazine subscription; web forums; privacy term';

-- --------------------------------------------------------

--
-- Struttura della tabella `test`
--

CREATE TABLE `test` (
  `a` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `periodical_report_id` int(11) NOT NULL,
  `transaction_template_id` int(11) NOT NULL,
  `expo_id` int(11) DEFAULT NULL,
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
  `phone` varchar(20) DEFAULT NULL,
  `auth_key` varchar(100) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `access_token` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `otp_secret` varchar(128) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
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
-- Struttura per vista `processable_digital_receipts`
--
DROP TABLE IF EXISTS `processable_digital_receipts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ywfdbuser`@`localhost` SQL SECURITY DEFINER VIEW `processable_digital_receipts`  AS SELECT `digital_receipts`.`date` AS `date`, `digital_receipts`.`wf_status` AS `wf_status`, `digital_receipts`.`organizational_unit_id` AS `organizational_unit_id`, `digital_receipts`.`digital_receipt_type_id` AS `digital_receipt_type_id`, `digital_receipts`.`payment_method` AS `payment_method`, `digital_receipt_lines`.`notes` AS `notes`, group_concat(distinct `digital_receipts`.`id` order by `digital_receipts`.`id` ASC separator ',') AS `digital_receipt_ids`, count(0) AS `number_of_receipts`, sum(`digital_receipts`.`total_amount`) AS `sum_of_amounts`, sum(`digital_receipt_lines`.`amount`) AS `sum_of_line_amounts`, greatest(sum(case when `digital_receipt_lines`.`vat_rate_code` = '22' then `digital_receipt_lines`.`amount` else 0 end),0) AS `sum_of_amounts_22`, greatest(sum(case when `digital_receipt_lines`.`vat_rate_code` = 'N2' then `digital_receipt_lines`.`amount` else 0 end),0) AS `sum_of_amounts_n2`, greatest(sum(`digital_receipt_lines`.`discount`),0) AS `sum_of_discounts`, greatest(sum(case when `digital_receipt_lines`.`vat_rate_code` = '22' then `digital_receipt_lines`.`discount` else 0 end),0) AS `sum_of_discounts_22`, greatest(sum(case when `digital_receipt_lines`.`vat_rate_code` = 'N2' then `digital_receipt_lines`.`discount` else 0 end),0) AS `sum_of_discounts_n2`, group_concat(distinct `digital_receipt_lines`.`vat_rate_code` order by `digital_receipt_lines`.`vat_rate_code` ASC separator ',') AS `vat_rate_codes` FROM (`digital_receipts` join `digital_receipt_lines` on(`digital_receipts`.`id` = `digital_receipt_lines`.`digital_receipt_id`)) GROUP BY `digital_receipts`.`organizational_unit_id`, `digital_receipts`.`digital_receipt_type_id`, `digital_receipts`.`payment_method`, `digital_receipt_lines`.`notes` ;

-- --------------------------------------------------------

--
-- Struttura per vista `viewed_ou_main_activities`
--
DROP TABLE IF EXISTS `viewed_ou_main_activities`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ywfdbuser`@`localhost` SQL SECURITY DEFINER VIEW `viewed_ou_main_activities`  AS SELECT `subquery`.`id` AS `id`, `subquery`.`happened_at` AS `happened_at`, `subquery`.`activity_type` AS `activity_type`, `subquery`.`user_id` AS `user_id`, `subquery`.`first_name` AS `first_name`, `subquery`.`last_name` AS `last_name`, `subquery`.`organizational_unit_id` AS `organizational_unit_id`, `subquery`.`name` AS `name`, `subquery`.`role_id` AS `role_id`, `subquery`.`role_description` AS `role_description` FROM (select `activities`.`id` AS `id`,`activities`.`happened_at` AS `happened_at`,`activities`.`activity_type` AS `activity_type`,`users`.`id` AS `user_id`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`organizational_units`.`id` AS `organizational_unit_id`,`organizational_units`.`name` AS `name`,`roles`.`id` AS `role_id`,`roles`.`description` AS `role_description` from (((((`activities` join `authorizations` on(`activities`.`authorization_id` = `authorizations`.`id`)) join `roles` on(`authorizations`.`role_id` = `roles`.`id`)) join `users` on(`activities`.`user_id` = `users`.`id`)) join `periodical_reports` on(`activities`.`model_id` = `periodical_reports`.`id`)) join `organizational_units` on(`periodical_reports`.`organizational_unit_id` = `organizational_units`.`id`)) where `activities`.`activity_type` in ('PeriodicalReportWorkflow/submitted','PeriodicalReportWorkflow/submitted-empty') union select `activities`.`id` AS `id`,`activities`.`happened_at` AS `happened_at`,`activities`.`activity_type` AS `activity_type`,`users`.`id` AS `user_id`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`organizational_units`.`id` AS `organizational_unit_id`,`organizational_units`.`name` AS `name`,`roles`.`id` AS `role_id`,`roles`.`description` AS `role_description` from (((((`activities` join `authorizations` on(`activities`.`authorization_id` = `authorizations`.`id`)) join `roles` on(`authorizations`.`role_id` = `roles`.`id`)) join `users` on(`activities`.`user_id` = `users`.`id`)) join `projects` on(`activities`.`model_id` = `projects`.`id`)) join `organizational_units` on(`projects`.`organizational_unit_id` = `organizational_units`.`id`)) where `activities`.`activity_type` = 'ProjectWorkflow/submitted') AS `subquery` ;

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
  ADD KEY `reversed_name` (`reversed_name`),
  ADD KEY `parent_id` (`parent_id`);

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
-- Indici per le tabelle `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `main_organizational_unit_id` (`main_organizational_unit_id`);

--
-- Indici per le tabelle `contacts_ous_junction`
--
ALTER TABLE `contacts_ous_junction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`);

--
-- Indici per le tabelle `contacts_tags_junction`
--
ALTER TABLE `contacts_tags_junction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `model_id` (`model_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indici per le tabelle `contact_methods`
--
ALTER TABLE `contact_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contact_priority_uniqueness` (`contact_id`,`contact_method_type_id`,`priority`),
  ADD KEY `contact_method_type_id` (`contact_method_type_id`);

--
-- Indici per le tabelle `contact_method_types`
--
ALTER TABLE `contact_method_types`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `contact_tags`
--
ALTER TABLE `contact_tags`
  ADD PRIMARY KEY (`id`);

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
-- Indici per le tabelle `digital_receipts`
--
ALTER TABLE `digital_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`),
  ADD UNIQUE KEY `unique_receipt_per_year_type` (`sequential_number`,`receipt_year`,`digital_receipt_type_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `digital_receipts_ibfk_1` (`digital_receipt_type_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `expo_id` (`expo_id`);

--
-- Indici per le tabelle `digital_receipt_lines`
--
ALTER TABLE `digital_receipt_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `digital_receipt_lines_ibfk_1` (`digital_receipt_id`),
  ADD KEY `digital_receipt_lines_ibfk_2` (`product_id`),
  ADD KEY `item_assigned_id` (`item_assigned_id`);

--
-- Indici per le tabelle `digital_receipt_types`
--
ALTER TABLE `digital_receipt_types`
  ADD PRIMARY KEY (`id`);

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
-- Indici per le tabelle `expos`
--
ALTER TABLE `expos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `begin_date` (`begin_date`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `city` (`city`),
  ADD KEY `name` (`name`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `wf_status` (`wf_status`),
  ADD KEY `periodical_report_id` (`periodical_report_id`);

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
  ADD KEY `last_designation_date` (`last_designation_date`),
  ADD KEY `phone` (`phone`);

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
-- Indici per le tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `ecommerce_code` (`ecommerce_code`),
  ADD KEY `products_ibfk_1` (`digital_receipt_type_id`),
  ADD KEY `organizational_unit_id` (`organizational_unit_id`),
  ADD KEY `rank` (`rank`),
  ADD KEY `account_id` (`sales_account_id`),
  ADD KEY `fk_returns_account` (`returns_account_id`),
  ADD KEY `fk_discounts_account` (`discounts_account_id`);

--
-- Indici per le tabelle `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `organizationa_unit_id` (`organizational_unit_id`),
  ADD KEY `status` (`wf_status`),
  ADD KEY `periodical_report_id` (`periodical_report_id`);

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
-- Indici per le tabelle `subcriptions`
--
ALTER TABLE `subcriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_id` (`contact_id`),
  ADD KEY `subscription_type_id` (`subscription_type_id`);

--
-- Indici per le tabelle `subscription_types`
--
ALTER TABLE `subscription_types`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `transaction_template_id` (`transaction_template_id`),
  ADD KEY `expo_id` (`expo_id`);

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
  ADD KEY `last_action_at` (`last_action_at`),
  ADD KEY `phone` (`phone`);

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
-- AUTO_INCREMENT per la tabella `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `contacts_ous_junction`
--
ALTER TABLE `contacts_ous_junction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `contacts_tags_junction`
--
ALTER TABLE `contacts_tags_junction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `contact_methods`
--
ALTER TABLE `contact_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `contact_method_types`
--
ALTER TABLE `contact_method_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `contact_tags`
--
ALTER TABLE `contact_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `co_hosting`
--
ALTER TABLE `co_hosting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `digital_receipts`
--
ALTER TABLE `digital_receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `digital_receipt_lines`
--
ALTER TABLE `digital_receipt_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `digital_receipt_types`
--
ALTER TABLE `digital_receipt_types`
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
-- AUTO_INCREMENT per la tabella `expos`
--
ALTER TABLE `expos`
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
-- AUTO_INCREMENT per la tabella `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT per la tabella `subcriptions`
--
ALTER TABLE `subcriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `subscription_types`
--
ALTER TABLE `subscription_types`
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
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;

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
-- Limiti per la tabella `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`main_organizational_unit_id`) REFERENCES `organizational_units` (`id`);

--
-- Limiti per la tabella `contacts_ous_junction`
--
ALTER TABLE `contacts_ous_junction`
  ADD CONSTRAINT `contacts_ous_junction_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  ADD CONSTRAINT `contacts_ous_junction_ibfk_2` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`);

--
-- Limiti per la tabella `contacts_tags_junction`
--
ALTER TABLE `contacts_tags_junction`
  ADD CONSTRAINT `contacts_tags_junction_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `contacts` (`id`),
  ADD CONSTRAINT `contacts_tags_junction_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `contact_tags` (`id`);

--
-- Limiti per la tabella `contact_methods`
--
ALTER TABLE `contact_methods`
  ADD CONSTRAINT `contact_methods_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  ADD CONSTRAINT `contact_methods_ibfk_2` FOREIGN KEY (`contact_method_type_id`) REFERENCES `contact_method_types` (`id`);

--
-- Limiti per la tabella `digital_receipts`
--
ALTER TABLE `digital_receipts`
  ADD CONSTRAINT `digital_receipts_ibfk_1` FOREIGN KEY (`digital_receipt_type_id`) REFERENCES `digital_receipt_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `digital_receipts_ibfk_2` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `digital_receipts_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `digital_receipts_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `digital_receipts_ibfk_5` FOREIGN KEY (`parent_id`) REFERENCES `digital_receipts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `digital_receipts_ibfk_6` FOREIGN KEY (`expo_id`) REFERENCES `expos` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `digital_receipt_lines`
--
ALTER TABLE `digital_receipt_lines`
  ADD CONSTRAINT `digital_receipt_lines_ibfk_1` FOREIGN KEY (`digital_receipt_id`) REFERENCES `digital_receipts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `digital_receipt_lines_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

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
-- Limiti per la tabella `expos`
--
ALTER TABLE `expos`
  ADD CONSTRAINT `expos_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `expos_ibfk_2` FOREIGN KEY (`periodical_report_id`) REFERENCES `periodical_reports` (`id`) ON UPDATE CASCADE;

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
-- Limiti per la tabella `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_discounts_account` FOREIGN KEY (`discounts_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `fk_returns_account` FOREIGN KEY (`returns_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `fk_sales_account` FOREIGN KEY (`sales_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`digital_receipt_type_id`) REFERENCES `digital_receipt_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`sales_account_id`) REFERENCES `accounts` (`id`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`organizational_unit_id`) REFERENCES `organizational_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`periodical_report_id`) REFERENCES `periodical_reports` (`id`) ON UPDATE CASCADE;

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
-- Limiti per la tabella `subcriptions`
--
ALTER TABLE `subcriptions`
  ADD CONSTRAINT `subcriptions_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  ADD CONSTRAINT `subcriptions_ibfk_2` FOREIGN KEY (`subscription_type_id`) REFERENCES `subscription_types` (`id`);

--
-- Limiti per la tabella `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`periodical_report_id`) REFERENCES `periodical_reports` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`transaction_template_id`) REFERENCES `transaction_templates` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`expo_id`) REFERENCES `expos` (`id`) ON UPDATE CASCADE;

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
