/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.28-MariaDB : Database - sistemaalmacen
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sistemaalmacen` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `sistemaalmacen`;

/*Table structure for table `categorias` */

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `idcategoria` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) NOT NULL,
  PRIMARY KEY (`idcategoria`),
  UNIQUE KEY `uk_categoria_cat` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `categorias` */

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `idcategoria` int(11) NOT NULL,
  `nombreproducto` text NOT NULL,
  `fotografia` varchar(100) NOT NULL,
  `stock` int(11) NOT NULL,
  `fechacreacion` datetime NOT NULL,
  `estado` char(1) NOT NULL,
  PRIMARY KEY (`idproducto`),
  KEY `fk_idcategoria_prod` (`idcategoria`),
  CONSTRAINT `fk_idcategoria_prod` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `productos` */

/*Table structure for table `reestock` */

DROP TABLE IF EXISTS `reestock`;

CREATE TABLE `reestock` (
  `identrada` int(11) NOT NULL AUTO_INCREMENT,
  `idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fechareestock` datetime NOT NULL,
  `detallereestock` text NOT NULL,
  PRIMARY KEY (`identrada`),
  KEY `fk_idproducto_ent` (`idproducto`),
  CONSTRAINT `fk_idproducto_ent` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `reestock` */

/*Table structure for table `salidas` */

DROP TABLE IF EXISTS `salidas`;

CREATE TABLE `salidas` (
  `idsalida` int(11) NOT NULL AUTO_INCREMENT,
  `idproducto` int(11) NOT NULL,
  `cantidadsalida` int(11) NOT NULL,
  `detalle` text NOT NULL,
  `fechasalida` datetime NOT NULL,
  PRIMARY KEY (`idsalida`),
  KEY `fk_idproducto_sal` (`idproducto`),
  CONSTRAINT `fk_idproducto_sal` FOREIGN KEY (`idproducto`) REFERENCES `productos` (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `salidas` */

/*Table structure for table `usuarios` */

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(70) NOT NULL,
  `apellidos` varchar(70) NOT NULL,
  `nombreusuario` varchar(25) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `fechacreacion` date NOT NULL,
  `fechabaja` date DEFAULT NULL,
  `nivelacceso` char(1) NOT NULL,
  `estado` char(1) NOT NULL,
  `email` varchar(50) NOT NULL,
  `codverificacion` char(6) DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `uk_nombreusuario_user` (`nombreusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuarios` */

insert  into `usuarios`(`idusuario`,`nombres`,`apellidos`,`nombreusuario`,`clave`,`fechacreacion`,`fechabaja`,`nivelacceso`,`estado`,`email`,`codverificacion`) values 
(1,'admin','almacen','prueba','$2y$10$dvgzm2Jmh0u98DerZSGkX.QH5rVqqD/ctSC3UCgYNj4jFh0CgR5mi','2023-11-19',NULL,'A','A','prueba@gmail.com',NULL);

/* Procedure structure for procedure `spu_categorias_cargar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_categorias_cargar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_categorias_cargar`()
BEGIN
	SELECT * FROM categorias ORDER BY idcategoria;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_eliminar_producto` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_eliminar_producto` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_eliminar_producto`(IN _idproducto INT)
BEGIN
		DELETE FROM productos
		WHERE idproducto = _idproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_emailnoexiste_registrado` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_emailnoexiste_registrado` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_emailnoexiste_registrado`(
	IN _email VARCHAR(50)
)
BEGIN
	SELECT * FROM usuarios
	WHERE email = _email;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_nombreproducto_registrado` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_nombreproducto_registrado` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_nombreproducto_registrado`(
	IN _nombreproducto TEXT
)
BEGIN
	SELECT * FROM productos
	WHERE nombreproducto = _nombreproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_nombreusuario_registrado` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_nombreusuario_registrado` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_nombreusuario_registrado`(
	IN _nombreusuario VARCHAR(25)
)
BEGIN
	SELECT * FROM usuarios 
	WHERE nombreusuario = _nombreusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productos_filtrar_categorias` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productos_filtrar_categorias` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productos_filtrar_categorias`(
 IN _idcategoria INT
)
BEGIN
	SELECT * FROM productos
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria
	WHERE productos.idcategoria= _idcategoria;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productos_getdata` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productos_getdata` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productos_getdata`(IN _idproducto INT)
BEGIN
	SELECT * FROM productos
	WHERE idproducto = _idproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productos_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productos_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productos_listar`()
