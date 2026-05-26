-- ============================================================
-- MIGRACIÓN: Garantías y Mantenimientos como tablas detalle
-- ============================================================

-- 1. TABLA GARANTIA
CREATE TABLE IF NOT EXISTS garantia (
    id_garantia INT PRIMARY KEY AUTO_INCREMENT,
    id_activo INT NOT NULL,
    proveedor VARCHAR(250) DEFAULT NULL,
    tipo_garantia VARCHAR(100) DEFAULT NULL,
    fecha_inicio DATE DEFAULT NULL,
    fecha_fin DATE NOT NULL,
    tiempo_meses INT DEFAULT NULL,
    documento_pdf VARCHAR(255) DEFAULT NULL,
    observacion TEXT DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'VIGENTE',
    FOREIGN KEY (id_activo) REFERENCES activo(id_activo) ON DELETE CASCADE
);

-- 2. TABLA MANTENIMIENTO
CREATE TABLE IF NOT EXISTS mantenimiento (
    id_mantenimiento INT PRIMARY KEY AUTO_INCREMENT,
    id_activo INT NOT NULL,
    tipo_mantenimiento ENUM('PREVENTIVO','CORRECTIVO','PREDICTIVO') NOT NULL,
    fecha_mantenimiento DATE NOT NULL,
    descripcion TEXT DEFAULT NULL,
    responsable VARCHAR(250) DEFAULT NULL,
    costo DECIMAL(10,2) DEFAULT NULL,
    documento_pdf VARCHAR(255) DEFAULT NULL,
    proveedor VARCHAR(250) DEFAULT NULL,
    fecha_proximo_mantenimiento DATE DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_activo) REFERENCES activo(id_activo) ON DELETE CASCADE
);

-- 3. STORED PROCEDURES — GARANTÍAS

DELIMITER $$

CREATE PROCEDURE spu_garantia_listar_por_activo(IN _idactivo INT)
BEGIN
    SELECT
        g.id_garantia,
        g.id_activo,
        g.proveedor,
        g.tipo_garantia,
        g.fecha_inicio,
        g.fecha_fin,
        g.tiempo_meses,
        g.documento_pdf,
        g.observacion,
        g.fecha_registro,
        CASE
            WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA'
            WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
            ELSE 'VIGENTE'
        END AS estado
    FROM garantia g
    WHERE g.id_activo = _idactivo
    ORDER BY g.fecha_fin DESC;
END$$

CREATE PROCEDURE spu_garantia_registrar(
    IN _id_activo INT,
    IN _proveedor VARCHAR(250),
    IN _tipo_garantia VARCHAR(100),
    IN _fecha_inicio DATE,
    IN _fecha_fin DATE,
    IN _documento_pdf VARCHAR(255),
    IN _observacion TEXT
)
BEGIN
    DECLARE _tiempo_meses INT;
    DECLARE _estado VARCHAR(20);

    SET _tiempo_meses = TIMESTAMPDIFF(MONTH, _fecha_inicio, _fecha_fin);

    SET _estado = CASE
        WHEN _fecha_fin < CURDATE() THEN 'VENCIDA'
        WHEN _fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
        ELSE 'VIGENTE'
    END;

    INSERT INTO garantia (id_activo, proveedor, tipo_garantia, fecha_inicio, fecha_fin, tiempo_meses, documento_pdf, observacion, estado)
    VALUES (_id_activo, _proveedor, _tipo_garantia, _fecha_inicio, _fecha_fin, _tiempo_meses, _documento_pdf, _observacion, _estado);

    SELECT LAST_INSERT_ID() AS id_garantia;
END$$

