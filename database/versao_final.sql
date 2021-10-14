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
