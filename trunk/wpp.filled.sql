-- MySQL dump 10.10
--
-- Host: localhost    Database: wpp
-- ------------------------------------------------------
-- Server version	5.0.30-Debian_2-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `basket`
--

DROP TABLE IF EXISTS `basket`;
CREATE TABLE `basket` (
  `basket_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`basket_id`,`products_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `basket`
--

LOCK TABLES `basket` WRITE;
/*!40000 ALTER TABLE `basket` DISABLE KEYS */;
/*!40000 ALTER TABLE `basket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` char(255) NOT NULL,
  `sort_order` int(10) NOT NULL,
  PRIMARY KEY  (`categories_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`categories_id`, `name`, `parent`, `active`, `description`, `sort_order`) VALUES (1,'Kat 1',0,1,'Beschreibung 1',1),(2,'Kat 2',0,1,'Beschreibung 2',2),(3,'Kat 3',0,1,'Beschreibung 3',3),(4,'Kat 1.1',1,1,'Beschreibung 1.1',1),(5,'Kat 1.2',1,1,'Beschreibung 1.2',2),(6,'Kat 1.2',0,1,'Beschreibung 1.2',4),(7,'Fernseher',0,1,'Beschreibung Fernseher',5),(8,'Radios',0,0,'Beschreibung Radios',6);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `orders_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`orders_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` (`orders_id`, `products_id`, `count`) VALUES (1,2,5),(2,2,3),(2,4,1),(2,6,2),(3,3,6),(3,5,1),(3,10,6);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `orders_id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `users_id` int(11) NOT NULL,
  `shipping_date` datetime default NULL,
  `shipped` tinyint(1) NOT NULL,
  `bill_name` char(255) NOT NULL,
  `bill_street` char(255) NOT NULL,
  `bill_postcode` char(255) NOT NULL,
  `bill_city` char(255) NOT NULL,
  `bill_state` char(255) NOT NULL,
  `ship_name` char(255) NOT NULL,
  `ship_street` char(255) NOT NULL,
  `ship_postcode` char(255) NOT NULL,
  `ship_city` char(255) NOT NULL,
  `ship_state` char(255) NOT NULL,
  `bank_number` char(255) NOT NULL,
  `bank_iban` char(255) NOT NULL,
  `bank_name` char(255) NOT NULL,
  `bank_account` char(255) NOT NULL,
  PRIMARY KEY  (`orders_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` (`orders_id`, `date`, `users_id`, `shipping_date`, `shipped`, `bill_name`, `bill_street`, `bill_postcode`, `bill_city`, `bill_state`, `ship_name`, `ship_street`, `ship_postcode`, `ship_city`, `ship_state`, `bank_number`, `bank_iban`, `bank_name`, `bank_account`) VALUES (1,'2006-12-01 12:34:56',1,'2006-12-03 12:34:56',1,'','','','','','','','','','','','','',''),(2,'2006-12-27 00:00:00',1,NULL,2,'','','','','','','','','','','','','',''),(3,'2007-02-01 13:33:16',7,'2007-02-01 13:34:34',1,'kun de','street 2','02345','Leipzig','Deutschland','kun de','street 2','02355','Leipzig','Deutschland','987756','98765432','Bank Leipzig','kun de');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `products_id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_small` char(255) NOT NULL,
  `image_big` char(255) NOT NULL,
  `stock` int(10) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `create_time` datetime NOT NULL,
  `sort_order` int(10) NOT NULL,
  PRIMARY KEY  (`products_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` (`products_id`, `name`, `categories_id`, `deleted`, `active`, `description`, `image_small`, `image_big`, `stock`, `price`, `create_time`, `sort_order`) VALUES (1,'Prod. 1',1,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(2,'Prod. 2',1,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(3,'Prod. 3',2,0,1,'Beschreibung','kein Bild','Kein Bild',4,'10.00','2006-11-25 00:00:00',1),(4,'Prod. 4',2,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(5,'Prod. 5',2,0,1,'Beschreibung','kein Bild','Kein Bild',9,'10.00','2006-11-25 00:00:00',1),(6,'Prod. 6',3,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(7,'Prod. 7',4,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(8,'Prod. 8',5,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(9,'Prod. 9',5,0,1,'Beschreibung','kein Bild','Kein Bild',10,'10.00','2006-11-25 00:00:00',1),(10,'TV A',7,0,1,'Beschreibung TV A','kein Bild','Kein Bild',4,'1070.00','2006-11-25 00:00:00',1),(11,'TV B',7,0,0,'Beschreibung TV B','kein Bild','Kein Bild',10,'234.99','2006-11-25 00:00:00',1),(12,'Radio A',8,0,1,'Beschreibung Radio A','kein Bild','Kein Bild',10,'17.39','2006-11-25 00:00:00',1);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `read_content` tinyint(1) default '0',
  `write_content` tinyint(1) default '0',
  `purchase` tinyint(1) default '0',
  `deletable` tinyint(1) default '0',
  `read_orders` tinyint(1) default '0',
  `write_orders` tinyint(1) default '0',
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`role_id`, `name`, `read_content`, `write_content`, `purchase`, `deletable`, `read_orders`, `write_orders`) VALUES (1,'admin',1,1,1,0,1,1),(2,'customer',1,0,1,1,0,0),(3,'order_manager',1,0,0,0,1,1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `users_id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL,
  `lastname` char(255) NOT NULL,
  `password` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `active` tinyint(1) default '0',
  `bill_name` char(255) NOT NULL,
  `bill_street` char(255) NOT NULL,
  `bill_postcode` char(255) NOT NULL,
  `bill_city` char(255) NOT NULL,
  `bill_state` char(255) NOT NULL,
  `ship_name` char(255) NOT NULL,
  `ship_street` char(255) NOT NULL,
  `ship_postcode` char(255) NOT NULL,
  `ship_city` char(255) NOT NULL,
  `ship_state` char(255) NOT NULL,
  `bank_number` char(255) NOT NULL,
  `bank_iban` char(255) NOT NULL,
  `bank_name` char(255) NOT NULL,
  `bank_account` char(255) NOT NULL,
  PRIMARY KEY  (`users_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`users_id`, `name`, `lastname`, `password`, `email`, `role_id`, `active`, `bill_name`, `bill_street`, `bill_postcode`, `bill_city`, `bill_state`, `ship_name`, `ship_street`, `ship_postcode`, `ship_city`, `ship_state`, `bank_number`, `bank_iban`, `bank_name`, `bank_account`) VALUES (1,'Andre','flechs','andre','studium@luzip.de',1,0,'Andre Flechs','Augustenstrasse 6','04317','Leipzig','Deutschland','Andre Flechs','Augustenstrasse 6','04317','Leipzig','Deutschland','123456','12345678','Bank Leipzig','Andre Flechs'),(7,'kun','de','kun','kun@de.de',2,1,'kun de','street 2','02345','Leipzig','Deutschland','kun de','street 2','02355','Leipzig','Deutschland','987756','98765432','Bank Leipzig','kun de'),(4,'Lars','SchrÃ¶der','wpppw','safuser@web.de',1,1,'','','','','','','','','','','','','',''),(6,'order','manager','order','order@wpp.de',3,1,'order manager','street 1','01234','Leipzig','Deutschland','order manager','street 1','01234','Leipzig','Deutschland','12345','12345678','Bank Leipzig','Order Manager');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-02-01 12:41:44