CREATE PROCEDURE spu_garantia_editar(
    IN _id_garantia INT,
    IN _proveedor VARCHAR(250),
    IN _tipo_garantia VARCHAR(100),
    IN _fecha_inicio DATE,
    IN _fecha_fin DATE,
    IN _documento_pdf VARCHAR(255),
    IN _observacion TEXT
)
BEGIN
    DECLARE _tiempo_meses INT;
    DECLARE _estado VARCHAR(20);

    SET _tiempo_meses = TIMESTAMPDIFF(MONTH, _fecha_inicio, _fecha_fin);

    SET _estado = CASE
        WHEN _fecha_fin < CURDATE() THEN 'VENCIDA'
        WHEN _fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
        ELSE 'VIGENTE'
    END;

    UPDATE garantia SET
        proveedor = _proveedor,
        tipo_garantia = _tipo_garantia,
        fecha_inicio = _fecha_inicio,
        fecha_fin = _fecha_fin,
        tiempo_meses = _tiempo_meses,
        documento_pdf = _documento_pdf,
        observacion = _observacion,
        estado = _estado
    WHERE id_garantia = _id_garantia;
END$$

CREATE PROCEDURE spu_garantia_eliminar(IN _id_garantia INT)
BEGIN
    DELETE FROM garantia WHERE id_garantia = _id_garantia;
END$$

CREATE PROCEDURE spu_garantia_cargar(IN _id_garantia INT)
BEGIN
    SELECT
        g.id_garantia,
        g.id_activo,
        g.proveedor,
        g.tipo_garantia,
        g.fecha_inicio,
        g.fecha_fin,
        g.tiempo_meses,
        g.documento_pdf,
        g.observacion,
        g.fecha_registro,
        CASE
            WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA'
            WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
            ELSE 'VIGENTE'
        END AS estado
    FROM garantia g
    WHERE g.id_garantia = _id_garantia;
END$$

-- 4. STORED PROCEDURES — MANTENIMIENTOS

CREATE PROCEDURE spu_mantenimiento_listar_por_activo(IN _idactivo INT)
BEGIN
    SELECT *
    FROM mantenimiento
    WHERE id_activo = _idactivo
    ORDER BY fecha_mantenimiento DESC;
END$$

CREATE PROCEDURE spu_mantenimiento_registrar(
    IN _id_activo INT,
    IN _tipo_mantenimiento VARCHAR(20),
    IN _fecha_mantenimiento DATE,
    IN _descripcion TEXT,
    IN _responsable VARCHAR(250),
    IN _costo DECIMAL(10,2),
    IN _documento_pdf VARCHAR(255),
    IN _proveedor VARCHAR(250),
    IN _fecha_proximo_mantenimiento DATE
)
BEGIN
    INSERT INTO mantenimiento (id_activo, tipo_mantenimiento, fecha_mantenimiento, descripcion, responsable, costo, documento_pdf, proveedor, fecha_proximo_mantenimiento)
    VALUES (_id_activo, _tipo_mantenimiento, _fecha_mantenimiento, _descripcion, _responsable, _costo, _documento_pdf, _proveedor, _fecha_proximo_mantenimiento);

    SELECT LAST_INSERT_ID() AS id_mantenimiento;
END$$

CREATE PROCEDURE spu_mantenimiento_editar(
    IN _id_mantenimiento INT,
    IN _tipo_mantenimiento VARCHAR(20),
    IN _fecha_mantenimiento DATE,
    IN _descripcion TEXT,
    IN _responsable VARCHAR(250),
    IN _costo DECIMAL(10,2),
    IN _documento_pdf VARCHAR(255),
    IN _proveedor VARCHAR(250),
    IN _fecha_proximo_mantenimiento DATE
)
BEGIN
    UPDATE mantenimiento SET
        tipo_mantenimiento = _tipo_mantenimiento,
        fecha_mantenimiento = _fecha_mantenimiento,
        descripcion = _descripcion,
        responsable = _responsable,
        costo = _costo,
        documento_pdf = _documento_pdf,
        proveedor = _proveedor,
        fecha_proximo_mantenimiento = _fecha_proximo_mantenimiento
    WHERE id_mantenimiento = _id_mantenimiento;
END$$

