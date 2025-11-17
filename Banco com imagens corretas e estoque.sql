-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: ecommerce
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id_admin` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `cargo` varchar(20) DEFAULT 'administrador',
  PRIMARY KEY (`id_admin`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,3,'administrador'),(2,4,'administrador');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avaliacao` (
  `id_avaliacao` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `id_produto` bigint(20) unsigned NOT NULL,
  `nota` tinyint(4) NOT NULL,
  `data_avaliacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total` decimal(5,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id_avaliacao`),
  KEY `avaliacao_id_user_foreign` (`id_user`),
  KEY `avaliacao_id_produto_foreign` (`id_produto`),
  CONSTRAINT `avaliacao_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  CONSTRAINT `avaliacao_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
INSERT INTO `avaliacao` VALUES (3,1,1,4,'2025-11-04 12:12:10',4.00),(5,3,1,3,'2025-11-04 12:12:39',4.00),(6,3,1,5,'2025-11-04 12:15:04',4.00),(7,3,1,2,'2025-11-04 12:19:10',3.50);
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER trg_media_avaliacao

BEFORE INSERT ON avaliacao

FOR EACH ROW

BEGIN

    DECLARE media_antiga DECIMAL(5,2);

    DECLARE qtd INT;


    -- Conta quantas avaliações existem para o produto

    SELECT COUNT(*), AVG(nota)

    INTO qtd, media_antiga

    FROM avaliacao

    WHERE id_produto = NEW.id_produto;


    -- Se for a primeira, a média é a própria nota

    IF qtd = 0 OR media_antiga IS NULL THEN

        SET NEW.total = NEW.nota;

    ELSE

        -- Calcula nova média incluindo a nota atual

        SET NEW.total = ((media_antiga * qtd) + NEW.nota) / (qtd + 1);

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Computadores'),(2,'Notebooks'),(3,'Smartphone');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `celular`
--

DROP TABLE IF EXISTS `celular`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `celular` (
  `id_celular` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_categoria` bigint(20) unsigned NOT NULL,
  `id_marca` bigint(20) unsigned NOT NULL,
  `armazenamento` varchar(20) NOT NULL,
  `ram` varchar(50) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `tamanho_tela` varchar(50) NOT NULL,
  `processador` varchar(50) NOT NULL,
  `camera_traseira` varchar(100) NOT NULL,
  `camera_frontal` varchar(100) NOT NULL,
  `bateria` varchar(50) NOT NULL,
  PRIMARY KEY (`id_celular`),
  KEY `id_categoria` (`id_categoria`),
  KEY `id_marca` (`id_marca`),
  KEY `idx_celular_filtro` (`id_marca`),
  CONSTRAINT `celular_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `celular_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `celular`
--

LOCK TABLES `celular` WRITE;
/*!40000 ALTER TABLE `celular` DISABLE KEYS */;
INSERT INTO `celular` VALUES (1,3,6,'512GB','16GB','Prata','6.1\"','A17 PRO','48 MP','12 MP','3274 mAh'),(2,3,6,'1TB','16GB','Laranja','6.7\"','A17 PRO','48 MP','12 MP','4422 mAh'),(3,3,6,'512GB','12GB','Salvia','6.6\"','A17 PRO','48 MP','12 MP','4380 mAh'),(4,3,6,'256GB','8GB','Branco','5.8\"','A17 PRO','48 MP','12 MP','3200 mAh'),(5,3,6,'128GB','8GB','Branco','6.1\"','A16 Bionic','12 MP','12 MP','3279 mAh'),(6,3,7,'512GB','12GB','Preto','6.7\"','Snapdragon 8 Gen 3','50 MP','50 MP','4600 mAh'),(7,3,7,'256GB','8GB','Azul','6.7\"','Snapdragon 6 Gen 1','50 MP','32 MP','5000 mAh'),(8,3,7,'256GB','8GB','Verde','6.6\"','Dimensity 7030','50 MP','32 MP','5000 mAh'),(9,3,7,'256GB','8GB','Azul','6.5\"','Dimensity 930','50 MP','16 MP','5000 mAh'),(10,3,7,'64GB','4GB','Preto','6.5\"','Unisoc T606','13 MP','5 MP','5000 mAh'),(11,3,8,'512GB','16GB','Preto','6.82\"','Snapdragon 8 Gen 3','50 MP','32 MP','5000 mAh'),(12,3,8,'256GB','12GB','Azul','6.7\"','Dimensity 9200+','50 MP','32 MP','5000 mAh'),(13,3,8,'256GB','8GB','Prata','6.7\"','Dimensity 7050','64 MP','8 MP','5000 mAh'),(14,3,8,'512GB','12GB','Preto','6.8\"','Dimensity 9200','50 MP','32 MP','4300 mAh'),(15,3,8,'128GB','8GB','Rosa','6.72\"','Dimensity 6020','50 MP','8 MP','5000 mAh'),(16,3,9,'1TB','12GB','Preto','6.8\"','Snapdragon 8 Gen 3','200 MP','12 MP','5000 mAh'),(17,3,9,'256GB','8GB','Azul','6.4\"','Exynos 2200','50 MP','10 MP','4500 mAh'),(18,3,9,'256GB','8GB','Prata','6.6\"','Exynos 1480','50 MP','32 MP','5000 mAh'),(19,3,9,'256GB','8GB','Preto','6.7\"','Snapdragon 7 Gen 1','50 MP','32 MP','5000 mAh'),(20,3,9,'512GB','8GB','Dourado','6.7\"','Snapdragon 8 Gen 2','12 MP','10 MP','3700 mAh'),(21,3,10,'1TB','16GB','Preto','6.73\"','Snapdragon 8 Gen 3','50 MP','32 MP','5000 mAh'),(22,3,10,'512GB','12GB','Azul','6.67\"','Dimensity 7200 Ultra','200 MP','16 MP','5000 mAh'),(23,3,10,'512GB','16GB','Preto','6.67\"','Snapdragon 8 Gen 2','50 MP','20 MP','5000 mAh'),(24,3,10,'128GB','6GB','Cinza','6.74\"','Helio G85','50 MP','8 MP','5000 mAh'),(25,3,10,'256GB','12GB','Prata','6.67\"','Dimensity 8200 Ultra','50 MP','20 MP','5000 mAh');
/*!40000 ALTER TABLE `celular` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cupom_user`
--

DROP TABLE IF EXISTS `cupom_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cupom_user` (
  `id_cupom_user` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_cupom` bigint(20) unsigned NOT NULL,
  `id_user` bigint(20) unsigned NOT NULL,
  `usos` int(11) DEFAULT 0,
  PRIMARY KEY (`id_cupom_user`),
  KEY `cupons_cupom_user` (`id_cupom`),
  KEY `cumpom_in_user` (`id_user`),
  CONSTRAINT `cumpom_in_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  CONSTRAINT `cupons_cupom_user` FOREIGN KEY (`id_cupom`) REFERENCES `cupons` (`id_cupom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupom_user`
--

LOCK TABLES `cupom_user` WRITE;
/*!40000 ALTER TABLE `cupom_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `cupom_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cupons`
--

DROP TABLE IF EXISTS `cupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cupons` (
  `id_cupom` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `tipo_desconto` enum('porcentagem','valor') NOT NULL,
  `valor_desconto` decimal(10,2) NOT NULL,
  `uso_total` int(11) DEFAULT 1,
  `uso_user` int(11) DEFAULT 1,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_cupom`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cupons`
--

LOCK TABLES `cupons` WRITE;
/*!40000 ALTER TABLE `cupons` DISABLE KEYS */;
INSERT INTO `cupons` VALUES (1,'BEMVINDO10','Cupom de boas-vindas com 10% de desconto na primeira compra','porcentagem',10.00,100,1,'2025-10-01 00:00:00','2025-12-31 00:00:00',1);
/*!40000 ALTER TABLE `cupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `endereco`
--

DROP TABLE IF EXISTS `endereco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `endereco` (
  `id_endereco` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `complemento` varchar(50) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `estado` varchar(2) NOT NULL,
  PRIMARY KEY (`id_endereco`),
  KEY `endereco_user` (`id_user`),
  CONSTRAINT `endereco_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco`
--

LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
INSERT INTO `endereco` VALUES (1,3,'Rua flamingo gomes','91403-210','Galpão cinza','Queiroz','Tangamandapio','TP'),(2,4,'rua do sapo','87240-000','beira biquera','canto','Terra Boa','PR');
/*!40000 ALTER TABLE `endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estoque` (
  `id_estoque` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_produto` bigint(20) unsigned NOT NULL,
  `quantidade` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `data_estoque` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo` enum('Entrada','Saida') NOT NULL,
  `id_loja` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_estoque`),
  KEY `id_produto` (`id_produto`),
  KEY `id_loja` (`id_loja`),
  CONSTRAINT `estoque_ibfk_2` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`),
  CONSTRAINT `estoque_ibfk_3` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estoque`
--

LOCK TABLES `estoque` WRITE;
/*!40000 ALTER TABLE `estoque` DISABLE KEYS */;
INSERT INTO `estoque` VALUES (1,1,183,183,'2025-11-13 20:22:09','Entrada',1),(2,2,223,223,'2025-11-13 20:22:09','Entrada',1),(3,3,117,117,'2025-11-13 20:22:09','Entrada',1),(4,4,66,66,'2025-11-13 20:22:09','Entrada',1),(5,5,131,131,'2025-11-13 20:22:09','Entrada',1),(6,6,205,205,'2025-11-13 20:22:09','Entrada',1),(7,7,180,180,'2025-11-13 20:22:09','Entrada',1),(8,8,237,237,'2025-11-13 20:22:09','Entrada',1),(9,9,192,192,'2025-11-13 20:22:09','Entrada',1),(10,10,200,200,'2025-11-13 20:22:09','Entrada',1),(11,11,176,176,'2025-11-13 20:23:07','Entrada',2),(12,12,227,227,'2025-11-13 20:23:07','Entrada',2),(13,13,157,157,'2025-11-13 20:23:07','Entrada',2),(14,14,56,56,'2025-11-13 20:23:07','Entrada',2),(15,15,162,162,'2025-11-13 20:23:07','Entrada',2),(16,16,190,190,'2025-11-13 20:23:07','Entrada',2),(17,17,212,212,'2025-11-13 20:23:07','Entrada',2),(18,18,241,241,'2025-11-13 20:23:07','Entrada',2),(19,19,115,115,'2025-11-13 20:23:07','Entrada',2),(20,20,207,207,'2025-11-13 20:23:07','Entrada',2),(21,21,237,237,'2025-11-13 20:23:07','Entrada',2),(22,22,113,113,'2025-11-13 20:23:07','Entrada',2),(23,23,209,209,'2025-11-13 20:23:07','Entrada',2),(24,24,50,50,'2025-11-13 20:23:07','Entrada',2),(25,25,178,178,'2025-11-13 20:23:07','Entrada',2),(26,26,88,88,'2025-11-13 20:23:07','Entrada',2),(27,27,57,57,'2025-11-13 20:23:07','Entrada',2),(28,28,173,173,'2025-11-13 20:23:07','Entrada',2),(29,29,244,244,'2025-11-13 20:23:07','Entrada',2),(30,30,247,247,'2025-11-13 20:23:07','Entrada',2),(31,31,53,53,'2025-11-13 20:23:07','Entrada',2),(32,32,79,79,'2025-11-13 20:23:07','Entrada',2),(33,33,183,183,'2025-11-13 20:23:07','Entrada',2),(34,34,230,230,'2025-11-13 20:23:07','Entrada',2),(35,35,148,148,'2025-11-13 20:23:07','Entrada',2),(36,36,202,202,'2025-11-13 20:23:46','Entrada',1),(37,37,114,114,'2025-11-13 20:23:46','Entrada',1),(38,38,117,117,'2025-11-13 20:23:46','Entrada',1),(39,39,192,192,'2025-11-13 20:23:46','Entrada',1),(40,40,158,158,'2025-11-13 20:23:46','Entrada',1),(41,41,162,162,'2025-11-13 20:23:46','Entrada',1),(42,42,88,88,'2025-11-13 20:23:46','Entrada',1),(43,43,106,106,'2025-11-13 20:23:46','Entrada',1),(44,44,218,218,'2025-11-13 20:23:46','Entrada',1),(45,45,116,116,'2025-11-13 20:23:46','Entrada',1),(46,46,80,80,'2025-11-13 20:23:46','Entrada',1),(47,47,201,201,'2025-11-13 20:23:46','Entrada',1),(48,48,116,116,'2025-11-13 20:23:46','Entrada',1),(49,49,126,126,'2025-11-13 20:23:46','Entrada',1),(50,50,232,232,'2025-11-13 20:23:46','Entrada',1),(51,51,131,131,'2025-11-13 20:23:46','Entrada',1),(52,52,108,108,'2025-11-13 20:23:46','Entrada',1),(53,53,99,99,'2025-11-13 20:23:46','Entrada',1),(54,54,120,120,'2025-11-13 20:23:46','Entrada',1),(55,55,55,55,'2025-11-13 20:23:46','Entrada',1);
/*!40000 ALTER TABLE `estoque` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER atualiza_estoque_segura

BEFORE INSERT ON estoque

FOR EACH ROW

BEGIN

    DECLARE atual INT DEFAULT 0;


    -- Busca o último total registrado para o produto

    SELECT total

    INTO atual

    FROM estoque

    WHERE id_produto = NEW.id_produto

    ORDER BY id_estoque DESC

    LIMIT 1;


    -- Verificação: se for saída e a quantidade for maior que o estoque atual, aborta

    IF NEW.tipo = 'Saida' AND NEW.quantidade > atual THEN

        SIGNAL SQLSTATE '45000'

        SET MESSAGE_TEXT = 'Estoque insuficiente para Saída';

    END IF;


    -- Calcula o novo total

    IF NEW.tipo = 'Entrada' THEN

        SET NEW.total = atual + NEW.quantidade;

    ELSEIF NEW.tipo = 'Saida' THEN

        SET NEW.total = atual - NEW.quantidade;

    END IF;

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `imagem`
--

DROP TABLE IF EXISTS `imagem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagem` (
  `id_imagem` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_info` bigint(20) unsigned DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  `id_celular` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_imagem`),
  KEY `imagem` (`id_info`),
  KEY `id_celular` (`id_celular`),
  CONSTRAINT `imagem_ibfk_1` FOREIGN KEY (`id_info`) REFERENCES `produto_info` (`id_info`),
  CONSTRAINT `imagem_ibfk_2` FOREIGN KEY (`id_celular`) REFERENCES `celular` (`id_celular`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagem`
--

LOCK TABLES `imagem` WRITE;
/*!40000 ALTER TABLE `imagem` DISABLE KEYS */;
INSERT INTO `imagem` VALUES (1,1,'https://http2.mlstatic.com/D_NQ_NP_2X_644770-MLA88197867621_072025-F.webp',1,NULL),(2,1,'https://http2.mlstatic.com/D_NQ_NP_2X_997876-MLA88198947003_072025-F.webp',2,NULL),(3,1,'https://http2.mlstatic.com/D_NQ_NP_2X_937884-MLA87861931444_072025-F.webp',3,NULL),(4,2,'https://http2.mlstatic.com/D_NQ_NP_2X_686475-MLU77336186373_062024-F.webp',1,NULL),(5,2,'https://http2.mlstatic.com/D_NQ_NP_2X_892238-MLU77123415588_062024-F.webp',2,NULL),(6,2,'https://http2.mlstatic.com/D_NQ_NP_2X_968353-MLU77335823367_062024-F.webp',3,NULL),(7,3,'https://http2.mlstatic.com/D_NQ_NP_2X_881289-MLB49401614755_032022-F.webp',1,NULL),(8,3,'https://http2.mlstatic.com/D_NQ_NP_2X_839251-MLB49401614753_032022-F.webp',2,NULL),(9,3,'https://http2.mlstatic.com/D_NQ_NP_2X_949421-MLB49401614759_032022-F.webp',3,NULL),(10,4,'https://http2.mlstatic.com/D_NQ_NP_2X_687860-MLA95379266201_102025-F.webp',1,NULL),(11,4,'https://http2.mlstatic.com/D_NQ_NP_2X_864128-MLU77010117199_062024-F.webp',2,NULL),(12,4,'https://http2.mlstatic.com/D_NQ_NP_2X_648745-MLU76803864942_062024-F.webp',3,NULL),(13,5,'https://http2.mlstatic.com/D_NQ_NP_2X_863500-MLA95372168441_102025-F.webp',1,NULL),(14,5,'https://http2.mlstatic.com/D_NQ_NP_2X_779316-MLA79403134568_092024-F.webp',2,NULL),(15,5,'https://http2.mlstatic.com/D_NQ_NP_2X_843946-MLU78222959917_082024-F.webp',3,NULL),(16,6,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-001_2_4_1_2_1.jpg',1,NULL),(17,6,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-003_2_4_1_2_1.jpg',2,NULL),(18,6,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-004_2_4_1_2_1.jpg',3,NULL),(19,7,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/b/ab-tgt-b120-teclado-mouse_2_9.jpg',1,NULL),(20,7,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-tgt-b120-home-sgpu-002_2_17.jpg',2,NULL),(21,7,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-tgt-b120-home-sgpu-006_16.jpg',3,NULL),(22,8,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/v/o/voyager_preto_001_2.jpg',1,NULL),(23,8,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/v/o/voyager_preto_003_2.jpg',2,NULL),(24,8,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/v/o/voyager_preto_004_2.jpg',3,NULL),(25,9,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-001_3_11_1.jpg',1,NULL),(26,9,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-003_4_2_1.jpg',2,NULL),(27,9,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-004_3_11_1.jpg',3,NULL),(28,10,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/t/g/tgt-h100-teclado-mouse_23.jpg',1,NULL),(29,10,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-home-tgt-h100-004_20.jpg',2,NULL),(30,10,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-home-tgt-h100-005_5.jpg',3,NULL),(31,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_928832-MLA95400490597_102025-F.webp',1,1),(32,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_646926-MLA92147879245_092025-F.webp',2,1),(33,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_911861-MLA92147926653_092025-F.webp',3,1),(34,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_646159-MLA94808505684_102025-F.webp',1,2),(35,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_848223-MLA91747409006_092025-F.webp',2,2),(36,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_864666-MLA92148336081_092025-F.webp',3,2),(37,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_689046-MLA95247399669_102025-F.webp',1,3),(38,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_855971-MLA92147418805_092025-F.webp',2,3),(39,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_652694-MLA92147428391_092025-F.webp',3,3),(40,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_724113-MLA95353750009_102025-F.webp',1,4),(41,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_922839-MLA92147637943_092025-F.webp',2,4),(42,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_637178-MLA91746641392_092025-F.webp',3,4),(43,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_661885-MLA95355557975_102025-F.webp',1,5),(44,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_796141-MLA79138739857_092024-F.webp',2,5),(45,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_798340-MLA78900900200_092024-F.webp',3,5),(46,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_895694-MLA95358877873_102025-F.webp',1,6),(47,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_884811-MLU75706725031_042024-F.webp',2,6),(48,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_729510-MLU77716644757_072024-F.webp',3,6),(49,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_881475-MLB90274322503_082025-F-smartphone-motorola-moto-g85-5g-dual-sim-256gb-8gb-ram-nf.webp',1,7),(50,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_675053-MLB85582705290_062025-F-smartphone-motorola-moto-g85-5g-dual-sim-256gb-8gb-ram-nf.webp',2,7),(51,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_600627-MLB85582754940_062025-F-smartphone-motorola-moto-g85-5g-dual-sim-256gb-8gb-ram-nf.webp',3,7),(52,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_713455-MLB77490150782_072024-F.webp',1,8),(53,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_722779-MLB77490150778_072024-F.webp',2,8),(54,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_728956-MLB77490150780_072024-F.webp',3,8),(55,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_937861-MLA94808404574_102025-F.webp',1,9),(56,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_743798-MLU74054785696_012024-F.webp',2,9),(57,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_722279-MLU74862708711_032024-F.webp',3,9),(58,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_802500-MLA94918102106_102025-F.webp',1,10),(59,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_658536-MLA82937550334_032025-F.webp',2,10),(60,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_659045-MLA82937540332_032025-F.webp',3,10),(61,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_784235-MLB84100770047_042025-F-telefone-x7-pro-51212-gb-novo.webp',1,11),(62,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_637183-MLB84100770233_042025-F-telefone-x7-pro-51212-gb-novo.webp',2,11),(63,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_637183-MLB84100770233_042025-F-telefone-x7-pro-51212-gb-novo.webp',3,11),(64,NULL,'https://bludiode.com/39601-home_default/xiaomi-redmi-note-12-pro-plus-12256-gb-azul.jpg',1,12),(65,NULL,'https://bludiode.com/39602-home_default/xiaomi-redmi-note-12-pro-plus-12256-gb-azul.jpg',2,12),(66,NULL,'https://bludiode.com/39603-home_default/xiaomi-redmi-note-12-pro-plus-12256-gb-azul.jpg',3,12),(67,NULL,'https://www.giztop.com/media/catalog/product/cache/9f42716fd2ba91f80e52d9d3665ec9f7/o/p/oppo_a3-silver.png',1,13),(68,NULL,'https://www.giztop.com/media/catalog/product/cache/9f42716fd2ba91f80e52d9d3665ec9f7/o/p/oppo_a3-silver1.png',2,13),(69,NULL,'https://assorted.downloads.oppo.com/static/assets/images/products/a3/sec7-d16d0ee1a258458d4bcb6586a938f54ce8224b27.jpg',3,13),(70,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_847855-MLA74033176505_012024-F.webp',1,14),(71,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_900491-MLA74033176501_012024-F.webp',2,14),(72,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_696196-MLA74033176509_012024-F.webp',3,14),(73,NULL,'https://img.myipadbox.com/upload/store/product_l/EDA006113701B.jpg',1,15),(74,NULL,'https://img.myipadbox.com/upload/store/detail_l/EDA006113701B_1.jpg',2,15),(75,NULL,'https://img.myipadbox.com/upload/store/detail_l/EDA006113701B_5.jpg',3,15),(76,NULL,'https://m.media-amazon.com/images/I/51rPU0jDc0L._AC_SX679_.jpg',1,16),(77,NULL,'https://m.media-amazon.com/images/I/51Xu16ifo2L._AC_SX679_.jpg',2,16),(78,NULL,'https://m.media-amazon.com/images/I/51qtq6gHQnL._AC_SX679_.jpg',3,16),(79,NULL,'https://m.media-amazon.com/images/I/61t-8i2QAoL._AC_SY300_SX300_QL70_ML2_.jpg',1,17),(80,NULL,'https://m.media-amazon.com/images/I/71f1-OaaM5L._AC_SX679_.jpg',2,17),(81,NULL,'https://m.media-amazon.com/images/I/51ODZr+6AGL._AC_SL1500_.jpg',3,17),(82,NULL,'https://m.media-amazon.com/images/I/51WguaffFYL._AC_SX679_.jpg',1,18),(83,NULL,'https://m.media-amazon.com/images/I/61UKGa0zU4L._AC_SX679_.jpg',2,18),(84,NULL,'https://m.media-amazon.com/images/I/41S1mZ3V7TL._AC_SL1000_.jpg',3,18),(85,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_903372-MLA95370288523_102025-F.webp',1,19),(86,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_701098-MLA82793639472_032025-F.webp',2,19),(87,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_980585-MLA82793678152_032025-F.webp',3,19),(88,NULL,'https://m.media-amazon.com/images/I/51emAHX5QrL._AC_SY300_SX300_QL70_ML2_.jpg',1,20),(89,NULL,'https://m.media-amazon.com/images/I/514EtCTmCvL._AC_SX679_.jpg',2,20),(90,NULL,'https://m.media-amazon.com/images/I/71oZEEMaoxL._AC_SX679_.jpg',3,20),(91,NULL,'https://m.magazineluiza.com.br/a-static/420x420/smartphone-celular-xiaomi-redmi-note-14-lancamento-256gb-8gb-camera-108mp-20mp-tela-6-67-amoled-120hz-fhd-plus-design-premium-dual-sim-chip/martinpresentes/redmi-note14-256-8-preto/39b537db21bceae29c86f7491656a867.jpeg',1,21),(92,NULL,'https://a-static.mlcdn.com.br/420x420/smartphone-celular-xiaomi-redmi-note-14-lancamento-256gb-8gb-camera-108mp-20mp-tela-6-67-amoled-120hz-fhd-plus-design-premium-dual-sim-chip/martinpresentes/redmi-note14-256-8-preto/60954bb82004b8baf219239d8344b2cc.jpeg',2,21),(93,NULL,'https://a-static.mlcdn.com.br/420x420/smartphone-celular-xiaomi-redmi-note-14-lancamento-256gb-8gb-camera-108mp-20mp-tela-6-67-amoled-120hz-fhd-plus-design-premium-dual-sim-chip/martinpresentes/redmi-note14-256-8-preto/b6f6da367ce9d0271e853586fad85cd9.jpeg',3,21),(94,NULL,'https://http2.mlstatic.com/D_Q_NP_2X_920498-MLA94919936786_102025-R.webp',1,22),(95,NULL,'https://http2.mlstatic.com/D_Q_NP_2X_686036-MLA74676491014_022024-R.webp',2,22),(96,NULL,'https://http2.mlstatic.com/D_Q_NP_2X_849626-MLA91556837384_092025-R.webp',3,22),(97,NULL,'https://http2.mlstatic.com/D_NQ_NP_2X_767864-MLB94538151585_102025-F.webp',1,23),(98,NULL,'https://http2.mlstatic.com/D_Q_NP_2X_618808-MLB94537332633_102025-R.webp',2,23),(99,NULL,'https://http2.mlstatic.com/D_Q_NP_2X_607817-MLB94110429934_102025-R.webp',3,23),(100,NULL,'https://m.magazineluiza.com.br/a-static/420x420/smartphone-oppo-reno13-512gb-branco-5g-12gb-ram-667-cam-tripla-selfie-50mp/magazineluiza/240063300/c4dc5617ff96cba1c99a29a1f8cf8ff9.jpg',1,24),(101,NULL,'https://a-static.mlcdn.com.br/420x420/smartphone-oppo-reno13-512gb-branco-5g-12gb-ram-667-cam-tripla-selfie-50mp/magazineluiza/240063300/e30c2bea5be168adbadc5bc1bb7dd846.jpg',2,24),(102,NULL,'https://a-static.mlcdn.com.br/420x420/smartphone-oppo-reno13-512gb-branco-5g-12gb-ram-667-cam-tripla-selfie-50mp/magazineluiza/240063300/0d5a46361cae651b7e2f84b20a22801e.jpg',3,24),(103,NULL,'https://i.zst.com.br/thumbs/1/32/16/-1239415638.jpg',1,25),(104,NULL,'https://i.zst.com.br/thumbs/1/32/16/-1239415639.jpg',2,25),(105,NULL,'https://i.zst.com.br/thumbs/1/17/16/-1239415640.jpg',3,25),(106,36,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/e/1/e1504fa-nj825w8.jpg',1,NULL),(107,36,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/e/1/e1504fa-nj825w6.jpg',2,NULL),(108,36,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/e/1/e1504fa-nj825w.jpg',3,NULL),(109,37,'https://m.media-amazon.com/images/I/81IgxPCv6OL._AC_SX679_.jpg',1,NULL),(110,37,'https://m.media-amazon.com/images/I/714XBwRahKL._AC_SL1500_.jpg',2,NULL),(111,37,'https://m.media-amazon.com/images/I/71OBjG8Kp9L._AC_SL1500_.jpg',3,NULL),(112,38,'https://m.media-amazon.com/images/I/516ji5m10ML._AC_SX679_.jpg',1,NULL),(113,38,'https://m.media-amazon.com/images/I/51OfL6oV5lL._AC_SX679_.jpg',2,NULL),(114,38,'https://m.media-amazon.com/images/I/411HD8elX9L._AC_SX679_.jpg',3,NULL),(115,39,'https://m.media-amazon.com/images/I/51O4bS147tL._AC_SX679_.jpg',1,NULL),(116,39,'https://m.media-amazon.com/images/I/514yoko6meL._AC_SX679_.jpg',2,NULL),(117,39,'https://m.media-amazon.com/images/I/51Q5gEvIL7L._AC_SX679_.jpg',3,NULL),(118,40,'https://m.media-amazon.com/images/I/71iHE1YN73L._AC_SX679_.jpg',1,NULL),(119,40,'https://m.media-amazon.com/images/I/71YNlMG0u4L._AC_SX679_.jpg',2,NULL),(120,40,'https://m.media-amazon.com/images/I/61PwpyS8a9L._AC_SX679_.jpg',3,NULL),(121,41,'https://m.media-amazon.com/images/I/61916dNVFYL._AC_SX679_.jpg',1,NULL),(122,41,'https://m.media-amazon.com/images/I/51r1VEKV7wL._AC_SX679_.jpg',2,NULL),(123,41,'https://m.media-amazon.com/images/I/51O1YVpDy+L._AC_SX679_.jpg',3,NULL),(124,42,'https://http2.mlstatic.com/D_NQ_NP_2X_776296-MLB90956833527_082025-F.webp',1,NULL),(125,42,'https://http2.mlstatic.com/D_NQ_NP_2X_917196-MLA82482997725_022025-F.webp',2,NULL),(126,42,'https://http2.mlstatic.com/D_NQ_NP_2X_654624-MLA82482952431_022025-F.webp',3,NULL),(127,43,'https://http2.mlstatic.com/D_NQ_NP_2X_976269-MLU77356741265_062024-F.webp',1,NULL),(128,43,'https://http2.mlstatic.com/D_NQ_NP_2X_825549-MLA44986030133_022021-F.webp',2,NULL),(129,43,'https://http2.mlstatic.com/D_NQ_NP_2X_706843-MLU77143780748_062024-F.webp',3,NULL),(130,44,'https://http2.mlstatic.com/D_NQ_NP_2X_879552-MLB51017569830_082022-F.webp',1,NULL),(131,44,'https://http2.mlstatic.com/D_NQ_NP_2X_643785-MLB51017569831_082022-F.webp',2,NULL),(132,44,'https://http2.mlstatic.com/D_NQ_NP_2X_973520-MLB51017569829_082022-F.webp',3,NULL),(133,45,'https://http2.mlstatic.com/D_NQ_NP_2X_843368-MLU77445266509_072024-F.webp',1,NULL),(134,45,'https://http2.mlstatic.com/D_NQ_NP_2X_766915-MLU77445276023_072024-F.webp',2,NULL),(135,45,'https://http2.mlstatic.com/D_NQ_NP_2X_993347-MLU77445266521_072024-F.webp',3,NULL),(136,46,'https://http2.mlstatic.com/D_NQ_NP_2X_757934-MLB47969615916_102021-F.webp',1,NULL),(137,46,'https://http2.mlstatic.com/D_NQ_NP_2X_703846-MLB47969615915_102021-F.webp',2,NULL),(138,46,'https://http2.mlstatic.com/D_NQ_NP_2X_613582-MLB47969615913_102021-F.webp',3,NULL),(139,47,'https://http2.mlstatic.com/D_NQ_NP_2X_994951-MLA95513993751_102025-F.webp',1,NULL),(140,47,'https://http2.mlstatic.com/D_NQ_NP_2X_940244-MLU77116634384_062024-F.webp',2,NULL),(141,47,'https://http2.mlstatic.com/D_NQ_NP_2X_841341-MLU77116711030_062024-F.webp',3,NULL),(142,48,'https://http2.mlstatic.com/D_NQ_NP_2X_843972-MLB96412674553_102025-F.webp',1,NULL),(143,48,'https://http2.mlstatic.com/D_NQ_NP_2X_685940-MLB96395506283_102025-F.webp',2,NULL),(144,48,'https://http2.mlstatic.com/D_NQ_NP_2X_804383-MLB96395506273_102025-F.webp',3,NULL),(145,49,'https://http2.mlstatic.com/D_NQ_NP_2X_860904-MLA95693174756_102025-F.webp',1,NULL),(146,49,'https://http2.mlstatic.com/D_NQ_NP_2X_981312-MLA82670084890_032025-F.webp',2,NULL),(147,49,'https://http2.mlstatic.com/D_NQ_NP_2X_943165-MLA82670084900_032025-F.webp',3,NULL),(148,50,'https://http2.mlstatic.com/D_NQ_NP_2X_845218-MLU77312573251_062024-F.webp',1,NULL),(149,50,'https://http2.mlstatic.com/D_NQ_NP_2X_720960-MLU77312414493_062024-F.webp',2,NULL),(150,50,'https://http2.mlstatic.com/D_NQ_NP_2X_832436-MLU77312453957_062024-F.webp',3,NULL),(151,51,'https://http2.mlstatic.com/D_NQ_NP_2X_887648-MLU77385953225_072024-F.webp',1,NULL),(152,51,'https://http2.mlstatic.com/D_NQ_NP_2X_746740-MLU77386187349_072024-F.webp',2,NULL),(153,51,'https://http2.mlstatic.com/D_NQ_NP_2X_804811-MLU76634609109_052024-F.webp',3,NULL),(154,52,'https://http2.mlstatic.com/D_NQ_NP_2X_869334-MLC50581724425_072022-F.webp',1,NULL),(155,52,'https://http2.mlstatic.com/D_NQ_NP_2X_740552-MLC50581756194_072022-F.webp',2,NULL),(156,52,'https://http2.mlstatic.com/D_NQ_NP_2X_779012-MLC46890925772_072021-F.webp',3,NULL),(157,53,'https://http2.mlstatic.com/D_NQ_NP_2X_937447-MLA50709053984_072022-F.webp',1,NULL),(158,53,'https://http2.mlstatic.com/D_NQ_NP_2X_795923-MLA50709092661_072022-F.webp',2,NULL),(159,53,'https://http2.mlstatic.com/D_NQ_NP_2X_726695-MLA50709100627_072022-F.webp',3,NULL),(160,54,'https://http2.mlstatic.com/D_NQ_NP_2X_851253-MLU73368335036_122023-F.webp',1,NULL),(161,54,'https://http2.mlstatic.com/D_NQ_NP_2X_875803-MLU77321085639_062024-F.webp',2,NULL),(162,54,'https://http2.mlstatic.com/D_NQ_NP_2X_929411-MLU77320978233_062024-F.webp',3,NULL),(163,55,'https://http2.mlstatic.com/D_NQ_NP_2X_671549-MLU72253812956_102023-F.webp',1,NULL),(164,55,'https://http2.mlstatic.com/D_NQ_NP_2X_788872-MLU72253967622_102023-F.webp',2,NULL),(165,55,'https://http2.mlstatic.com/D_NQ_NP_2X_886002-MLU72317805891_102023-F.webp',3,NULL);
/*!40000 ALTER TABLE `imagem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loja`
--

DROP TABLE IF EXISTS `loja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loja` (
  `id_loja` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_endereco` bigint(20) unsigned NOT NULL,
  `id_admin` bigint(20) unsigned NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  PRIMARY KEY (`id_loja`),
  KEY `id_endereco` (`id_endereco`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `loja_ibfk_1` FOREIGN KEY (`id_endereco`) REFERENCES `endereco` (`id_endereco`),
  CONSTRAINT `loja_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loja`
--

LOCK TABLES `loja` WRITE;
/*!40000 ALTER TABLE `loja` DISABLE KEYS */;
INSERT INTO `loja` VALUES (1,1,1,'MR.Tech','Loja de computadores','12.345.678/0001-95'),(2,2,2,'AORUS','Loja de dispositivos móveis','12.302.931/0001-95');
/*!40000 ALTER TABLE `loja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marca` (
  `id_marca` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (1,'Acer'),(2,'Asus'),(3,'Dell'),(4,'Lenovo'),(5,'HP'),(6,'Apple'),(7,'Motorola'),(8,'Oppo'),(9,'Samsung'),(10,'Xiaomi'),(11,'AMD'),(12,'Intel');
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id_pedido` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `id_loja` bigint(20) unsigned NOT NULL,
  `id_cupom` bigint(20) unsigned DEFAULT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pendente','confirmado','enviado','entregue') DEFAULT 'pendente',
  `total` decimal(10,2) NOT NULL,
  `desconto` decimal(10,2) NOT NULL,
  `total_final` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `id_user` (`id_user`),
  KEY `id_loja` (`id_loja`),
  KEY `id_cupom` (`id_cupom`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`),
  CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`id_cupom`) REFERENCES `cupons` (`id_cupom`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (1,1,1,NULL,'2025-10-27 03:00:00','entregue',3000.00,412.00,2588.00),(2,1,1,NULL,'2025-09-30 17:20:00','entregue',3800.00,200.00,3600.00),(3,1,1,NULL,'2025-08-28 14:45:00','entregue',5200.00,400.00,4800.00),(4,1,1,NULL,'2025-07-29 19:10:00','entregue',2900.00,150.00,2750.00),(5,1,1,NULL,'2025-06-27 12:30:00','entregue',6100.00,500.00,5600.00),(6,1,1,NULL,'2025-05-25 21:05:00','entregue',3300.00,100.00,3200.00);
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_produto`
--

DROP TABLE IF EXISTS `pedido_produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_produto` (
  `id_pedido_produto` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` bigint(20) unsigned NOT NULL,
  `id_produto` bigint(20) unsigned NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_pedido_produto`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_produto` (`id_produto`),
  CONSTRAINT `pedido_produto_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  CONSTRAINT `pedido_produto_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_produto`
--

LOCK TABLES `pedido_produto` WRITE;
/*!40000 ALTER TABLE `pedido_produto` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido_produto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_status`
--

DROP TABLE IF EXISTS `pedido_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido_status` (
  `id_status` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` bigint(20) unsigned NOT NULL,
  `id_admin` bigint(20) unsigned NOT NULL,
  `status` enum('pendente','confirmado','enviado','entregue') DEFAULT 'pendente',
  `data_alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_status`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `pedido_status_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  CONSTRAINT `pedido_status_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_status`
--

LOCK TABLES `pedido_status` WRITE;
/*!40000 ALTER TABLE `pedido_status` DISABLE KEYS */;
INSERT INTO `pedido_status` VALUES (1,1,1,'confirmado','2025-10-29 18:16:49'),(2,1,1,'enviado','2025-10-29 18:17:55'),(3,1,1,'entregue','2025-10-29 22:16:30');
/*!40000 ALTER TABLE `pedido_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto`
--

DROP TABLE IF EXISTS `produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto` (
  `id_produto` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(8,2) NOT NULL,
  `id_info` bigint(20) unsigned DEFAULT NULL,
  `data_att` datetime NOT NULL,
  `id_loja` bigint(20) unsigned NOT NULL,
  `id_celular` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `produto_id_info_foreign` (`id_info`),
  KEY `id_loja` (`id_loja`),
  KEY `id_celular` (`id_celular`),
  CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`),
  CONSTRAINT `produto_ibfk_2` FOREIGN KEY (`id_celular`) REFERENCES `celular` (`id_celular`),
  CONSTRAINT `produto_ibfk_3` FOREIGN KEY (`id_info`) REFERENCES `produto_info` (`id_info`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (1,'Notebook Acer',4700.00,1,'2025-10-11 11:37:15',1,NULL),(2,'Notebook Acer Swift 3',4800.00,2,'2025-10-11 13:16:00',1,NULL),(3,'Notebook Acer Predator Helios 300',10500.00,3,'2025-10-11 13:16:00',1,NULL),(4,'Notebook Acer Aspire 5',4500.00,4,'2025-10-11 13:16:00',1,NULL),(5,'Notebook Acer Nitro V 15',6700.00,5,'2025-10-11 13:16:00',1,NULL),(6,'Computador Gamer Xtreme',12500.00,6,'2025-10-11 13:18:39',1,NULL),(7,'Computador Office Plus',4800.00,7,'2025-10-11 13:18:39',1,NULL),(8,'Computador Workstation Pro',35000.00,8,'2025-10-11 13:18:39',1,NULL),(9,'Computador Gamer Storm',22000.00,9,'2025-10-11 13:18:39',1,NULL),(10,'Computador Home Basic',3500.00,10,'2025-10-11 13:18:39',1,NULL),(11,'iPhone 17 Pro',12500.00,NULL,'2025-10-11 13:21:29',2,1),(12,'iPhone 17 Pro Max',13500.00,NULL,'2025-10-11 13:21:29',2,2),(13,'iPhone 17 Plus',11000.00,NULL,'2025-10-11 13:21:29',2,3),(14,'iPhone 17',9500.00,NULL,'2025-10-11 13:21:29',2,4),(15,'iPhone 17 SE',7200.00,NULL,'2025-10-11 13:21:29',2,5),(16,'Motorola Edge 50 Pro',6200.00,NULL,'2025-10-11 13:21:29',2,6),(17,'Motorola Moto G85',1800.00,NULL,'2025-10-11 13:21:29',2,7),(18,'Motorola Edge 40 Neo',2800.00,NULL,'2025-10-11 13:21:29',2,8),(19,'Motorola Moto G73',1600.00,NULL,'2025-10-11 13:21:29',2,9),(20,'Motorola Moto E14',850.00,NULL,'2025-10-11 13:21:29',2,10),(21,'Oppo Find X7 Pro',7200.00,NULL,'2025-10-11 13:21:29',2,11),(22,'Oppo Reno 12 Pro',5100.00,NULL,'2025-10-11 13:21:29',2,12),(23,'Oppo A3 Pro',3200.00,NULL,'2025-10-11 13:21:29',2,13),(24,'Oppo Find N3 Flip',6100.00,NULL,'2025-10-11 13:21:29',2,14),(25,'Oppo A79 5G',2900.00,NULL,'2025-10-11 13:21:29',2,15),(26,'Samsung Galaxy S24 Ultra',10200.00,NULL,'2025-10-11 13:21:29',2,16),(27,'Samsung Galaxy S23 FE',4500.00,NULL,'2025-10-11 13:21:29',2,17),(28,'Samsung Galaxy A55 5G',3900.00,NULL,'2025-10-11 13:21:29',2,18),(29,'Samsung Galaxy M55',3500.00,NULL,'2025-10-11 13:21:29',2,19),(30,'Samsung Galaxy Z Flip 5',5200.00,NULL,'2025-10-11 13:21:29',2,20),(31,'Xiaomi 14 Ultra',9800.00,NULL,'2025-10-11 13:21:29',2,21),(32,'Xiaomi Redmi Note 13 Pro+',4600.00,NULL,'2025-10-11 13:21:29',2,22),(33,'Xiaomi Poco F6 Pro',4800.00,NULL,'2025-10-11 13:21:29',2,23),(34,'Xiaomi Redmi 13C',1700.00,NULL,'2025-10-11 13:21:29',2,24),(35,'Xiaomi Mi 13T',5200.00,NULL,'2025-10-11 13:21:29',2,25),(36,'Notebook Asus Vivobook 15',3500.00,36,'2025-10-13 10:10:24',1,NULL),(37,'Notebook Asus TUF Gaming F15',6200.00,37,'2025-10-13 10:10:24',1,NULL),(38,'Notebook Asus Zenbook 14',5800.00,38,'2025-10-13 10:10:24',1,NULL),(39,'Notebook Asus ROG Strix G16',8900.00,39,'2025-10-13 10:10:24',1,NULL),(40,'Notebook Asus ExpertBook B1',5100.00,40,'2025-10-13 10:10:24',1,NULL),(41,'Notebook Dell Inspiron 15 3000',3400.00,41,'2025-10-13 10:10:24',1,NULL),(42,'Notebook Dell G15 Gaming',6500.00,42,'2025-10-13 10:10:24',1,NULL),(43,'Notebook Dell XPS 13',7200.00,43,'2025-10-13 10:10:24',1,NULL),(44,'Notebook Dell Latitude 5440',5600.00,44,'2025-10-13 10:10:24',1,NULL),(45,'Notebook Dell Alienware M18',12500.00,45,'2025-10-13 10:10:24',1,NULL),(46,'Notebook Lenovo IdeaPad 3',3300.00,46,'2025-10-13 10:10:24',1,NULL),(47,'Notebook Lenovo ThinkPad E14 Gen 5',5400.00,47,'2025-10-13 10:10:24',1,NULL),(48,'Notebook Lenovo Legion 5',6900.00,48,'2025-10-13 10:10:24',1,NULL),(49,'Notebook Lenovo Yoga Slim 7',5800.00,49,'2025-10-13 10:10:24',1,NULL),(50,'Notebook Lenovo LOQ 15',7600.00,50,'2025-10-13 10:10:24',1,NULL),(51,'Notebook HP 250 G9',3400.00,51,'2025-10-13 10:10:24',1,NULL),(52,'Notebook HP Pavilion 15',5200.00,52,'2025-10-13 10:10:24',1,NULL),(53,'Notebook HP Victus 16',6100.00,53,'2025-10-13 10:10:24',1,NULL),(54,'Notebook HP Envy x360',6300.00,54,'2025-10-13 10:10:24',1,NULL),(55,'Notebook HP Omen 16',8700.00,55,'2025-10-13 10:10:24',1,NULL);
/*!40000 ALTER TABLE `produto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produto_info`
--

DROP TABLE IF EXISTS `produto_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto_info` (
  `id_info` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(240) NOT NULL,
  `id_marca` bigint(20) unsigned DEFAULT NULL,
  `id_categoria` bigint(20) unsigned DEFAULT NULL,
  `ram` varchar(100) NOT NULL,
  `armazenamento` varchar(100) NOT NULL,
  `processador` varchar(100) NOT NULL,
  `placa_mae` varchar(100) NOT NULL,
  `placa_video` varchar(100) DEFAULT NULL,
  `fonte` varchar(100) DEFAULT NULL,
  `cor` varchar(40) NOT NULL,
  PRIMARY KEY (`id_info`),
  KEY `produto_marca` (`id_marca`),
  KEY `info_categoria` (`id_categoria`),
  CONSTRAINT `produto_info_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `produto_info_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_info`
--

LOCK TABLES `produto_info` WRITE;
/*!40000 ALTER TABLE `produto_info` DISABLE KEYS */;
INSERT INTO `produto_info` VALUES (1,'Notebook Acer Aspire 7',1,2,'16GB','512GB SSD','Ryzen 5 5500U','NB-A715','GTX 1650 4GB','135 WATTS','Preto'),(2,'Notebook Acer Swift 3',1,2,'8GB','512GB SSD','Intel i7 1255U','SF314-43','Iris Xe','65 WATTS','Prata'),(3,'Notebook Acer Predator Helios 300',1,2,'32GB','1TB SSD','Intel i7 13700H','PH315-55','RTX 4060 8GB','230 WATTS','Preto'),(4,'Notebook Acer Aspire 5',1,2,'12GB','256GB SSD','Intel i5 1235U','A515-57','Iris Xe','90 WATTS','Cinza'),(5,'Notebook Acer Nitro V 15',1,2,'16GB','1TB SSD','Intel i7 13620H','NV-515','RTX 4050 6GB','180 WATTS','Preto'),(6,'Computador Gamer Xtreme',12,1,'16GB','1TB SSD','I7 12700K','B660M','RTX 4070 12GB','750 WATTS','Preto'),(7,'Computador Office Plus',12,1,'8GB','480GB SSD','I5 12400','H610M','Intel UHD 730','500 WATTS','Cinza'),(8,'Computador Workstation Pro',12,1,'32GB','2TB SSD','I9 13900K','Z790','RTX 4090 24GB','1000 WATTS','Preto'),(9,'Computador Gamer Storm',11,1,'16GB','1TB SSD','Ryzen 7 7800X3D','B650','RTX 4080 16GB','850 WATTS','Preto'),(10,'Computador Home Basic',12,1,'8GB','256GB SSD','I3 12100','H610M','Intel UHD 730','450 WATTS','Branco'),(36,'Notebook Asus Vivobook 15',2,2,'8GB','256GB SSD','Intel i5-1135G7','X515EA-BQ931','Intel Iris Xe','65 WATTS','Preto'),(37,'Notebook Asus TUF Gaming F15',2,2,'16GB','512GB SSD','Intel i7-12700H','FX507ZE-HN103W','RTX 3050 Ti 4GB','200 WATTS','Cinza'),(38,'Notebook Asus Zenbook 14',2,2,'16GB','1TB SSD','Ryzen 7 7730U','UM3402YA','Radeon Vega 8','90 WATTS','Prata'),(39,'Notebook Asus ROG Strix G16',2,2,'32GB','1TB SSD','Intel i9-13980HX','G614JV-AS73','RTX 4070 8GB','240 WATTS','Preto'),(40,'Notebook Asus ExpertBook B1',2,2,'16GB','512GB SSD','Intel i7-1255U','B1502CBA-EJ0892','Intel Iris Xe','90 WATTS','Azul'),(41,'Notebook Dell Inspiron 15 3000',3,2,'8GB','256GB SSD','Intel i5-1135G7','i3501-M30','Intel Iris Xe','65 WATTS','Prata'),(42,'Notebook Dell G15 Gaming',3,2,'16GB','512GB SSD','Ryzen 7 6800H','G15-5525-A20P','RTX 3050 4GB','200 WATTS','Preto'),(43,'Notebook Dell XPS 13',3,2,'16GB','512GB SSD','Intel i7-1360P','XPS9315-M10S','Intel Iris Xe','90 WATTS','Prata'),(44,'Notebook Dell Latitude 5440',3,2,'16GB','512GB SSD','Intel i5-1335U','LAT5440','Intel UHD Graphics','90 WATTS','Preto'),(45,'Notebook Dell Alienware M18',3,2,'32GB','1TB SSD','Intel i9-13980HX','AWM18-13980HX','RTX 4080 12GB','280 WATTS','Cinza'),(46,'Notebook Lenovo IdeaPad 3',4,2,'8GB','256GB SSD','Ryzen 5 5500U','82MFS00100','Radeon Vega 7','65 WATTS','Prata'),(47,'Notebook Lenovo ThinkPad E14 Gen 5',4,2,'16GB','512GB SSD','Intel i7-1355U','21JK0000BR','Intel Iris Xe','90 WATTS','Preto'),(48,'Notebook Lenovo Legion 5',4,2,'16GB','1TB SSD','Ryzen 7 6800H','82RD0002BR','RTX 3060 6GB','230 WATTS','Cinza'),(49,'Notebook Lenovo Yoga Slim 7',4,2,'16GB','512GB SSD','Ryzen 7 7735HS','82YM0000BR','Radeon 680M','100 WATTS','Roxo'),(50,'Notebook Lenovo LOQ 15',4,2,'32GB','1TB SSD','Intel i7-13650HX','83GS0000BR','RTX 4060 8GB','240 WATTS','Preto'),(51,'Notebook HP 250 G9',5,2,'8GB','256GB SSD','Intel i5-1235U','6Q8W2LA','Intel Iris Xe','65 WATTS','Prata'),(52,'Notebook HP Pavilion 15',5,2,'16GB','512GB SSD','Ryzen 7 5825U','6K8P5LA','Radeon Vega 8','90 WATTS','Preto'),(53,'Notebook HP Victus 16',5,2,'16GB','512GB SSD','Intel i7-12700H','7N8P6LA','RTX 3050 4GB','200 WATTS','Azul'),(54,'Notebook HP Envy x360',5,2,'16GB','1TB SSD','Ryzen 7 7730U','8L2L3LA','Radeon 680M','100 WATTS','Prata'),(55,'Notebook HP Omen 16',5,2,'32GB','1TB SSD','Intel i9-13900HX','8J2R4LA','RTX 4070 8GB','240 WATTS','Preto');
/*!40000 ALTER TABLE `produto_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id_user` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `data_nascimento` date NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'aitom donatoni','aitom@gmail.com','$2y$10$2dZ6gMi8tFgV8pQi5SamKe/dp59pKYHRN8GUj3axjPiALBr3G7JuS',0,'0000-00-00'),(3,'Vendedor Aitom','vendedor@gmail.com','$2y$10$NN8w8Y4NvRBKxyovOB1zrOsz8lGyiZtvYOBRSn73Se13ZE09XbA0W',1,'2002-10-30'),(4,'vendedor aitinho','aitinho@gmail.com','$2y$10$K5CNoF/r03lDZeNELgSxUeihF2ZAk7A5.FPndyUXX5NUCr/Ewfmsi',1,'2002-10-30');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ecommerce'
--
/*!50003 DROP PROCEDURE IF EXISTS `inserir_estoque_massivo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `inserir_estoque_massivo`(
    IN produto_inicio INT,
    IN produto_fim INT,
    IN loja_id INT,
    IN qtd_min INT,
    IN qtd_max INT,
    IN tipo_produto VARCHAR(20)
)
BEGIN
    DECLARE i INT DEFAULT 0;

    SET i = produto_inicio;

    WHILE i <= produto_fim DO
        
        INSERT INTO estoque (id_produto, quantidade, tipo, id_loja)
        VALUES (
            i,
            FLOOR(RAND() * (qtd_max - qtd_min + 1)) + qtd_min,
            tipo_produto,
            loja_id
        );

        SET i = i + 1;
    END WHILE;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-13 21:36:11
