-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for bdcarritocompras
DROP DATABASE IF EXISTS `bdcarritocompras`;
CREATE DATABASE IF NOT EXISTS `bdcarritocompras` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `bdcarritocompras`;

-- Dumping structure for table bdcarritocompras.compra
DROP TABLE IF EXISTS `compra`;
CREATE TABLE IF NOT EXISTS `compra` (
  `idcompra` bigint NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  `idusuario` bigint NOT NULL,
  PRIMARY KEY (`idcompra`),
  UNIQUE KEY `idcompra` (`idcompra`),
  KEY `fkcompra_1` (`idusuario`),
  CONSTRAINT `fkcompra_1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.compra: ~11 rows (approximately)
DELETE FROM `compra`;
INSERT INTO `compra` (`idcompra`, `fecha`, `idusuario`) VALUES
	(1, '2025-06-26 12:17:59', 1),
	(2, '2025-06-26 12:17:59', 2),
	(3, '2025-07-07 21:09:53', 1),
	(4, '2025-07-07 21:14:30', 1),
	(5, '2025-07-07 21:20:48', 1),
	(6, '2025-07-07 21:23:05', 1),
	(7, '2025-07-07 21:24:25', 1),
	(8, '2025-07-07 21:40:36', 1),
	(9, '2025-07-07 21:44:30', 1),
	(10, '2025-07-07 21:50:38', 1),
	(11, '2025-07-07 21:53:09', 1),
	(12, '2025-07-07 21:54:30', 1);

-- Dumping structure for table bdcarritocompras.compraestado
DROP TABLE IF EXISTS `compraestado`;
CREATE TABLE IF NOT EXISTS `compraestado` (
  `idcompraestado` bigint unsigned NOT NULL AUTO_INCREMENT,
  `idcompra` bigint NOT NULL,
  `idcompraestadotipo` int NOT NULL,
  `fechainicio` timestamp NOT NULL DEFAULT (now()),
  `fechafin` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idcompraestado`),
  UNIQUE KEY `idcompraestado` (`idcompraestado`),
  KEY `fkcompraestado_1` (`idcompra`),
  KEY `fkcompraestado_2` (`idcompraestadotipo`),
  CONSTRAINT `fkcompraestado_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fkcompraestado_2` FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.compraestado: ~8 rows (approximately)
DELETE FROM `compraestado`;
INSERT INTO `compraestado` (`idcompraestado`, `idcompra`, `idcompraestadotipo`, `fechainicio`, `fechafin`) VALUES
	(1, 1, 1, '2025-06-26 12:18:46', '2025-06-26 12:23:27'),
	(2, 1, 2, '2025-06-26 12:23:45', '2025-06-30 20:29:48'),
	(3, 2, 1, '2025-06-26 12:18:46', NULL),
	(4, 1, 3, '2025-06-30 20:29:48', NULL),
	(5, 7, 1, '2025-07-07 21:24:25', NULL),
	(6, 8, 1, '2025-07-07 21:40:36', NULL),
	(7, 9, 1, '2025-07-07 21:44:30', NULL),
	(8, 10, 1, '2025-07-07 21:50:38', NULL),
	(9, 11, 1, '2025-07-07 21:53:09', NULL),
	(10, 12, 1, '2025-07-07 21:54:30', NULL);

