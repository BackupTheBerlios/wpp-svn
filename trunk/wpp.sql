-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	4.1.15-Debian_1ubuntu5-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=`NO_AUTO_VALUE_ON_ZERO` */;


--
-- Create schema wpp
--

DROP DATABASE IF EXISTS wpp;
CREATE DATABASE wpp;
USE wpp;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `read_content` tinyint(1) default 0,
  `write_content` tinyint(1) default 0,
  `purchase` tinyint(1) default 0,
  `deletable` tinyint(1) default 0,
  `read_orders` tinyint(1) default 0,
  `write_orders` tinyint(1) default 0,
  PRIMARY KEY  (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `users_id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL,
  `lastname` char(255) NOT NULL,
  `password` char(255) NOT NULL,
  `email` char(255) NOT NULL,
  `role_id` int NOT NULL,
  `active` tinyint(1) default 0,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL auto_increment,
  `name` char(255) NOT NULL,
  `parent` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `description` char(255) NOT NULL,
  `sort_order` int(10) NOT NULL,
  PRIMARY KEY  (`categories_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `orders_id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `users_id` int(11) NOT NULL,
  `shipping_date` datetime,
  `shipped` tinyint(1) NOT NULL,
  PRIMARY KEY  (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `orders_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`orders_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `basket`;
CREATE TABLE `basket` (
  `basket_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY ( `basket_id` , `products_id` )
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*Beispieldaten*/;

INSERT INTO categories VALUES(0,'Kat 1',0,1,'Beschreibung 1',1);
INSERT INTO categories VALUES(0,'Kat 2',0,1,'Beschreibung 2',2);
INSERT INTO categories VALUES(0,'Kat 3',0,1,'Beschreibung 3',3);
INSERT INTO categories VALUES(0,'Kat 1.1',1,1,'Beschreibung 1.1',1);
INSERT INTO categories VALUES(0,'Kat 1.2',1,1,'Beschreibung 1.2',2);
INSERT INTO categories VALUES(0,'Kat 1.2',0,1,'Beschreibung 1.2',4);
INSERT INTO categories VALUES(0,'Fernseher',0,1,'Beschreibung Fernseher',5);
INSERT INTO categories VALUES(0,'Radios',0,0,'Beschreibung Radios',6);

INSERT INTO products VALUES(0,'Prod. 1',1,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 2',1,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 3',2,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 4',2,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 5',2,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 6',3,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 7',4,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 8',5,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 9',5,0,1,'Beschreibung','kein Bild','Kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'TV A',7,0,1,'Beschreibung TV A','kein Bild','Kein Bild',10,1070.00,20061125,1);
INSERT INTO products VALUES(0,'TV B',7,0,0,'Beschreibung TV B','kein Bild','Kein Bild',10,234.99,20061125,1);
INSERT INTO products VALUES(0,'Radio A',8,0,1,'Beschreibung Radio A','kein Bild','Kein Bild',10,17.39,20061125,1);

INSERT INTO roles VALUES(0,'admin',1,1,1,0,1,1);
INSERT INTO roles VALUES(0,'customer',1,0,1,1,0,0);
INSERT INTO roles VALUES(0,'order_manager',1,0,0,0,1,1);

INSERT INTO users VALUES(0,'andre','flechs','test','studium@luzip.de',1,1,'','','','','','','','','','','','','','');
INSERT INTO users VALUES(0,'hans','meier','test','studium@luzip.de',2,1,'','','','','','','','','','','','','','');
INSERT INTO users VALUES(0,'peter','müller','test','studium@luzip.de',2,1,'','','','','','','','','','','','','','');
INSERT INTO users VALUES(0,'Lars','Schröder','wpppw','safuser@web.de',1,1,'','','','','','','','','','','','','','');
INSERT INTO users VALUES(0,'User','Schröder','wpppw','safuser@web.de',2,1,'','','','','','','','','','','','','','');

INSERT INTO orders VALUES(0,20061201123456,2,20061203123456,1);
INSERT INTO order_items VALUES(1,2,5);
INSERT INTO orders VALUES(0,20061227,2,NULL,2);
INSERT INTO order_items VALUES(2,2,3);
INSERT INTO order_items VALUES(2,4,1);
INSERT INTO order_items VALUES(2,6,2);