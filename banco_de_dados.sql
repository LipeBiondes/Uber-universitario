SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `pessoa` DEFAULT CHARACTER SET utf8mb4 ;
USE `pessoa`;

-- -----------------------------------------------------
-- Table `pessoa`.`pessoa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pessoa`.`pessoa` (
  `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id de controle',
  `nome` VARCHAR(50) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'nome da pessoa',
  `sexo` VARCHAR(1) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'sexo da pessoa',
  `data_nascimento` DATE NOT NULL COMMENT 'data de nascimento da pessoa',
  `imagem` BLOB NOT NULL COMMENT 'imagem da pessoa',
  `telefone` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL COMMENT 'Telefone para contato',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- -----------------------------------------------------
-- Table `pessoa`.`carona`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pessoa`.`carona` (
  `id` SMALLINT(2) NOT NULL AUTO_INCREMENT,
  `tipo` ENUM('NENHUM', 'CARRO', 'MOTO', '(PÉ) COMPANHIA', 'ÔNIBUS', 'VAN', 'BICICLETA') NOT NULL DEFAULT 'NENHUM',
  `idPessoa` INT(4) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `idPessoa`),
  INDEX `fk_carona_pessoa1_idx` (`idPessoa` ASC),
  CONSTRAINT `fk_carona_pessoa1`
    FOREIGN KEY (`idPessoa`)
    REFERENCES `pessoa`.`pessoa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4;

-- -----------------------------------------------------
-- Table `pessoa`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pessoa`.`usuario` (
  `id` INT(4) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id de controle',
  `senha` VARCHAR(32) NOT NULL COMMENT 'senha do usuário, salva em md5',
  `email` VARCHAR(64) NOT NULL COMMENT 'e-mail do usuário',
  `idPessoa` INT(4) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idPessoa` (`idPessoa` ASC),
  UNIQUE INDEX `email` (`email` ASC),
  CONSTRAINT `usuario_ibfk_1`
    FOREIGN KEY (`idPessoa`)
    REFERENCES `pessoa`.`pessoa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8mb4;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
