
/*CREATE DATABASE registerlog;*/

/*CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    nombre_usuario VARCHAR(50) NOT NULL,
    contrasena VARCHAR(255) NOT NULL
);
*/

/* Tabla de comentarios */
/*CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tema VARCHAR(255) NOT NULL, -- Nombre del tema (partido) en el que se está comentando
    comentario TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);*/


/* Tabla de respuestas */
/*CREATE TABLE respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comentario_id INT NOT NULL,
    usuario_id INT NOT NULL,
    respuesta TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comentario_id) REFERENCES comentarios(id) ON DELETE CASCADE, -- Borra las respuestas automáticamente cuando se borra el comentario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
*/



/* Crear la tabla con el campo `id` */
/*CREATE TABLE comentarios_eliminados (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Añadir ID único
    usuario_id INT,
    tema VARCHAR(255),
    comentario TEXT,
    fecha TIMESTAMP,
    fecha_eliminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


DELIMITER $$*/
/* Crear un trigger para guardar el comentario eliminado 
CREATE TRIGGER guardar_comentario_eliminado
BEFORE DELETE ON comentarios
FOR EACH ROW
BEGIN
    INSERT INTO comentarios_eliminados (usuario_id, tema, comentario, fecha)
    VALUES (OLD.usuario_id, OLD.tema, OLD.comentario, OLD.fecha);
END $$

DELIMITER ;
*/

/*CREATE TABLE respuestas_eliminadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    comentario_id INT NOT NULL,  -- Esto ya no tiene una relación con la tabla comentarios
    respuesta TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_eliminacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);*/
  

/*DELIMITER $$
CREATE TRIGGER guardar_respuesta_eliminada
BEFORE DELETE ON respuestas
FOR EACH ROW
BEGIN
    INSERT INTO respuestas_eliminadas (usuario_id, comentario_id, respuesta, fecha)
    VALUES (OLD.usuario_id, OLD.comentario_id, OLD.respuesta, OLD.fecha);
END $$
DELIMITER ;*/


SELECT * FROM comentarios;
SELECT * FROM comentarios_eliminados;
SELECT * FROM respuestas;
SELECT * FROM respuestas_eliminadas;





