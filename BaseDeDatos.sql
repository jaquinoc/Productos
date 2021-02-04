/*
MySQL Backup
Source Server Version: 5.7.24
Source Database: productos
Date: 3/02/2021 22:10:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
--  Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `baneado` tinyint(1) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `category` VALUES ('1','Ropa','1','2021-02-03 00:53:06','2021-02-03 18:14:51'), ('2','Calzado','1','2021-02-03 19:27:53','2021-02-03 19:27:53'), ('3','Accesorios','1','2021-02-03 19:28:06','2021-02-03 19:28:06');
INSERT INTO `product` VALUES ('1','1010','Camisa','Camisa caballero','Adidas','10000','2021-02-03 02:25:33','2021-02-03 02:25:34','1'), ('3','3030','Cinturón','Cinturón dama','BOSS','15000','2021-02-03 19:29:00','2021-02-03 19:29:00','3'), ('4','2222','Pantalón','Pantalón hombre','Levis','25000','2021-02-03 19:46:35','2021-02-03 19:46:35','1'), ('5','4040','Sandalias rosa','Sandalias mujer','ComfortFrattini','32500','2021-02-03 19:47:55','2021-02-03 19:47:55','2'), ('6','5555','Cartera','Cartera mujer','Artesanal','80000','2021-02-03 23:33:44','2021-02-03 23:34:39','3'), ('7','6666','Sombrero','Sombrero para hombre','Artesanal','120000','2021-02-03 23:38:13','2021-02-03 23:38:13','3'), ('8','5644XXX','Vestido','Vestido azul','Leonisa','980000','2021-02-04 00:18:06','2021-02-04 00:18:54','1');
INSERT INTO `user` VALUES ('1','jaquinoc@gmail.com','[\"ROLE_USER\"]','$argon2i$v=19$m=65536,t=4,p=1$Y2dsU3FwWHRxaDRSTFJjUw$TSlvStAXdXl4WhAK+GmDBi3j5wEKEcntRPOzio6V8BY','0','Jaime Muñoz'), ('2','jaquinoc@hotmail.com','[\"ROLE_USER\"]','$argon2i$v=19$m=65536,t=4,p=1$SHFoOHZ1UVpqTXAxNkRZZg$8McowTtZRJe5su02KTQWFYzoctqg7s0CLCmSxklziJQ','0','Jaime Muñoz'), ('3','leiydy@gmail.com','[\"ROLE_USER\"]','$argon2i$v=19$m=65536,t=4,p=1$Z2xYdS9RTnRxZHZHVDVKbQ$/+pRlL+LWN5TIGhmbQVuJDmpLOoWIw6ybwMuPpBtK1k','0','Leidy Granados');
