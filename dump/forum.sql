-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas generowania: 31 Lip 2017, 00:58
-- Wersja serwera: 5.5.57-0+deb8u1
-- Wersja PHP: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `forum`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_achievements`
--

CREATE TABLE IF NOT EXISTS `qa_achievements` (
  `user_id` int(11) NOT NULL,
  `first_visit` datetime DEFAULT NULL,
  `oldest_consec_visit` datetime DEFAULT NULL,
  `longest_consec_visit` int(10) DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `total_days_visited` int(10) DEFAULT NULL,
  `questions_read` int(10) DEFAULT NULL,
  `posts_edited` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_achievements`
--

INSERT INTO `qa_achievements` (`user_id`, `first_visit`, `oldest_consec_visit`, `longest_consec_visit`, `last_visit`, `total_days_visited`, `questions_read`, `posts_edited`) VALUES
(1, '2016-05-16 20:14:03', '2016-05-16 20:14:03', 1, '2016-05-16 20:29:47', 1, 0, 0),
(7, '2016-05-16 20:18:58', '2016-05-16 20:18:58', 1, '2016-05-16 20:25:40', 1, 1, 0),
(2, '2016-07-26 20:53:02', '2017-07-31 00:40:45', 1, '2017-07-31 00:57:36', 3, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_blobs`
--

CREATE TABLE IF NOT EXISTS `qa_blobs` (
  `blobid` bigint(20) unsigned NOT NULL,
  `format` varchar(20) CHARACTER SET ascii NOT NULL,
  `content` mediumblob,
  `filename` varchar(255) DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT NULL,
  `cookieid` bigint(20) unsigned DEFAULT NULL,
  `createip` int(10) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_blobs`
--

INSERT INTO `qa_blobs` (`blobid`, `format`, `content`, `filename`, `userid`, `cookieid`, `createip`, `created`) VALUES
(12386828404904007216, 'jpeg', 0xffd8ffe000104a46494600010101006000600000fffe003b43524541544f523a2067642d6a7065672076312e3020287573696e6720494a47204a50454720763632292c207175616c697479203d2039300affdb0043000302020302020303030304030304050805050404050a070706080c0a0c0c0b0a0b0b0d0e12100d0e110e0b0b1016101113141515150c0f171816141812141514ffdb00430103040405040509050509140d0b0d1414141414141414141414141414141414141414141414141414141414141414141414141414141414141414141414141414ffc00011080050005003012200021101031101ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a737475767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffda000c03010002110311003f00fadf8a5a4fc68fc6800fe2a36d1f8d1f8d001b68fc2af58e85a8ea4bbad6c6e2e13fbf1c64afe78c52df683a969abbaeac6e6dd3fbf24642fe78c50050fc28fc28fc68fc6800fc28fc28fc68fc680168a28a002bd3fc05f0fa1fb2c7a8ea910964906e86ddc7caabd9987727d3fc8e0fc2da72eade22d3ed5c6639251bc7aa8e48fc81afa180c0c0e0500355446a1540551c003a0a5650e0a9190460834fa2803cdbc7bf0f2192d65d474b88453460bcb6e830ae3b951d8fb77fe7e5b5f4dd7cf5e2fd35349f12ea16a836c69292a07656f980fc8d0064d145140051451401b3e0cbd5d3fc53a6cf210104a1493d837cb9fd6be81fc2be641c1af65f00f8e22d72d22b3bb9026a518dbf31ff005c0771efea3f1a00ede93f0a6d1400efc2bc07c717a9a878af529a320a79bb011df680bfd2bd33c79e378b40b492d2d64126a522ed014e7ca07f88fbfa0af1627272792680168a28a004fc68fc696a5b3b49afeee1b6810bcd2b04451dc9a00b9a0e8179e22bd16f669b8f5791b8541ea4d7aff877e1e699a0aa48d18bdbc1c99a61900ffb2bd07f3f7ad0f0bf8760f0d6991dac403487e6965c72eddcfd3d2b6e8013f1a4fc69d4500729e22f87ba66beaf208c59de373e7c23193fed2f43fcfdebc835ed02f3c3b7a6def1369ea922f2ae3d41afa1eb1fc51e1d83c4ba6496b280b20f9a2971ca3763f4f5a00f9fbf1a3f1a9af2d66b0bb9ada7429344e5194f620d45400577ff0008b46173a9dcea322e56d9764791fc6dd4fe03f9d7015ed1f0a6d041e134900e6799dcfe076ffecb401d9d14527e1400b4ca7d32801f4ca5fc292803c9be2ee8e2df53b6d4635c2dcaec931fde5e87f11fcab80af68f8ad6a27f09bc847304c8e0fd4edffd9abc5e803fffd9, NULL, NULL, NULL, NULL, '2017-07-31 00:53:09');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_blockades`
--

CREATE TABLE IF NOT EXISTS `qa_blockades` (
`id_blockade` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1=email',
  `content` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_blockades`
--

INSERT INTO `qa_blockades` (`id_blockade`, `type`, `content`, `id_user`, `added_date`) VALUES
(1, 1, 'example2.com', 2, '2016-07-24 18:06:26'),
(2, 1, 'example.com', 2, '2016-07-24 19:17:06'),
(3, 1, 'examplee.com', 2, '2016-07-24 21:10:27'),
(4, 1, 'aaa.pl', 2, '2016-07-24 21:14:11');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_cache`
--

CREATE TABLE IF NOT EXISTS `qa_cache` (
  `type` char(8) CHARACTER SET ascii NOT NULL,
  `cacheid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumblob NOT NULL,
  `created` datetime NOT NULL,
  `lastread` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_categories`
--

CREATE TABLE IF NOT EXISTS `qa_categories` (
`categoryid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `title` varchar(80) NOT NULL,
  `tags` varchar(200) NOT NULL,
  `content` varchar(800) NOT NULL DEFAULT '',
  `qcount` int(10) unsigned NOT NULL DEFAULT '0',
  `position` smallint(5) unsigned NOT NULL,
  `backpath` varchar(804) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_categories`
--

INSERT INTO `qa_categories` (`categoryid`, `parentid`, `title`, `tags`, `content`, `qcount`, `position`, `backpath`) VALUES
(1, NULL, 'Programowanie', 'programowanie', 'Pytania dotyczące rozwiązywania problemów koderskich w konkretnych językach programowania i technologiach. Tu zdobywamy konkretną wiedzę, rozwijamy umiejętności, nie offtopujemy za dużo.', 4, 1, 'programowanie'),
(2, 1, 'C i C++', 'c-plus-plus', 'C++ to najlepszy start w programowanie dla każdego. Poznaj składnię języka, rozwiń analityczne myślenie, naucz się kodować analizując konkretne przykłady i problemy. Pytania mogą dotyczyć zarówno programowania proceduralnego jak i obiektowego.', 2, 1, 'c-plus-plus/programowanie'),
(3, 1, 'HTML i CSS', 'html-css', 'Programowanie webowe najlepiej rozpocząć od pisania w HTML, który służy do wstawienia zawartości strony www (tekstu, obrazów, formularzy) oraz w CSS, który opisuje wygląd witryny. Tutaj można zadawać pytania dotyczące tych dwóch technologii frontendowych.', 2, 2, 'html-css/programowanie'),
(4, NULL, 'Sprzęt komputerowy', 'sprzet-komputerowy', 'Sekcja hardware na forum. Dyskusje na temat podzespołów, urządzeń i wszelkiego rodzaju peryferii komputerowych: diagnostyka, zakup sprzętu, zestawy komputerowe, laptopy, akcesoria, podkręcanie, testowanie.', 3, 2, 'sprzet-komputerowy');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_categorymetas`
--

CREATE TABLE IF NOT EXISTS `qa_categorymetas` (
  `categoryid` int(10) unsigned NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` varchar(8000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_contentwords`
--

CREATE TABLE IF NOT EXISTS `qa_contentwords` (
  `postid` int(10) unsigned NOT NULL,
  `wordid` int(10) unsigned NOT NULL,
  `count` tinyint(3) unsigned NOT NULL,
  `type` enum('Q','A','C','NOTE') NOT NULL,
  `questionid` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_contentwords`
--

INSERT INTO `qa_contentwords` (`postid`, `wordid`, `count`, `type`, `questionid`) VALUES
(1, 1, 1, 'Q', 1),
(1, 2, 1, 'Q', 1),
(1, 3, 1, 'Q', 1),
(2, 1, 1, 'Q', 2),
(2, 2, 1, 'Q', 2),
(2, 4, 1, 'Q', 2),
(3, 1, 1, 'Q', 3),
(3, 2, 1, 'Q', 3),
(3, 6, 1, 'Q', 3),
(4, 1, 1, 'Q', 4),
(4, 2, 1, 'Q', 4),
(4, 7, 1, 'Q', 4),
(5, 2, 1, 'Q', 5),
(5, 8, 1, 'Q', 5),
(6, 2, 1, 'Q', 6),
(6, 8, 1, 'Q', 6),
(6, 9, 1, 'Q', 6),
(6, 10, 1, 'Q', 6),
(6, 11, 1, 'Q', 6),
(6, 12, 1, 'Q', 6),
(7, 2, 1, 'Q', 7),
(7, 13, 1, 'Q', 7);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_cookies`
--

CREATE TABLE IF NOT EXISTS `qa_cookies` (
  `cookieid` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `createip` int(10) unsigned NOT NULL,
  `written` datetime DEFAULT NULL,
  `writeip` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_eventlog`
--

CREATE TABLE IF NOT EXISTS `qa_eventlog` (
  `datetime` datetime NOT NULL,
  `ipaddress` varchar(15) CHARACTER SET ascii DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT NULL,
  `handle` varchar(20) DEFAULT NULL,
  `cookieid` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(20) CHARACTER SET ascii NOT NULL,
  `params` varchar(800) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_eventlog`
--

INSERT INTO `qa_eventlog` (`datetime`, `ipaddress`, `userid`, `handle`, `cookieid`, `event`, `params`) VALUES
('2016-05-16 19:53:27', '192.168.112.1', 1, 'test', NULL, 'u_logout', ''),
('2016-05-16 19:53:36', '192.168.112.1', 1, 'test', NULL, 'u_login', ''),
('2016-05-16 20:01:26', '192.168.112.1', 1, 'test', NULL, 'u_save', ''),
('2016-05-16 20:01:50', '192.168.112.1', 1, 'superadmin', NULL, 'u_save', ''),
('2016-05-16 20:02:16', '192.168.112.1', 1, 'superadmin', NULL, 'u_logout', ''),
('2016-05-16 20:02:34', '192.168.112.1', 1, 'superadmin', NULL, 'u_login', ''),
('2016-05-16 20:02:57', '192.168.112.1', 1, 'superadmin', NULL, 'u_login', ''),
('2016-05-16 20:02:57', '192.168.112.1', 1, 'superadmin', NULL, 'u_password', ''),
('2016-05-16 20:03:04', '192.168.112.1', 1, 'superadmin', NULL, 'u_logout', ''),
('2016-05-16 20:03:13', '192.168.112.1', 2, 'admin', NULL, 'u_register', 'email=admin@example.com	level=0'),
('2016-05-16 20:03:13', '192.168.112.1', 2, 'admin', NULL, 'u_login', ''),
('2016-05-16 20:03:20', '192.168.112.1', 2, 'admin', NULL, 'u_logout', ''),
('2016-05-16 20:03:26', '192.168.112.1', 1, 'superadmin', NULL, 'u_login', ''),
('2016-05-16 20:04:43', '192.168.112.1', 1, 'superadmin', NULL, 'q_post', 'postid=1	parentid=	parent=	title=Testowe pytanie superadministratora	content=Testowe pytanie superadministratora	format=	text=Testowe pytanie superadministratora	tags=pytanie	categoryid=2	extra=	name=	notify=	email='),
('2016-05-16 20:04:50', '192.168.112.1', 1, 'superadmin', NULL, 'u_logout', ''),
('2016-05-16 20:04:55', '192.168.112.1', 2, 'admin', NULL, 'u_login', ''),
('2016-05-16 20:05:24', '192.168.112.1', 2, 'admin', NULL, 'q_post', 'postid=2	parentid=	parent=	title=Testowe pytanie administratora	content=Testowe pytanie administratora	format=	text=Testowe pytanie administratora	tags=pytanie,admin	categoryid=4	extra=	name=	notify=	email='),
('2016-05-16 20:06:06', '192.168.112.1', 2, 'admin', NULL, 'u_logout', ''),
('2016-05-16 20:06:30', '192.168.112.1', 3, 'moderator', NULL, 'u_register', 'email=moderator@example.com	level=0'),
('2016-05-16 20:06:30', '192.168.112.1', 3, 'moderator', NULL, 'u_login', ''),
('2016-05-16 20:06:47', '192.168.112.1', 3, 'moderator', NULL, 'q_post', 'postid=3	parentid=	parent=	title=Testowe pytanie moderatora	content=Testowe pytanie moderatora	format=	text=Testowe pytanie moderatora	tags=pytanie	categoryid=3	extra=	name=	notify=	email='),
('2016-05-16 20:06:51', '192.168.112.1', 3, 'moderator', NULL, 'u_logout', ''),
('2016-05-16 20:07:05', '192.168.112.1', 4, 'redaktor', NULL, 'u_register', 'email=redaktor@example.com	level=0'),
('2016-05-16 20:07:05', '192.168.112.1', 4, 'redaktor', NULL, 'u_login', ''),
('2016-05-16 20:07:29', '192.168.112.1', 4, 'redaktor', NULL, 'q_post', 'postid=4	parentid=	parent=	title=Testowe pytanie redaktora	content=Testowe pytanie redaktora	format=	text=Testowe pytanie redaktora	tags=pytanie,testowe	categoryid=2	extra=	name=	notify=	email='),
('2016-05-16 20:07:33', '192.168.112.1', 4, 'redaktor', NULL, 'u_logout', ''),
('2016-05-16 20:07:49', '192.168.112.1', 5, 'ekspert', NULL, 'u_register', 'email=ekspert@example.com	level=0'),
('2016-05-16 20:07:49', '192.168.112.1', 5, 'ekspert', NULL, 'u_login', ''),
('2016-05-16 20:08:04', '192.168.112.1', 5, 'ekspert', NULL, 'q_post', 'postid=5	parentid=	parent=	title=Pytanie eksperta	content=Pytanie eksperta	format=	text=Pytanie eksperta	tags=pytanie	categoryid=4	extra=	name=	notify=	email='),
('2016-05-16 20:08:13', '192.168.112.1', 5, 'ekspert', NULL, 'u_logout', ''),
('2016-05-16 20:08:28', '192.168.112.1', 6, 'ekspertkategoria', NULL, 'u_register', 'email=ekspertkategoria@example.com	level=0'),
('2016-05-16 20:08:28', '192.168.112.1', 6, 'ekspertkategoria', NULL, 'u_login', ''),
('2016-05-16 20:08:52', '192.168.112.1', 6, 'ekspertkategoria', NULL, 'q_post', 'postid=6	parentid=	parent=	title=Pytanie eksperta kategorii CSS i HTML	content=Pytanie eksperta kategorii CSS i HTML	format=	text=Pytanie eksperta kategorii CSS i HTML	tags=pytanie	categoryid=3	extra=	name=	notify=	email='),
('2016-05-16 20:08:56', '192.168.112.1', 6, 'ekspertkategoria', NULL, 'u_logout', ''),
('2016-05-16 20:13:35', '192.168.112.1', 1, 'superadmin', NULL, 'u_login', ''),
('2016-05-16 20:17:23', '192.168.112.1', 1, 'superadmin', NULL, 'u_edit', 'userid=2	handle=admin'),
('2016-05-16 20:17:23', '192.168.112.1', 1, 'superadmin', NULL, 'u_level', 'userid=2	handle=admin	level=100	oldlevel=0'),
('2016-05-16 20:17:33', '192.168.112.1', 1, 'superadmin', NULL, 'u_edit', 'userid=3	handle=moderator'),
('2016-05-16 20:17:33', '192.168.112.1', 1, 'superadmin', NULL, 'u_level', 'userid=3	handle=moderator	level=80	oldlevel=0'),
('2016-05-16 20:17:38', '192.168.112.1', 1, 'superadmin', NULL, 'u_edit', 'userid=4	handle=redaktor'),
('2016-05-16 20:17:38', '192.168.112.1', 1, 'superadmin', NULL, 'u_level', 'userid=4	handle=redaktor	level=50	oldlevel=0'),
('2016-05-16 20:17:44', '192.168.112.1', 1, 'superadmin', NULL, 'u_edit', 'userid=5	handle=ekspert'),
('2016-05-16 20:17:44', '192.168.112.1', 1, 'superadmin', NULL, 'u_level', 'userid=5	handle=ekspert	level=20	oldlevel=0'),
('2016-05-16 20:17:59', '192.168.112.1', 1, 'superadmin', NULL, 'u_edit', 'userid=6	handle=ekspertkategoria'),
('2016-05-16 20:18:46', '192.168.112.1', 1, 'superadmin', NULL, 'u_logout', ''),
('2016-05-16 20:18:58', '192.168.112.1', 7, 'user', NULL, 'u_register', 'email=user@example.com	level=0'),
('2016-05-16 20:18:58', '192.168.112.1', 7, 'user', NULL, 'u_login', ''),
('2016-05-16 20:19:12', '192.168.112.1', 7, 'user', NULL, 'q_post', 'postid=7	parentid=	parent=	title=Pytanie użytkownika	content=Pytanie użytkownika	format=	text=Pytanie użytkownika	tags=pytanie	categoryid=4	extra=	name=	notify=	email='),
('2016-05-16 20:25:43', '192.168.112.1', 7, 'user', NULL, 'u_logout', ''),
('2016-05-16 20:25:59', '192.168.112.1', 1, 'superadmin', NULL, 'u_login', ''),
('2017-02-13 23:45:41', '192.168.112.1', 2, 'admin', NULL, 'u_login', ''),
('2017-02-13 23:51:08', '192.168.112.1', 2, 'admin', NULL, 'u_logout', ''),
('2017-07-31 00:40:45', '192.168.100.2', 2, 'admin', NULL, 'u_login', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_iplimits`
--

CREATE TABLE IF NOT EXISTS `qa_iplimits` (
  `ip` int(10) unsigned NOT NULL,
  `action` char(1) CHARACTER SET ascii NOT NULL,
  `period` int(10) unsigned NOT NULL,
  `count` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_iplimits`
--

INSERT INTO `qa_iplimits` (`ip`, `action`, `period`, `count`) VALUES
(3232261122, 'L', 417070, 1),
(3232264193, 'L', 413062, 2),
(3232264193, 'Q', 406506, 7),
(3232264193, 'R', 406506, 6);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_messages`
--

CREATE TABLE IF NOT EXISTS `qa_messages` (
`messageid` int(10) unsigned NOT NULL,
  `type` enum('PUBLIC','PRIVATE') NOT NULL DEFAULT 'PRIVATE',
  `fromuserid` int(10) unsigned NOT NULL,
  `touserid` int(10) unsigned NOT NULL,
  `fromhidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tohidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` varchar(15000) NOT NULL,
  `format` varchar(20) CHARACTER SET ascii NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_online_user`
--

CREATE TABLE IF NOT EXISTS `qa_online_user` (
`id` int(11) NOT NULL,
  `user_id` int(1) NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
  `last_activity` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_online_user`
--

INSERT INTO `qa_online_user` (`id`, `user_id`, `ip`, `last_activity`) VALUES
(6, 2, '192.168.100.2', '2017-07-31 00:57:36');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_options`
--

CREATE TABLE IF NOT EXISTS `qa_options` (
  `title` varchar(40) NOT NULL,
  `content` varchar(15000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_options`
--

INSERT INTO `qa_options` (`title`, `content`) VALUES
('activity_time_out', ''),
('adsense_publisher_id', ''),
('allow_change_usernames', '0'),
('allow_close_questions', '1'),
('allow_login_email_only', '0'),
('allow_multi_answers', '1'),
('allow_no_category', ''),
('allow_no_sub_category', ''),
('allow_private_messages', '1'),
('allow_self_answer', '1'),
('allow_user_walls', '1'),
('allow_view_q_bots', '1'),
('ami_sss_btn_em', ''),
('ami_sss_btn_fb', '1'),
('ami_sss_btn_gp', '1'),
('ami_sss_btn_li', ''),
('ami_sss_btn_re', ''),
('ami_sss_btn_st', ''),
('ami_sss_btn_status', '1'),
('ami_sss_btn_tg', ''),
('ami_sss_btn_tw', '1'),
('ami_sss_btn_vk', ''),
('ami_sss_btn_wa', ''),
('ami_sss_costum_css', ''),
('ami_sss_default_share_image', ''),
('ami_sss_enable_opengraph', ''),
('ami_sss_fb_id', ''),
('ami_sss_text', ''),
('ami_sss_text_blog_post', ''),
('ami_sss_text_home', ''),
('ami_sss_twitter_handle', ''),
('ami_sss_type_q_desktop_opt', 'squared_btns_with_icon'),
('ami_sss_type_q_mob_opt', 'squared_btns_with_icon'),
('ami_sss_type_w_desktop_opt', 'squared_btns_with_icon'),
('ami_sss_type_w_mob_opt', 'squared_btns_with_icon'),
('ami_sss_website_desc', ''),
('aol_app_enabled', '0'),
('aol_app_id', ''),
('aol_app_secret', ''),
('aol_app_shortcut', '0'),
('approve_user_required', '0'),
('avatar_allow_gravatar', '1'),
('avatar_allow_upload', '1'),
('avatar_default_blobid', '12386828404904007216'),
('avatar_default_height', '80'),
('avatar_default_show', '1'),
('avatar_default_width', '80'),
('avatar_message_list_size', '20'),
('avatar_profile_size', '200'),
('avatar_q_list_size', '0'),
('avatar_q_page_a_size', '40'),
('avatar_q_page_c_size', '20'),
('avatar_q_page_q_size', '50'),
('avatar_store_size', '400'),
('avatar_users_size', '30'),
('badges_css', '.notify-container {\r\n	left: 0;\r\n	right: 0;\r\n	top: 0;\r\n	padding: 0;\r\n	position: fixed;\r\n	width: 100%;\r\n	z-index: 10000;\r\n}\r\n.badge-container-badge {\r\n	white-space: nowrap;\r\n}\r\n.badge-notify {\r\n	background-color: #F6DF30;\r\n	color: #444444;\r\n	font-weight: bold;\r\n	width: 100%;\r\n	text-align: center;\r\n	font-family: sans-serif;\r\n	font-size: 14px;\r\n	padding: 10px 0;\r\n	position:relative;\r\n}\r\n.notify-close {\r\n	color: #735005;\r\n	cursor: pointer;\r\n	font-size: 18px;\r\n	line-height: 18px;\r\n	padding: 0 3px;\r\n	position: absolute;\r\n	right: 8px;\r\n	text-decoration: none;\r\n	top: 8px;\r\n}				\r\n#badge-form td {\r\n	vertical-align:top;\r\n}\r\n.badge-bronze,.badge-silver, .badge-gold {\r\n	margin-right:4px;\r\n	color: #000;\r\n	font-weight:bold;\r\n	text-align:center;\r\n	border-radius:4px;\r\n	width:120px;\r\n	padding: 5px 10px;\r\n	display: inline-block;\r\n}\r\n.badge-bronze {\r\n	background-color: #CB9114;\r\n\r\n	background-image: -webkit-linear-gradient(left center , #CB9114, #EDB336, #CB9114, #A97002, #CB9114); \r\n	background-image:    -moz-linear-gradient(left center , #CB9114, #EDB336, #CB9114, #A97002, #CB9114);\r\n	background-image:     -ms-linear-gradient(left center , #CB9114, #EDB336, #CB9114, #A97002, #CB9114); \r\n	background-image:      -o-linear-gradient(left center , #CB9114, #EDB336, #CB9114, #A97002, #CB9114); \r\n	background-image:         linear-gradient(left center , #CB9114, #EDB336, #CB9114, #A97002, #CB9114); /* standard, but currently unimplemented */\r\n\r\n	border:2px solid #6C582C;\r\n}				\r\n.badge-silver {\r\n	background-color: #CDCDCD;\r\n	background-image: -webkit-linear-gradient(left center , #CDCDCD, #EFEFEF, #CDCDCD, #ABABAB, #CDCDCD); \r\n	background-image:    -moz-linear-gradient(left center , #CDCDCD, #EFEFEF, #CDCDCD, #ABABAB, #CDCDCD); \r\n	background-image:     -ms-linear-gradient(left center , #CDCDCD, #EFEFEF, #CDCDCD, #ABABAB, #CDCDCD); \r\n	background-image:      -o-linear-gradient(left center , #CDCDCD, #EFEFEF, #CDCDCD, #ABABAB, #CDCDCD); \r\n	background-image:         linear-gradient(left center , #CDCDCD, #EFEFEF, #CDCDCD, #ABABAB, #CDCDCD); /* standard, but currently unimplemented */\r\n	border:2px solid #737373;\r\n}				\r\n.badge-gold {\r\n	background-color: #EEDD0F;\r\n	background-image: -webkit-linear-gradient(left center , #EEDD0F, #FFFF2F, #EEDD0F, #CCBB0D, #EEDD0F); \r\n	background-image:    -moz-linear-gradient(left center , #EEDD0F, #FFFF2F, #EEDD0F, #CCBB0D, #EEDD0F); \r\n	background-image:     -ms-linear-gradient(left center , #EEDD0F, #FFFF2F, #EEDD0F, #CCBB0D, #EEDD0F); \r\n	background-image:      -o-linear-gradient(left center , #EEDD0F, #FFFF2F, #EEDD0F, #CCBB0D, #EEDD0F); \r\n	background-image:         linear-gradient(left center , #EEDD0F, #FFFF2F, #EEDD0F, #CCBB0D, #EEDD0F); /* standard, but currently unimplemented */\r\n	border:2px solid #7E7B2A;\r\n}				\r\n.badge-bronze-medal, .badge-silver-medal, .badge-gold-medal  {\r\n	font-size: 14px;\r\n	font-family:sans-serif;\r\n}\r\n.badge-bronze-medal {\r\n	color: #CB9114;\r\n}				\r\n.badge-silver-medal {\r\n	color: #CDCDCD;\r\n}				\r\n.badge-gold-medal {\r\n	color: #EEDD0F;\r\n}\r\n.badge-pointer {\r\n	cursor:pointer;\r\n}				\r\n.badge-desc {\r\n	padding-left:8px;\r\n}			\r\n.badge-count {\r\n	font-weight:bold;\r\n}			\r\n.badge-count-link {\r\n	cursor:pointer;\r\n	color:#992828;\r\n}			\r\n.badge-source {\r\n	text-align:center;\r\n	padding:0;\r\n}\r\n.badge-widget-entry {\r\n	white-space:nowrap;\r\n}\r\n'),
('badge_10000_club_desc', ''),
('badge_10000_club_enabled', '0'),
('badge_10000_club_name', '10,000 Club'),
('badge_10000_club_var', '10000'),
('badge_1000_club_desc', ''),
('badge_1000_club_enabled', '0'),
('badge_1000_club_name', '1,000 Club'),
('badge_1000_club_var', '1000'),
('badge_100_club_desc', ''),
('badge_100_club_enabled', '0'),
('badge_100_club_name', '100 Club'),
('badge_100_club_var', '100'),
('badge_active', '1'),
('badge_admin_loggedin_widget', ''),
('badge_admin_user_field', ''),
('badge_admin_user_field_no_tab', ''),
('badge_admin_user_widget', ''),
('badge_admin_user_widget_q_item', ''),
('badge_ancestor_desc', ''),
('badge_ancestor_enabled', '0'),
('badge_ancestor_name', 'Ancestor'),
('badge_ancestor_var', '365'),
('badge_annotator_desc', ''),
('badge_annotator_enabled', '0'),
('badge_annotator_name', 'Annotator'),
('badge_annotator_var', '500'),
('badge_answerer_desc', ''),
('badge_answerer_enabled', '0'),
('badge_answerer_name', 'Answerer'),
('badge_answerer_var', '25'),
('badge_asker_desc', ''),
('badge_asker_enabled', '0'),
('badge_asker_name', 'Asker'),
('badge_asker_var', '10'),
('badge_avatar_desc', ''),
('badge_avatar_enabled', '0'),
('badge_avatar_name', 'Photogenic'),
('badge_avatar_var', ''),
('badge_avid_reader_desc', ''),
('badge_avid_reader_enabled', '0'),
('badge_avid_reader_name', 'Avid Reader'),
('badge_avid_reader_var', '50'),
('badge_avid_voter_desc', ''),
('badge_avid_voter_enabled', '0'),
('badge_avid_voter_name', 'Avid Voter'),
('badge_avid_voter_var', '50'),
('badge_bloodhound_desc', ''),
('badge_bloodhound_enabled', '0'),
('badge_bloodhound_name', 'Bloodhound'),
('badge_bloodhound_var', '10'),
('badge_champion_desc', ''),
('badge_champion_enabled', '0'),
('badge_champion_name', 'Champion'),
('badge_champion_var', '30'),
('badge_commentator_desc', ''),
('badge_commentator_enabled', '0'),
('badge_commentator_name', 'Commentator'),
('badge_commentator_var', '100'),
('badge_commenter_desc', ''),
('badge_commenter_enabled', '0'),
('badge_commenter_name', 'Commenter'),
('badge_commenter_var', '50'),
('badge_copy_editor_desc', ''),
('badge_copy_editor_enabled', '0'),
('badge_copy_editor_name', 'Copy Editor'),
('badge_copy_editor_var', '15'),
('badge_dedicated_desc', ''),
('badge_dedicated_enabled', '0'),
('badge_dedicated_name', 'Dedicated'),
('badge_dedicated_var', '10'),
('badge_devoted_desc', ''),
('badge_devoted_enabled', '0'),
('badge_devoted_name', 'Devoted'),
('badge_devoted_reader_desc', ''),
('badge_devoted_reader_enabled', '0'),
('badge_devoted_reader_name', 'Devoted Reader'),
('badge_devoted_reader_var', '200'),
('badge_devoted_var', '25'),
('badge_devoted_voter_desc', ''),
('badge_devoted_voter_enabled', '0'),
('badge_devoted_voter_name', 'Devoted Voter'),
('badge_devoted_voter_var', '200'),
('badge_editor_desc', ''),
('badge_editor_enabled', '0'),
('badge_editor_name', 'Editor'),
('badge_editor_var', '1'),
('badge_email_body', 'Congratulations!  You have earned a "^badge_name" badge from ^site_title ^if_post_text="for the following post:\r\n\r\n^post_title\r\n^post_url"\r\n\r\nPlease log in and visit your profile:\r\n\r\n^profile_url\r\n\r\nYou may cancel these notices at any time by visiting your profile at the link above.'),
('badge_email_notify', ''),
('badge_email_notify_on', ''),
('badge_email_subject', '[^site_title] '),
('badge_enlightened_desc', ''),
('badge_enlightened_enabled', '0'),
('badge_enlightened_name', 'Enlightened'),
('badge_enlightened_var', '30'),
('badge_famous_question_desc', ''),
('badge_famous_question_enabled', '0'),
('badge_famous_question_name', 'Famous Question'),
('badge_famous_question_var', '500'),
('badge_gifted_desc', ''),
('badge_gifted_enabled', '0'),
('badge_gifted_name', 'Gifted'),
('badge_gifted_var', '1'),
('badge_good_answer_desc', ''),
('badge_good_answer_enabled', '0'),
('badge_good_answer_name', 'Good Answer'),
('badge_good_answer_old_desc', ''),
('badge_good_answer_old_enabled', '0'),
('badge_good_answer_old_name', 'Revival'),
('badge_good_answer_old_var', '60'),
('badge_good_answer_var', '5'),
('badge_good_comment_desc', ''),
('badge_good_comment_enabled', '0'),
('badge_good_comment_name', '[badges/good_comment]'),
('badge_good_comment_var', '5'),
('badge_good_question_desc', ''),
('badge_good_question_enabled', '0'),
('badge_good_question_name', 'Good Question'),
('badge_good_question_var', '5'),
('badge_grateful_desc', ''),
('badge_grateful_enabled', '0'),
('badge_grateful_name', 'Grateful'),
('badge_grateful_var', '1'),
('badge_great_answer_desc', ''),
('badge_great_answer_enabled', '0'),
('badge_great_answer_name', 'Great Answer'),
('badge_great_answer_old_desc', ''),
('badge_great_answer_old_enabled', '0'),
('badge_great_answer_old_name', 'Resurrection'),
('badge_great_answer_old_var', '120'),
('badge_great_answer_var', '10'),
('badge_great_comment_desc', ''),
('badge_great_comment_enabled', '0'),
('badge_great_comment_name', '[badges/great_comment]'),
('badge_great_comment_var', '10'),
('badge_great_question_desc', ''),
('badge_great_question_enabled', '0'),
('badge_great_question_name', 'Great Question'),
('badge_great_question_var', '10'),
('badge_inquisitor_desc', ''),
('badge_inquisitor_enabled', '0'),
('badge_inquisitor_name', 'Inquisitor'),
('badge_inquisitor_var', '50'),
('badge_lecturer_desc', ''),
('badge_lecturer_enabled', '0'),
('badge_lecturer_name', 'Lecturer'),
('badge_lecturer_var', '50'),
('badge_liked_desc', ''),
('badge_liked_enabled', '0'),
('badge_liked_name', 'Liked'),
('badge_liked_var', '20'),
('badge_loved_desc', ''),
('badge_loved_enabled', '0'),
('badge_loved_name', 'Loved'),
('badge_loved_var', '50'),
('badge_medalist_desc', ''),
('badge_medalist_enabled', '0'),
('badge_medalist_name', 'Medalist'),
('badge_medalist_var', '10'),
('badge_nice_answer_desc', ''),
('badge_nice_answer_enabled', '0'),
('badge_nice_answer_name', 'Nice Answer'),
('badge_nice_answer_old_desc', ''),
('badge_nice_answer_old_enabled', '0'),
('badge_nice_answer_old_name', 'Renewal'),
('badge_nice_answer_old_var', '30'),
('badge_nice_answer_var', '2'),
('badge_nice_comment_desc', ''),
('badge_nice_comment_enabled', '0'),
('badge_nice_comment_name', '[badges/nice_comment]'),
('badge_nice_comment_var', '2'),
('badge_nice_question_desc', ''),
('badge_nice_question_enabled', '0'),
('badge_nice_question_name', 'Nice Question'),
('badge_nice_question_var', '2'),
('badge_notable_question_desc', ''),
('badge_notable_question_enabled', '0'),
('badge_notable_question_name', 'Notable Question'),
('badge_notable_question_var', '50'),
('badge_notify_time', '0'),
('badge_old_timer_desc', ''),
('badge_old_timer_enabled', '0'),
('badge_old_timer_name', 'Old-Timer'),
('badge_old_timer_var', '180'),
('badge_olympian_desc', ''),
('badge_olympian_enabled', '0'),
('badge_olympian_name', 'Olympian'),
('badge_olympian_var', '100'),
('badge_pitbull_desc', ''),
('badge_pitbull_enabled', '0'),
('badge_pitbull_name', 'Pitbull'),
('badge_pitbull_var', '30'),
('badge_popular_question_desc', ''),
('badge_popular_question_enabled', '0'),
('badge_popular_question_name', 'Popular Question'),
('badge_popular_question_var', '100'),
('badge_preacher_desc', ''),
('badge_preacher_enabled', '0'),
('badge_preacher_name', 'Preacher'),
('badge_preacher_var', '100'),
('badge_profiler_desc', ''),
('badge_profiler_enabled', '0'),
('badge_profiler_name', 'Autobiographer'),
('badge_profiler_var', ''),
('badge_questioner_desc', ''),
('badge_questioner_enabled', '0'),
('badge_questioner_name', 'Questioner'),
('badge_questioner_var', '25'),
('badge_reader_desc', ''),
('badge_reader_enabled', '0'),
('badge_reader_name', 'Reader'),
('badge_reader_var', '20'),
('badge_regular_desc', ''),
('badge_regular_enabled', '0'),
('badge_regular_name', 'Regular'),
('badge_regular_var', '90'),
('badge_respectful_desc', ''),
('badge_respectful_enabled', '0'),
('badge_respectful_name', 'Respectful'),
('badge_respectful_var', '20'),
('badge_revered_desc', ''),
('badge_revered_enabled', '0'),
('badge_revered_name', 'Revered'),
('badge_revered_var', '200'),
('badge_reverential_desc', ''),
('badge_reverential_enabled', '0'),
('badge_reverential_name', 'Reverential'),
('badge_reverential_var', '50'),
('badge_senior_editor_desc', ''),
('badge_senior_editor_enabled', '0'),
('badge_senior_editor_name', 'Senior Editor'),
('badge_senior_editor_var', '50'),
('badge_show_source_posts', ''),
('badge_show_source_users', ''),
('badge_show_users_badges', ''),
('badge_trouper_desc', ''),
('badge_trouper_enabled', '0'),
('badge_trouper_name', 'Trouper'),
('badge_trouper_var', '100'),
('badge_verified_desc', ''),
('badge_verified_enabled', '0'),
('badge_verified_name', 'Verified Human'),
('badge_verified_var', ''),
('badge_veteran_desc', ''),
('badge_veteran_enabled', '0'),
('badge_veteran_name', 'Veteran'),
('badge_veteran_var', '200'),
('badge_visitor_desc', ''),
('badge_visitor_enabled', '0'),
('badge_visitor_name', 'Visitor'),
('badge_visitor_var', '30'),
('badge_voter_desc', ''),
('badge_voter_enabled', '0'),
('badge_voter_name', 'Voter'),
('badge_voter_var', '10'),
('badge_watchdog_desc', ''),
('badge_watchdog_enabled', '0'),
('badge_watchdog_name', 'Watchdog'),
('badge_watchdog_var', '1'),
('badge_widget_date_max', '0'),
('badge_widget_list_max', '0'),
('badge_wise_desc', ''),
('badge_wise_enabled', '0'),
('badge_wise_name', 'Wise'),
('badge_wise_var', '10'),
('badge_zealous_desc', ''),
('badge_zealous_enabled', '0'),
('badge_zealous_name', 'Zealous'),
('badge_zealous_var', '50'),
('block_bad_words', ''),
('block_ips_write', ''),
('cache_acount', '0'),
('cache_ccount', '0'),
('cache_flaggedcount', ''),
('cache_qcount', '7'),
('cache_queuedcount', ''),
('cache_tagcount', '3'),
('cache_uapprovecount', '2'),
('cache_unaqcount', '7'),
('cache_unselqcount', '7'),
('cache_unupaqcount', '7'),
('cache_userpointscount', '7'),
('captcha_module', 'reCAPTCHA'),
('captcha_on_anon_post', '1'),
('captcha_on_feedback', '1'),
('captcha_on_register', '1'),
('captcha_on_reset_password', '1'),
('captcha_on_unapproved', '0'),
('captcha_on_unconfirmed', '0'),
('ckeditor4_config', 'toolbarCanCollapse:false,\nremovePlugins:''elementspath,contextmenu,tabletools,liststyle'',\nresize_enabled:true,\nautogrow:false,\nentities:false,extraPlugins:''syntaxhighlight'',\ndisableNativeSpellChecker:false,\npasteFromWordRemoveFontStyles:true,\npasteFromWordRemoveStyles:true'),
('ckeditor4_config_advanced', 'toolbarCanCollapse:false,\nremovePlugins:''elementspath'',\nresize_enabled:false,\nautogrow:false,\nentities:false'),
('ckeditor4_htmLawed_anti_link_spam', '/.*/,'),
('ckeditor4_htmLawed_controler', '0'),
('ckeditor4_htmLawed_elements', '*+embed+object-form'),
('ckeditor4_htmLawed_hook_tag', 'qa_sanitize_html_hook_tag'),
('ckeditor4_htmLawed_keep_bad', '0'),
('ckeditor4_htmLawed_safe', '1'),
('ckeditor4_htmLawed_schemes', 'href: aim, feed, file, ftp, gopher, http, https, irc, mailto, news, nntp, sftp, ssh, telnet; *:file, http, https; style: !; classid:clsid'),
('ckeditor4_inline_editing', '0'),
('ckeditor4_select', '0'),
('ckeditor4_skin', 'office2013'),
('ckeditor4_toolbar', '[''Bold'',''Italic'',''Underline'',''Strike''],\n[''Font'',''FontSize''],\n[''TextColor'',''BGColor''],\n[''Link'',''Unlink''],\n[''NumberedList'',''BulletedList'',''-'',''Outdent'',''Indent'',''Blockquote''],\n[''JustifyLeft'',''JustifyCenter'',''JustifyRight'',''JustifyBlock''],\n[''Image'',''Table'',''HorizontalRule'',''Smiley'',''SpecialChar''],\n[''RemoveFormat'',''Syntaxhighlight'']'),
('ckeditor4_toolbar_advanced', '[''Bold'',''Italic'',''Underline'',''Strike''],\n[''Font'',''FontSize''],\n[''TextColor'',''BGColor''],\n[''Link'',''Unlink''],\n[''JustifyLeft'',''JustifyCenter'',''JustifyRight'',''JustifyBlock''],\n[''NumberedList'',''BulletedList'',''-'',''Outdent'',''Indent'',''Blockquote''],\n[''Image'',''Flash'',''Table'',''HorizontalRule'',''Smiley'',''SpecialChar''],\n[''RemoveFormat'',''Maximize'']'),
('ckeditor4_upload_all', '0'),
('ckeditor4_upload_images', '1'),
('ckeditor4_upload_max_size', '2097152'),
('columns_tags', '3'),
('columns_users', '2'),
('comment_on_as', '1'),
('comment_on_qs', '1'),
('confirm_user_emails', '0'),
('confirm_user_required', '0'),
('custom_answer', ''),
('custom_ask', ''),
('custom_comment', ''),
('custom_footer', ''),
('custom_header', ''),
('custom_home_content', ''),
('custom_home_heading', ''),
('custom_in_head', '<link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32">\n<link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16">\n\n<style> html {visibility:hidden;} </style>\n<script>\nif(self == top)\n{ document.documentElement.style.visibility="visible"; }\nelse { top.location = self.location; }\n</script>\n\n<link href=''https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,latin-ext'' rel=''stylesheet'' type=''text/css''>\n\n<link rel="stylesheet" href="/qa-content/css/shCore.css" type="text/css">\n<link rel="stylesheet" href="/qa-content/css/shThemeDefault.css" type="text/css">\n\n\n<script type="text/javascript" src="/qa-content/javascript/shCore.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shAutoloader.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shLegacy.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushAppleScript.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushAS3.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushBash.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushColdFusion.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushCpp.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushCSharp.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushCss.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushDelphi.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushDiff.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushErlang.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushGroovy.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushJavaFX.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushJava.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushJScript.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushPerl.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushPhp.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushPlain.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushPowerShell.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushPython.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushRuby.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushSass.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushScala.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushSql.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushVb.js"></script>\n<script type="text/javascript" src="/qa-content/javascript/shBrushXml.js"></script>\n<script type="text/javascript" src="/qa-content/cookies.js"></script>\n	\n<script type="text/javascript">\n			\n		CookieAlert.init({\n		style: ''dark'',\n		position: ''bottom'',\n		opacity: ''0.9'',\n		displayTime: 20000,\n		cookiePolicy: ''http://wszystkoociasteczkach.pl'',\n		text: ''Informacja: Serwis niniejszy wykorzystuje pliki cookies''\n		});\n</script>\n\n<script type="text/javascript" src="/qa-content/javascript/imgpre.js"></script>\n\n<script type="text/javascript">\n     SyntaxHighlighter.all()\n</script>'),
('custom_register', ''),
('custom_sidebar', 'Witaj w serwisie Pasja informatyki local, w którym możesz zadawać pytania innym użytkownikom i ekspertom, dzielić się wiedzą z innymi i zdobywać wiedzę na liczne tematy.'),
('custom_sidepanel', ''),
('custom_welcome', ''),
('db_version', '59'),
('do_ask_check_qs', '1'),
('do_close_on_select', '0'),
('do_complete_tags', '1'),
('do_count_q_views', '1'),
('do_example_tags', '1'),
('editor_for_as', 'CKEditor4'),
('editor_for_cs', 'CKEditor4'),
('editor_for_qs', 'CKEditor4'),
('embed_enable', '1'),
('embed_enable_gmap', ''),
('embed_enable_img', ''),
('embed_enable_mp3', ''),
('embed_enable_thickbox', ''),
('embed_gmap_height', '349'),
('embed_gmap_width', '425'),
('embed_image_height', '349'),
('embed_image_width', '425'),
('embed_mp3_player_code', '<object type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_mini.swf" width="200" height="20"><param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_mini.swf" ><param name="bgcolor" value="#000000" ><param name="FlashVars" value="mp3=$1" ></object>'),
('embed_smileys', ''),
('embed_smileys_animated', ''),
('embed_smileys_css', '\r\n				.smiley-button {\r\n					cursor:pointer !important;\r\n				}\r\n				.smiley-box {\r\n					background: none repeat scroll 0 0 rgba(255, 255, 255, 0.8) !important;\r\n					border: 1px solid black !important;\r\n					padding: 10px !important;\r\n					display: none;\r\n					width: 378px;\r\n					margin: 7px 0 0 20px;\r\n					z-index: 1000 !important;\r\n					position: absolute !important;\r\n				}\r\n				.wmd-button-bar{\r\n					min-height:16px;\r\n					width:auto !important;\r\n					margin-right:36px !important;\r\n					position:relative !important;\r\n				}\r\n				.wmd-button-bar .smiley-button {\r\n					position:absolute !important;\r\n					right:-25px !important;\r\n					top:3px !important;\r\n				}\r\n				.wmd-button-bar	.smiley-box {\r\n					margin: 24px 0 0 169px !important;\r\n				}\r\n				.smiley-child {\r\n					margin:4px !important;\r\n					cursor:pointer !important;\r\n				}\r\n				'),
('embed_smileys_editor_button', ''),
('embed_smileys_markdown_button', ''),
('embed_video_height', '349'),
('embed_video_width', '425'),
('event_logger_directory', ''),
('event_logger_hide_header', ''),
('event_logger_to_database', '1'),
('event_logger_to_files', ''),
('extra_field_active', '0'),
('extra_field_display', '0'),
('extra_field_label', ''),
('extra_field_prompt', ''),
('facebook_app_enabled', '1'),
('facebook_app_id', ''),
('facebook_app_secret', ''),
('facebook_app_shortcut', '1'),
('featured_questions_list', ''),
('featured_question_css', '\r\n.qa-q-list-item-featured {\r\n    background-color:#FFC;\r\n}\r\n'),
('feedback_email', 'test@example.com'),
('feedback_enabled', '1'),
('feed_for_activity', '1'),
('feed_for_hot', '1'),
('feed_for_qa', '1'),
('feed_for_questions', '1'),
('feed_for_search', '0'),
('feed_for_tag_qs', '1'),
('feed_for_unanswered', '1'),
('feed_full_text', '1'),
('feed_number_items', '50'),
('feed_per_category', '1'),
('flagging_hide_after', '5'),
('flagging_notify_every', '2'),
('flagging_notify_first', '1'),
('flagging_of_posts', '1'),
('follow_on_as', '1'),
('form_security_salt', 'xis0kgc2wyt7n0fjxaq2fbvtcsgdcyg1'),
('foursquare_app_enabled', '0'),
('foursquare_app_id', ''),
('foursquare_app_secret', ''),
('foursquare_app_shortcut', '0'),
('from_email', 'no-reply@forum.pasja-informatyki.local'),
('github_app_enabled', '0'),
('github_app_id', ''),
('github_app_secret', ''),
('github_app_shortcut', '0'),
('googleplus_app_enabled', '0'),
('googleplus_app_id', ''),
('googleplus_app_secret', ''),
('googleplus_app_shortcut', '0'),
('google_app_enabled', '0'),
('google_app_id', ''),
('google_app_secret', ''),
('google_app_shortcut', '0'),
('home_description', ''),
('hot_weight_answers', '100'),
('hot_weight_a_age', '60'),
('hot_weight_q_age', '100'),
('hot_weight_views', '100'),
('hot_weight_votes', '100'),
('linkedin_app_enabled', '0'),
('linkedin_app_id', ''),
('linkedin_app_secret', ''),
('linkedin_app_shortcut', '0'),
('links_in_new_window', '1'),
('live_app_enabled', '0'),
('live_app_id', ''),
('live_app_secret', ''),
('live_app_shortcut', '0'),
('logo_height', ''),
('logo_show', '1'),
('logo_url', '/images/logo.png'),
('logo_width', ''),
('mailing_body', '\n\n\n--\nPasja informatyki local\nhttp://forum.pasja-informatyki.local/'),
('mailing_enabled', ''),
('mailing_from_email', 'no-reply@forum.pasja-informatyki.local'),
('mailing_from_name', 'Pasja informatyki local'),
('mailing_last_userid', ''),
('mailing_per_minute', '500'),
('mailing_subject', 'Wiadomość od Pasja informatyki local'),
('match_ask_check_qs', '3'),
('match_example_tags', '3'),
('match_related_qs', '3'),
('max_len_q_title', '120'),
('max_num_q_tags', '5'),
('max_rate_ip_as', '50'),
('max_rate_ip_cs', '40'),
('max_rate_ip_flags', '10'),
('max_rate_ip_logins', '20'),
('max_rate_ip_messages', '10'),
('max_rate_ip_qs', '20'),
('max_rate_ip_registers', '10'),
('max_rate_ip_uploads', '20'),
('max_rate_ip_votes', '600'),
('max_rate_user_as', '25'),
('max_rate_user_cs', '20'),
('max_rate_user_flags', '5'),
('max_rate_user_messages', '5'),
('max_rate_user_qs', '10'),
('max_rate_user_uploads', '10'),
('max_rate_user_votes', '300'),
('max_store_user_updates', '50'),
('min_len_a_content', '3'),
('min_len_c_content', '3'),
('min_len_q_content', '0'),
('min_len_q_title', '12'),
('min_num_q_tags', '1'),
('moderate_anon_post', ''),
('moderate_by_points', '0'),
('moderate_edited_again', '0'),
('moderate_notify_admin', '1'),
('moderate_points_limit', '150'),
('moderate_unapproved', '0'),
('moderate_unconfirmed', ''),
('moderate_update_time', '1'),
('moderate_users', '0'),
('mouseover_content_max_len', '480'),
('mouseover_content_on', '1'),
('myspace_app_enabled', '0'),
('myspace_app_id', ''),
('myspace_app_secret', ''),
('myspace_app_shortcut', '0'),
('nav_activity', '0'),
('nav_ask', '0'),
('nav_categories', '0'),
('nav_hot', '0'),
('nav_qa_is_home', '0'),
('nav_questions', '0'),
('nav_tags', '0'),
('nav_unanswered', '0'),
('nav_users', '0'),
('neat_urls', '1'),
('notice_visitor', ''),
('notice_welcome', ''),
('notify_admin_q_post', ''),
('notify_users_default', '0'),
('open_login_css', '1'),
('open_login_hideform', '0'),
('open_login_ok', '3'),
('open_login_remember', '1'),
('open_login_zocial', '0'),
('pages_prev_next', '5'),
('page_size_activity', '20'),
('page_size_ask_check_qs', '5'),
('page_size_ask_tags', '5'),
('page_size_home', '20'),
('page_size_hot_qs', '20'),
('page_size_pms', '10'),
('page_size_qs', '20'),
('page_size_q_as', '20'),
('page_size_related_qs', '3'),
('page_size_search', '10'),
('page_size_tags', '30'),
('page_size_tag_qs', '20'),
('page_size_una_qs', '20'),
('page_size_users', '30'),
('page_size_wall', '10'),
('permit_anon_view_ips', '70'),
('permit_anon_view_ips_points', ''),
('permit_close_poll', '40'),
('permit_close_poll_points', ''),
('permit_close_q', '70'),
('permit_close_q_points', ''),
('permit_delete_hidden', '40'),
('permit_delete_hidden_points', ''),
('permit_delete_poll', '20'),
('permit_delete_poll_points', ''),
('permit_edit_a', '70'),
('permit_edit_a_points', ''),
('permit_edit_c', '70'),
('permit_edit_c_points', ''),
('permit_edit_q', '70'),
('permit_edit_q_points', ''),
('permit_edit_silent', '40'),
('permit_edit_silent_points', ''),
('permit_flag', '110'),
('permit_flag_points', ''),
('permit_hide_show', '100'),
('permit_hide_show_points', ''),
('permit_moderate', '70'),
('permit_moderate_points', ''),
('permit_post_a', '110'),
('permit_post_a_points', ''),
('permit_post_c', '110'),
('permit_post_c_points', ''),
('permit_post_poll', '120'),
('permit_post_poll_points', ''),
('permit_post_q', '110'),
('permit_post_q_points', ''),
('permit_post_wall', '110'),
('permit_post_wall_points', ''),
('permit_retag_cat', '70'),
('permit_retag_cat_points', ''),
('permit_select_a', '100'),
('permit_select_a_points', ''),
('permit_view_q_page', '150'),
('permit_view_voters_flaggers', '20'),
('permit_view_voters_flaggers_points', ''),
('permit_vote_a', '110'),
('permit_vote_a_points', ''),
('permit_vote_c', '110'),
('permit_vote_c_points', ''),
('permit_vote_down', '110'),
('permit_vote_down_points', ''),
('permit_vote_poll', '120'),
('permit_vote_poll_points', ''),
('permit_vote_q', '110'),
('permit_vote_q_points', ''),
('points_a_selected', '30'),
('points_a_voted_max_gain', '20'),
('points_a_voted_max_loss', '5'),
('points_base', '100'),
('points_multiple', '10'),
('points_per_a_voted', ''),
('points_per_a_voted_down', '2'),
('points_per_a_voted_up', '2'),
('points_per_q_voted', ''),
('points_per_q_voted_down', '1'),
('points_per_q_voted_up', '1'),
('points_post_a', '4'),
('points_post_q', '2'),
('points_q_voted_max_gain', '10'),
('points_q_voted_max_loss', '3'),
('points_select_a', '3'),
('points_to_titles', ''),
('points_vote_down_a', '1'),
('points_vote_down_q', '1'),
('points_vote_on_a', ''),
('points_vote_on_q', ''),
('points_vote_up_a', '1'),
('points_vote_up_q', '1'),
('poll_css', '#qa-poll-div {\n	background-color: #D9E3EA;\n	border: 1px solid #658296;\n	font-size: 14px;\n	padding: 10px;\n	margin-top: 15px;\n	margin-bottom: 10px;\n}\n#qa-poll-choices-title {\n	font-weight:bold;\n	margin-bottom:20px;\n	font-size: 15px;\n\n}\n.qa-poll-choice {\n	clear:both;\n	padding: 8px 0 8px 10px;\n        max-width:540px;\n}\n#qa-poll-choices > div:last-child  {\n	padding-bottom:0px;\n}\n#qa-poll-choices > div:first-child  {\n	padding-top:0px;\n}\n\n.qa-poll-choice-title {\n	line-height:12px;\n	margin-left:10px;\n}\n.qa-poll-votes {\n	max-width:500px;\n	height:10px;\n	margin: 5px 0px 0px 0px;\n}\n.qa-poll-vote-block {\n	width:10px;\n	height:20px;\n	background-color:#3498DB;\n}\n.qa-poll-vote-block-empty {\n	width:10px;\n	height:10px;\n}\n.qa-poll-voted-button, .qa-poll-vote-button {\n	cursor:pointer;\n	width:12px;\n	height:12px;\n	float:left;\n	margin-top: 1px;\n}\n.qa-poll-disabled-button {\n	width:12px;\n	height:12px;\n	float:left;\n	margin-top: 1px;\n	background-image:url(^button_vote.png);\n        margin-top: 5px;\n}\n.qa-poll-voted-button {\n	background-image:url(^button_voted.png);\n        margin-top: 5px;\n}\n.qa-poll-vote-button {\n	background-image:url(^button_vote.png);\n        margin-top: 5px;\n}\n.qa-poll-vote-button:hover, .qa-poll-voted-button:hover {\n	background-image:url(^button_voting.png);\n        margin-top: 5px;\n}'),
('poll_enable', '1'),
('poll_enable_subnav', '1'),
('poll_update_on_vote', ''),
('poll_votes_hide', ''),
('poll_votes_percent', '1'),
('poll_vote_change', '1'),
('q2am_custom_home_content', ''),
('q2am_sidebar_activity', ''),
('q2am_sidebar_admin', ''),
('q2am_sidebar_ask', ''),
('q2am_sidebar_categories', ''),
('q2am_sidebar_custom', ''),
('q2am_sidebar_custom_pages', 'chat-irc'),
('q2am_sidebar_hot', ''),
('q2am_sidebar_qa', ''),
('q2am_sidebar_question', ''),
('q2am_sidebar_questions', ''),
('q2am_sidebar_tags', ''),
('q2am_sidebar_unanswered', ''),
('q2am_sidebar_users', ''),
('q2apro_cooltooltips_enabled', ''),
('q2apro_onsitenotifications_enabled', '1'),
('q2apro_onsitenotifications_maxage', '365'),
('q2apro_onsitenotifications_maxevshow', '50'),
('q2apro_onsitenotifications_newwindow', ''),
('q2apro_onsitenotifications_nill', '0'),
('q2apro_onsitenotifications_rtl', ''),
('q2apro_userinfo_enabled', '1'),
('q2apro_userinfo_show_avatar', '1'),
('q2apro_userinfo_show_bonuspoints', ''),
('q2apro_userinfo_show_downvotes', '1'),
('q2apro_warnonleave_enabled', '1'),
('q_urls_remove_accents', '1'),
('q_urls_title_length', '70'),
('recaptcha_private_key', ''),
('recaptcha_public_key', ''),
('register_notify_admin', '0'),
('register_terms', 'Wyrażam zgodę na Pasja informatyki local Zasady i Warunki oraz Politykę Prywatności'),
('search_module', ''),
('show_a_c_links', '1'),
('show_a_form_immediate', 'if_no_as'),
('show_custom_answer', '0'),
('show_custom_ask', '0'),
('show_custom_comment', '0'),
('show_custom_footer', '0'),
('show_custom_header', '0'),
('show_custom_home', '0'),
('show_custom_in_head', '1'),
('show_custom_register', '0'),
('show_custom_sidebar', '0'),
('show_custom_sidepanel', '0'),
('show_custom_welcome', '0'),
('show_c_reply_buttons', '1'),
('show_fewer_cs_count', '5'),
('show_fewer_cs_from', '10'),
('show_full_date_days', '7'),
('show_home_description', '0'),
('show_message_history', '1'),
('show_notice_visitor', '0'),
('show_notice_welcome', '0'),
('show_online_user_list', ''),
('show_post_update_meta', '1'),
('show_register_terms', '0'),
('show_selected_first', '1'),
('show_url_links', '1'),
('show_user_points', '1'),
('show_user_titles', '1'),
('show_view_counts', '1'),
('show_view_count_q_page', '1'),
('show_when_created', '1'),
('site_language', 'pl'),
('site_maintenance', '0'),
('site_text_direction', 'ltr'),
('site_theme', 'SnowFlat'),
('site_theme_mobile', 'SnowFlat'),
('site_title', 'Pasja informatyki local'),
('site_url', 'http://forum.pasja-informatyki.local/'),
('smtp_active', ''),
('smtp_address', ''),
('smtp_authenticate', ''),
('smtp_password', ''),
('smtp_port', '25'),
('smtp_secure', ''),
('smtp_username', ''),
('sort_answers_by', 'votes'),
('suspend_register_users', '0'),
('tags_or_categories', 'tc'),
('tag_cloud_count_tags', '100'),
('tag_cloud_font_size', '24'),
('tag_cloud_minimal_font_size', '8'),
('tag_cloud_size_popular', '1'),
('tag_separator_comma', '0'),
('tumblr_app_enabled', '0'),
('tumblr_app_id', ''),
('tumblr_app_secret', ''),
('tumblr_app_shortcut', '0'),
('twitter_app_enabled', '0'),
('twitter_app_id', ''),
('twitter_app_secret', ''),
('twitter_app_shortcut', '0'),
('useract_css', '1'),
('user_theme_enable', '1'),
('votes_separated', '0'),
('voting_down_cs', ''),
('voting_on_as', '1'),
('voting_on_cs', '1'),
('voting_on_qs', '1'),
('voting_on_q_page_only', '0'),
('wysiwyg_editor_upload_all', ''),
('wysiwyg_editor_upload_images', ''),
('wysiwyg_editor_upload_max_size', '1048576'),
('yahoo_app_enabled', '0'),
('yahoo_app_id', ''),
('yahoo_app_secret', ''),
('yahoo_app_shortcut', '0');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_pages`
--

CREATE TABLE IF NOT EXISTS `qa_pages` (
`pageid` smallint(5) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `nav` char(1) CHARACTER SET ascii NOT NULL,
  `position` smallint(5) unsigned NOT NULL,
  `flags` tinyint(3) unsigned NOT NULL,
  `permit` tinyint(3) unsigned DEFAULT NULL,
  `tags` varchar(200) NOT NULL,
  `heading` varchar(800) DEFAULT NULL,
  `content` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_polls`
--

CREATE TABLE IF NOT EXISTS `qa_polls` (
`id` bigint(20) unsigned NOT NULL,
  `parentid` bigint(20) unsigned NOT NULL,
  `votes` longtext,
  `content` varchar(255) DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_postmeta`
--

CREATE TABLE IF NOT EXISTS `qa_postmeta` (
`meta_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT '',
  `meta_value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_postmetas`
--

CREATE TABLE IF NOT EXISTS `qa_postmetas` (
  `postid` int(10) unsigned NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` varchar(8000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_posts`
--

CREATE TABLE IF NOT EXISTS `qa_posts` (
`postid` int(10) unsigned NOT NULL,
  `type` enum('Q','A','C','Q_HIDDEN','A_HIDDEN','C_HIDDEN','Q_QUEUED','A_QUEUED','C_QUEUED','NOTE') NOT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `categoryid` int(10) unsigned DEFAULT NULL,
  `catidpath1` int(10) unsigned DEFAULT NULL,
  `catidpath2` int(10) unsigned DEFAULT NULL,
  `catidpath3` int(10) unsigned DEFAULT NULL,
  `acount` smallint(5) unsigned NOT NULL DEFAULT '0',
  `amaxvote` smallint(5) unsigned NOT NULL DEFAULT '0',
  `selchildid` int(10) unsigned DEFAULT NULL,
  `closedbyid` int(10) unsigned DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT NULL,
  `cookieid` bigint(20) unsigned DEFAULT NULL,
  `createip` int(10) unsigned DEFAULT NULL,
  `lastuserid` int(10) unsigned DEFAULT NULL,
  `lastip` int(10) unsigned DEFAULT NULL,
  `upvotes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `downvotes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `netvotes` smallint(6) NOT NULL DEFAULT '0',
  `lastviewip` int(10) unsigned DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `hotness` float DEFAULT NULL,
  `flagcount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `format` varchar(20) CHARACTER SET ascii NOT NULL DEFAULT '',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updatetype` char(1) CHARACTER SET ascii DEFAULT NULL,
  `title` varchar(800) DEFAULT NULL,
  `content` varchar(15000) DEFAULT NULL,
  `tags` varchar(800) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `notify` varchar(80) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_posts`
--

INSERT INTO `qa_posts` (`postid`, `type`, `parentid`, `categoryid`, `catidpath1`, `catidpath2`, `catidpath3`, `acount`, `amaxvote`, `selchildid`, `closedbyid`, `userid`, `cookieid`, `createip`, `lastuserid`, `lastip`, `upvotes`, `downvotes`, `netvotes`, `lastviewip`, `views`, `hotness`, `flagcount`, `format`, `created`, `updated`, `updatetype`, `title`, `content`, `tags`, `name`, `notify`) VALUES
(1, 'Q', NULL, 2, 1, 2, NULL, 0, 0, NULL, NULL, 1, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232264193, 1, 32180400000, 0, '', '2016-05-16 20:04:43', NULL, NULL, 'Testowe pytanie superadministratora', 'Testowe pytanie superadministratora', 'pytanie', NULL, NULL),
(2, 'Q', NULL, 4, 4, NULL, NULL, 0, 0, NULL, NULL, 2, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232264193, 1, 32180400000, 0, '', '2016-05-16 20:05:23', NULL, NULL, 'Testowe pytanie administratora', 'Testowe pytanie administratora', 'pytanie,admin', NULL, NULL),
(3, 'Q', NULL, 3, 1, 3, NULL, 0, 0, NULL, NULL, 3, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232264193, 1, 32180400000, 0, '', '2016-05-16 20:06:47', NULL, NULL, 'Testowe pytanie moderatora', 'Testowe pytanie moderatora', 'pytanie', NULL, NULL),
(4, 'Q', NULL, 2, 1, 2, NULL, 0, 0, NULL, NULL, 4, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232264193, 1, 32180400000, 0, '', '2016-05-16 20:07:29', NULL, NULL, 'Testowe pytanie redaktora', 'Testowe pytanie redaktora', 'pytanie,testowe', NULL, NULL),
(5, 'Q', NULL, 4, 4, NULL, NULL, 0, 0, NULL, NULL, 5, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232264193, 1, 32180400000, 0, '', '2016-05-16 20:08:04', NULL, NULL, 'Pytanie eksperta', 'Pytanie eksperta', 'pytanie', NULL, NULL),
(6, 'Q', NULL, 3, 1, 3, NULL, 0, 0, NULL, NULL, 6, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232264193, 1, 32180500000, 0, '', '2016-05-16 20:08:52', NULL, NULL, 'Pytanie eksperta kategorii CSS i HTML', 'Pytanie eksperta kategorii CSS i HTML', 'pytanie', NULL, NULL),
(7, 'Q', NULL, 4, 4, NULL, NULL, 0, 0, NULL, NULL, 7, NULL, 3232264193, NULL, NULL, 0, 0, 0, 3232261122, 2, 32181000000, 0, '', '2016-05-16 20:19:12', NULL, NULL, 'Pytanie użytkownika', 'Pytanie użytkownika', 'pytanie', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_posttags`
--

CREATE TABLE IF NOT EXISTS `qa_posttags` (
  `postid` int(10) unsigned NOT NULL,
  `wordid` int(10) unsigned NOT NULL,
  `postcreated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_posttags`
--

INSERT INTO `qa_posttags` (`postid`, `wordid`, `postcreated`) VALUES
(1, 2, '2016-05-16 20:04:43'),
(2, 5, '2016-05-16 20:05:23'),
(2, 2, '2016-05-16 20:05:23'),
(3, 2, '2016-05-16 20:06:47'),
(4, 1, '2016-05-16 20:07:29'),
(4, 2, '2016-05-16 20:07:29'),
(5, 2, '2016-05-16 20:08:04'),
(6, 2, '2016-05-16 20:08:52'),
(7, 2, '2016-05-16 20:19:12');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_sharedevents`
--

CREATE TABLE IF NOT EXISTS `qa_sharedevents` (
  `entitytype` char(1) CHARACTER SET ascii NOT NULL,
  `entityid` int(10) unsigned NOT NULL,
  `questionid` int(10) unsigned NOT NULL,
  `lastpostid` int(10) unsigned NOT NULL,
  `updatetype` char(1) CHARACTER SET ascii DEFAULT NULL,
  `lastuserid` int(10) unsigned DEFAULT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_sharedevents`
--

INSERT INTO `qa_sharedevents` (`entitytype`, `entityid`, `questionid`, `lastpostid`, `updatetype`, `lastuserid`, `updated`) VALUES
('Q', 1, 1, 1, NULL, 1, '2016-05-16 20:04:43'),
('U', 1, 1, 1, NULL, 1, '2016-05-16 20:04:43'),
('T', 2, 1, 1, NULL, 1, '2016-05-16 20:04:43'),
('C', 1, 1, 1, NULL, 1, '2016-05-16 20:04:43'),
('C', 2, 1, 1, NULL, 1, '2016-05-16 20:04:43'),
('Q', 2, 2, 2, NULL, 2, '2016-05-16 20:05:23'),
('U', 2, 2, 2, NULL, 2, '2016-05-16 20:05:23'),
('T', 5, 2, 2, NULL, 2, '2016-05-16 20:05:24'),
('T', 2, 2, 2, NULL, 2, '2016-05-16 20:05:24'),
('C', 4, 2, 2, NULL, 2, '2016-05-16 20:05:24'),
('Q', 3, 3, 3, NULL, 3, '2016-05-16 20:06:47'),
('U', 3, 3, 3, NULL, 3, '2016-05-16 20:06:47'),
('T', 2, 3, 3, NULL, 3, '2016-05-16 20:06:47'),
('C', 1, 3, 3, NULL, 3, '2016-05-16 20:06:47'),
('C', 3, 3, 3, NULL, 3, '2016-05-16 20:06:47'),
('Q', 4, 4, 4, NULL, 4, '2016-05-16 20:07:29'),
('U', 4, 4, 4, NULL, 4, '2016-05-16 20:07:29'),
('T', 2, 4, 4, NULL, 4, '2016-05-16 20:07:29'),
('T', 1, 4, 4, NULL, 4, '2016-05-16 20:07:29'),
('C', 1, 4, 4, NULL, 4, '2016-05-16 20:07:29'),
('C', 2, 4, 4, NULL, 4, '2016-05-16 20:07:29'),
('Q', 5, 5, 5, NULL, 5, '2016-05-16 20:08:04'),
('U', 5, 5, 5, NULL, 5, '2016-05-16 20:08:04'),
('T', 2, 5, 5, NULL, 5, '2016-05-16 20:08:04'),
('C', 4, 5, 5, NULL, 5, '2016-05-16 20:08:04'),
('Q', 6, 6, 6, NULL, 6, '2016-05-16 20:08:52'),
('U', 6, 6, 6, NULL, 6, '2016-05-16 20:08:52'),
('T', 2, 6, 6, NULL, 6, '2016-05-16 20:08:52'),
('C', 1, 6, 6, NULL, 6, '2016-05-16 20:08:52'),
('C', 3, 6, 6, NULL, 6, '2016-05-16 20:08:52'),
('Q', 7, 7, 7, NULL, 7, '2016-05-16 20:19:12'),
('U', 7, 7, 7, NULL, 7, '2016-05-16 20:19:12'),
('T', 2, 7, 7, NULL, 7, '2016-05-16 20:19:12'),
('C', 4, 7, 7, NULL, 7, '2016-05-16 20:19:12');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_tagmetas`
--

CREATE TABLE IF NOT EXISTS `qa_tagmetas` (
  `tag` varchar(80) NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` varchar(8000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_tagwords`
--

CREATE TABLE IF NOT EXISTS `qa_tagwords` (
  `postid` int(10) unsigned NOT NULL,
  `wordid` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_tagwords`
--

INSERT INTO `qa_tagwords` (`postid`, `wordid`) VALUES
(1, 2),
(2, 2),
(2, 5),
(3, 2),
(4, 2),
(4, 1),
(5, 2),
(6, 2),
(7, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_titlewords`
--

CREATE TABLE IF NOT EXISTS `qa_titlewords` (
  `postid` int(10) unsigned NOT NULL,
  `wordid` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_titlewords`
--

INSERT INTO `qa_titlewords` (`postid`, `wordid`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 4),
(3, 1),
(3, 2),
(3, 6),
(4, 1),
(4, 2),
(4, 7),
(5, 2),
(5, 8),
(6, 2),
(6, 8),
(6, 9),
(6, 10),
(6, 11),
(6, 12),
(7, 2),
(7, 13);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userbadges`
--

CREATE TABLE IF NOT EXISTS `qa_userbadges` (
  `awarded_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `notify` tinyint(4) NOT NULL DEFAULT '0',
  `object_id` int(10) DEFAULT NULL,
  `badge_slug` varchar(64) CHARACTER SET ascii DEFAULT '',
`id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userevents`
--

CREATE TABLE IF NOT EXISTS `qa_userevents` (
  `userid` int(10) unsigned NOT NULL,
  `entitytype` char(1) CHARACTER SET ascii NOT NULL,
  `entityid` int(10) unsigned NOT NULL,
  `questionid` int(10) unsigned NOT NULL,
  `lastpostid` int(10) unsigned NOT NULL,
  `updatetype` char(1) CHARACTER SET ascii DEFAULT NULL,
  `lastuserid` int(10) unsigned DEFAULT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userfavorites`
--

CREATE TABLE IF NOT EXISTS `qa_userfavorites` (
  `userid` int(10) unsigned NOT NULL,
  `entitytype` char(1) CHARACTER SET ascii NOT NULL,
  `entityid` int(10) unsigned NOT NULL,
  `nouserevents` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userfields`
--

CREATE TABLE IF NOT EXISTS `qa_userfields` (
`fieldid` smallint(5) unsigned NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` varchar(40) DEFAULT NULL,
  `position` smallint(5) unsigned NOT NULL,
  `flags` tinyint(3) unsigned NOT NULL,
  `permit` tinyint(3) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_userfields`
--

INSERT INTO `qa_userfields` (`fieldid`, `title`, `content`, `position`, `flags`, `permit`) VALUES
(1, 'name', NULL, 1, 0, NULL),
(2, 'location', NULL, 2, 0, NULL),
(3, 'website', 'Strona www', 3, 2, 150),
(4, 'about', 'Ulubione technologie', 4, 0, 120);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userlevels`
--

CREATE TABLE IF NOT EXISTS `qa_userlevels` (
  `userid` int(10) unsigned NOT NULL,
  `entitytype` char(1) CHARACTER SET ascii NOT NULL,
  `entityid` int(10) unsigned NOT NULL,
  `level` tinyint(3) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_userlevels`
--

INSERT INTO `qa_userlevels` (`userid`, `entitytype`, `entityid`, `level`) VALUES
(6, 'C', 3, 20);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userlimits`
--

CREATE TABLE IF NOT EXISTS `qa_userlimits` (
  `userid` int(10) unsigned NOT NULL,
  `action` char(1) CHARACTER SET ascii NOT NULL,
  `period` int(10) unsigned NOT NULL,
  `count` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_userlimits`
--

INSERT INTO `qa_userlimits` (`userid`, `action`, `period`, `count`) VALUES
(1, 'Q', 406506, 1),
(2, 'Q', 406506, 1),
(3, 'Q', 406506, 1),
(4, 'Q', 406506, 1),
(5, 'Q', 406506, 1),
(6, 'Q', 406506, 1),
(7, 'Q', 406506, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userlogins`
--

CREATE TABLE IF NOT EXISTS `qa_userlogins` (
  `userid` int(10) unsigned NOT NULL,
  `source` varchar(16) CHARACTER SET ascii NOT NULL,
  `identifier` varbinary(1024) NOT NULL,
  `identifiermd5` binary(16) NOT NULL,
  `oemail` varchar(80) DEFAULT NULL,
  `ohandle` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_usermeta`
--

CREATE TABLE IF NOT EXISTS `qa_usermeta` (
`meta_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_usermetas`
--

CREATE TABLE IF NOT EXISTS `qa_usermetas` (
  `userid` int(10) unsigned NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` varchar(8000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_usernotices`
--

CREATE TABLE IF NOT EXISTS `qa_usernotices` (
`noticeid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `content` varchar(15000) NOT NULL,
  `format` varchar(20) CHARACTER SET ascii NOT NULL,
  `tags` varchar(200) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userpoints`
--

CREATE TABLE IF NOT EXISTS `qa_userpoints` (
  `userid` int(10) unsigned NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `qposts` mediumint(9) NOT NULL DEFAULT '0',
  `aposts` mediumint(9) NOT NULL DEFAULT '0',
  `cposts` mediumint(9) NOT NULL DEFAULT '0',
  `aselects` mediumint(9) NOT NULL DEFAULT '0',
  `aselecteds` mediumint(9) NOT NULL DEFAULT '0',
  `qupvotes` mediumint(9) NOT NULL DEFAULT '0',
  `qdownvotes` mediumint(9) NOT NULL DEFAULT '0',
  `aupvotes` mediumint(9) NOT NULL DEFAULT '0',
  `adownvotes` mediumint(9) NOT NULL DEFAULT '0',
  `qvoteds` int(11) NOT NULL DEFAULT '0',
  `avoteds` int(11) NOT NULL DEFAULT '0',
  `upvoteds` int(11) NOT NULL DEFAULT '0',
  `downvoteds` int(11) NOT NULL DEFAULT '0',
  `bonus` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_userpoints`
--

INSERT INTO `qa_userpoints` (`userid`, `points`, `qposts`, `aposts`, `cposts`, `aselects`, `aselecteds`, `qupvotes`, `qdownvotes`, `aupvotes`, `adownvotes`, `qvoteds`, `avoteds`, `upvoteds`, `downvoteds`, `bonus`) VALUES
(1, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 120, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_userprofile`
--

CREATE TABLE IF NOT EXISTS `qa_userprofile` (
  `userid` int(10) unsigned NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` varchar(8000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_userprofile`
--

INSERT INTO `qa_userprofile` (`userid`, `title`, `content`) VALUES
(1, 'about', ''),
(1, 'location', ''),
(1, 'name', ''),
(1, 'website', ''),
(2, 'about', ''),
(2, 'location', ''),
(2, 'name', ''),
(2, 'website', ''),
(3, 'about', ''),
(3, 'location', ''),
(3, 'name', ''),
(3, 'website', ''),
(4, 'about', ''),
(4, 'location', ''),
(4, 'name', ''),
(4, 'website', ''),
(5, 'about', ''),
(5, 'location', ''),
(5, 'name', ''),
(5, 'website', ''),
(6, 'about', ''),
(6, 'location', ''),
(6, 'name', ''),
(6, 'website', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_users`
--

CREATE TABLE IF NOT EXISTS `qa_users` (
`userid` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `createip` int(10) unsigned NOT NULL,
  `email` varchar(80) NOT NULL,
  `handle` varchar(20) NOT NULL,
  `avatarblobid` bigint(20) unsigned DEFAULT NULL,
  `avatarwidth` smallint(5) unsigned DEFAULT NULL,
  `avatarheight` smallint(5) unsigned DEFAULT NULL,
  `passsalt` binary(16) DEFAULT NULL,
  `passcheck` binary(20) DEFAULT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `loggedin` datetime NOT NULL,
  `loginip` int(10) unsigned NOT NULL,
  `written` datetime DEFAULT NULL,
  `writeip` int(10) unsigned DEFAULT NULL,
  `emailcode` char(8) CHARACTER SET ascii NOT NULL DEFAULT '',
  `sessioncode` char(8) CHARACTER SET ascii NOT NULL DEFAULT '',
  `sessionsource` varchar(16) CHARACTER SET ascii DEFAULT '',
  `flags` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pwemail` smallint(5) unsigned NOT NULL DEFAULT '1',
  `wallposts` mediumint(9) NOT NULL DEFAULT '0',
  `oemail` varchar(80) DEFAULT NULL,
  `activityemail` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `theme` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_users`
--

INSERT INTO `qa_users` (`userid`, `created`, `createip`, `email`, `handle`, `avatarblobid`, `avatarwidth`, `avatarheight`, `passsalt`, `passcheck`, `level`, `loggedin`, `loginip`, `written`, `writeip`, `emailcode`, `sessioncode`, `sessionsource`, `flags`, `pwemail`, `wallposts`, `oemail`, `activityemail`, `theme`) VALUES
(1, '2016-05-16 19:07:12', 3232264193, 'superadmin@example.com', 'superadmin', NULL, NULL, NULL, 0x656a39796771776d317232766d657471, 0x22895c8f635f78c782605c7bac454e92130d45e3, 120, '2016-05-16 20:25:59', 3232264193, '2016-05-16 20:17:59', 3232264193, '', 'xgb3w1k0', NULL, 0, 1, 0, NULL, 0, 0),
(2, '2016-05-16 20:03:13', 3232264193, 'admin@example.com', 'admin', NULL, NULL, NULL, 0x37747973326f636d386564306b356b67, 0x7dd1f0d7af1adfbf898bcec69f98c3fa4d1af838, 100, '2017-07-31 00:40:45', 3232261122, '2016-05-16 20:05:23', 3232264193, '', 'xzolja3l', NULL, 0, 1, 0, NULL, 0, 1),
(3, '2016-05-16 20:06:30', 3232264193, 'moderator@example.com', 'moderator', NULL, NULL, NULL, 0x7064626f7878676534787a6e33703832, 0x81f6a124e0dcae981e8a077b8cd31c1c1f5ee1bc, 80, '2016-05-16 20:06:30', 3232264193, '2016-05-16 20:06:47', 3232264193, '', 'mynnvaft', NULL, 0, 1, 0, NULL, 0, 0),
(4, '2016-05-16 20:07:05', 3232264193, 'redaktor@example.com', 'redaktor', NULL, NULL, NULL, 0x797a78717462767470796e6f6d616972, 0xbbb302c2c33cfef3665738f7578cbcb254b2819a, 50, '2016-05-16 20:07:05', 3232264193, '2016-05-16 20:07:29', 3232264193, '', 'mav5hhhz', NULL, 0, 1, 0, NULL, 0, 0),
(5, '2016-05-16 20:07:49', 3232264193, 'ekspert@example.com', 'ekspert', NULL, NULL, NULL, 0x7a7a38306738686164376b6a67796f32, 0x24096d21f3be39145d63563499a7763657965833, 20, '2016-05-16 20:07:49', 3232264193, '2016-05-16 20:08:04', 3232264193, '', 'q1ppwkra', NULL, 0, 1, 0, NULL, 0, 0),
(6, '2016-05-16 20:08:28', 3232264193, 'ekspertkategoria@example.com', 'ekspertkategoria', NULL, NULL, NULL, 0x6277316a797a346d7333357735353270, 0x7e359f22321faf04f9d2b6e4bcc84453bf1dd900, 0, '2016-05-16 20:08:28', 3232264193, '2016-05-16 20:08:52', 3232264193, '', 'o58vm26q', NULL, 0, 1, 0, NULL, 0, 0),
(7, '2016-05-16 20:18:58', 3232264193, 'user@example.com', 'user', NULL, NULL, NULL, 0x766f63756874346b667a677675713667, 0xceae7ae5e5b6c4b0ab66ed328e6045a8b69e013c, 0, '2016-05-16 20:18:58', 3232264193, '2016-05-16 20:19:12', 3232264193, '', 'nbowefw3', NULL, 0, 1, 0, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_uservotes`
--

CREATE TABLE IF NOT EXISTS `qa_uservotes` (
  `postid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `vote` tinyint(4) NOT NULL,
  `flag` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_widgets`
--

CREATE TABLE IF NOT EXISTS `qa_widgets` (
`widgetid` smallint(5) unsigned NOT NULL,
  `place` char(2) CHARACTER SET ascii NOT NULL,
  `position` smallint(5) unsigned NOT NULL,
  `tags` varchar(800) CHARACTER SET ascii NOT NULL,
  `title` varchar(80) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_widgets`
--

INSERT INTO `qa_widgets` (`widgetid`, `place`, `position`, `tags`, `title`) VALUES
(1, 'MB', 4, 'question', 'Related Questions'),
(2, 'ST', 2, 'all', 'Activity Count'),
(3, 'ST', 3, 'all', 'Show online user count'),
(4, 'SL', 1, 'all', 'User changeable theme widget');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `qa_words`
--

CREATE TABLE IF NOT EXISTS `qa_words` (
`wordid` int(10) unsigned NOT NULL,
  `word` varchar(80) NOT NULL,
  `titlecount` int(10) unsigned NOT NULL DEFAULT '0',
  `contentcount` int(10) unsigned NOT NULL DEFAULT '0',
  `tagwordcount` int(10) unsigned NOT NULL DEFAULT '0',
  `tagcount` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `qa_words`
--

INSERT INTO `qa_words` (`wordid`, `word`, `titlecount`, `contentcount`, `tagwordcount`, `tagcount`) VALUES
(1, 'testowe', 4, 4, 1, 1),
(2, 'pytanie', 7, 7, 7, 7),
(3, 'superadministratora', 1, 1, 0, 0),
(4, 'administratora', 1, 1, 0, 0),
(5, 'admin', 0, 0, 1, 1),
(6, 'moderatora', 1, 1, 0, 0),
(7, 'redaktora', 1, 1, 0, 0),
(8, 'eksperta', 2, 2, 0, 0),
(9, 'kategorii', 1, 1, 0, 0),
(10, 'css', 1, 1, 0, 0),
(11, 'i', 1, 1, 0, 0),
(12, 'html', 1, 1, 0, 0),
(13, 'użytkownika', 1, 1, 0, 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `qa_achievements`
--
ALTER TABLE `qa_achievements`
 ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `qa_blobs`
--
ALTER TABLE `qa_blobs`
 ADD PRIMARY KEY (`blobid`);

--
-- Indexes for table `qa_blockades`
--
ALTER TABLE `qa_blockades`
 ADD PRIMARY KEY (`id_blockade`);

--
-- Indexes for table `qa_cache`
--
ALTER TABLE `qa_cache`
 ADD PRIMARY KEY (`type`,`cacheid`), ADD KEY `lastread` (`lastread`);

--
-- Indexes for table `qa_categories`
--
ALTER TABLE `qa_categories`
 ADD PRIMARY KEY (`categoryid`), ADD UNIQUE KEY `parentid` (`parentid`,`tags`), ADD UNIQUE KEY `parentid_2` (`parentid`,`position`), ADD KEY `backpath` (`backpath`(200));

--
-- Indexes for table `qa_categorymetas`
--
ALTER TABLE `qa_categorymetas`
 ADD PRIMARY KEY (`categoryid`,`title`);

--
-- Indexes for table `qa_contentwords`
--
ALTER TABLE `qa_contentwords`
 ADD KEY `postid` (`postid`), ADD KEY `wordid` (`wordid`);

--
-- Indexes for table `qa_cookies`
--
ALTER TABLE `qa_cookies`
 ADD PRIMARY KEY (`cookieid`);

--
-- Indexes for table `qa_eventlog`
--
ALTER TABLE `qa_eventlog`
 ADD KEY `datetime` (`datetime`), ADD KEY `ipaddress` (`ipaddress`), ADD KEY `userid` (`userid`), ADD KEY `event` (`event`);

--
-- Indexes for table `qa_iplimits`
--
ALTER TABLE `qa_iplimits`
 ADD UNIQUE KEY `ip` (`ip`,`action`);

--
-- Indexes for table `qa_messages`
--
ALTER TABLE `qa_messages`
 ADD PRIMARY KEY (`messageid`), ADD KEY `type` (`type`,`fromuserid`,`touserid`,`created`), ADD KEY `touserid` (`touserid`,`type`,`created`), ADD KEY `fromhidden` (`fromhidden`), ADD KEY `tohidden` (`tohidden`);

--
-- Indexes for table `qa_online_user`
--
ALTER TABLE `qa_online_user`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qa_options`
--
ALTER TABLE `qa_options`
 ADD PRIMARY KEY (`title`);

--
-- Indexes for table `qa_pages`
--
ALTER TABLE `qa_pages`
 ADD PRIMARY KEY (`pageid`), ADD UNIQUE KEY `position` (`position`), ADD KEY `tags` (`tags`);

--
-- Indexes for table `qa_polls`
--
ALTER TABLE `qa_polls`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qa_postmeta`
--
ALTER TABLE `qa_postmeta`
 ADD PRIMARY KEY (`meta_id`), ADD KEY `post_id` (`post_id`), ADD KEY `meta_key` (`meta_key`);

--
-- Indexes for table `qa_postmetas`
--
ALTER TABLE `qa_postmetas`
 ADD PRIMARY KEY (`postid`,`title`);

--
-- Indexes for table `qa_posts`
--
ALTER TABLE `qa_posts`
 ADD PRIMARY KEY (`postid`), ADD KEY `type` (`type`,`created`), ADD KEY `type_2` (`type`,`acount`,`created`), ADD KEY `type_4` (`type`,`netvotes`,`created`), ADD KEY `type_5` (`type`,`views`,`created`), ADD KEY `type_6` (`type`,`hotness`), ADD KEY `type_7` (`type`,`amaxvote`,`created`), ADD KEY `parentid` (`parentid`,`type`), ADD KEY `userid` (`userid`,`type`,`created`), ADD KEY `selchildid` (`selchildid`,`type`,`created`), ADD KEY `closedbyid` (`closedbyid`), ADD KEY `catidpath1` (`catidpath1`,`type`,`created`), ADD KEY `catidpath2` (`catidpath2`,`type`,`created`), ADD KEY `catidpath3` (`catidpath3`,`type`,`created`), ADD KEY `categoryid` (`categoryid`,`type`,`created`), ADD KEY `createip` (`createip`,`created`), ADD KEY `updated` (`updated`,`type`), ADD KEY `flagcount` (`flagcount`,`created`,`type`), ADD KEY `catidpath1_2` (`catidpath1`,`updated`,`type`), ADD KEY `catidpath2_2` (`catidpath2`,`updated`,`type`), ADD KEY `catidpath3_2` (`catidpath3`,`updated`,`type`), ADD KEY `categoryid_2` (`categoryid`,`updated`,`type`), ADD KEY `lastuserid` (`lastuserid`,`updated`,`type`), ADD KEY `lastip` (`lastip`,`updated`,`type`);

--
-- Indexes for table `qa_posttags`
--
ALTER TABLE `qa_posttags`
 ADD KEY `postid` (`postid`), ADD KEY `wordid` (`wordid`,`postcreated`);

--
-- Indexes for table `qa_sharedevents`
--
ALTER TABLE `qa_sharedevents`
 ADD KEY `entitytype` (`entitytype`,`entityid`,`updated`), ADD KEY `questionid` (`questionid`,`entitytype`,`entityid`);

--
-- Indexes for table `qa_tagmetas`
--
ALTER TABLE `qa_tagmetas`
 ADD PRIMARY KEY (`tag`,`title`);

--
-- Indexes for table `qa_tagwords`
--
ALTER TABLE `qa_tagwords`
 ADD KEY `postid` (`postid`), ADD KEY `wordid` (`wordid`);

--
-- Indexes for table `qa_titlewords`
--
ALTER TABLE `qa_titlewords`
 ADD KEY `postid` (`postid`), ADD KEY `wordid` (`wordid`);

--
-- Indexes for table `qa_userbadges`
--
ALTER TABLE `qa_userbadges`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `qa_userevents`
--
ALTER TABLE `qa_userevents`
 ADD KEY `userid` (`userid`,`updated`), ADD KEY `questionid` (`questionid`,`userid`);

--
-- Indexes for table `qa_userfavorites`
--
ALTER TABLE `qa_userfavorites`
 ADD PRIMARY KEY (`userid`,`entitytype`,`entityid`), ADD KEY `userid` (`userid`,`nouserevents`), ADD KEY `entitytype` (`entitytype`,`entityid`,`nouserevents`);

--
-- Indexes for table `qa_userfields`
--
ALTER TABLE `qa_userfields`
 ADD PRIMARY KEY (`fieldid`);

--
-- Indexes for table `qa_userlevels`
--
ALTER TABLE `qa_userlevels`
 ADD UNIQUE KEY `userid` (`userid`,`entitytype`,`entityid`), ADD KEY `entitytype` (`entitytype`,`entityid`);

--
-- Indexes for table `qa_userlimits`
--
ALTER TABLE `qa_userlimits`
 ADD UNIQUE KEY `userid` (`userid`,`action`);

--
-- Indexes for table `qa_userlogins`
--
ALTER TABLE `qa_userlogins`
 ADD KEY `source` (`source`,`identifiermd5`), ADD KEY `userid` (`userid`);

--
-- Indexes for table `qa_usermeta`
--
ALTER TABLE `qa_usermeta`
 ADD PRIMARY KEY (`meta_id`), ADD UNIQUE KEY `user_id` (`user_id`,`meta_key`);

--
-- Indexes for table `qa_usermetas`
--
ALTER TABLE `qa_usermetas`
 ADD PRIMARY KEY (`userid`,`title`);

--
-- Indexes for table `qa_usernotices`
--
ALTER TABLE `qa_usernotices`
 ADD PRIMARY KEY (`noticeid`), ADD KEY `userid` (`userid`,`created`);

--
-- Indexes for table `qa_userpoints`
--
ALTER TABLE `qa_userpoints`
 ADD PRIMARY KEY (`userid`), ADD KEY `points` (`points`);

--
-- Indexes for table `qa_userprofile`
--
ALTER TABLE `qa_userprofile`
 ADD UNIQUE KEY `userid` (`userid`,`title`);

--
-- Indexes for table `qa_users`
--
ALTER TABLE `qa_users`
 ADD PRIMARY KEY (`userid`), ADD KEY `email` (`email`), ADD KEY `handle` (`handle`), ADD KEY `level` (`level`), ADD KEY `created` (`created`,`level`,`flags`);

--
-- Indexes for table `qa_uservotes`
--
ALTER TABLE `qa_uservotes`
 ADD UNIQUE KEY `userid` (`userid`,`postid`), ADD KEY `postid` (`postid`);

--
-- Indexes for table `qa_widgets`
--
ALTER TABLE `qa_widgets`
 ADD PRIMARY KEY (`widgetid`), ADD UNIQUE KEY `position` (`position`);

--
-- Indexes for table `qa_words`
--
ALTER TABLE `qa_words`
 ADD PRIMARY KEY (`wordid`), ADD KEY `word` (`word`), ADD KEY `tagcount` (`tagcount`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `qa_blockades`
--
ALTER TABLE `qa_blockades`
MODIFY `id_blockade` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `qa_categories`
--
ALTER TABLE `qa_categories`
MODIFY `categoryid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `qa_messages`
--
ALTER TABLE `qa_messages`
MODIFY `messageid` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_online_user`
--
ALTER TABLE `qa_online_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT dla tabeli `qa_pages`
--
ALTER TABLE `qa_pages`
MODIFY `pageid` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_polls`
--
ALTER TABLE `qa_polls`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_postmeta`
--
ALTER TABLE `qa_postmeta`
MODIFY `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_posts`
--
ALTER TABLE `qa_posts`
MODIFY `postid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT dla tabeli `qa_userbadges`
--
ALTER TABLE `qa_userbadges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_userfields`
--
ALTER TABLE `qa_userfields`
MODIFY `fieldid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `qa_usermeta`
--
ALTER TABLE `qa_usermeta`
MODIFY `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_usernotices`
--
ALTER TABLE `qa_usernotices`
MODIFY `noticeid` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `qa_users`
--
ALTER TABLE `qa_users`
MODIFY `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT dla tabeli `qa_widgets`
--
ALTER TABLE `qa_widgets`
MODIFY `widgetid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `qa_words`
--
ALTER TABLE `qa_words`
MODIFY `wordid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `qa_categorymetas`
--
ALTER TABLE `qa_categorymetas`
ADD CONSTRAINT `qa_categorymetas_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `qa_categories` (`categoryid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_contentwords`
--
ALTER TABLE `qa_contentwords`
ADD CONSTRAINT `qa_contentwords_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `qa_posts` (`postid`) ON DELETE CASCADE,
ADD CONSTRAINT `qa_contentwords_ibfk_2` FOREIGN KEY (`wordid`) REFERENCES `qa_words` (`wordid`);

--
-- Ograniczenia dla tabeli `qa_postmetas`
--
ALTER TABLE `qa_postmetas`
ADD CONSTRAINT `qa_postmetas_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `qa_posts` (`postid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_posts`
--
ALTER TABLE `qa_posts`
ADD CONSTRAINT `qa_posts_ibfk_2` FOREIGN KEY (`parentid`) REFERENCES `qa_posts` (`postid`),
ADD CONSTRAINT `qa_posts_ibfk_3` FOREIGN KEY (`categoryid`) REFERENCES `qa_categories` (`categoryid`) ON DELETE SET NULL,
ADD CONSTRAINT `qa_posts_ibfk_4` FOREIGN KEY (`closedbyid`) REFERENCES `qa_posts` (`postid`),
ADD CONSTRAINT `qa_posts_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE SET NULL;

--
-- Ograniczenia dla tabeli `qa_posttags`
--
ALTER TABLE `qa_posttags`
ADD CONSTRAINT `qa_posttags_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `qa_posts` (`postid`) ON DELETE CASCADE,
ADD CONSTRAINT `qa_posttags_ibfk_2` FOREIGN KEY (`wordid`) REFERENCES `qa_words` (`wordid`);

--
-- Ograniczenia dla tabeli `qa_tagwords`
--
ALTER TABLE `qa_tagwords`
ADD CONSTRAINT `qa_tagwords_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `qa_posts` (`postid`) ON DELETE CASCADE,
ADD CONSTRAINT `qa_tagwords_ibfk_2` FOREIGN KEY (`wordid`) REFERENCES `qa_words` (`wordid`);

--
-- Ograniczenia dla tabeli `qa_titlewords`
--
ALTER TABLE `qa_titlewords`
ADD CONSTRAINT `qa_titlewords_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `qa_posts` (`postid`) ON DELETE CASCADE,
ADD CONSTRAINT `qa_titlewords_ibfk_2` FOREIGN KEY (`wordid`) REFERENCES `qa_words` (`wordid`);

--
-- Ograniczenia dla tabeli `qa_userevents`
--
ALTER TABLE `qa_userevents`
ADD CONSTRAINT `qa_userevents_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_userfavorites`
--
ALTER TABLE `qa_userfavorites`
ADD CONSTRAINT `qa_userfavorites_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_userlevels`
--
ALTER TABLE `qa_userlevels`
ADD CONSTRAINT `qa_userlevels_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_userlimits`
--
ALTER TABLE `qa_userlimits`
ADD CONSTRAINT `qa_userlimits_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_userlogins`
--
ALTER TABLE `qa_userlogins`
ADD CONSTRAINT `qa_userlogins_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_usermetas`
--
ALTER TABLE `qa_usermetas`
ADD CONSTRAINT `qa_usermetas_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_usernotices`
--
ALTER TABLE `qa_usernotices`
ADD CONSTRAINT `qa_usernotices_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_userprofile`
--
ALTER TABLE `qa_userprofile`
ADD CONSTRAINT `qa_userprofile_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `qa_uservotes`
--
ALTER TABLE `qa_uservotes`
ADD CONSTRAINT `qa_uservotes_ibfk_1` FOREIGN KEY (`postid`) REFERENCES `qa_posts` (`postid`) ON DELETE CASCADE,
ADD CONSTRAINT `qa_uservotes_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `qa_users` (`userid`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
