CREATE DATABASE sistemaalmacen;
USE sistemaalmacen;

CREATE TABLE usuarios(
	idusuario			INT 			AUTO_INCREMENT PRIMARY KEY,
	nombres				VARCHAR(70)		NOT NULL,
	apellidos			VARCHAR(70)		NOT NULL,
	nombreusuario		VARCHAR(25)		NOT NULL,
	clave				VARCHAR(100)		NOT NULL,
	fechacreacion 		DATE 			NOT NULL,
	fechabaja			DATE 			NULL,
	nivelacceso	 		CHAR(1)			NOT NULL,
	estado				CHAR(1)			NOT NULL,
	email 				VARCHAR(50) 		NOT NULL,
	codverificacion 	CHAR(6) 		NULL,
	CONSTRAINT uk_nombreusuario_user UNIQUE (nombreusuario)
)ENGINE = INNODB;

CREATE TABLE categorias(
	idcategoria		INT 			AUTO_INCREMENT PRIMARY KEY,
	categoria		VARCHAR(50)		NOT NULL,
	CONSTRAINT uk_categoria_cat UNIQUE (categoria)
)ENGINE = INNODB;

CREATE TABLE productos(
	idproducto			INT 			AUTO_INCREMENT PRIMARY KEY,
	idcategoria			INT				NOT NULL,
	nombreproducto		TEXT			NOT NULL,
	fotografia			VARCHAR(100)	NOT NULL,
	stock				INT				NOT NULL,
	fechacreacion		DATETIME		NOT NULL,
	estado			CHAR(1)		NOT NULL,
	CONSTRAINT fk_idcategoria_prod FOREIGN KEY (idcategoria) REFERENCES categorias(idcategoria)
)ENGINE = INNODB;

CREATE TABLE reestock(
	identrada				INT 			AUTO_INCREMENT PRIMARY KEY,
	idproducto				INT				NOT NULL,
	cantidad    			INT				NOT NULL,
	fechareestock			DATETIME		NOT NULL,
	detallereestock			TEXT			NOT NULL,
	CONSTRAINT fk_idproducto_ent FOREIGN KEY (idproducto) REFERENCES productos(idproducto)
)ENGINE = INNODB;

CREATE TABLE salidas(
	idsalida				INT 			AUTO_INCREMENT PRIMARY KEY,
	idproducto				INT				NOT NULL,
	cantidadsalida			INT				NOT NULL,
	detalle 				TEXT		NOT NULL,
	fechasalida 			DATETIME		NOT NULL,
	CONSTRAINT fk_idproducto_sal FOREIGN KEY (idproducto) REFERENCES productos(idproducto)
)ENGINE = INNODB;

INSERT INTO categorias(categoria) VALUES
("categoria1"),
("categoria2"),
("categoria3");

SELECT * FROM categorias ORDER BY idcategoria;