CREATE PROCEDURE spu_mantenimiento_eliminar(IN _id_mantenimiento INT)
BEGIN
    DELETE FROM mantenimiento WHERE id_mantenimiento = _id_mantenimiento;
END$$

CREATE PROCEDURE spu_mantenimiento_cargar(IN _id_mantenimiento INT)
BEGIN
    SELECT * FROM mantenimiento WHERE id_mantenimiento = _id_mantenimiento;
END$$

-- 5. SP — CARGA MASIVA DE ACTIVOS

CREATE PROCEDURE spu_activo_carga_masiva(
    IN _idcategoria INT,
    IN _marca VARCHAR(150),
    IN _modelo VARCHAR(150),
    IN _serie VARCHAR(150),
    IN _cod_patrimonial VARCHAR(150),
    IN _idsede INT,
    IN _iddependencia INT,
    IN _idadministrativo INT,
    IN _estado VARCHAR(20),
    IN _fecha_adquisicion DATE,
    IN _observacion TEXT
)
BEGIN
    INSERT INTO activo (
        id_categoria, marca, modelo, serie, cod_patrimonial,
        id_sede, id_dependencia, id_administrativo,
        foto, estado, fecha_adquisicion, observacion
    ) VALUES (
        _idcategoria, _marca, _modelo, _serie, _cod_patrimonial,
        _idsede, _iddependencia, _idadministrativo,
        'default.png', _estado, _fecha_adquisicion, _observacion
    );

    SELECT LAST_INSERT_ID() AS id_activo;
END$$

-- 6. SP — LISTAR ACTIVOS CON INFORMACIÓN DE GARANTÍA

CREATE PROCEDURE spu_activo_listar_con_garantia()
BEGIN
    SELECT
        ac.id_activo,
        ac.id_administrativo,
        ac.id_categoria,
        ac.id_dependencia,
        ac.id_sede,
        ac.foto,
        ac.cod_patrimonial,
        c.nombre_categoria,
        CONCAT(ac.marca, ' / ', ac.modelo) AS marca_modelo,
        s.nombre_sede,
        d.nombre_dependencia,
        CONCAT(p.per_nombre, ' ', p.per_apepat, ' ', p.per_apemat) AS npersona,
        ac.estado,
        ac.fecha_adquisicion,
        ac.fecha_registro,
        ac.observacion,
        ac.serie,
        ac.estado_mov,
        COALESCE(gar.estado_garantia, 'SIN_GARANTIA') AS estado_garantia,
        gar.fecha_fin AS garantia_fecha_fin,
        gar.proveedor AS garantia_proveedor,
        mov.tipo_mov AS ultimo_movimiento
    FROM activo ac
    LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
    LEFT JOIN sede s ON ac.id_sede = s.id_sede
    LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
    LEFT JOIN dependencia d ON ac.id_dependencia = d.id_dependencia
    LEFT JOIN persona p ON a.id_persona = p.id_persona
    LEFT JOIN (
        SELECT
            g.id_activo,
            CASE
                WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA'
                WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
                ELSE 'VIGENTE'
            END AS estado_garantia,
            g.fecha_fin,
            g.proveedor
        FROM garantia g
        INNER JOIN (
            SELECT id_activo, MAX(id_garantia) AS id_garantia
            FROM garantia
            GROUP BY id_activo
        ) ult_g ON ult_g.id_garantia = g.id_garantia
    ) gar ON gar.id_activo = ac.id_activo
    LEFT JOIN (
        SELECT m.id_activo, m.tipo_mov
        FROM movimiento m
        INNER JOIN (
            SELECT MAX(id_movimiento) AS id_movimiento, id_activo
            FROM movimiento
            GROUP BY id_activo
        ) lm ON lm.id_movimiento = m.id_movimiento
    ) mov ON mov.id_activo = ac.id_activo
    ORDER BY ac.fecha_registro DESC;
END$$

