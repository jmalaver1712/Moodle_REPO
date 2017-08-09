-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-06-2017 a las 07:34:50
-- Versión del servidor: 5.6.35
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `campusvi_md_aula0`
--

DELIMITER $$
--
-- Procedimientos
--

-- CALIFICACIONES --------------------------------------------------------
DROP PROCEDURE IF EXISTS `calificaciones`$$
CREATE PROCEDURE `calificaciones` ()  BEGIN 

REPLACE INTO am_calificaciones_finales (per_aca, codigo_curso, nombre_curso, codigo_estudiante, nota_max, nota_obtenida, nota_final) 
(SELECT per_aca, codigo_curso, nombre_curso, codigo_estudiante, 500, IF(SUM(nota_obtenida) >= 500, 500, ROUND(SUM(nota_obtenida),2)), ROUND(((SUM(nota_obtenida)*5)/500),2) AS nota_final
FROM am_calificaciones_parciales 
GROUP BY per_aca, codigo_curso , codigo_estudiante);

END$$

-- CREAR USUARIOS MASTER ------------------------------------------------
DROP PROCEDURE IF EXISTS `estudiantes_master`$$
CREATE PROCEDURE `estudiantes_master`()
BEGIN

	INSERT IGNORE INTO mdl_user (auth,confirmed,mnethostid,username,password,idnumber,firstname,lastname,email,city,lang,calendartype,n_identidad)
	SELECT 'manual',1,1,username,md5(password),idnumber,firstname,lastname,email,city,lang,calendartype,n_identidad FROM am_usuario;

	INSERT INTO mdl_role_assignments (roleid, contextid, userid, timemodified, modifierid)
	SELECT 9, 1, id, UNIX_TIMESTAMP(now()), 2 FROM mdl_user;

	UPDATE am_usuario a,
	(SELECT id, idnumber, n_identidad FROM mdl_user) b
	SET a.id_moodle = b.id 
	WHERE a.idnumber = b.idnumber AND a.n_identidad = b.n_identidad;

END$$


-- CREAR USUARIOS ESCLAVO ------------------------------------------------
DROP PROCEDURE IF EXISTS `estudiantes_esclavo`$$
CREATE PROCEDURE `estudiantes_esclavo`()
BEGIN

	INSERT IGNORE INTO mdl_user (id,auth,confirmed,mnethostid,username,idnumber,firstname,lastname,email,city,lang,calendartype,n_identidad)
	SELECT DISTINCT a.id_moodle,
	   'mnet',
	   1,
	   b.id,
	   a.username,
	   a.idnumber,
	   a.firstname,
	   a.lastname,
	   a.email,
	   a.city,
	   a.lang,
	   a.calendartype,
	   a.n_identidad
	FROM am_usuario a,
	mdl_mnet_host b
	WHERE b.id > 2 AND b.deleted <> 1 GROUP BY a.id_moodle;

	INSERT INTO mdl_role_assignments (roleid, contextid, userid, timemodified, modifierid)
	SELECT 9, 1, id, UNIX_TIMESTAMP(now()), 2 FROM mdl_user;

END$$



