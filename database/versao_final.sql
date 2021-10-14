-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.1.37-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para consultorio
CREATE DATABASE IF NOT EXISTS `consultorio` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `consultorio`;

-- Copiando estrutura para tabela consultorio.branchs
CREATE TABLE IF NOT EXISTS `branchs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(100) NOT NULL,
  `fantasy` varchar(100) DEFAULT NULL,
  `document` varchar(50) NOT NULL,
  `ie` varchar(50) NOT NULL,
  `street` varchar(100) NOT NULL,
  `number` varchar(10) NOT NULL,
  `complement` varchar(150) DEFAULT NULL,
  `neight` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `uf` varchar(50) NOT NULL,
  `uf_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `celphone` varchar(20) DEFAULT NULL,
  `mail` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.branchs: ~0 rows (aproximadamente)
DELETE FROM `branchs`;
/*!40000 ALTER TABLE `branchs` DISABLE KEYS */;
INSERT INTO `branchs` (`id`, `company`, `fantasy`, `document`, `ie`, `street`, `number`, `complement`, `neight`, `city`, `uf`, `uf_code`, `phone`, `celphone`, `mail`, `created_at`, `updated_at`) VALUES
	(1, 'Erick Cordeiro de Arruda', 'Erick Cordeiro', '38015989000180', '512652655125', 'Rua 4, Casa', '120', 'Casa', 'AGROCHA 2', 'Registro', 'São Paulo', '11900000', '1338222302', '13996631713', 'erickcordeiroa@gmail.com', '2020-11-03 10:54:17', '2020-11-03 11:07:47');
/*!40000 ALTER TABLE `branchs` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company` varchar(100) NOT NULL,
  `fantasy` varchar(100) NOT NULL,
  `document` varchar(20) NOT NULL,
  `ie` varchar(20) NOT NULL,
  `street` varchar(100) NOT NULL,
  `number` varchar(10) NOT NULL,
  `city` varchar(50) NOT NULL,
  `neight` varchar(50) NOT NULL,
  `uf` varchar(50) NOT NULL,
  `complement` varchar(100) DEFAULT NULL,
  `uf_code` varchar(13) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `celphone` varchar(20) DEFAULT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.clients: ~0 rows (aproximadamente)
DELETE FROM `clients`;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` (`id`, `company`, `fantasy`, `document`, `ie`, `street`, `number`, `city`, `neight`, `uf`, `complement`, `uf_code`, `phone`, `celphone`, `mail`, `password`, `created_at`, `updated_at`) VALUES
	(2, 'ERICK CORDEIRO DE ARRUDA 42127555805', 'EWD MARKETING DIGITAL E DESENVOLVIMENTO WEB', '38015989000180', '512.652.655.125', '10 R QUATRO', '120', 'REGISTRO', 'AGROCHA 2', 'SP', 'CASA', '11900000', '13 96060090', '13 996631713', 'tiemitanno@hotmail.com', '$2y$10$WJyA6x3f76jE2.HDGB69EOp8XfPBwtRX7bxOw0xE/9ecoW0r4ZGxq', '2020-10-08 21:48:18', NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.collaborators
CREATE TABLE IF NOT EXISTS `collaborators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `document` varchar(30) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `office` varchar(80) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `celphone` varchar(50) DEFAULT NULL,
  `birth_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `address` varchar(50) DEFAULT NULL,
  `complement` varchar(50) DEFAULT NULL,
  `neight` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `uf` varchar(50) DEFAULT NULL,
  `uf_code` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `forget` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.collaborators: ~3 rows (aproximadamente)
DELETE FROM `collaborators`;
/*!40000 ALTER TABLE `collaborators` DISABLE KEYS */;
INSERT INTO `collaborators` (`id`, `clients_id`, `name`, `document`, `mail`, `office`, `phone`, `celphone`, `birth_date`, `address`, `complement`, `neight`, `city`, `uf`, `uf_code`, `password`, `forget`, `created_at`, `updated_at`) VALUES
	(5, 2, 'Erick Cordeiro', '42127555805', 'tiemitanno@gmail.com', 'Gerente', '13 99663171', '13 996631713', '2020-11-29 16:35:12', 'Rua 4, Casa', 'Casa', 'AGROCHA 2', 'Registro', 'São Paulo', '11900000', '$2y$10$livMyS.bTTnAVCMi/4h3o.YSLBPmmTvh7NfEaM7hOF0HglBOMHoDa', NULL, '2020-11-18 08:17:25', '2020-11-29 16:35:12'),
	(7, 2, 'Evelin Tanno', '42786627850', 'tiemitanno@hotmail.com', 'Gerente', '13 99663171', '13 996631713', '1993-06-17 00:00:00', 'Rua 4, Casa, Casa', 'Casa', 'AGROCHA 2', 'Registro', 'São Paulo', '11900000', '$2y$10$4D1sVqqVa4DFPOkEArANKODGtpWYBZ8mFme574Yfvzfacqaw2.KT6', NULL, '2020-11-24 15:30:58', NULL),
	(8, 1, 'Erick Cordeiro Segundo Registro', '42127555805', 'erickcordeiroa@gmail.com', 'Analista de TI', '13 99663171', '13 996631713', '2020-11-29 16:42:55', 'Rua 4, Casa, Casa', 'Casa', 'AGROCHA 2', 'Registro', 'São Paulo', '11900000', '$2y$10$cCUHuYvwGd8d3jML2VUPMeHLKht7qZPGeqY8z4uYT8AoXN4ffnwum', NULL, '2020-11-29 16:34:50', '2020-11-29 16:42:55');