-- 7. SP — FILTRAR ACTIVOS INCLUYENDO GARANTÍA

CREATE PROCEDURE spu_activo_filtrar_con_garantia(
    IN _filtro_categoria INT,
    IN _filtro_responsable INT,
    IN _filtro_calidad VARCHAR(20),
    IN _filtro_sede INT,
    IN _filtro_dependencia INT,
    IN _filtro_estado VARCHAR(20),
    IN _filtro_estado_garantia VARCHAR(20)
)
BEGIN
    SELECT
        ac.id_activo,
        ac.id_administrativo,
        ac.id_categoria,
        ac.id_dependencia,
        ac.id_sede,
        ac.foto,
        ac.cod_patrimonial,
        c.nombre_categoria,
        CONCAT(ac.marca, ' / ', ac.modelo) AS marca_modelo,
        s.nombre_sede,
        d.nombre_dependencia,
        CONCAT(p.per_nombre, ' ', p.per_apepat, ' ', p.per_apemat) AS npersona,
        ac.estado,
        ac.fecha_adquisicion,
        ac.fecha_registro,
        ac.observacion,
        ac.serie,
        ac.estado_mov,
        COALESCE(gar.estado_garantia, 'SIN_GARANTIA') AS estado_garantia,
        gar.fecha_fin AS garantia_fecha_fin,
        gar.proveedor AS garantia_proveedor,
        mov.tipo_mov AS ultimo_movimiento
    FROM activo ac
    LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
    LEFT JOIN sede s ON ac.id_sede = s.id_sede
    LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
    LEFT JOIN dependencia d ON ac.id_dependencia = d.id_dependencia
    LEFT JOIN persona p ON a.id_persona = p.id_persona
    LEFT JOIN (
        SELECT
            g.id_activo,
            CASE
                WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA'
                WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
                ELSE 'VIGENTE'
            END AS estado_garantia,
            g.fecha_fin,
            g.proveedor
        FROM garantia g
        INNER JOIN (
            SELECT id_activo, MAX(id_garantia) AS id_garantia
            FROM garantia
            GROUP BY id_activo
        ) ult_g ON ult_g.id_garantia = g.id_garantia
    ) gar ON gar.id_activo = ac.id_activo
    LEFT JOIN (
        SELECT m.id_activo, m.tipo_mov
        FROM movimiento m
        INNER JOIN (
            SELECT MAX(id_movimiento) AS id_movimiento, id_activo
            FROM movimiento
            GROUP BY id_activo
        ) lm ON lm.id_movimiento = m.id_movimiento
    ) mov ON mov.id_activo = ac.id_activo
    WHERE
        (_filtro_categoria IS NULL OR ac.id_categoria = _filtro_categoria)
        AND (_filtro_responsable IS NULL OR ac.id_administrativo = _filtro_responsable)
        AND (_filtro_calidad IS NULL OR ac.estado_mov = _filtro_calidad)
        AND (_filtro_sede IS NULL OR ac.id_sede = _filtro_sede)
        AND (_filtro_dependencia IS NULL OR ac.id_dependencia = _filtro_dependencia)
        AND (_filtro_estado IS NULL OR ac.estado = _filtro_estado)
        AND (
            _filtro_estado_garantia IS NULL
            OR (_filtro_estado_garantia = 'SIN_GARANTIA' AND gar.id_activo IS NULL)
            OR gar.estado_garantia = _filtro_estado_garantia
        )
    ORDER BY ac.fecha_registro DESC;
END$$

-- 8. SP — DASHBOARD: CONTAR GARANTÍAS POR ESTADO