DROP PROCEDURE IF EXISTS `insertar_cursos`$$
CREATE PROCEDURE `insertar_cursos` (IN `PERACA` BIGINT, IN `CAMPUS` INT)  BEGIN
DECLARE SECCIONES INTEGER DEFAULT 11;

	SET @token = ( SELECT comprobar_campus(CAMPUS) );
	IF @token = 1 THEN

		SET @new_index = (SELECT IFNULL (MAX(moodle_course_id)+1 , 2) FROM am_cursos );
		SET @sql = CONCAT('ALTER TABLE mdl_course AUTO_INCREMENT = ', @new_index);
		PREPARE st FROM @sql;
		EXECUTE st;


		INSERT IGNORE INTO mdl_course (fullname,shortname,category,visible,startdate,idnumber,summaryformat,format,newsitems,showgrades,showreports,maxbytes,enablecompletion,defaultgroupingid,timecreated,timemodified,visibleold,per_aca,groupmode,groupmodeforce)
		SELECT CONCAT(a.nombre_curso,' / ',b.descripcion), a.nombre_corto, a.id_categoria, '1', UNIX_TIMESTAMP(now()), a.codigo_curso, '1', 'topics', '5', '1', '0', '0', '0', '0', UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()), '1', b.id, '1', '1' 
		FROM am_cursos a, am_per_aca b, am_numero_campus c
		WHERE a.per_aca = b.id AND a.moodle_course_id IS NULL AND
		c.per_aca = b.id AND c.codigo_curso = a.codigo_curso
		AND a.n_campus = c.n_campus
		AND b.id = PERACA AND c.n_campus = CAMPUS;


		INSERT IGNORE INTO mdl_context (contextlevel,instanceid,path,depth)
		SELECT '50', d.id ,NULL, '3'
		FROM am_cursos a, am_per_aca b, am_numero_campus c, mdl_course d
		WHERE a.per_aca = b.id AND a.moodle_course_id IS NULL 
		AND c.per_aca = b.id AND c.codigo_curso = a.codigo_curso
		AND d.idnumber = a.codigo_curso 
		AND a.n_campus = c.n_campus
		AND b.id = PERACA AND c.n_campus = CAMPUS;

		
		DROP TABLE IF EXISTS am_temp_cursos; 
		CREATE TABLE am_temp_cursos
		(
		id_course    int(11) NOT NULL,
		n_campus     int(11) NOT NULL,
		id_context   int(11) NOT NULL,
		id_enrol     int(11) NOT NULL DEFAULT '0',
		UNIQUE KEY`llave`(`id_course`, `id_context`)
		);

		INSERT IGNORE INTO am_temp_cursos (id_course, id_context, n_campus)
		SELECT d.id, e.id, c.n_campus
		FROM am_cursos           a,
		am_per_aca          b,
		am_numero_campus    c,
		mdl_course          d,
		mdl_context         e
		WHERE     a.per_aca = b.id
		AND a.moodle_course_id IS NULL
		AND c.per_aca = b.id
		AND c.codigo_curso = a.codigo_curso
		AND d.idnumber = a.codigo_curso
		AND e.contextlevel = 50
		AND e.instanceid = d.id
		AND e.depth = 3
		AND a.n_campus = c.n_campus
		AND b.id = PERACA
		AND c.n_campus = CAMPUS;

		UPDATE mdl_context AS T1,
		(SELECT id_context, CONCAT('/1/3/',id_context) AS new_path
		FROM am_temp_cursos) AS T2 
		SET T1.path =T2.new_path
		WHERE T1.id = T2.id_context;

		INSERT IGNORE INTO mdl_course_format_options (courseid,format,sectionid,name,value) SELECT id_course, 'topics', '0', 'numsections', SECCIONES FROM am_temp_cursos;
		INSERT IGNORE INTO mdl_course_format_options (courseid,format,sectionid,name,value) SELECT id_course, 'topics', '0', 'hiddensections', '1' FROM am_temp_cursos;
		INSERT IGNORE INTO mdl_course_format_options (courseid,format,sectionid,name,value) SELECT id_course, 'topics', '0', 'coursedisplay', '0' FROM am_temp_cursos;

		WHILE SECCIONES > 0 DO
		INSERT IGNORE INTO mdl_course_sections (course,section,name,summaryformat,visible,availability) SELECT id_course, SECCIONES, NULL, '1', '0', NULL FROM am_temp_cursos;
		SET SECCIONES = SECCIONES - 1;
		END WHILE;

		INSERT IGNORE INTO mdl_enrol (enrol, status, courseid, sortorder, expirythreshold, roleid, timecreated, timemodified)
		SELECT 'manual', '0', id_course, '0', '86400', '5', UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()) FROM am_temp_cursos;

		INSERT IGNORE INTO mdl_enrol (enrol, status, courseid, sortorder, expirythreshold, roleid, timecreated, timemodified, customint1, customint2, customint3, customint4, customint5, customint6) 
		SELECT 'db', 0, id_course, 1, 86400, 5, UNIX_TIMESTAMP(now()), UNIX_TIMESTAMP(now()), 0, 0, 0, 1, 0, 1 FROM am_temp_cursos;

		UPDATE am_temp_cursos AS T1,
		(SELECT a.id as id_enrol, a.courseid as id_course
		FROM mdl_enrol a, am_temp_cursos b WHERE a.courseid = b.id_course AND a.enrol = 'manual') AS T2 
		SET T1.id_enrol =T2.id_enrol
		WHERE T1.id_course = T2.id_course;

		-- INSERT IGNORE INTO mdl_block_positions (id, blockinstanceid, contextid, pagetype, visible, region, weight)
		-- SELECT '1', '5', id_context, 'course-view-topics', '0', 'side-pre', '1' FROM am_temp_cursos;

		-- INSERT IGNORE INTO mdl_block_positions (id, blockinstanceid, contextid, pagetype, visible, region, weight)
		-- SELECT '1', '4', id_context, 'course-view-topics', '0', 'side-pre', '1' FROM am_temp_cursos;

		UPDATE am_cursos AS T1,
		(SELECT a.*, b.idnumber as codigo_curso
			FROM am_temp_cursos a, mdl_course b, am_numero_campus c 
			WHERE a.id_course = b.id AND a.n_campus = c.n_campus AND c.n_campus = CAMPUS) 
		AS T2 
		SET T1.moodle_course_id  = T2.id_course,
		T1.moodle_contex_id  = T2.id_context, 
		T1.moodle_enrol_id = T2.id_enrol 
		WHERE T1.codigo_curso = T2.codigo_curso AND T1.n_campus = T2.n_campus;

		UPDATE mdl_course e,
		(SELECT @n := (a.category*10000), b.moodle_course_id FROM mdl_course a, am_cursos b WHERE b.moodle_course_id = a.id) m
		SET e.sortorder = @n := @n + 1 
		WHERE e.id = m.moodle_course_id;

		DROP TABLE am_temp_cursos;

	END IF;  