-- Dumping structure for table bdcarritocompras.compraestadotipo
DROP TABLE IF EXISTS `compraestadotipo`;
CREATE TABLE IF NOT EXISTS `compraestadotipo` (
  `idcompraestadotipo` int NOT NULL,
  `nombre` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `descripcion` varchar(256) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`idcompraestadotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.compraestadotipo: ~4 rows (approximately)
DELETE FROM `compraestadotipo`;
INSERT INTO `compraestadotipo` (`idcompraestadotipo`, `nombre`, `descripcion`) VALUES
	(1, 'iniciada', 'cuando el usuario : cliente inicia la compra de uno o mas productos del carrito'),
	(2, 'aceptada', 'cuando el usuario administrador da ingreso a uno de las compras en estado = 1 '),
	(3, 'enviada', 'cuando el usuario administrador envia a uno de las compras en estado =2 '),
	(4, 'cancelada', 'un usuario administrador podra cancelar una compra en cualquier estado y un usuario cliente solo en estado=1 ');

-- Dumping structure for table bdcarritocompras.compraitem
DROP TABLE IF EXISTS `compraitem`;
CREATE TABLE IF NOT EXISTS `compraitem` (
  `idcompraitem` bigint unsigned NOT NULL AUTO_INCREMENT,
  `idproducto` bigint NOT NULL,
  `idcompra` bigint NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`idcompraitem`),
  UNIQUE KEY `idcompraitem` (`idcompraitem`),
  KEY `fkcompraitem_1` (`idcompra`),
  KEY `fkcompraitem_2` (`idproducto`),
  CONSTRAINT `fkcompraitem_1` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fkcompraitem_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.compraitem: ~9 rows (approximately)
DELETE FROM `compraitem`;
INSERT INTO `compraitem` (`idcompraitem`, `idproducto`, `idcompra`, `cantidad`) VALUES
	(1, 1, 1, 2),
	(2, 2, 1, 3),
	(3, 3, 2, 1),
	(4, 1, 5, 2),
	(5, 1, 6, 2),
	(6, 1, 7, 2),
	(7, 1, 8, 1),
	(8, 1, 9, 1),
	(9, 1, 10, 1),
	(10, 1, 11, 1),
	(11, 1, 12, 1);

-- Dumping structure for table bdcarritocompras.menu
DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `idmenu` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Nombre del item del menu',
  `descripcion` varchar(124) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Descripcion mas detallada del item del menu',
  `idpadre` bigint DEFAULT NULL COMMENT 'Referencia al id del menu que es subitem',
  `deshabilitado` timestamp NULL DEFAULT NULL COMMENT 'Fecha en la que el menu fue deshabilitado por ultima vez',
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `html_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `has_user` tinyint DEFAULT NULL,
  PRIMARY KEY (`idmenu`),
  UNIQUE KEY `idmenu` (`idmenu`),
  KEY `fkmenu_1` (`idpadre`),
  CONSTRAINT `fkmenu_1` FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.menu: ~15 rows (approximately)
DELETE FROM `menu`;
INSERT INTO `menu` (`idmenu`, `nombre`, `descripcion`, `idpadre`, `deshabilitado`, `route_name`, `html_id`, `has_user`) VALUES
	(1, 'Header', 'Menú de usuario', NULL, NULL, NULL, NULL, 0),
	(2, 'Crear cuenta', 'Crear cuenta', 1, NULL, 'register.index', NULL, 0),
	(3, 'Ingresar', 'Inicio de sesión', 1, NULL, 'auth.index', NULL, 0),
	(4, 'Salir', 'Finalizar sesión', 1, NULL, 'auth.logout', NULL, 1),
	(5, 'Mis compras', 'Ver compras de usuario', 1, NULL, 'my_purchases.index', NULL, 1),
	(6, '🛒 ({0})', 'Carrito de compras', 1, NULL, 'cart.index', 'itemCount', 1),
	(7, 'Main', 'Menú principal', NULL, NULL, NULL, NULL, 0),
	(8, 'Inicio', 'Página de inicio', 7, NULL, 'home.index', NULL, 0),
	(9, 'Contacto', 'Página de contacto', 7, NULL, 'contact.index', NULL, 0),
	(10, 'Administración', 'Página de administración', 7, NULL, 'admin.index', '', 0),
	(11, 'Usuarios', 'Página de usuarios', 10, NULL, 'admin.users.index', NULL, 1),
	(12, 'Roles', 'Página de roles', 10, NULL, 'admin.roles.index', NULL, 1),
	(13, 'Productos', 'Página de productos', 10, NULL, 'admin.products.index', NULL, 1),
	(14, 'Menús', 'Página de menús', 10, NULL, 'admin.menus.index', NULL, 1),
	(15, 'Ventas', 'Ventas', 10, NULL, 'admin.sales.index', NULL, 1);

-- Dumping structure for table bdcarritocompras.menurol
DROP TABLE IF EXISTS `menurol`;
CREATE TABLE IF NOT EXISTS `menurol` (
  `idmenu` bigint NOT NULL,
  `idrol` bigint NOT NULL,
  PRIMARY KEY (`idmenu`,`idrol`),
  KEY `fkmenurol_2` (`idrol`),
  CONSTRAINT `fkmenurol_1` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE,
  CONSTRAINT `fkmenurol_2` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.menurol: ~23 rows (approximately)
DELETE FROM `menurol`;
INSERT INTO `menurol` (`idmenu`, `idrol`) VALUES
	(1, 1),
	(2, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 2),
	(6, 2),
	(7, 2),
	(8, 2),
	(9, 2);

-- Dumping structure for table bdcarritocompras.producto
DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `idproducto` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `descripcion` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `stock` int NOT NULL,
  `precio` float NOT NULL,
  PRIMARY KEY (`idproducto`),
  UNIQUE KEY `idproducto` (`idproducto`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.producto: ~8 rows (approximately)
DELETE FROM `producto`;
INSERT INTO `producto` (`idproducto`, `nombre`, `descripcion`, `stock`, `precio`) VALUES
	(1, 'Producto 1', 'Producto 1', 0, 1000),
	(2, 'Producto 2', 'Producto 2', 9, 1000),
	(3, 'Producto 3', 'Producto 3', 10, 1000),
	(4, 'Producto 4', 'Producto 4', 10, 1000),
	(5, 'Producto 5', 'Producto 5', 10, 1000),
	(6, 'Producto 6', 'Producto 6', 10, 1000),
	(7, 'Producto 7', 'Producto 7', 10, 1000),
	(8, 'Producto 8', 'Producto 8', 10, 1000);

-- Dumping structure for table bdcarritocompras.rol
DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `idrol` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`idrol`),
  UNIQUE KEY `idrol` (`idrol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.rol: ~2 rows (approximately)
DELETE FROM `rol`;
INSERT INTO `rol` (`idrol`, `nombre`) VALUES
	(1, 'Administrador'),
	(2, 'Cliente');

-- Dumping structure for table bdcarritocompras.usuario
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `deshabilitado` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `idusuario` (`idusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.usuario: ~1 rows (approximately)
DELETE FROM `usuario`;
INSERT INTO `usuario` (`idusuario`, `nombre`, `password`, `email`, `deshabilitado`) VALUES
	(1, 'hjagar', '$2y$12$JPJytHNx5tEUfvTNZKIMWOhBMmCakSIlEafPoRBXdnQRWdLW23mjG', 'hjagar@gmail.com', NULL),
	(2, 'void', '$2y$12$JPJytHNx5tEUfvTNZKIMWOhBMmCakSIlEafPoRBXdnQRWdLW23mjG', 'void@gmail.com', NULL);

-- Dumping structure for table bdcarritocompras.usuariorol
DROP TABLE IF EXISTS `usuariorol`;
CREATE TABLE IF NOT EXISTS `usuariorol` (
  `idusuario` bigint NOT NULL,
  `idrol` bigint NOT NULL,
  PRIMARY KEY (`idusuario`,`idrol`),
  KEY `idusuario` (`idusuario`),
  KEY `idrol` (`idrol`),
  CONSTRAINT `fkmovimiento_1` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE,
  CONSTRAINT `usuariorol_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Dumping data for table bdcarritocompras.usuariorol: ~0 rows (approximately)
DELETE FROM `usuariorol`;
INSERT INTO `usuariorol` (`idusuario`, `idrol`) VALUES
	(1, 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
