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
  `read` tinyint(1) default 0,
  `write` tinyint(1) default 0,
  `purchase` tinyint(1) default 0,
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
  `deletable` tinyint(1) default 0,
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
  `image` char(255) NOT NULL,
  `stock` int(10) NOT NULL,
  `price` decimal(15,4) NOT NULL,
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
  `order_items_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `shipping_date` datetime NOT NULL,
  `shipped` tinyint(1) NOT NULL,
  PRIMARY KEY  (`orders_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `order_items_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`order_items_id`,`products_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `basket`;
CREATE TABLE `basket` (
  `basket_id` int(11) NOT NULL auto_increment,
  `products_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY  (`basket_id`)
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
INSERT INTO products VALUES(0,'Prod. 1',1,0,1,'Beschreibung','kein Bild',10,10.00,20061125,1);
INSERT INTO products VALUES(0,'Prod. 1',1,0,1,'Beschreibung','kein Bild',10,10.00,20061125,1);