END$$


-- MATRICULAR ESTUDIANTES ----------------------------------------------- 
DROP PROCEDURE IF EXISTS `matricular_estudiantes`$$
CREATE PROCEDURE `matricular_estudiantes`(IN `PERACA` BIGINT, IN `CAMPUS` INT)
BEGIN

	SET @token = ( SELECT comprobar_campus(CAMPUS) );
	IF @token = 1 THEN

		UPDATE am_cursos AS T1, 
		(SELECT b.id, b.courseid FROM mdl_enrol b WHERE b.enrol = 'manual') AS T2 
		SET T1.moodle_enrol_id = T2.id 
		WHERE T1.moodle_course_id = T2.courseid;

		DROP TABLE IF EXISTS am_temp_matriculacion; 
		CREATE TABLE am_temp_matriculacion (
		id_temp int(11) NOT NULL AUTO_INCREMENT, 
		moodle_rol_id int(11) NOT NULL, 
		moodle_contex_id int(11) NOT NULL, 
		moodle_enrol_id int(11) NOT NULL, 
		id_moodle int(11) NOT NULL, 
		time bigint(20) NOT NULL,
		PRIMARY KEY (`id_temp`), 
		UNIQUE KEY `role_assignments` (`moodle_rol_id`, `moodle_contex_id`, `id_moodle`, `time`),
		KEY `user_enrolments` (`moodle_enrol_id`, `id_moodle`, `time`))
		ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

		INSERT IGNORE INTO am_temp_matriculacion (moodle_rol_id, moodle_contex_id, moodle_enrol_id, id_moodle, time)
		SELECT 
		a.moodle_rol_id, 
		c.moodle_contex_id, 
		c.moodle_enrol_id, 
		d.id_moodle,
		UNIX_TIMESTAMP(now())
		FROM am_usuario_curso    a,
		mdl_course          b,
		am_cursos           c,
		am_usuario          d
		WHERE     a.codigo_curso = b.idnumber
		AND a.per_aca = b.per_aca
		AND a.codigo_curso = c.codigo_curso
		AND b.idnumber = c.codigo_curso
		AND d.idnumber = a.idnumber 
		AND a.fecha = 0
		AND a.estado = 'A'
		AND a.n_campus = c.n_campus
		AND a.n_campus = CAMPUS
		AND a.per_aca = PERACA;


		INSERT IGNORE INTO mdl_role_assignments (roleid, contextid, userid, timemodified, modifierid)
		SELECT moodle_rol_id, moodle_contex_id, id_moodle, time, '2' FROM am_temp_matriculacion;

		INSERT IGNORE INTO mdl_user_enrolments (enrolid, userid, timestart, timeend, modifierid, timecreated, timemodified)
		SELECT moodle_enrol_id, id_moodle, time, '0', '2', time, time FROM am_temp_matriculacion;

		DROP TABLE am_temp_matriculacion; 

		INSERT IGNORE INTO mdl_groups (courseid, idnumber, name, timecreated, timemodified, per_aca, description, descriptionformat)
		SELECT DISTINCT b.moodle_course_id,
		CONCAT(b.codigo_curso, "_", a.grupo),
		CONCAT(b.nombre_corto, "_", a.grupo),
		UNIX_TIMESTAMP(now()),
		UNIX_TIMESTAMP(now()),
		a.per_aca, '', 1
		FROM am_usuario_curso a, am_cursos b, mdl_course c
		WHERE     b.codigo_curso = a.codigo_curso
		AND c.idnumber = b.codigo_curso
		AND a.n_campus = b.n_campus
		AND a.n_campus = CAMPUS
		AND a.per_aca = PERACA;


		INSERT IGNORE INTO mdl_groups_members (groupid, userid, timeadded)
		SELECT 
		c.id,
		b.id,
		UNIX_TIMESTAMP(now())
		FROM am_usuario_curso a , mdl_user b, mdl_groups c, mdl_course d
		WHERE a.idnumber = b.idnumber 
		AND a.estado = 'A'
		AND a.fecha = 0
		AND a.codigo_curso = d.idnumber
		AND c.idnumber = CONCAT(a.codigo_curso,"_",a.grupo)
		AND a.n_campus = CAMPUS
		AND a.per_aca = PERACA;

		UPDATE am_usuario_curso AS T1,
		(SELECT b.idnumber, b.per_aca FROM mdl_course b) AS T2 
		SET T1.fecha = UNIX_TIMESTAMP(now())
		WHERE T1.codigo_curso = T2.idnumber AND T1.per_aca = T2.per_aca AND T1.n_campus = CAMPUS AND T1.fecha = 0;

	END IF;  