CREATE PROCEDURE spu_garantia_resumen()
BEGIN
    SELECT
        COALESCE(SUM(CASE WHEN estado_calc = 'VIGENTE' THEN 1 ELSE 0 END), 0) AS vigentes,
        COALESCE(SUM(CASE WHEN estado_calc = 'PROXIMO_A_VENCER' THEN 1 ELSE 0 END), 0) AS proximas_a_vencer,
        COALESCE(SUM(CASE WHEN estado_calc = 'VENCIDA' THEN 1 ELSE 0 END), 0) AS vencidas,
        COALESCE(SUM(CASE WHEN g.id_activo IS NULL THEN 1 ELSE 0 END), 0) AS sin_garantia
    FROM activo ac
    LEFT JOIN (
        SELECT
            g.id_activo,
            CASE
                WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA'
                WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
                ELSE 'VIGENTE'
            END AS estado_calc
        FROM garantia g
        INNER JOIN (
            SELECT id_activo, MAX(id_garantia) AS id_garantia
            FROM garantia
            GROUP BY id_activo
        ) ult ON ult.id_garantia = g.id_garantia
    ) g ON g.id_activo = ac.id_activo;
END$$

-- 9. SP — LISTAR GARANTÍAS GLOBAL (para el dashboard)

CREATE PROCEDURE spu_garantia_listar_todos()
BEGIN
    SELECT
        g.id_garantia,
        g.id_activo,
        g.proveedor,
        g.tipo_garantia,
        g.fecha_inicio,
        g.fecha_fin,
        g.tiempo_meses,
        g.documento_pdf,
        g.observacion,
        g.fecha_registro,
        CASE
            WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA'
            WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
            ELSE 'VIGENTE'
        END AS estado,
        ac.cod_patrimonial,
        CONCAT(ac.marca, ' / ', ac.modelo) AS activo_nombre,
        c.nombre_categoria,
        CONCAT(p.per_nombre, ' ', p.per_apepat, ' ', p.per_apemat) AS responsable
    FROM garantia g
    INNER JOIN activo ac ON ac.id_activo = g.id_activo
    LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
    LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
    LEFT JOIN persona p ON a.id_persona = p.id_persona
    ORDER BY g.fecha_fin ASC;
END$$

-- 10. SP — ACTUALIZAR ESTADO DE GARANTÍAS (para ejecutar periódicamente)

CREATE PROCEDURE spu_garantia_actualizar_estados()
BEGIN
    UPDATE garantia SET
        estado = CASE
            WHEN fecha_fin < CURDATE() THEN 'VENCIDA'
            WHEN fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER'
            ELSE 'VIGENTE'
        END;
END$$

-- 11. SP ACTUALIZADO — REGISTRO DE ACTIVO (sin campos de garantía)
DELIMITER $$
DROP PROCEDURE IF EXISTS spu_activo_registro$$
CREATE PROCEDURE spu_activo_registro(
    IN _idcategoria INT,
    IN _txt_marca VARCHAR(150),
    IN _txt_modelo VARCHAR(150),
    IN _txt_serie VARCHAR(150),
    IN _txt_patrimonial VARCHAR(150),
    IN _select_responsable INT,
    IN _select_sede INT,
    IN _select_dependencia INT,
    IN _foto VARCHAR(200),
    IN _select_estado VARCHAR(20),
    IN _date_fecha_adquisicion DATE,
    IN _orden_compra VARCHAR(255),
    IN _observacion VARCHAR(200)
)
BEGIN
    INSERT INTO activo (id_categoria, marca, modelo, serie, cod_patrimonial, id_administrativo, id_sede, id_dependencia, foto, estado, fec_compra, orden_compra, observacion)
    VALUES (_idcategoria, _txt_marca, _txt_modelo, _txt_serie, _txt_patrimonial, _select_responsable, _select_sede, _select_dependencia, _foto, _select_estado, _date_fecha_adquisicion, _orden_compra, _observacion);
END$$

-- 12. SOFT DELETE — columna estado_activo y SPs actualizados
ALTER TABLE activo ADD COLUMN estado_activo CHAR(1) NOT NULL DEFAULT '1' AFTER estado_mov;
UPDATE activo SET estado_activo = '1';