BEGIN
	SELECT * FROM productos 
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productos_modificar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productos_modificar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productos_modificar`(
	IN _idproducto INT,
	IN _nombreproducto TEXT,
	IN _stock		INT
)
BEGIN
	UPDATE productos SET
	nombreproducto = _nombreproducto
	WHERE idproducto = _idproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_productos_registro` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_productos_registro` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_productos_registro`(
	IN _idcategoria INT,
	IN _nombreproducto TEXT,
	IN _fotografia VARCHAR(100),
	IN _stock		INT
)
BEGIN	
	INSERT INTO productos(idcategoria, nombreproducto, fotografia, stock, fechacreacion) VALUES 
		(_idcategoria, _nombreproducto, _fotografia, _stock, NOW());
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_restock_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_restock_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_restock_listar`()
BEGIN	
	SELECT * FROM reestock
	INNER JOIN productos ON productos.idproducto = reestock.idproducto
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_restock_registro` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_restock_registro` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_restock_registro`(
	IN _idproducto		INT,
	IN _cantidad 		INT,
	IN _detallereestock	TEXT
)
BEGIN	
	INSERT INTO reestock(idproducto, cantidad, fechareestock, detallereestock) VALUES 
		(idproducto, cantidad, NOW(), detallereestock);
		
	UPDATE productos SET 
		stock = stock + _cantidad
        WHERE idproducto = _idproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_salidas_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_salidas_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_salidas_listar`()
BEGIN	
	SELECT * FROM salidas
	INNER JOIN productos ON productos.idproducto = salidas.idproducto
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_salidas_registro` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_salidas_registro` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_salidas_registro`(
	IN _idproducto		INT,
	IN _cantidadsalida	INT,
	IN _detalle		TEXT
)
BEGIN	
	INSERT INTO salidas(idproducto, cantidadsalida, detalle, fechasalida) VALUES 
		(_idproducto, _cantidadsalida, _detalle, NOW());
		
	UPDATE productos SET 
		stock = stock - _cantidadsalida
        WHERE idproducto = _idproducto;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_actualizarclave` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_actualizarclave` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_actualizarclave`(
	IN _idusuario INT,
	IN _clave	VARCHAR(100)
)
BEGIN
	UPDATE usuarios SET clave = _clave WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_eliminar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_eliminar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_eliminar`(
	IN _idusuario INT
)
BEGIN
	UPDATE usuarios SET
		fechabaja = CURDATE(),
		estado = "I"
	WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_getdata` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_getdata` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_getdata`(IN _idusuario INT)
BEGIN
	SELECT idusuario, nombres, apellidos, nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios
	WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_listar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_listar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_listar`()
BEGIN
	SELECT idusuario, nombres, apellidos, nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_login` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_login` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_login`(
	IN _nombreusuario 	VARCHAR(25)
)
BEGIN
	SELECT idusuario, nombres, apellidos, nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios
	WHERE nombreusuario = _nombreusuario AND estado = "A";
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_modificar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_modificar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_modificar`(
	IN _idusuario INT,
	IN _nombreusuario VARCHAR(25),
	IN _nivelacceso CHAR(1),
	IN _email VARCHAR(50)
)
BEGIN
	UPDATE usuarios SET
		nombreusuario = _nombreusuario,
		nivelacceso = _nivelacceso,
		email = _email
	WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_reactivar` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_reactivar` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_reactivar`(
	IN _idusuario INT
)
BEGIN
	UPDATE usuarios SET
		fechacreacion = CURDATE(),
		fechabaja = NULL,
		estado = "A"
	WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuarios_registro` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuarios_registro` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuarios_registro`(
	IN _nombres 			VARCHAR(50),
	IN _apellidos 			VARCHAR(50),
	IN _nombreusuario 		VARCHAR(25),
	IN _nivelacceso			CHAR(1),
	IN _email			VARCHAR(80)
)
BEGIN
	INSERT INTO usuarios(nombres, apellidos,nombreusuario, clave, fechacreacion,fechabaja, nivelacceso, estado, email, codverificacion)VALUES
			(_nombres, _apellidos, _nombreusuario, "$2y$10$dvgzm2Jmh0u98DerZSGkX.QH5rVqqD/ctSC3UCgYNj4jFh0CgR5mi", CURDATE(), NULL, _nivelacceso, "A", _email, NULL);
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuario_codverificacion` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuario_codverificacion` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuario_codverificacion`(
	IN _idusuario INT,
	IN _codverificacion CHAR(6)
)
BEGIN 
	UPDATE usuarios SET
		codverificacion = _codverificacion
	WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuario_eliminarcodverificacion` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuario_eliminarcodverificacion` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuario_eliminarcodverificacion`(IN _idusuario INT)
BEGIN 
	UPDATE usuarios SET
		codverificacion = NULL
	WHERE idusuario = _idusuario;
END */$$
DELIMITER ;

/* Procedure structure for procedure `spu_usuario_verificarcorreo` */

/*!50003 DROP PROCEDURE IF EXISTS  `spu_usuario_verificarcorreo` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `spu_usuario_verificarcorreo`(IN _email VARCHAR(50))
BEGIN	
		SELECT idusuario, CONCAT (apellidos, " ", nombres) AS 'datospersona', nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
			WHEN nivelacceso = "F" THEN "Farmacia"
			WHEN nivelacceso = "M" THEN "Médico"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios
	 WHERE email =_email AND estado = "A";
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
