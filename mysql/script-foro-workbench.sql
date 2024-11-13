-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Tema`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Tema` (
  `idTema` INT NOT NULL,
  `titulo` VARCHAR(45) NULL,
  `descripcion` VARCHAR(45) NULL,
  `fecha_creacion` VARCHAR(45) NULL,
  PRIMARY KEY (`idTema`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Usuario` (
  `idUsuario` INT NOT NULL,
  `nombre_usuario` VARCHAR(45) NULL,
  `contrasena` VARCHAR(45) NULL,
  `correo_electronico` VARCHAR(45) NULL,
  `fecha_registro` VARCHAR(45) NULL,
  `Tema_idTema` INT NOT NULL,
  PRIMARY KEY (`idUsuario`, `Tema_idTema`),
  INDEX `fk_Usuario_Tema_idx` (`Tema_idTema` ASC) VISIBLE,
  CONSTRAINT `fk_Usuario_Tema`
    FOREIGN KEY (`Tema_idTema`)
    REFERENCES `mydb`.`Tema` (`idTema`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Comentarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Comentarios` (
  `id_Comentarios` INT NOT NULL,
  `Contenido` VARCHAR(45) NULL,
  `Fecha_Comentario` VARCHAR(45) NULL,
  `Estado_Comentario` VARCHAR(45) NULL,
  `Tema_idTema` INT NOT NULL,
  `Usuario_idUsuario` INT NOT NULL,
  `Usuario_Tema_idTema` INT NOT NULL,
  PRIMARY KEY (`id_Comentarios`, `Tema_idTema`, `Usuario_idUsuario`, `Usuario_Tema_idTema`),
  INDEX `fk_Comentarios_Tema1_idx` (`Tema_idTema` ASC) VISIBLE,
  INDEX `fk_Comentarios_Usuario1_idx` (`Usuario_idUsuario` ASC, `Usuario_Tema_idTema` ASC) VISIBLE,
  CONSTRAINT `fk_Comentarios_Tema1`
    FOREIGN KEY (`Tema_idTema`)
    REFERENCES `mydb`.`Tema` (`idTema`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Comentarios_Usuario1`
    FOREIGN KEY (`Usuario_idUsuario` , `Usuario_Tema_idTema`)
    REFERENCES `mydb`.`Usuario` (`idUsuario` , `Tema_idTema`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Respuesta_Comentario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Respuesta_Comentario` (
  `idRespuesta_Comentario` INT NOT NULL,
  `Fecha_Respuesta` VARCHAR(45) NULL,
  `Respuesta_Comentariocol` VARCHAR(45) NULL,
  `Comentarios_id_Comentarios` INT NOT NULL,
  `Comentarios_Tema_idTema` INT NOT NULL,
  `Comentarios_Usuario_idUsuario` INT NOT NULL,
  `Comentarios_Usuario_Tema_idTema` INT NOT NULL,
  PRIMARY KEY (`idRespuesta_Comentario`, `Comentarios_id_Comentarios`, `Comentarios_Tema_idTema`, `Comentarios_Usuario_idUsuario`, `Comentarios_Usuario_Tema_idTema`),
  INDEX `fk_Respuesta_Comentario_Comentarios1_idx` (`Comentarios_id_Comentarios` ASC, `Comentarios_Tema_idTema` ASC, `Comentarios_Usuario_idUsuario` ASC, `Comentarios_Usuario_Tema_idTema` ASC) VISIBLE,
  CONSTRAINT `fk_Respuesta_Comentario_Comentarios1`
    FOREIGN KEY (`Comentarios_id_Comentarios` , `Comentarios_Tema_idTema` , `Comentarios_Usuario_idUsuario` , `Comentarios_Usuario_Tema_idTema`)
    REFERENCES `mydb`.`Comentarios` (`id_Comentarios` , `Tema_idTema` , `Usuario_idUsuario` , `Usuario_Tema_idTema`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