/*!40000 ALTER TABLE `collaborators` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.complementary
CREATE TABLE IF NOT EXISTS `complementary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_exams` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.complementary: ~0 rows (aproximadamente)
DELETE FROM `complementary`;
/*!40000 ALTER TABLE `complementary` DISABLE KEYS */;
/*!40000 ALTER TABLE `complementary` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.doctors
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `crm` varchar(50) NOT NULL,
  `street` varchar(120) DEFAULT NULL,
  `number` varchar(5) DEFAULT NULL,
  `neight` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `uf` varchar(50) DEFAULT NULL,
  `uf_code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.doctors: ~0 rows (aproximadamente)
DELETE FROM `doctors`;
/*!40000 ALTER TABLE `doctors` DISABLE KEYS */;
INSERT INTO `doctors` (`id`, `name`, `crm`, `street`, `number`, `neight`, `city`, `uf`, `uf_code`, `created_at`, `updated_at`) VALUES
	(1, 'Erick Cordeiro Médico', '421575842', 'Rua 4', '120', 'AGROCHA 2', 'Registro', 'São Paulo', '11900-000', '2020-10-31 16:58:32', '2020-11-24 14:50:06');
/*!40000 ALTER TABLE `doctors` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.exams
CREATE TABLE IF NOT EXISTS `exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_doctors` int(11) NOT NULL,
  `id_branchs` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.exams: ~0 rows (aproximadamente)
DELETE FROM `exams`;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` (`id`, `id_doctors`, `id_branchs`, `description`, `created_at`, `updated_at`) VALUES
	(2, 1, 1, 'Exame de Teste ', '2020-11-11 19:51:31', '2020-11-11 19:57:26');
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.record
CREATE TABLE IF NOT EXISTS `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clients_id` int(11) NOT NULL,
  `deteled` char(1) NOT NULL DEFAULT 'N',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.record: ~0 rows (aproximadamente)
DELETE FROM `record`;
/*!40000 ALTER TABLE `record` DISABLE KEYS */;
/*!40000 ALTER TABLE `record` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.record_item
CREATE TABLE IF NOT EXISTS `record_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `goal` varchar(50) NOT NULL,
  `observation` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.record_item: ~0 rows (aproximadamente)
DELETE FROM `record_item`;
/*!40000 ALTER TABLE `record_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `record_item` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.schedulings
CREATE TABLE IF NOT EXISTS `schedulings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_branchs` int(11) NOT NULL,
  `id_clients` int(11) NOT NULL,
  `id_collaborators` int(11) NOT NULL,
  `id_exams` int(11) NOT NULL,
  `complementary` varchar(255) DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `observation` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.schedulings: ~8 rows (aproximadamente)
DELETE FROM `schedulings`;
/*!40000 ALTER TABLE `schedulings` DISABLE KEYS */;
INSERT INTO `schedulings` (`id`, `id_branchs`, `id_clients`, `id_collaborators`, `id_exams`, `complementary`, `start`, `end`, `status`, `observation`, `created_at`, `updated_at`) VALUES
	(1, 1, 2, 5, 2, NULL, '2020-11-24 10:30:00', '2020-11-24 11:00:00', 'success', NULL, '2020-11-24 10:09:03', '2020-11-26 12:54:10'),
	(2, 1, 2, 5, 2, NULL, '2020-11-25 10:40:00', '2020-11-25 11:00:00', 'success', NULL, '2020-11-24 10:41:29', '2020-11-26 12:54:13'),
	(5, 1, 2, 6, 2, NULL, '2020-11-25 10:00:00', '2020-11-25 11:00:00', 'success', 'teste', '2020-11-24 10:54:36', '2020-11-26 12:54:16'),
	(8, 1, 2, 5, 2, NULL, '2020-11-04 10:00:00', '2020-11-04 10:30:00', 'success', 'teste', '2020-11-24 14:23:59', '2020-11-26 12:54:19'),
	(12, 1, 2, 5, 2, NULL, '2020-11-04 19:00:00', '2020-11-04 19:30:00', 'success', 'teste de cadastro', '2020-11-24 15:49:48', '2020-11-26 12:54:22'),
	(13, 1, 2, 7, 2, NULL, '2020-11-26 10:00:00', '2020-11-26 11:00:00', 'occurrence', 'Novo Agendamento Com Status', '2020-11-26 09:51:29', '2020-11-26 13:23:46'),
	(15, 1, 2, 7, 2, 'Exame Complementar de Teste', '2020-11-29 15:20:00', '2020-11-29 15:50:00', 'pending', 'Teste', '2020-11-29 14:22:27', NULL),
	(16, 1, 2, 5, 2, 'Exame Complementar de Teste', '2020-11-30 20:30:00', '2020-11-30 21:00:00', 'pending', 'agendamento 2', '2020-11-29 14:33:58', NULL);
/*!40000 ALTER TABLE `schedulings` ENABLE KEYS */;

-- Copiando estrutura para tabela consultorio.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela consultorio.users: ~0 rows (aproximadamente)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'Erick Cordeiro', 'erickcordeiroa@gmail.com', '$2y$10$QEarlVC/Dq9g7JrpRpJDUOhIkMwQO3.8G0RxhEa8VMY.TJ4QgZeSS', '2020-06-28 18:39:35', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