DROP PROCEDURE IF EXISTS spu_activo_eliminar_logico$$
CREATE PROCEDURE spu_activo_eliminar_logico(IN _idactivo INT)
BEGIN
    UPDATE activo SET estado_activo = '0' WHERE id_activo = _idactivo;
END$$

-- Actualiza spu_activo_listar con filtro estado_activo
DROP PROCEDURE IF EXISTS spu_activo_listar$$
CREATE PROCEDURE spu_activo_listar()
BEGIN
SELECT ac.id_activo, ac.id_administrativo, ac.id_categoria, ac.id_dependencia, ac.id_sede, ac.foto, ac.cod_patrimonial,
c.nombre_categoria, CONCAT(ac.marca, ' / ', ac.modelo) as marca_modelo, s.nombre_sede, d.nombre_dependencia,
CONCAT(p.per_nombre, ' ', p.per_apepat, ' ',p.per_apemat) as npersona, ac.estado, ac.fecha_adquisicion,
ac.fecha_registro, ac.observacion, ac.qr_code, ac.serie, ac.estado_mov, ac.valor_precio,
mov.tipo_mov AS ultimo_movimiento
FROM activo ac
LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
LEFT JOIN sede s ON ac.id_sede = s.id_sede
LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
LEFT JOIN dependencia d ON ac.id_dependencia = d.id_dependencia
LEFT JOIN persona p ON a.id_persona = p.id_persona
LEFT JOIN (SELECT md.id_activo, m.tipo_mov FROM activo_movimiento md INNER JOIN movimiento m ON m.id_movimiento = md.id_movimiento INNER JOIN (SELECT MAX(md2.id_movimiento) AS id_movimiento, md2.id_activo FROM activo_movimiento md2 GROUP BY md2.id_activo) ult ON ult.id_movimiento = md.id_movimiento AND ult.id_activo = md.id_activo) mov ON mov.id_activo = ac.id_activo
WHERE ac.estado_activo = '1'
ORDER BY ac.fecha_registro DESC;
END$$

-- Actualiza spu_activo_aplicarfiltro con filtro estado_activo
DROP PROCEDURE IF EXISTS spu_activo_aplicarfiltro$$
CREATE PROCEDURE spu_activo_aplicarfiltro(
    IN _filtro_categoria INT, IN _filtro_responsable INT, IN _filtro_calidad VARCHAR(20),
    IN _filtro_sede INT, IN _filtro_dependencia INT, IN _filtro_estado VARCHAR(20))
BEGIN
SELECT ac.id_activo, ac.id_administrativo, ac.id_categoria, ac.id_dependencia, ac.id_sede, ac.foto, ac.cod_patrimonial,
c.nombre_categoria, CONCAT(ac.marca, ' / ', ac.modelo) as marca_modelo, s.nombre_sede, d.nombre_dependencia,
CONCAT(p.per_nombre, ' ', p.per_apepat, ' ',p.per_apemat) as npersona, ac.estado, ac.fecha_adquisicion,
ac.fecha_registro, ac.observacion, ac.qr_code, ac.serie, ac.estado_mov, ac.valor_precio,
mov.tipo_mov AS ultimo_movimiento
FROM activo ac
LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
LEFT JOIN sede s ON ac.id_sede = s.id_sede
LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
LEFT JOIN dependencia d ON ac.id_dependencia = d.id_dependencia
LEFT JOIN persona p ON a.id_persona = p.id_persona
LEFT JOIN (SELECT md.id_activo, m.tipo_mov FROM activo_movimiento md INNER JOIN movimiento m ON m.id_movimiento = md.id_movimiento INNER JOIN (SELECT MAX(md2.id_movimiento) AS id_movimiento, md2.id_activo FROM activo_movimiento md2 GROUP BY md2.id_activo) ult ON ult.id_movimiento = md.id_movimiento AND ult.id_activo = md.id_activo) mov ON mov.id_activo = ac.id_activo
WHERE ac.estado_activo = '1' AND
      (_filtro_categoria IS NULL OR ac.id_categoria = _filtro_categoria) AND
      (_filtro_responsable IS NULL OR ac.id_administrativo = _filtro_responsable) AND
      (_filtro_calidad IS NULL OR ac.estado_mov = _filtro_calidad) AND
      (_filtro_sede IS NULL OR ac.id_sede = _filtro_sede) AND
      (_filtro_dependencia IS NULL OR ac.id_dependencia = _filtro_dependencia) AND
      (_filtro_estado IS NULL OR ac.estado = _filtro_estado)
