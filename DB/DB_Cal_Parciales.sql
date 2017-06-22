DROP VIEW IF EXISTS am_calificaciones_parciales;
CREATE VIEW `am_calificaciones_parciales` AS
-- ENTREGAS
SELECT 
c.per_aca AS per_aca,
b.idnumber AS codigo_curso,
b.fullname AS nombre_curso,
d.idnumber AS codigo_estudiante,
d.n_identidad AS identificacion,
a.itemname AS actividad,
FROM_UNIXTIME(f.allowsubmissionsfromdate) AS f_inicio,
FROM_UNIXTIME(f.duedate) AS f_final,
FROM_UNIXTIME(e.timemodified) AS f_presentacion,
f.grade AS nota_max,
e.finalgrade AS nota_obtenida
FROM mdl_grade_items a 
INNER JOIN mdl_course b ON b.id = a.courseid 
INNER JOIN am_usuario_curso c ON c.codigo_curso = b.idnumber AND c.moodle_rol_id = 5 
INNER JOIN mdl_user d ON d.idnumber = c.idnumber
LEFT JOIN mdl_grade_grades e ON e.itemid = a.id AND e.userid = d.id
INNER JOIN mdl_assign f ON f.id = a.iteminstance
WHERE a.grademax > 1 
AND a.itemmodule = 'assign'
AND a.itemname IS NOT NULL
UNION
-- QUIZ
SELECT 
c.per_aca AS per_aca,
b.idnumber AS codigo_curso,
b.fullname AS nombre_curso,
d.idnumber AS codigo_estudiante,
d.n_identidad AS identificacion,
a.itemname AS actividad,
FROM_UNIXTIME(f.timeopen) AS f_inicio,
FROM_UNIXTIME(f.timeclose) AS f_final,
FROM_UNIXTIME(e.timemodified) AS f_presentacion,
f.grade AS nota_max,
e.finalgrade AS nota_obtenida
FROM mdl_grade_items a 
INNER JOIN mdl_course b ON b.id = a.courseid 
INNER JOIN am_usuario_curso c ON c.codigo_curso = b.idnumber AND c.moodle_rol_id = 5
INNER JOIN mdl_user d ON d.idnumber = c.idnumber
LEFT JOIN mdl_grade_grades e ON e.itemid = a.id AND e.userid = d.id
INNER JOIN mdl_quiz f ON f.id = a.iteminstance
WHERE a.grademax > 1 
AND a.itemmodule = 'quiz'
AND a.itemname IS NOT NULL
UNION
-- FOROS
SELECT 
c.per_aca AS per_aca,
b.idnumber AS codigo_curso,
b.fullname AS nombre_curso,
d.idnumber AS codigo_estudiante,
d.n_identidad AS identificacion,
a.itemname AS actividad,
FROM_UNIXTIME(f.assesstimestart) AS f_inicio,
FROM_UNIXTIME(f.assesstimefinish) AS f_final,
FROM_UNIXTIME(e.timemodified) AS f_presentacion,
f.scale AS nota_max,
e.finalgrade AS nota_obtenida
FROM mdl_grade_items a 
INNER JOIN mdl_course b ON b.id = a.courseid 
INNER JOIN am_usuario_curso c ON c.codigo_curso = b.idnumber AND c.moodle_rol_id = 5 
INNER JOIN mdl_user d ON d.idnumber = c.idnumber
LEFT JOIN mdl_grade_grades e ON e.itemid = a.id AND e.userid = d.id
INNER JOIN mdl_forum f ON f.id = a.iteminstance
WHERE a.grademax > 1 
AND a.itemmodule = 'forum'
AND a.itemname IS NOT NULL
ORDER BY codigo_estudiante ASC;