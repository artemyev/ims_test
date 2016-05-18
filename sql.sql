/*
MySQL - 5.5.44-MariaDB-log : Database - webekfep_1
*********************************************************************
*/

CREATE DATABASE IF NOT EXISTS `webekfep_1` DEFAULT CHARACTER SET utf8;

/*Table structure for table `basket` */

CREATE TABLE `basket` (
  `md5` varchar(32) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `s_products` */

CREATE TABLE `s_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '',
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(500) NOT NULL,
  `annotation` text NOT NULL,
  `body` longtext NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `price` int(11) NOT NULL DEFAULT '0',
  `meta_title` varchar(500) NOT NULL,
  `meta_keywords` varchar(500) NOT NULL,
  `meta_description` varchar(500) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `featured` tinyint(1) DEFAULT NULL,
  `external_id` varchar(36) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `brand_id` (`brand_id`),
  KEY `visible` (`visible`),
  KEY `price` (`price`),
  KEY `external_id` (`external_id`),
  KEY `hit` (`featured`),
  KEY `name` (`name`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `s_products` */

insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (1,'lampochka1',1,'Лампочка 1','Лампочка 1 - кратное описание','Лампочка 1 - полное описание',1,199,'Лампочка 1','Лампочка 1','Лампочка 1','2014-02-19 09:25:04',NULL,'');
insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (2,'lampochka2',1,'Лампочка 2','Лампочка 2 - кратное описание','Лампочка 2 - полное описание',1,299,'Лампочка 2','Лампочка 2','Лампочка 2','2014-02-19 09:25:04',NULL,'');
insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (3,'lampochka3',1,'Лампочка 3','Лампочка 3 - кратное описание','Лампочка 3 - полное описание',1,399,'Лампочка 3','Лампочка 3','Лампочка 3','2014-02-19 09:25:04',NULL,'');
insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (4,'lampochka4',1,'Лампочка 4','Лампочка 4 - кратное описание','Лампочка 4 - полное описание',1,499,'Лампочка 4','Лампочка 4','Лампочка 4','2014-02-19 09:25:04',NULL,'');
insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (5,'lampochka5',1,'Лампочка 5','Лампочка 5 - кратное описание','Лампочка 5 - полное описание',1,599,'Лампочка 5','Лампочка 5','Лампочка 5','2014-02-19 09:25:04',NULL,'');
insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (6,'lampochka6',1,'Лампочка 6','Лампочка 6 - кратное описание','Лампочка 6 - полное описание',1,699,'Лампочка 6','Лампочка 6','Лампочка 6','2014-02-19 09:25:04',NULL,'');
insert  into `s_products`(`id`,`url`,`brand_id`,`name`,`annotation`,`body`,`visible`,`price`,`meta_title`,`meta_keywords`,`meta_description`,`created`,`featured`,`external_id`) values (7,'lampochka7',1,'Лампочка 7','Лампочка 7 - кратное описание','Лампочка 7 - полное описание',1,799,'Лампочка 7','Лампочка 7','Лампочка 7','2014-02-19 09:25:04',NULL,'');