ORDER BY ac.fecha_registro DESC;
END$$

-- Actualiza spu_activo_listar_con_garantia con filtro estado_activo
DROP PROCEDURE IF EXISTS spu_activo_listar_con_garantia$$
CREATE PROCEDURE spu_activo_listar_con_garantia()
BEGIN
    SELECT ac.id_activo, ac.id_administrativo, ac.id_categoria, ac.id_dependencia, ac.id_sede, ac.foto,
        ac.cod_patrimonial, c.nombre_categoria, CONCAT(ac.marca, ' / ', ac.modelo) AS marca_modelo,
        s.nombre_sede, d.nombre_dependencia,
        CONCAT(p.per_nombre, ' ', p.per_apepat, ' ', p.per_apemat) AS npersona,
        ac.estado, ac.fecha_adquisicion, ac.fecha_registro, ac.observacion, ac.serie, ac.estado_mov,
        COALESCE(gar.estado_garantia, 'SIN_GARANTIA') AS estado_garantia,
        gar.fecha_fin AS garantia_fecha_fin, gar.proveedor AS garantia_proveedor,
        mov.tipo_mov AS ultimo_movimiento
    FROM activo ac
    LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
    LEFT JOIN sede s ON ac.id_sede = s.id_sede
    LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
    LEFT JOIN dependencia d ON ac.id_dependencia = d.id_dependencia
    LEFT JOIN persona p ON a.id_persona = p.id_persona
    LEFT JOIN (SELECT g.id_activo, CASE WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA' WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER' ELSE 'VIGENTE' END AS estado_garantia, g.fecha_fin, g.proveedor FROM garantia g INNER JOIN (SELECT id_activo, MAX(id_garantia) AS id_garantia FROM garantia GROUP BY id_activo) ult_g ON ult_g.id_garantia = g.id_garantia) gar ON gar.id_activo = ac.id_activo
    LEFT JOIN (SELECT m.id_activo, m.tipo_mov FROM movimiento m INNER JOIN (SELECT MAX(id_movimiento) AS id_movimiento, id_activo FROM movimiento GROUP BY id_activo) lm ON lm.id_movimiento = m.id_movimiento) mov ON mov.id_activo = ac.id_activo
    WHERE ac.estado_activo = '1'
    ORDER BY ac.fecha_registro DESC;
END$$

-- Actualiza spu_activo_filtrar_con_garantia con filtro estado_activo
DROP PROCEDURE IF EXISTS spu_activo_filtrar_con_garantia$$
CREATE PROCEDURE spu_activo_filtrar_con_garantia(
    IN _filtro_categoria INT, IN _filtro_responsable INT, IN _filtro_calidad VARCHAR(20),
    IN _filtro_sede INT, IN _filtro_dependencia INT, IN _filtro_estado VARCHAR(20),
    IN _filtro_estado_garantia VARCHAR(20))
