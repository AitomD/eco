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
INSERT INTO `admin` VALUES (1,3,'administrador'),(2,3,'administrador');
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
  `data_avaliacao` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_avaliacao`),
  KEY `avaliacao_id_user_foreign` (`id_user`),
  KEY `avaliacao_id_produto_foreign` (`id_produto`),
  CONSTRAINT `avaliacao_id_produto_foreign` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  CONSTRAINT `avaliacao_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Computadores'),(2,'Notebooks'),(3,'SmartTV'),(4,'Smartphone');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco`
--

LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
INSERT INTO `endereco` VALUES (1,3,'Rua flamingo gomes','91403-210','Galpão cinza','Queiroz','Tangamandapio','TP');
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
  CONSTRAINT `estoque_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`),
  CONSTRAINT `estoque_ibfk_2` FOREIGN KEY (`id_loja`) REFERENCES `loja` (`id_loja`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estoque`
--

LOCK TABLES `estoque` WRITE;
/*!40000 ALTER TABLE `estoque` DISABLE KEYS */;
INSERT INTO `estoque` VALUES (1,1,10,10,'2025-10-20 20:01:12','Entrada',1),(2,1,5,5,'2025-10-20 20:01:39','Saida',1),(3,1,30,35,'2025-10-21 12:46:28','Entrada',1),(4,1,5,30,'2025-10-21 12:53:42','Saida',1);
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
  `id_info` bigint(20) unsigned NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `ordem` int(11) DEFAULT 0,
  PRIMARY KEY (`id_imagem`),
  KEY `imagem` (`id_info`),
  CONSTRAINT `imagem` FOREIGN KEY (`id_info`) REFERENCES `produto_info` (`id_info`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=472 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagem`
--

LOCK TABLES `imagem` WRITE;
/*!40000 ALTER TABLE `imagem` DISABLE KEYS */;
INSERT INTO `imagem` VALUES (1,7,'https://http2.mlstatic.com/D_NQ_NP_2X_644770-MLA88197867621_072025-F.webp',1),(2,7,'https://http2.mlstatic.com/D_NQ_NP_2X_997876-MLA88198947003_072025-F.webp',2),(3,7,'https://http2.mlstatic.com/D_NQ_NP_2X_937884-MLA87861931444_072025-F.webp',3),(238,8,'https://http2.mlstatic.com/D_NQ_NP_2X_686475-MLU77336186373_062024-F.webp',1),(239,8,'https://http2.mlstatic.com/D_NQ_NP_2X_892238-MLU77123415588_062024-F.webp',2),(240,8,'https://http2.mlstatic.com/D_NQ_NP_2X_968353-MLU77335823367_062024-F.webp',3),(241,9,'https://http2.mlstatic.com/D_NQ_NP_2X_881289-MLB49401614755_032022-F.webp',1),(242,9,'https://http2.mlstatic.com/D_NQ_NP_2X_839251-MLB49401614753_032022-F.webp',2),(243,9,'https://http2.mlstatic.com/D_NQ_NP_2X_949421-MLB49401614759_032022-F.webp',3),(244,10,'https://http2.mlstatic.com/D_NQ_NP_2X_687860-MLA95379266201_102025-F.webp',1),(245,10,'https://http2.mlstatic.com/D_NQ_NP_2X_864128-MLU77010117199_062024-F.webp',2),(246,10,'https://http2.mlstatic.com/D_NQ_NP_2X_648745-MLU76803864942_062024-F.webp',3),(247,7,'https://http2.mlstatic.com/D_NQ_NP_2X_644770-MLA88197867621_072025-F.webp',1),(248,7,'https://http2.mlstatic.com/D_NQ_NP_2X_997876-MLA88198947003_072025-F.webp',2),(249,7,'https://http2.mlstatic.com/D_NQ_NP_2X_937884-MLA87861931444_072025-F.webp',3),(250,8,'https://http2.mlstatic.com/D_NQ_NP_2X_686475-MLU77336186373_062024-F.webp',1),(251,8,'https://http2.mlstatic.com/D_NQ_NP_2X_892238-MLU77123415588_062024-F.webp',2),(252,8,'https://http2.mlstatic.com/D_NQ_NP_2X_968353-MLU77335823367_062024-F.webp',3),(253,9,'https://http2.mlstatic.com/D_NQ_NP_2X_881289-MLB49401614755_032022-F.webp',1),(254,9,'https://http2.mlstatic.com/D_NQ_NP_2X_839251-MLB49401614753_032022-F.webp',2),(255,9,'https://http2.mlstatic.com/D_NQ_NP_2X_949421-MLB49401614759_032022-F.webp',3),(256,10,'https://http2.mlstatic.com/D_NQ_NP_2X_687860-MLA95379266201_102025-F.webp',1),(257,10,'https://http2.mlstatic.com/D_NQ_NP_2X_864128-MLU77010117199_062024-F.webp',2),(258,10,'https://http2.mlstatic.com/D_NQ_NP_2X_648745-MLU76803864942_062024-F.webp',3),(259,11,'https://http2.mlstatic.com/D_NQ_NP_2X_863500-MLA95372168441_102025-F.webp',1),(260,11,'https://http2.mlstatic.com/D_NQ_NP_2X_779316-MLA79403134568_092024-F.webp',2),(261,11,'https://http2.mlstatic.com/D_NQ_NP_2X_843946-MLU78222959917_082024-F.webp',3),(262,12,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-001_2_4_1_2_1.jpg',1),(263,12,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-003_2_4_1_2_1.jpg',2),(264,12,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-004_2_4_1_2_1.jpg',3),(265,13,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/b/ab-tgt-b120-teclado-mouse_2_9.jpg',1),(266,13,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-tgt-b120-home-sgpu-002_2_17.jpg',2),(267,13,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-tgt-b120-home-sgpu-006_16.jpg',3),(268,14,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/v/o/voyager_preto_001_2.jpg',1),(269,14,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/v/o/voyager_preto_003_2.jpg',2),(270,14,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/v/o/voyager_preto_004_2.jpg',3),(271,15,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-001_3_11_1.jpg',1),(272,15,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-003_4_2_1.jpg',2),(273,15,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/c/v/cv500l-water_fan-media-004_3_11_1.jpg',3),(274,16,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/t/g/tgt-h100-teclado-mouse_23.jpg',1),(275,16,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-home-tgt-h100-004_20.jpg',2),(276,16,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/a/-/a-pc-home-tgt-h100-005_5.jpg',3),(277,17,'https://http2.mlstatic.com/D_NQ_NP_2X_928832-MLA95400490597_102025-F.webp',1),(278,17,'https://http2.mlstatic.com/D_NQ_NP_2X_646926-MLA92147879245_092025-F.webp',2),(279,17,'https://http2.mlstatic.com/D_NQ_NP_2X_911861-MLA92147926653_092025-F.webp',3),(280,18,'https://http2.mlstatic.com/D_NQ_NP_2X_646159-MLA94808505684_102025-F.webp',1),(281,18,'https://http2.mlstatic.com/D_NQ_NP_2X_848223-MLA91747409006_092025-F.webp',2),(282,18,'https://http2.mlstatic.com/D_NQ_NP_2X_864666-MLA92148336081_092025-F.webp',3),(283,19,'https://http2.mlstatic.com/D_NQ_NP_2X_689046-MLA95247399669_102025-F.webp',1),(284,19,'https://http2.mlstatic.com/D_NQ_NP_2X_855971-MLA92147418805_092025-F.webp',2),(285,19,'https://http2.mlstatic.com/D_NQ_NP_2X_652694-MLA92147428391_092025-F.webp',3),(286,20,'https://http2.mlstatic.com/D_NQ_NP_2X_724113-MLA95353750009_102025-F.webp',1),(287,20,'https://http2.mlstatic.com/D_NQ_NP_2X_922839-MLA92147637943_092025-F.webp',2),(288,20,'https://http2.mlstatic.com/D_NQ_NP_2X_637178-MLA91746641392_092025-F.webp',3),(289,21,'https://http2.mlstatic.com/D_NQ_NP_2X_661885-MLA95355557975_102025-F.webp',1),(290,21,'https://http2.mlstatic.com/D_NQ_NP_2X_796141-MLA79138739857_092024-F.webp',2),(291,21,'https://http2.mlstatic.com/D_NQ_NP_2X_798340-MLA78900900200_092024-F.webp',3),(292,22,'https://http2.mlstatic.com/D_NQ_NP_2X_895694-MLA95358877873_102025-F.webp',1),(293,22,'https://http2.mlstatic.com/D_NQ_NP_2X_884811-MLU75706725031_042024-F.webp',2),(294,22,'https://http2.mlstatic.com/D_NQ_NP_2X_729510-MLU77716644757_072024-F.webp',3),(295,23,'https://http2.mlstatic.com/D_NQ_NP_2X_881475-MLB90274322503_082025-F-smartphone-motorola-moto-g85-5g-dual-sim-256gb-8gb-ram-nf.webp',1),(296,23,'https://http2.mlstatic.com/D_NQ_NP_2X_675053-MLB85582705290_062025-F-smartphone-motorola-moto-g85-5g-dual-sim-256gb-8gb-ram-nf.webp',2),(297,23,'https://http2.mlstatic.com/D_NQ_NP_2X_600627-MLB85582754940_062025-F-smartphone-motorola-moto-g85-5g-dual-sim-256gb-8gb-ram-nf.webp',3),(298,24,'https://http2.mlstatic.com/D_NQ_NP_2X_713455-MLB77490150782_072024-F.webp',1),(299,24,'https://http2.mlstatic.com/D_NQ_NP_2X_722779-MLB77490150778_072024-F.webp',2),(300,24,'https://http2.mlstatic.com/D_NQ_NP_2X_728956-MLB77490150780_072024-F.webp',3),(301,25,'https://http2.mlstatic.com/D_NQ_NP_2X_937861-MLA94808404574_102025-F.webp',1),(302,25,'https://http2.mlstatic.com/D_NQ_NP_2X_743798-MLU74054785696_012024-F.webp',2),(303,25,'https://http2.mlstatic.com/D_NQ_NP_2X_722279-MLU74862708711_032024-F.webp',3),(304,26,'https://http2.mlstatic.com/D_NQ_NP_2X_802500-MLA94918102106_102025-F.webp',1),(305,26,'https://http2.mlstatic.com/D_NQ_NP_2X_658536-MLA82937550334_032025-F.webp',2),(306,26,'https://http2.mlstatic.com/D_NQ_NP_2X_659045-MLA82937540332_032025-F.webp',3),(307,27,'https://http2.mlstatic.com/D_NQ_NP_2X_784235-MLB84100770047_042025-F-telefone-x7-pro-51212-gb-novo.webp',1),(308,27,'https://http2.mlstatic.com/D_NQ_NP_2X_637183-MLB84100770233_042025-F-telefone-x7-pro-51212-gb-novo.webp',2),(309,27,'https://http2.mlstatic.com/D_NQ_NP_2X_637183-MLB84100770233_042025-F-telefone-x7-pro-51212-gb-novo.webp',3),(310,28,'https://bludiode.com/39601-home_default/xiaomi-redmi-note-12-pro-plus-12256-gb-azul.jpg',1),(311,28,'https://bludiode.com/39602-home_default/xiaomi-redmi-note-12-pro-plus-12256-gb-azul.jpg',2),(312,28,'https://bludiode.com/39603-home_default/xiaomi-redmi-note-12-pro-plus-12256-gb-azul.jpg',3),(313,29,'https://www.giztop.com/media/catalog/product/cache/9f42716fd2ba91f80e52d9d3665ec9f7/o/p/oppo_a3-silver.png',1),(314,29,'https://www.giztop.com/media/catalog/product/cache/9f42716fd2ba91f80e52d9d3665ec9f7/o/p/oppo_a3-silver1.png',2),(315,29,'https://assorted.downloads.oppo.com/static/assets/images/products/a3/sec7-d16d0ee1a258458d4bcb6586a938f54ce8224b27.jpg',3),(316,30,'https://http2.mlstatic.com/D_NQ_NP_2X_847855-MLA74033176505_012024-F.webp',1),(317,30,'https://http2.mlstatic.com/D_NQ_NP_2X_900491-MLA74033176501_012024-F.webp',2),(318,30,'https://http2.mlstatic.com/D_NQ_NP_2X_696196-MLA74033176509_012024-F.webp',3),(319,31,'https://img.myipadbox.com/upload/store/product_l/EDA006113701B.jpg',1),(320,31,'https://img.myipadbox.com/upload/store/detail_l/EDA006113701B_1.jpg',2),(321,31,'https://img.myipadbox.com/upload/store/detail_l/EDA006113701B_5.jpg',3),(322,32,'https://m.media-amazon.com/images/I/51rPU0jDc0L._AC_SX679_.jpg',1),(323,32,'https://m.media-amazon.com/images/I/51Xu16ifo2L._AC_SX679_.jpg',2),(324,32,'https://m.media-amazon.com/images/I/51qtq6gHQnL._AC_SX679_.jpg',3),(325,33,'https://m.media-amazon.com/images/I/61t-8i2QAoL._AC_SY300_SX300_QL70_ML2_.jpg',1),(326,33,'https://m.media-amazon.com/images/I/71f1-OaaM5L._AC_SX679_.jpg',2),(327,33,'https://m.media-amazon.com/images/I/51ODZr+6AGL._AC_SL1500_.jpg',3),(328,34,'https://m.media-amazon.com/images/I/51WguaffFYL._AC_SX679_.jpg',1),(329,34,'https://m.media-amazon.com/images/I/61UKGa0zU4L._AC_SX679_.jpg',2),(330,34,'https://m.media-amazon.com/images/I/41S1mZ3V7TL._AC_SL1000_.jpg',3),(331,35,'https://http2.mlstatic.com/D_NQ_NP_2X_903372-MLA95370288523_102025-F.webp',1),(332,35,'https://http2.mlstatic.com/D_NQ_NP_2X_701098-MLA82793639472_032025-F.webp',2),(333,35,'https://http2.mlstatic.com/D_NQ_NP_2X_980585-MLA82793678152_032025-F.webp',3),(334,36,'https://m.media-amazon.com/images/I/51emAHX5QrL._AC_SY300_SX300_QL70_ML2_.jpg',1),(335,36,'https://m.media-amazon.com/images/I/514EtCTmCvL._AC_SX679_.jpg',2),(336,36,'https://m.media-amazon.com/images/I/71oZEEMaoxL._AC_SX679_.jpg',3),(337,37,'https://m.magazineluiza.com.br/a-static/420x420/smartphone-celular-xiaomi-redmi-note-14-lancamento-256gb-8gb-camera-108mp-20mp-tela-6-67-amoled-120hz-fhd-plus-design-premium-dual-sim-chip/martinpresentes/redmi-note14-256-8-preto/39b537db21bceae29c86f7491656a867.jpeg',1),(338,37,'https://a-static.mlcdn.com.br/420x420/smartphone-celular-xiaomi-redmi-note-14-lancamento-256gb-8gb-camera-108mp-20mp-tela-6-67-amoled-120hz-fhd-plus-design-premium-dual-sim-chip/martinpresentes/redmi-note14-256-8-preto/60954bb82004b8baf219239d8344b2cc.jpeg',2),(339,37,'https://a-static.mlcdn.com.br/420x420/smartphone-celular-xiaomi-redmi-note-14-lancamento-256gb-8gb-camera-108mp-20mp-tela-6-67-amoled-120hz-fhd-plus-design-premium-dual-sim-chip/martinpresentes/redmi-note14-256-8-preto/b6f6da367ce9d0271e853586fad85cd9.jpeg',3),(340,38,'https://http2.mlstatic.com/D_Q_NP_2X_920498-MLA94919936786_102025-R.webp',1),(341,38,'https://http2.mlstatic.com/D_Q_NP_2X_686036-MLA74676491014_022024-R.webp',2),(342,38,'https://http2.mlstatic.com/D_Q_NP_2X_849626-MLA91556837384_092025-R.webp',3),(343,39,'https://http2.mlstatic.com/D_NQ_NP_2X_767864-MLB94538151585_102025-F.webp',1),(344,39,'https://http2.mlstatic.com/D_Q_NP_2X_618808-MLB94537332633_102025-R.webp',2),(345,39,'https://http2.mlstatic.com/D_Q_NP_2X_607817-MLB94110429934_102025-R.webp',3),(346,40,'https://m.magazineluiza.com.br/a-static/420x420/smartphone-oppo-reno13-512gb-branco-5g-12gb-ram-667-cam-tripla-selfie-50mp/magazineluiza/240063300/c4dc5617ff96cba1c99a29a1f8cf8ff9.jpg',1),(347,40,'https://a-static.mlcdn.com.br/420x420/smartphone-oppo-reno13-512gb-branco-5g-12gb-ram-667-cam-tripla-selfie-50mp/magazineluiza/240063300/e30c2bea5be168adbadc5bc1bb7dd846.jpg',2),(348,40,'https://a-static.mlcdn.com.br/420x420/smartphone-oppo-reno13-512gb-branco-5g-12gb-ram-667-cam-tripla-selfie-50mp/magazineluiza/240063300/0d5a46361cae651b7e2f84b20a22801e.jpg',3),(349,41,'https://i.zst.com.br/thumbs/1/32/16/-1239415638.jpg',1),(350,41,'https://i.zst.com.br/thumbs/1/32/16/-1239415639.jpg',2),(351,41,'https://i.zst.com.br/thumbs/1/17/16/-1239415640.jpg',3),(352,42,'https://http2.mlstatic.com/D_Q_NP_2X_718430-MLA96100188113_102025-R.webp',1),(353,42,'https://http2.mlstatic.com/D_NQ_NP_2X_974609-MLA80561031120_112024-F.webp',2),(354,42,'https://http2.mlstatic.com/D_NQ_NP_2X_872750-MLA80822964493_112024-F.webp',3),(355,43,'https://http2.mlstatic.com/D_NQ_NP_2X_635781-MLA96111133151_102025-F.webp',1),(356,43,'https://http2.mlstatic.com/D_NQ_NP_2X_915547-MLA88988381379_072025-F.webp',2),(357,43,'https://http2.mlstatic.com/D_NQ_NP_2X_921613-MLA88637512340_072025-F.webp',3),(358,44,'https://http2.mlstatic.com/D_NQ_NP_2X_626553-MLB95721464881_102025-F.webp',1),(359,44,'https://http2.mlstatic.com/D_NQ_NP_2X_754609-MLB95283188074_102025-F.webp',2),(360,44,'https://http2.mlstatic.com/D_NQ_NP_2X_752839-MLB95283188084_102025-F.webp',3),(361,45,'https://http2.mlstatic.com/D_NQ_NP_2X_759201-MLB88150470452_072025-F.webp',1),(362,45,'https://http2.mlstatic.com/D_NQ_NP_2X_737438-MLB88150470456_072025-F.webp',2),(363,45,'https://http2.mlstatic.com/D_NQ_NP_2X_961775-MLB88150470458_072025-F.webp',3),(364,46,'https://d1j48ryyrcdvj8.cloudfront.net/Custom/Content/Products/10/11/101193_smart-tv-75-tcl-4k-75p7k-preta_z8_638884488314272792.webp',1),(365,46,'https://d1j48ryyrcdvj8.cloudfront.net/Custom/Content/Products/10/11/101193_smart-tv-75-tcl-4k-75p7k-preta_z5_638884488223045157.webp',2),(366,46,'https://d1j48ryyrcdvj8.cloudfront.net/Custom/Content/Products/10/11/101193_smart-tv-75-tcl-4k-75p7k-preta_z7_638884488280550929.webp',3),(367,47,'https://http2.mlstatic.com/D_NQ_NP_2X_832248-MLA95658914192_102025-F.webp',1),(368,47,'https://http2.mlstatic.com/D_NQ_NP_2X_740850-MLA89992472546_082025-F.webp',2),(369,47,'https://http2.mlstatic.com/D_NQ_NP_2X_657526-MLA90372433405_082025-F.webp',3),(370,48,'https://http2.mlstatic.com/D_NQ_NP_2X_705243-MLA95708566274_102025-F.webp',1),(371,48,'https://http2.mlstatic.com/D_NQ_NP_2X_630301-MLA88405622375_072025-F.webp',2),(372,48,'https://http2.mlstatic.com/D_NQ_NP_2X_704195-MLA88406136455_072025-F.webp',3),(373,49,'https://http2.mlstatic.com/D_NQ_NP_2X_751637-MLA96126701631_102025-F.webp',1),(374,49,'https://http2.mlstatic.com/D_NQ_NP_2X_902701-MLA84561785470_052025-F.webp',2),(375,49,'https://http2.mlstatic.com/D_NQ_NP_2X_910837-MLA87312099786_072025-F.webp',3),(376,50,'https://http2.mlstatic.com/D_NQ_NP_2X_654921-MLA96148778515_102025-F.webp',1),(377,50,'https://http2.mlstatic.com/D_NQ_NP_2X_628417-MLA89579456078_082025-F.webp',2),(378,50,'https://http2.mlstatic.com/D_NQ_NP_2X_672645-MLA89579604902_082025-F.webp',3),(379,51,'https://http2.mlstatic.com/D_NQ_NP_2X_636075-MLA96137632625_102025-F.webp',1),(380,51,'https://http2.mlstatic.com/D_NQ_NP_2X_958367-MLA89879706401_082025-F.webp',2),(381,51,'https://http2.mlstatic.com/D_NQ_NP_2X_619550-MLA89879617935_082025-F.webp',3),(382,52,'https://http2.mlstatic.com/D_NQ_NP_2X_689696-MLA95496996602_102025-F.webp',1),(383,52,'https://http2.mlstatic.com/D_NQ_NP_2X_653820-MLA95496631864_102025-F.webp',2),(384,52,'https://http2.mlstatic.com/D_NQ_NP_2X_718403-MLA95936239459_102025-F.webp',3),(385,53,'https://http2.mlstatic.com/D_NQ_NP_2X_937415-MLA96121810583_102025-F.webp',1),(386,53,'https://http2.mlstatic.com/D_NQ_NP_2X_810892-MLA82151472092_022025-F.webp',2),(387,53,'https://http2.mlstatic.com/D_NQ_NP_2X_914494-MLA82434447929_022025-F.webp',3),(388,54,'https://http2.mlstatic.com/D_NQ_NP_2X_630721-MLA96120280907_102025-F.webp',1),(389,54,'https://http2.mlstatic.com/D_NQ_NP_2X_939390-MLA92539284237_092025-F.webp',2),(390,54,'https://http2.mlstatic.com/D_NQ_NP_2X_856741-MLA92539830737_092025-F.webp',3),(391,55,'https://http2.mlstatic.com/D_NQ_NP_2X_867441-MLA95657756144_102025-F.webp',1),(392,55,'https://http2.mlstatic.com/D_NQ_NP_2X_818113-MLA88408597573_072025-F.webp',2),(393,55,'https://http2.mlstatic.com/D_NQ_NP_2X_634548-MLA88408478069_072025-F.webp',3),(394,56,'https://http2.mlstatic.com/D_NQ_NP_2X_733608-MLA95700436565_102025-F.webp',1),(395,56,'https://http2.mlstatic.com/D_NQ_NP_2X_659744-MLA88319940729_072025-F.webp',2),(396,56,'https://http2.mlstatic.com/D_NQ_NP_2X_638286-MLA88319940767_072025-F.webp',3),(397,57,'https://http2.mlstatic.com/D_NQ_NP_2X_775869-MLU77161647808_072024-F.webp',1),(398,57,'https://http2.mlstatic.com/D_NQ_NP_2X_939855-MLU77161647842_072024-F.webp',2),(399,57,'https://http2.mlstatic.com/D_NQ_NP_2X_991817-MLU77161736438_072024-F.webp',3),(400,58,'https://m.media-amazon.com/images/I/71pme7j9JLL._AC_SX679_.jpg',1),(401,58,'https://m.media-amazon.com/images/I/51GBknqniUL._AC_SL1000_.jpg',2),(402,58,'https://m.media-amazon.com/images/I/51rLyCJiz7L._AC_SL1000_.jpg',3),(403,59,'https://http2.mlstatic.com/D_NQ_NP_2X_657738-MLU77111826448_062024-F.webp',1),(404,59,'https://http2.mlstatic.com/D_NQ_NP_2X_723455-MLU77111826474_062024-F.webp',2),(405,59,'https://http2.mlstatic.com/D_NQ_NP_2X_933503-MLU77111719726_062024-F.webp',3),(406,60,'https://http2.mlstatic.com/D_NQ_NP_2X_942361-MLU77133194816_062024-F.webp',1),(407,60,'https://http2.mlstatic.com/D_NQ_NP_2X_615546-MLU77345898843_062024-F.webp',2),(408,60,'https://http2.mlstatic.com/D_NQ_NP_2X_650510-MLU77133194832_062024-F.webp',3),(409,61,'https://http2.mlstatic.com/D_NQ_NP_2X_982694-MLU77345873909_062024-F.webp',1),(410,61,'https://http2.mlstatic.com/D_Q_NP_2X_677220-MLU77346207585_062024-R.webp',2),(411,61,'https://http2.mlstatic.com/D_NQ_NP_2X_954040-MLU77133199612_062024-F.webp',3),(412,62,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/e/1/e1504fa-nj825w8.jpg',1),(413,62,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/e/1/e1504fa-nj825w6.jpg',2),(414,62,'https://media.pichau.com.br/media/catalog/product/cache/2f958555330323e505eba7ce930bdf27/e/1/e1504fa-nj825w.jpg',3),(415,63,'https://m.media-amazon.com/images/I/81IgxPCv6OL._AC_SX679_.jpg',1),(416,63,'https://m.media-amazon.com/images/I/714XBwRahKL._AC_SL1500_.jpg',2),(417,63,'https://m.media-amazon.com/images/I/71OBjG8Kp9L._AC_SL1500_.jpg',3),(418,64,'https://m.media-amazon.com/images/I/516ji5m10ML._AC_SX679_.jpg',1),(419,64,'https://m.media-amazon.com/images/I/51OfL6oV5lL._AC_SX679_.jpg',2),(420,64,'https://m.media-amazon.com/images/I/411HD8elX9L._AC_SX679_.jpg',3),(421,65,'https://m.media-amazon.com/images/I/51O4bS147tL._AC_SX679_.jpg',1),(422,65,'https://m.media-amazon.com/images/I/514yoko6meL._AC_SX679_.jpg',2),(423,65,'https://m.media-amazon.com/images/I/51Q5gEvIL7L._AC_SX679_.jpg',3),(424,66,'https://m.media-amazon.com/images/I/71iHE1YN73L._AC_SX679_.jpg',1),(425,66,'https://m.media-amazon.com/images/I/71YNlMG0u4L._AC_SX679_.jpg',2),(426,66,'https://m.media-amazon.com/images/I/61PwpyS8a9L._AC_SX679_.jpg',3),(427,67,'https://m.media-amazon.com/images/I/61916dNVFYL._AC_SX679_.jpg',1),(428,67,'https://m.media-amazon.com/images/I/51r1VEKV7wL._AC_SX679_.jpg',2),(429,67,'https://m.media-amazon.com/images/I/51O1YVpDy+L._AC_SX679_.jpg',3),(430,68,'https://http2.mlstatic.com/D_NQ_NP_2X_776296-MLB90956833527_082025-F.webp',1),(431,68,'https://http2.mlstatic.com/D_NQ_NP_2X_917196-MLA82482997725_022025-F.webp',2),(432,68,'https://http2.mlstatic.com/D_NQ_NP_2X_654624-MLA82482952431_022025-F.webp',3),(433,69,'https://http2.mlstatic.com/D_NQ_NP_2X_976269-MLU77356741265_062024-F.webp',1),(434,69,'https://http2.mlstatic.com/D_NQ_NP_2X_825549-MLA44986030133_022021-F.webp',2),(435,69,'https://http2.mlstatic.com/D_NQ_NP_2X_706843-MLU77143780748_062024-F.webp',3),(436,70,'https://http2.mlstatic.com/D_NQ_NP_2X_879552-MLB51017569830_082022-F.webp',1),(437,70,'https://http2.mlstatic.com/D_NQ_NP_2X_643785-MLB51017569831_082022-F.webp',2),(438,70,'https://http2.mlstatic.com/D_NQ_NP_2X_973520-MLB51017569829_082022-F.webp',3),(439,71,'https://http2.mlstatic.com/D_NQ_NP_2X_843368-MLU77445266509_072024-F.webp',1),(440,71,'https://http2.mlstatic.com/D_NQ_NP_2X_766915-MLU77445276023_072024-F.webp',2),(441,71,'https://http2.mlstatic.com/D_NQ_NP_2X_993347-MLU77445266521_072024-F.webp',3),(442,72,'https://http2.mlstatic.com/D_NQ_NP_2X_757934-MLB47969615916_102021-F.webp',1),(443,72,'https://http2.mlstatic.com/D_NQ_NP_2X_703846-MLB47969615915_102021-F.webp',2),(444,72,'https://http2.mlstatic.com/D_NQ_NP_2X_613582-MLB47969615913_102021-F.webp',3),(445,73,'https://http2.mlstatic.com/D_NQ_NP_2X_994951-MLA95513993751_102025-F.webp',1),(446,73,'https://http2.mlstatic.com/D_NQ_NP_2X_940244-MLU77116634384_062024-F.webp',2),(447,73,'https://http2.mlstatic.com/D_NQ_NP_2X_841341-MLU77116711030_062024-F.webp',3),(448,74,'https://http2.mlstatic.com/D_NQ_NP_2X_843972-MLB96412674553_102025-F.webp',1),(449,74,'https://http2.mlstatic.com/D_NQ_NP_2X_685940-MLB96395506283_102025-F.webp',2),(450,74,'https://http2.mlstatic.com/D_NQ_NP_2X_804383-MLB96395506273_102025-F.webp',3),(451,75,'https://http2.mlstatic.com/D_NQ_NP_2X_860904-MLA95693174756_102025-F.webp',1),(452,75,'https://http2.mlstatic.com/D_NQ_NP_2X_981312-MLA82670084890_032025-F.webp',2),(453,75,'https://http2.mlstatic.com/D_NQ_NP_2X_943165-MLA82670084900_032025-F.webp',3),(454,76,'https://http2.mlstatic.com/D_NQ_NP_2X_845218-MLU77312573251_062024-F.webp',1),(455,76,'https://http2.mlstatic.com/D_NQ_NP_2X_720960-MLU77312414493_062024-F.webp',2),(456,76,'https://http2.mlstatic.com/D_NQ_NP_2X_832436-MLU77312453957_062024-F.webp',3),(457,77,'https://http2.mlstatic.com/D_NQ_NP_2X_887648-MLU77385953225_072024-F.webp',1),(458,77,'https://http2.mlstatic.com/D_NQ_NP_2X_746740-MLU77386187349_072024-F.webp',2),(459,77,'https://http2.mlstatic.com/D_NQ_NP_2X_804811-MLU76634609109_052024-F.webp',3),(460,78,'https://http2.mlstatic.com/D_NQ_NP_2X_869334-MLC50581724425_072022-F.webp',1),(461,78,'https://http2.mlstatic.com/D_NQ_NP_2X_740552-MLC50581756194_072022-F.webp',2),(462,78,'https://http2.mlstatic.com/D_NQ_NP_2X_779012-MLC46890925772_072021-F.webp',3),(463,79,'https://http2.mlstatic.com/D_NQ_NP_2X_937447-MLA50709053984_072022-F.webp',1),(464,79,'https://http2.mlstatic.com/D_NQ_NP_2X_795923-MLA50709092661_072022-F.webp',2),(465,79,'https://http2.mlstatic.com/D_NQ_NP_2X_726695-MLA50709100627_072022-F.webp',3),(466,80,'https://http2.mlstatic.com/D_NQ_NP_2X_851253-MLU73368335036_122023-F.webp',1),(467,80,'https://http2.mlstatic.com/D_NQ_NP_2X_875803-MLU77321085639_062024-F.webp',2),(468,80,'https://http2.mlstatic.com/D_NQ_NP_2X_929411-MLU77320978233_062024-F.webp',3),(469,81,'https://http2.mlstatic.com/D_NQ_NP_2X_671549-MLU72253812956_102023-F.webp',1),(470,81,'https://http2.mlstatic.com/D_NQ_NP_2X_788872-MLU72253967622_102023-F.webp',2),(471,81,'https://http2.mlstatic.com/D_NQ_NP_2X_886002-MLU72317805891_102023-F.webp',3);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loja`
--

LOCK TABLES `loja` WRITE;
/*!40000 ALTER TABLE `loja` DISABLE KEYS */;
INSERT INTO `loja` VALUES (1,1,1,'MR.Tech','Especializada em dispositivos móveis','12.345.678/0001-95');
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (1,'Acer'),(2,'Asus'),(3,'Dell'),(4,'Lenovo'),(5,'HP'),(6,'Apple'),(7,'Motorola'),(8,'Oppo'),(9,'Samsung'),(10,'Xiaomi'),(11,'AOC'),(12,'LG'),(13,'Philco'),(14,'Sony'),(15,'AMD'),(16,'Intel');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (1,1,1,NULL,'2025-10-27 03:00:00','pendente',3000.00,412.00,2588.00);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_status`
--

LOCK TABLES `pedido_status` WRITE;
/*!40000 ALTER TABLE `pedido_status` DISABLE KEYS */;
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
  `cor` varchar(40) DEFAULT NULL,
  `preco` decimal(8,2) NOT NULL,
  `id_info` bigint(20) unsigned NOT NULL,
  `data_att` datetime NOT NULL,
  `id_loja` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `produto_id_info_foreign` (`id_info`),
  CONSTRAINT `produto_id_info_foreign` FOREIGN KEY (`id_info`) REFERENCES `produto_info` (`id_info`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto`
--

LOCK TABLES `produto` WRITE;
/*!40000 ALTER TABLE `produto` DISABLE KEYS */;
INSERT INTO `produto` VALUES (1,'Notebook Acer','Preto',4700.00,7,'2025-10-11 11:37:15',0),(3,'Notebook Acer Swift 3','Prata',4800.00,8,'2025-10-11 13:16:00',0),(4,'Notebook Acer Predator Helios 300','Preto',10500.00,9,'2025-10-11 13:16:00',0),(5,'Notebook Acer Aspire 5','Cinza',4500.00,10,'2025-10-11 13:16:00',0),(6,'Notebook Acer Nitro V 15','Preto',6700.00,11,'2025-10-11 13:16:00',0),(7,'Computador Gamer Xtreme','Preto',12500.00,12,'2025-10-11 13:18:39',0),(8,'Computador Office Plus','Cinza',4800.00,13,'2025-10-11 13:18:39',0),(9,'Computador Workstation Pro','Preto',35000.00,14,'2025-10-11 13:18:39',0),(10,'Computador Gamer Storm','Preto',22000.00,15,'2025-10-11 13:18:39',0),(11,'Computador Home Basic','Branco',3500.00,16,'2025-10-11 13:18:39',0),(12,'iPhone 17 Pro','Prata',12500.00,17,'2025-10-11 13:21:29',0),(13,'iPhone 17 Pro Max','Laranja',13500.00,18,'2025-10-11 13:21:29',0),(14,'iPhone 17 Plus','Salvia',11000.00,19,'2025-10-11 13:21:29',0),(15,'iPhone 17 ','Branco',9500.00,20,'2025-10-11 13:21:29',0),(16,'iPhone 17 SE','Branco',7200.00,21,'2025-10-11 13:21:29',0),(17,'Motorola Edge 50 Pro','Preto',6200.00,22,'2025-10-11 13:21:29',0),(18,'Motorola Moto G85','Azul',1800.00,23,'2025-10-11 13:21:29',0),(19,'Motorola Edge 40 Neo','Verde',2800.00,24,'2025-10-11 13:21:29',0),(20,'Motorola Moto G73','Azul',1600.00,25,'2025-10-11 13:21:29',0),(21,'Motorola Moto E14','Preto',850.00,26,'2025-10-11 13:21:29',0),(22,'Oppo Find X7 Pro','Preto',7200.00,27,'2025-10-11 13:21:29',0),(23,'Oppo Reno 12 Pro','Azul',5100.00,28,'2025-10-11 13:21:29',0),(24,'Oppo A3 Pro','Prata',3200.00,29,'2025-10-11 13:21:29',0),(25,'Oppo Find N3 Flip','Preto',6100.00,30,'2025-10-11 13:21:29',0),(26,'Oppo A79 5G','Rosa',2900.00,31,'2025-10-11 13:21:29',0),(27,'Samsung Galaxy S24 Ultra','Preto',10200.00,32,'2025-10-11 13:21:29',0),(28,'Samsung Galaxy S23 FE','Azul',4500.00,33,'2025-10-11 13:21:29',0),(29,'Samsung Galaxy A55 5G','Prata',3900.00,34,'2025-10-11 13:21:29',0),(30,'Samsung Galaxy M55','Preto',3500.00,35,'2025-10-11 13:21:29',0),(31,'Samsung Galaxy Z Flip 5','Dourado',5200.00,36,'2025-10-11 13:21:29',0),(32,'Xiaomi 14 Ultra','Preto',9800.00,37,'2025-10-11 13:21:29',0),(33,'Xiaomi Redmi Note 13 Pro+','Azul',4600.00,38,'2025-10-11 13:21:29',0),(34,'Xiaomi Poco F6 Pro','Preto',4800.00,39,'2025-10-11 13:21:29',0),(35,'Xiaomi Redmi 13C','Cinza',1700.00,40,'2025-10-11 13:21:29',0),(36,'Xiaomi Mi 13T','Prata',5200.00,41,'2025-10-11 13:21:29',0),(37,'SmartTV AOC 43\" 4K','Preto',2200.00,42,'2025-10-11 13:23:07',0),(38,'SmartTV AOC 50\" UHD','Preto',2700.00,43,'2025-10-11 13:23:07',0),(39,'SmartTV AOC 55\" QLED','Preto',3200.00,44,'2025-10-11 13:23:07',0),(40,'SmartTV AOC 65\" 4K UHD','Preto',4500.00,45,'2025-10-11 13:23:07',0),(41,'SmartTV AOC 75\" 4K','Preto',5500.00,46,'2025-10-11 13:23:07',0),(42,'SmartTV LG 43\" OLED','Preto',3100.00,47,'2025-10-11 13:23:07',0),(43,'SmartTV LG 50\" 4K UHD','Preto',3600.00,48,'2025-10-11 13:23:07',0),(44,'SmartTV LG 55\" OLED evo','Preto',4200.00,49,'2025-10-11 13:23:07',0),(45,'SmartTV LG 65\" 4K NanoCell','Preto',4800.00,50,'2025-10-11 13:23:07',0),(46,'SmartTV LG 75\" 4K UHD','Preto',6000.00,51,'2025-10-11 13:23:07',0),(47,'SmartTV Philco 32\" HD','Preto',1200.00,52,'2025-10-11 13:23:07',0),(48,'SmartTV Philco 43\" 4K','Preto',2500.00,53,'2025-10-11 13:23:07',0),(49,'SmartTV Philco 50\" 4K UHD','Preto',2800.00,54,'2025-10-11 13:23:07',0),(50,'SmartTV Philco 55\" 4K','Preto',3200.00,55,'2025-10-11 13:23:07',0),(51,'SmartTV Philco 65\" 4K UHD','Preto',3800.00,56,'2025-10-11 13:23:07',0),(52,'SmartTV Sony Bravia 43\" 4K','Preto',3300.00,57,'2025-10-11 13:23:07',0),(53,'SmartTV Sony Bravia 50\" 4K','Preto',3700.00,58,'2025-10-11 13:23:07',0),(54,'SmartTV Sony Bravia 55\" OLED','Preto',4200.00,59,'2025-10-11 13:23:07',0),(55,'SmartTV Sony Bravia 65\" 4K','Preto',4800.00,60,'2025-10-11 13:23:07',0),(56,'SmartTV Sony Bravia 75\" 4K','Preto',5800.00,61,'2025-10-11 13:23:07',0),(57,'Notebook Asus Vivobook 15','Preto',3500.00,62,'2025-10-13 10:10:24',0),(58,'Notebook Asus TUF Gaming F15','Cinza',6200.00,63,'2025-10-13 10:10:24',0),(59,'Notebook Asus Zenbook 14','Prata',5800.00,64,'2025-10-13 10:10:24',0),(60,'Notebook Asus ROG Strix G16','Preto',8900.00,65,'2025-10-13 10:10:24',0),(61,'Notebook Asus ExpertBook B1','Azul',5100.00,66,'2025-10-13 10:10:24',0),(62,'Notebook Dell Inspiron 15 3000','Prata',3400.00,67,'2025-10-13 10:10:24',0),(63,'Notebook Dell G15 Gaming','Preto',6500.00,68,'2025-10-13 10:10:24',0),(64,'Notebook Dell XPS 13','Prata',7200.00,69,'2025-10-13 10:10:24',0),(65,'Notebook Dell Latitude 5440','Preto',5600.00,70,'2025-10-13 10:10:24',0),(66,'Notebook Dell Alienware M18','Cinza',12500.00,71,'2025-10-13 10:10:24',0),(67,'Notebook Lenovo IdeaPad 3','Prata',3300.00,72,'2025-10-13 10:10:24',0),(68,'Notebook Lenovo ThinkPad E14 Gen 5','Preto',5400.00,73,'2025-10-13 10:10:24',0),(69,'Notebook Lenovo Legion 5','Cinza',6900.00,74,'2025-10-13 10:10:24',0),(70,'Notebook Lenovo Yoga Slim 7','Roxo',5800.00,75,'2025-10-13 10:10:24',0),(71,'Notebook Lenovo LOQ 15','Preto',7600.00,76,'2025-10-13 10:10:24',0),(72,'Notebook HP 250 G9','Prata',3400.00,77,'2025-10-13 10:10:24',0),(73,'Notebook HP Pavilion 15','Preto',5200.00,78,'2025-10-13 10:10:24',0),(74,'Notebook HP Victus 16','Azul',6100.00,79,'2025-10-13 10:10:24',0),(75,'Notebook HP Envy x360','Prata',6300.00,80,'2025-10-13 10:10:24',0),(76,'Notebook HP Omen 16','Preto',8700.00,81,'2025-10-13 10:10:24',0);
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
  PRIMARY KEY (`id_info`),
  KEY `produto_marca` (`id_marca`),
  KEY `info_categoria` (`id_categoria`),
  CONSTRAINT `info_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `produto_info` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produto_info`
--

LOCK TABLES `produto_info` WRITE;
/*!40000 ALTER TABLE `produto_info` DISABLE KEYS */;
INSERT INTO `produto_info` VALUES (7,'Notebook Acer Aspire 7',1,2,'16GB','512GB SSD','Ryzen 5 5500U','NB-A715','GTX 1650 4GB','135 WATTS'),(8,'Notebook Acer Swift 3',1,2,'8GB','512GB SSD','Intel i7 1255U','SF314-43','Iris Xe','65 WATTS'),(9,'Notebook Acer Predator Helios 300',1,2,'32GB','1TB SSD','Intel i7 13700H','PH315-55','RTX 4060 8GB','230 WATTS'),(10,'Notebook Acer Aspire 5',1,2,'12GB','256GB SSD','Intel i5 1235U','A515-57','Iris Xe','90 WATTS'),(11,'Notebook Acer Nitro V 15',1,2,'16GB','1TB SSD','Intel i7 13620H','NV-515','RTX 4050 6GB','180 WATTS'),(12,'Computador Gamer Xtreme',16,1,'16GB','1TB SSD','I7 12700K','B660M','RTX 4070 12GB','750 WATTS'),(13,'Computador Office Plus',16,1,'8GB','480GB SSD','I5 12400','H610M','Intel UHD 730','500 WATTS'),(14,'Computador Workstation Pro',16,1,'32GB','2TB SSD','I9 13900K','Z790','RTX 4090 24GB','1000 WATTS'),(15,'Computador Gamer Storm',15,1,'16GB','1TB SSD','Ryzen 7 7800X3D','B650','RTX 4080 16GB','850 WATTS'),(16,'Computador Home Basic',16,1,'8GB','256GB SSD','I3 12100','H610M','Intel UHD 730','450 WATTS'),(17,'iPhone 17 Pro',6,4,'16GB','512GB','A17 PRO','Apple Logic Board','Apple GPU 6-core','20 WATTS'),(18,'iPhone 17 Pro Max',6,4,'16GB','1TB','A17 PRO','Apple Logic Board','Apple GPU 6-core','25 WATTS'),(19,'iPhone 17 Plus',6,4,'12GB','512GB','A17 PRO','Apple Logic Board','Apple GPU 6-core','20 WATTS'),(20,'iPhone 17 Mini',6,4,'8GB','256GB','A17 PRO','Apple Logic Board','Apple GPU 6-core','18 WATTS'),(21,'iPhone 17 SE',6,4,'8GB','128GB','A16 Bionic','Apple Logic Board','Apple GPU 5-core','18 WATTS'),(22,'Motorola Edge 50 Pro',7,4,'12GB','512GB','Snapdragon 8 Gen 3','Moto Edge Board','Adreno 750','68 WATTS'),(23,'Motorola Moto G85',7,4,'8GB','256GB','Snapdragon 6 Gen 1','Moto G Board','Adreno 710','33 WATTS'),(24,'Motorola Edge 40 Neo',7,4,'8GB','256GB','Dimensity 7030','Edge Neo Board','Mali-G610','30 WATTS'),(25,'Motorola Moto G73',7,4,'8GB','256GB','Dimensity 930','G73 Board','IMG BXM-8-256','30 WATTS'),(26,'Motorola Moto E14',7,4,'4GB','64GB','Unisoc T606','E14 Board','Mali-G57','15 WATTS'),(27,'Oppo Find X7 Pro',8,4,'16GB','512GB','Snapdragon 8 Gen 3','Oppo X7 Board','Adreno 750','80 WATTS'),(28,'Oppo Reno 12 Pro',8,4,'12GB','256GB','Dimensity 9200+','Reno 12 Board','Mali-G715','65 WATTS'),(29,'Oppo A3 Pro',8,4,'8GB','256GB','Dimensity 7050','A3 Board','Mali-G68','33 WATTS'),(30,'Oppo Find N3 Flip',8,4,'12GB','512GB','Dimensity 9200','Find N3 Board','Mali-G715 Immortalis','44 WATTS'),(31,'Oppo A79 5G',8,4,'8GB','128GB','Dimensity 6020','A79 Board','Mali-G57','33 WATTS'),(32,'Samsung Galaxy S24 Ultra',9,4,'12GB','1TB','Snapdragon 8 Gen 3','SM-S928B','Adreno 750','45 WATTS'),(33,'Samsung Galaxy S23 FE',9,4,'8GB','256GB','Exynos 2200','SM-S711B','Xclipse 920','25 WATTS'),(34,'Samsung Galaxy A55 5G',9,4,'8GB','256GB','Exynos 1480','SM-A556B','Xclipse 530','25 WATTS'),(35,'Samsung Galaxy M55',9,4,'8GB','256GB','Snapdragon 7 Gen 1','SM-M556B','Adreno 644','25 WATTS'),(36,'Samsung Galaxy Z Flip 5',9,4,'8GB','512GB','Snapdragon 8 Gen 2','SM-F731B','Adreno 740','25 WATTS'),(37,'Xiaomi 14 Ultra',10,4,'16GB','1TB','Snapdragon 8 Gen 3','XM14U-Board','Adreno 750','90 WATTS'),(38,'Xiaomi Redmi Note 13 Pro+',10,4,'12GB','512GB','Dimensity 7200 Ultra','RN13P-Board','Mali-G610','67 WATTS'),(39,'Xiaomi Poco F6 Pro',10,4,'16GB','512GB','Snapdragon 8 Gen 2','POCO-F6P-Board','Adreno 740','120 WATTS'),(40,'Xiaomi Redmi 13C',10,4,'6GB','128GB','Helio G85','RM13C-Board','Mali-G52','18 WATTS'),(41,'Xiaomi Mi 13T',10,4,'12GB','256GB','Dimensity 8200 Ultra','MI13T-Board','Mali-G610','67 WATTS'),(42,'SmartTV AOC 43\" 4K',11,3,'2GB','16GB','Quantum 4K','TV Main Board','Integrated','150 WATTS'),(43,'SmartTV AOC 50\" UHD',11,3,'2GB','16GB','Quantum 4K','TV Main Board','Integrated','150 WATTS'),(44,'SmartTV AOC 55\" QLED',11,3,'3GB','32GB','Quantum 4K','TV Main Board','Integrated','160 WATTS'),(45,'SmartTV AOC 65\" 4K UHD',11,3,'3GB','32GB','Quantum 4K','TV Main Board','Integrated','170 WATTS'),(46,'SmartTV AOC 75\" 4K',11,3,'4GB','32GB','Quantum 4K','TV Main Board','Integrated','180 WATTS'),(47,'SmartTV LG 43\" OLED',12,3,'3GB','32GB','Alpha 9 Gen 6','TV Main Board','Integrated','160 WATTS'),(48,'SmartTV LG 50\" 4K UHD',12,3,'2GB','16GB','Quad Core 4K','TV Main Board','Integrated','150 WATTS'),(49,'SmartTV LG 55\" OLED evo',12,3,'4GB','32GB','Alpha 9 Gen 6','TV Main Board','Integrated','170 WATTS'),(50,'SmartTV LG 65\" 4K NanoCell',12,3,'3GB','32GB','Quad Core 4K','TV Main Board','Integrated','175 WATTS'),(51,'SmartTV LG 75\" 4K UHD',12,3,'4GB','32GB','Alpha 9 Gen 6','TV Main Board','Integrated','180 WATTS'),(52,'SmartTV Philco 32\" HD',13,3,'1GB','8GB','Quad Core HD','TV Main Board','Integrated','50 WATTS'),(53,'SmartTV Philco 43\" 4K',13,3,'2GB','16GB','Quad Core 4K','TV Main Board','Integrated','120 WATTS'),(54,'SmartTV Philco 50\" 4K UHD',13,3,'2GB','16GB','Quad Core 4K','TV Main Board','Integrated','140 WATTS'),(55,'SmartTV Philco 55\" 4K',13,3,'3GB','32GB','Quad Core 4K','TV Main Board','Integrated','150 WATTS'),(56,'SmartTV Philco 65\" 4K UHD',13,3,'3GB','32GB','Quad Core 4K','TV Main Board','Integrated','160 WATTS'),(57,'SmartTV Sony Bravia 43\" 4K',14,3,'3GB','16GB','X1 Processor','TV Main Board','Integrated','130 WATTS'),(58,'SmartTV Sony Bravia 50\" 4K',14,3,'3GB','16GB','X1 Processor','TV Main Board','Integrated','140 WATTS'),(59,'SmartTV Sony Bravia 55\" OLED',14,3,'4GB','32GB','X1 Ultimate','TV Main Board','Integrated','160 WATTS'),(60,'SmartTV Sony Bravia 65\" 4K',14,3,'4GB','32GB','X1 Ultimate','TV Main Board','Integrated','170 WATTS'),(61,'SmartTV Sony Bravia 75\" 4K',14,3,'4GB','32GB','X1 Ultimate','TV Main Board','Integrated','180 WATTS'),(62,'Notebook Asus Vivobook 15',2,2,'8GB','256GB SSD','Intel i5-1135G7','X515EA-BQ931','Intel Iris Xe','65 WATTS'),(63,'Notebook Asus TUF Gaming F15',2,2,'16GB','512GB SSD','Intel i7-12700H','FX507ZE-HN103W','RTX 3050 Ti 4GB','200 WATTS'),(64,'Notebook Asus Zenbook 14',2,2,'16GB','1TB SSD','Ryzen 7 7730U','UM3402YA','Radeon Vega 8','90 WATTS'),(65,'Notebook Asus ROG Strix G16',2,2,'32GB','1TB SSD','Intel i9-13980HX','G614JV-AS73','RTX 4070 8GB','240 WATTS'),(66,'Notebook Asus ExpertBook B1',2,2,'16GB','512GB SSD','Intel i7-1255U','B1502CBA-EJ0892','Intel Iris Xe','90 WATTS'),(67,'Notebook Dell Inspiron 15 3000',3,2,'8GB','256GB SSD','Intel i5-1135G7','i3501-M30','Intel Iris Xe','65 WATTS'),(68,'Notebook Dell G15 Gaming',3,2,'16GB','512GB SSD','Ryzen 7 6800H','G15-5525-A20P','RTX 3050 4GB','200 WATTS'),(69,'Notebook Dell XPS 13',3,2,'16GB','512GB SSD','Intel i7-1360P','XPS9315-M10S','Intel Iris Xe','90 WATTS'),(70,'Notebook Dell Latitude 5440',3,2,'16GB','512GB SSD','Intel i5-1335U','LAT5440','Intel UHD Graphics','90 WATTS'),(71,'Notebook Dell Alienware M18',3,2,'32GB','1TB SSD','Intel i9-13980HX','AWM18-13980HX','RTX 4080 12GB','280 WATTS'),(72,'Notebook Lenovo IdeaPad 3',4,2,'8GB','256GB SSD','Ryzen 5 5500U','82MFS00100','Radeon Vega 7','65 WATTS'),(73,'Notebook Lenovo ThinkPad E14 Gen 5',4,2,'16GB','512GB SSD','Intel i7-1355U','21JK0000BR','Intel Iris Xe','90 WATTS'),(74,'Notebook Lenovo Legion 5',4,2,'16GB','1TB SSD','Ryzen 7 6800H','82RD0002BR','RTX 3060 6GB','230 WATTS'),(75,'Notebook Lenovo Yoga Slim 7',4,2,'16GB','512GB SSD','Ryzen 7 7735HS','82YM0000BR','Radeon 680M','100 WATTS'),(76,'Notebook Lenovo LOQ 15',4,2,'32GB','1TB SSD','Intel i7-13650HX','83GS0000BR','RTX 4060 8GB','240 WATTS'),(77,'Notebook HP 250 G9',5,2,'8GB','256GB SSD','Intel i5-1235U','6Q8W2LA','Intel Iris Xe','65 WATTS'),(78,'Notebook HP Pavilion 15',5,2,'16GB','512GB SSD','Ryzen 7 5825U','6K8P5LA','Radeon Vega 8','90 WATTS'),(79,'Notebook HP Victus 16',5,2,'16GB','512GB SSD','Intel i7-12700H','7N8P6LA','RTX 3050 4GB','200 WATTS'),(80,'Notebook HP Envy x360',5,2,'16GB','1TB SSD','Ryzen 7 7730U','8L2L3LA','Radeon 680M','100 WATTS'),(81,'Notebook HP Omen 16',5,2,'32GB','1TB SSD','Intel i9-13900HX','8J2R4LA','RTX 4070 8GB','240 WATTS');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'aitom donatoni','aitom@gmail.com','$2y$10$2dZ6gMi8tFgV8pQi5SamKe/dp59pKYHRN8GUj3axjPiALBr3G7JuS',0,'0000-00-00'),(2,'awd aw','awd@gmail.com','$2y$10$ybPcXu6gp2kpGctrAa/obOmdU02T/hhhFHLFvbmtEAtnJ/CsDdJWm',0,'2002-10-30'),(3,'Vendedor Aitom','vendedor@gmail.com','$2y$10$NN8w8Y4NvRBKxyovOB1zrOsz8lGyiZtvYOBRSn73Se13ZE09XbA0W',1,'2002-10-30');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ecommerce'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-28 20:18:53
