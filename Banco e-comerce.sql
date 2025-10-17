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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `endereco`
--

LOCK TABLES `endereco` WRITE;
/*!40000 ALTER TABLE `endereco` DISABLE KEYS */;
/*!40000 ALTER TABLE `endereco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagem`
--

DROP TABLE IF EXISTS `imagem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagem` (
  `id_imagem` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_info` bigint(20) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordem` int(11) DEFAULT 0,
  PRIMARY KEY (`id_imagem`),
  KEY `imagem` (`id_info`),
  CONSTRAINT `imagem` FOREIGN KEY (`id_info`) REFERENCES `produto_info` (`id_info`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagem`
--

LOCK TABLES `imagem` WRITE;
/*!40000 ALTER TABLE `imagem` DISABLE KEYS */;
INSERT INTO `imagem` VALUES (1,7,'https://http2.mlstatic.com/D_NQ_NP_2X_644770-MLA88197867621_072025-F.webp',1),(2,7,'https://http2.mlstatic.com/D_NQ_NP_2X_997876-MLA88198947003_072025-F.webp',2);
/*!40000 ALTER TABLE `imagem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_pedido`
--

DROP TABLE IF EXISTS `item_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_pedido` (
  `id_item_pedido` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` bigint(20) unsigned NOT NULL,
  `id_produto` bigint(20) unsigned NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unit` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_item_pedido`),
  KEY `item_pedido_id_produto_foreign` (`id_produto`),
  KEY `item_pedido_id_pedido_foreign` (`id_pedido`),
  CONSTRAINT `item_pedido_id_pedido_foreign` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  CONSTRAINT `item_pedido_id_produto_foreign` FOREIGN KEY (`id_produto`) REFERENCES `produto` (`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_pedido`
--

LOCK TABLES `item_pedido` WRITE;
/*!40000 ALTER TABLE `item_pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_pedido` ENABLE KEYS */;
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
  `data_pedido` datetime NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `pedido_id_user_foreign` (`id_user`),
  CONSTRAINT `pedido_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
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
  `quantidade` int(11) NOT NULL,
  `id_info` bigint(20) unsigned NOT NULL,
  `data_att` datetime NOT NULL,
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
INSERT INTO `produto` VALUES (1,'Notebook Acer','Preto',4700.00,23,7,'2025-10-11 11:37:15'),(3,'Notebook Acer Swift 3','Prata',4800.00,10,8,'2025-10-11 13:16:00'),(4,'Notebook Acer Predator Helios 300','Preto',10500.00,5,9,'2025-10-11 13:16:00'),(5,'Notebook Acer Aspire 5','Cinza',4500.00,12,10,'2025-10-11 13:16:00'),(6,'Notebook Acer Nitro V 15','Preto',6700.00,8,11,'2025-10-11 13:16:00'),(7,'Computador Gamer Xtreme','Preto',12500.00,10,12,'2025-10-11 13:18:39'),(8,'Computador Office Plus','Cinza',4800.00,15,13,'2025-10-11 13:18:39'),(9,'Computador Workstation Pro','Preto',35000.00,3,14,'2025-10-11 13:18:39'),(10,'Computador Gamer Storm','Preto',22000.00,7,15,'2025-10-11 13:18:39'),(11,'Computador Home Basic','Branco',3500.00,20,16,'2025-10-11 13:18:39'),(12,'iPhone 17 Pro','Prata',12500.00,12,17,'2025-10-11 13:21:29'),(13,'iPhone 17 Pro Max','Preto',13500.00,10,18,'2025-10-11 13:21:29'),(14,'iPhone 17 Plus','Dourado',11000.00,8,19,'2025-10-11 13:21:29'),(15,'iPhone 17 Mini','Cinza',9500.00,15,20,'2025-10-11 13:21:29'),(16,'iPhone 17 SE','Branco',7200.00,20,21,'2025-10-11 13:21:29'),(17,'Motorola Edge 50 Pro','Preto',6200.00,10,22,'2025-10-11 13:21:29'),(18,'Motorola Moto G85','Azul',1800.00,25,23,'2025-10-11 13:21:29'),(19,'Motorola Edge 40 Neo','Verde',2800.00,18,24,'2025-10-11 13:21:29'),(20,'Motorola Moto G73','Preto',1600.00,22,25,'2025-10-11 13:21:29'),(21,'Motorola Moto E14','Branco',850.00,30,26,'2025-10-11 13:21:29'),(22,'Oppo Find X7 Pro','Preto',7200.00,12,27,'2025-10-11 13:21:29'),(23,'Oppo Reno 12 Pro','Azul',5100.00,15,28,'2025-10-11 13:21:29'),(24,'Oppo A3 Pro','Prata',3200.00,20,29,'2025-10-11 13:21:29'),(25,'Oppo Find N3 Flip','Preto',6100.00,10,30,'2025-10-11 13:21:29'),(26,'Oppo A79 5G','Rosa',2900.00,18,31,'2025-10-11 13:21:29'),(27,'Samsung Galaxy S24 Ultra','Preto',10200.00,12,32,'2025-10-11 13:21:29'),(28,'Samsung Galaxy S23 FE','Azul',4500.00,20,33,'2025-10-11 13:21:29'),(29,'Samsung Galaxy A55 5G','Prata',3900.00,22,34,'2025-10-11 13:21:29'),(30,'Samsung Galaxy M55','Preto',3500.00,18,35,'2025-10-11 13:21:29'),(31,'Samsung Galaxy Z Flip 5','Dourado',5200.00,8,36,'2025-10-11 13:21:29'),(32,'Xiaomi 14 Ultra','Preto',9800.00,10,37,'2025-10-11 13:21:29'),(33,'Xiaomi Redmi Note 13 Pro+','Azul',4600.00,15,38,'2025-10-11 13:21:29'),(34,'Xiaomi Poco F6 Pro','Preto',4800.00,12,39,'2025-10-11 13:21:29'),(35,'Xiaomi Redmi 13C','Cinza',1700.00,25,40,'2025-10-11 13:21:29'),(36,'Xiaomi Mi 13T','Prata',5200.00,18,41,'2025-10-11 13:21:29'),(37,'SmartTV AOC 43\" 4K','Preto',2200.00,10,42,'2025-10-11 13:23:07'),(38,'SmartTV AOC 50\" UHD','Preto',2700.00,8,43,'2025-10-11 13:23:07'),(39,'SmartTV AOC 55\" QLED','Preto',3200.00,7,44,'2025-10-11 13:23:07'),(40,'SmartTV AOC 65\" 4K UHD','Preto',4500.00,5,45,'2025-10-11 13:23:07'),(41,'SmartTV AOC 75\" 4K','Preto',5500.00,3,46,'2025-10-11 13:23:07'),(42,'SmartTV LG 43\" OLED','Preto',3100.00,6,47,'2025-10-11 13:23:07'),(43,'SmartTV LG 50\" 4K UHD','Preto',3600.00,5,48,'2025-10-11 13:23:07'),(44,'SmartTV LG 55\" OLED evo','Preto',4200.00,4,49,'2025-10-11 13:23:07'),(45,'SmartTV LG 65\" 4K NanoCell','Preto',4800.00,3,50,'2025-10-11 13:23:07'),(46,'SmartTV LG 75\" 4K UHD','Preto',6000.00,2,51,'2025-10-11 13:23:07'),(47,'SmartTV Philco 32\" HD','Preto',1200.00,12,52,'2025-10-11 13:23:07'),(48,'SmartTV Philco 43\" 4K','Preto',2500.00,8,53,'2025-10-11 13:23:07'),(49,'SmartTV Philco 50\" 4K UHD','Preto',2800.00,7,54,'2025-10-11 13:23:07'),(50,'SmartTV Philco 55\" 4K','Preto',3200.00,6,55,'2025-10-11 13:23:07'),(51,'SmartTV Philco 65\" 4K UHD','Preto',3800.00,5,56,'2025-10-11 13:23:07'),(52,'SmartTV Sony Bravia 43\" 4K','Preto',3300.00,7,57,'2025-10-11 13:23:07'),(53,'SmartTV Sony Bravia 50\" 4K','Preto',3700.00,6,58,'2025-10-11 13:23:07'),(54,'SmartTV Sony Bravia 55\" OLED','Preto',4200.00,5,59,'2025-10-11 13:23:07'),(55,'SmartTV Sony Bravia 65\" 4K','Preto',4800.00,4,60,'2025-10-11 13:23:07'),(56,'SmartTV Sony Bravia 75\" 4K','Preto',5800.00,3,61,'2025-10-11 13:23:07'),(57,'Notebook Asus Vivobook 15','Preto',3500.00,25,62,'2025-10-13 10:10:24'),(58,'Notebook Asus TUF Gaming F15','Cinza',6200.00,18,63,'2025-10-13 10:10:24'),(59,'Notebook Asus Zenbook 14','Prata',5800.00,20,64,'2025-10-13 10:10:24'),(60,'Notebook Asus ROG Strix G16','Preto',8900.00,10,65,'2025-10-13 10:10:24'),(61,'Notebook Asus ExpertBook B1','Azul',5100.00,22,66,'2025-10-13 10:10:24'),(62,'Notebook Dell Inspiron 15 3000','Prata',3400.00,27,67,'2025-10-13 10:10:24'),(63,'Notebook Dell G15 Gaming','Preto',6500.00,15,68,'2025-10-13 10:10:24'),(64,'Notebook Dell XPS 13','Prata',7200.00,12,69,'2025-10-13 10:10:24'),(65,'Notebook Dell Latitude 5440','Preto',5600.00,17,70,'2025-10-13 10:10:24'),(66,'Notebook Dell Alienware M18','Cinza',12500.00,8,71,'2025-10-13 10:10:24'),(67,'Notebook Lenovo IdeaPad 3','Prata',3300.00,30,72,'2025-10-13 10:10:24'),(68,'Notebook Lenovo ThinkPad E14 Gen 5','Preto',5400.00,19,73,'2025-10-13 10:10:24'),(69,'Notebook Lenovo Legion 5','Cinza',6900.00,14,74,'2025-10-13 10:10:24'),(70,'Notebook Lenovo Yoga Slim 7','Roxo',5800.00,16,75,'2025-10-13 10:10:24'),(71,'Notebook Lenovo LOQ 15','Preto',7600.00,12,76,'2025-10-13 10:10:24'),(72,'Notebook HP 250 G9','Prata',3400.00,24,77,'2025-10-13 10:10:24'),(73,'Notebook HP Pavilion 15','Preto',5200.00,20,78,'2025-10-13 10:10:24'),(74,'Notebook HP Victus 16','Azul',6100.00,14,79,'2025-10-13 10:10:24'),(75,'Notebook HP Envy x360','Prata',6300.00,11,80,'2025-10-13 10:10:24'),(76,'Notebook HP Omen 16','Preto',8700.00,9,81,'2025-10-13 10:10:24');
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
-- Table structure for table `telefone`
--

DROP TABLE IF EXISTS `telefone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telefone` (
  `id_telefone` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `numero` char(9) NOT NULL,
  `ddd_telefone` char(2) NOT NULL,
  PRIMARY KEY (`id_telefone`),
  KEY `telefone_user` (`id_user`),
  CONSTRAINT `telefone_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telefone`
--

LOCK TABLES `telefone` WRITE;
/*!40000 ALTER TABLE `telefone` DISABLE KEYS */;
/*!40000 ALTER TABLE `telefone` ENABLE KEYS */;
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
  `genero` enum('masculino','feminino','outro') DEFAULT NULL,
  `data_nascimento` date NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'aitom donatoni','aitom@gmail.com','$2y$10$2dZ6gMi8tFgV8pQi5SamKe/dp59pKYHRN8GUj3axjPiALBr3G7JuS',0,'outro','0000-00-00'),(2,'awd aw','awd@gmail.com','$2y$10$ybPcXu6gp2kpGctrAa/obOmdU02T/hhhFHLFvbmtEAtnJ/CsDdJWm',0,'feminino','2002-10-30');
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

-- Dump completed on 2025-10-16 20:29:16
