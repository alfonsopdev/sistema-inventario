USE sistemaalmacen;

-- ---------------------------------------------------------------------------------------------
-- USUARIOS
-- ---------------------------------------------------------------------------------------------
DELIMITER $$
CREATE PROCEDURE spu_usuarios_registro
(
	IN _nombres 			VARCHAR(50),
	IN _apellidos 			VARCHAR(50),
	IN _nombreusuario 		VARCHAR(25),
	IN _nivelacceso			CHAR(1),
	IN _email			VARCHAR(80)
)
BEGIN
	INSERT INTO usuarios(nombres, apellidos,nombreusuario, clave, fechacreacion,fechabaja, nivelacceso, estado, email, codverificacion)VALUES
			(_nombres, _apellidos, _nombreusuario, "$2y$10$dvgzm2Jmh0u98DerZSGkX.QH5rVqqD/ctSC3UCgYNj4jFh0CgR5mi", CURDATE(), NULL, _nivelacceso, "A", _email, NULL);
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuarios_login
(
	IN _nombreusuario 	VARCHAR(25)
)
BEGIN
	SELECT idusuario, nombres, apellidos, nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios
	WHERE nombreusuario = _nombreusuario AND estado = "A";
END $$

CALL spu_usuarios_registro("admin", "almacen", "prueba", "A", "prueba@gmail.com");

DELIMITER $$
CREATE PROCEDURE spu_usuarios_actualizarclave
(
	IN _idusuario INT,
	IN _clave	VARCHAR(100)
)
BEGIN
	UPDATE usuarios SET clave = _clave WHERE idusuario = _idusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuarios_listar()
BEGIN
	SELECT idusuario, nombres, apellidos, nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuarios_eliminar
(
	IN _idusuario INT
)
BEGIN
	UPDATE usuarios SET
		fechabaja = CURDATE(),
		estado = "I"
	WHERE idusuario = _idusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuarios_reactivar
(
	IN _idusuario INT
)
BEGIN
	UPDATE usuarios SET
		fechacreacion = CURDATE(),
		fechabaja = NULL,
		estado = "A"
	WHERE idusuario = _idusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_nombreusuario_registrado
(
	IN _nombreusuario VARCHAR(25)
)
BEGIN
	SELECT * FROM usuarios 
	WHERE nombreusuario = _nombreusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuarios_getdata(IN _idusuario INT)
BEGIN
	SELECT idusuario, nombres, apellidos, nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios
	WHERE idusuario = _idusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuarios_modificar
(
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
END $$

DELIMITER $$
CREATE PROCEDURE spu_emailnoexiste_registrado -- listo
(
	IN _email VARCHAR(50)
)
BEGIN
	SELECT * FROM usuarios
	WHERE email = _email;
END $$

DELIMITER $$ -- listo
CREATE PROCEDURE spu_usuario_codverificacion(
	IN _idusuario INT,
	IN _codverificacion CHAR(6)
)
BEGIN 
	UPDATE usuarios SET
		codverificacion = _codverificacion
	WHERE idusuario = _idusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuario_eliminarcodverificacion(IN _idusuario INT)  -- listo
BEGIN 
	UPDATE usuarios SET
		codverificacion = NULL
	WHERE idusuario = _idusuario;
END $$

DELIMITER $$
CREATE PROCEDURE spu_usuario_verificarcorreo(IN _email VARCHAR(50)) -- listo
BEGIN	
		SELECT idusuario, CONCAT (apellidos, " ", nombres) AS 'datospersona', nombreusuario, clave, fechacreacion, fechabaja, 
		CASE 
			WHEN nivelacceso = "A" THEN "Administrador"
			WHEN nivelacceso = "F" THEN "Farmacia"
			WHEN nivelacceso = "M" THEN "Médico"
		END "nivelacceso", estado, email, codverificacion
	 FROM usuarios
	 WHERE email =_email AND estado = "A";
END $$

-- ----------------------------------------------------------------------------------------------------------------------
-- CATEGORIAS
-- ----------------------------------------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE spu_categorias_cargar()
BEGIN
	SELECT * FROM categorias ORDER BY idcategoria;
END $$

DELIMITER $$
CREATE PROCEDURE spu_productos_filtrar_categorias
(
 IN _idcategoria INT
)
BEGIN
	SELECT * FROM productos
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria
	WHERE productos.idcategoria= _idcategoria;
END $$
-- ----------------------------------------------------------------------------------------------------------------------
-- PRODUCTOS
-- ----------------------------------------------------------------------------------------------------------------------
DELIMITER $$
CREATE PROCEDURE spu_productos_registro
(
	IN _idcategoria INT,
	IN _nombreproducto TEXT,
	IN _fotografia VARCHAR(100),
	IN _stock		INT
)
BEGIN	
	INSERT INTO productos(idcategoria, nombreproducto, fotografia, stock, fechacreacion) VALUES 
		(_idcategoria, _nombreproducto, _fotografia, _stock, NOW());
END $$

CALL spu_productos_registro (1, "lápiz", "lapiz.jpg", 15);

DELIMITER $$
CREATE PROCEDURE spu_nombreproducto_registrado -- listo
(
	IN _nombreproducto TEXT
)
BEGIN
	SELECT * FROM productos
	WHERE nombreproducto = _nombreproducto;
END $$

DELIMITER $$
CREATE PROCEDURE spu_eliminar_producto(IN _idproducto INT)
BEGIN
		DELETE FROM productos
		WHERE idproducto = _idproducto;
END $$

DELIMITER $$
CREATE PROCEDURE spu_productos_modificar
(
	IN _idproducto INT,
	IN _nombreproducto TEXT,
	IN _stock		INT
)
BEGIN
	UPDATE productos SET
	nombreproducto = _nombreproducto
	WHERE idproducto = _idproducto;
END $$

DELIMITER $$
CREATE PROCEDURE spu_productos_getdata(IN _idproducto INT)
BEGIN
	SELECT * FROM productos
	WHERE idproducto = _idproducto;
END $$

DELIMITER $$
CREATE PROCEDURE spu_productos_listar()
BEGIN
	SELECT * FROM productos 
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria;
END $$


-- ------------------------------------------------------------------------------------
-- RESTOCK
-- ------------------------------------------------------------------------------------

DELIMITER $$
CREATE PROCEDURE spu_restock_registro
(
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
END $$

DELIMITER $$
CREATE PROCEDURE spu_restock_listar()
BEGIN	
	SELECT * FROM reestock
	INNER JOIN productos ON productos.idproducto = reestock.idproducto
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria;
END $$ 
-- ----------------------------------------------------------------------------------
-- SALIDAS
-- ----------------------------------------------------------------------------------
DELIMITER $$
CREATE PROCEDURE spu_salidas_registro
(
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
END $$

DELIMITER $$
CREATE PROCEDURE spu_salidas_listar()
BEGIN	
	SELECT * FROM salidas
	INNER JOIN productos ON productos.idproducto = salidas.idproducto
	INNER JOIN categorias ON categorias.idcategoria = productos.idcategoria;
END $$ 