BEGIN
    SELECT ac.id_activo, ac.id_administrativo, ac.id_categoria, ac.id_dependencia, ac.id_sede, ac.foto,
        ac.cod_patrimonial, c.nombre_categoria, CONCAT(ac.marca, ' / ', ac.modelo) AS marca_modelo,
        s.nombre_sede, d.nombre_dependencia,
        CONCAT(p.per_nombre, ' ', p.per_apepat, ' ', p.per_apemat) AS npersona,
        ac.estado, ac.fecha_adquisicion, ac.fecha_registro, ac.observacion, ac.serie, ac.estado_mov,
        COALESCE(gar.estado_garantia, 'SIN_GARANTIA') AS estado_garantia,
        gar.fecha_fin AS garantia_fecha_fin, gar.proveedor AS garantia_proveedor,
        mov.tipo_mov AS ultimo_movimiento
    FROM activo ac
    LEFT JOIN categoria c ON ac.id_categoria = c.id_categoria
    LEFT JOIN sede s ON ac.id_sede = s.id_sede
    LEFT JOIN administrativo a ON ac.id_administrativo = a.id_administrativo
    LEFT JOIN dependencia d ON ac.id_dependencia = d.id_dependencia
    LEFT JOIN persona p ON a.id_persona = p.id_persona
    LEFT JOIN (SELECT g.id_activo, CASE WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA' WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER' ELSE 'VIGENTE' END AS estado_garantia, g.fecha_fin, g.proveedor FROM garantia g INNER JOIN (SELECT id_activo, MAX(id_garantia) AS id_garantia FROM garantia GROUP BY id_activo) ult_g ON ult_g.id_garantia = g.id_garantia) gar ON gar.id_activo = ac.id_activo
    LEFT JOIN (SELECT m.id_activo, m.tipo_mov FROM movimiento m INNER JOIN (SELECT MAX(id_movimiento) AS id_movimiento, id_activo FROM movimiento GROUP BY id_activo) lm ON lm.id_movimiento = m.id_movimiento) mov ON mov.id_activo = ac.id_activo
    WHERE ac.estado_activo = '1'
        AND (_filtro_categoria IS NULL OR ac.id_categoria = _filtro_categoria)
        AND (_filtro_responsable IS NULL OR ac.id_administrativo = _filtro_responsable)
        AND (_filtro_calidad IS NULL OR ac.estado_mov = _filtro_calidad)
        AND (_filtro_sede IS NULL OR ac.id_sede = _filtro_sede)
        AND (_filtro_dependencia IS NULL OR ac.id_dependencia = _filtro_dependencia)
        AND (_filtro_estado IS NULL OR ac.estado = _filtro_estado)
        AND (_filtro_estado_garantia IS NULL OR (_filtro_estado_garantia = 'SIN_GARANTIA' AND gar.id_activo IS NULL) OR gar.estado_garantia = _filtro_estado_garantia)
    ORDER BY ac.fecha_registro DESC;
END$$

-- Actualiza spu_garantia_resumen con filtro estado_activo
DROP PROCEDURE IF EXISTS spu_garantia_resumen$$
CREATE PROCEDURE spu_garantia_resumen()
BEGIN
    SELECT
        COALESCE(SUM(CASE WHEN estado_calc = 'VIGENTE' THEN 1 ELSE 0 END), 0) AS vigentes,
        COALESCE(SUM(CASE WHEN estado_calc = 'PROXIMO_A_VENCER' THEN 1 ELSE 0 END), 0) AS proximas_a_vencer,
        COALESCE(SUM(CASE WHEN estado_calc = 'VENCIDA' THEN 1 ELSE 0 END), 0) AS vencidas,
        COALESCE(SUM(CASE WHEN g.id_activo IS NULL THEN 1 ELSE 0 END), 0) AS sin_garantia
    FROM activo ac
    LEFT JOIN (SELECT g.id_activo, CASE WHEN g.fecha_fin < CURDATE() THEN 'VENCIDA' WHEN g.fecha_fin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'PROXIMO_A_VENCER' ELSE 'VIGENTE' END AS estado_calc FROM garantia g INNER JOIN (SELECT id_activo, MAX(id_garantia) AS id_garantia FROM garantia GROUP BY id_activo) ult ON ult.id_garantia = g.id_garantia) g ON g.id_activo = ac.id_activo
    WHERE ac.estado_activo = '1';
END$$

DELIMITER ;