END$$


-- NOVEDADES DE MATRICULACION ----------------------------------------------- 
DROP PROCEDURE IF EXISTS `novedades_matriculacion`$$
CREATE PROCEDURE `novedades_matriculacion`(IN `PERACA` INT, IN `CAMPUS` INT)
BEGIN

	SET @token = ( SELECT comprobar_campus(CAMPUS) );
	IF @token = 1 THEN

		UPDATE am_cursos AS T1, 
		(SELECT b.id, b.courseid FROM mdl_enrol b WHERE b.enrol = 'manual') AS T2 
		SET T1.moodle_enrol_id = T2.id 
		WHERE T1.moodle_course_id = T2.courseid;

		-- MATRICULACION 
		DROP TABLE IF EXISTS am_temp_novedades; 
		CREATE TABLE am_temp_novedades (
		id_user_enrolments int(11) NOT NULL, 
		id_role_assignments int(11) NOT NULL, 
		KEY `id_user_enrolments` (`id_user_enrolments`),
		KEY `id_role_assignments` (`id_role_assignments`));

		INSERT IGNORE INTO am_temp_novedades (id_user_enrolments, id_role_assignments)
		SELECT d.id AS id_user_enrolments, e.id AS id_role_assignments
		FROM am_usuario_curso        a,
		mdl_user                b,
		am_cursos               c,
		mdl_user_enrolments     d,
		mdl_role_assignments    e
		WHERE     
		a.idnumber = b.idnumber
		AND a.codigo_curso = c.codigo_curso
		AND a.per_aca = c.per_aca
		AND d.userid = b.id
		AND c.moodle_enrol_id = d.enrolid
		AND e.userid = b.id
		AND (a.moodle_rol_id <> e.roleid OR a.estado <> 'A')
		AND c.n_campus = a.n_campus
		AND e.id <> 9
		AND a.fecha = 0
		AND a.n_campus = CAMPUS
		AND a.per_aca = PERACA;

		DELETE FROM mdl_user_enrolments WHERE id IN (SELECT id_user_enrolments FROM am_temp_novedades);
		DELETE FROM mdl_role_assignments WHERE id IN (SELECT id_role_assignments FROM am_temp_novedades);

		DROP TABLE am_temp_novedades;

		-- GRUPOS 
		CREATE TABLE am_temp_grupos (id_reg int(11) NOT NULL);
		ALTER TABLE am_temp_grupos ADD PRIMARY KEY (id_reg); 

		INSERT IGNORE INTO am_temp_grupos (id_reg)
		SELECT e.id
		FROM am_usuario_curso      a,
		am_cursos             b,
		mdl_groups            c,
		am_usuario            d,
		mdl_groups_members    e
		WHERE     
		(a.fecha = 0 OR a.estado <> 'A')
		AND b.codigo_curso = a.codigo_curso
		AND c.courseid = b.moodle_course_id
		AND a.idnumber = d.idnumber
		AND e.groupid = c.id
		AND e.userid = d.id_moodle
		AND a.n_campus = b.n_campus
		AND a.n_campus = CAMPUS
		AND a.per_aca = PERACA;

		DELETE FROM mdl_groups_members WHERE id IN (SELECT id_reg FROM am_temp_grupos);

		DROP TABLE am_temp_grupos;

	END IF;
	
END$$

-- COMPROBAR CAMPUS ----------------------------------------------- 
DROP FUNCTION IF EXISTS `comprobar_campus`$$
CREATE FUNCTION comprobar_campus(CAMPUS INT)
RETURNS INT(11)
BEGIN

  DECLARE AUX_CAMPUS INT;
  
  SELECT a.id  INTO AUX_CAMPUS
  FROM am_campus a, mdl_mnet_host b
  WHERE b.id = 1 AND b.wwwroot = a.url_master;
  
  IF AUX_CAMPUS = CAMPUS THEN
  RETURN 1;
  ELSE 
  RETURN 0;
  END IF;
  
END$$

DELIMITER ;