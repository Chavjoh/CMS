SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
 
-- -----------------------------------------------------
-- Table `[[prefix]]template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]template` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]template` (
  `id_template` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name_template` VARCHAR(250) NOT NULL ,
  `path_template` VARCHAR(250) NOT NULL ,
  `type_template` ENUM('FRONTEND', 'BACKEND') NOT NULL ,
  `active_template` ENUM('0', '1') NOT NULL ,
  PRIMARY KEY (`id_template`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]menu` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]menu` (
  `id_menu` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `key_menu` VARCHAR(250) NOT NULL ,
  `name_menu` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_menu`) ,
  UNIQUE INDEX `IDX_UQ_MENU_KEY` (`key_menu` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]template_position`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]template_position` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]template_position` (
  `id_template_position` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_template` INT UNSIGNED NOT NULL ,
  `key_template_position` VARCHAR(250) NOT NULL ,
  `name_template_position` VARCHAR(250) NOT NULL ,
  `description_template_position` TEXT NOT NULL ,
  PRIMARY KEY (`id_template_position`) ,
  UNIQUE INDEX `IDX_UQ_TEMPLATE_POSITION_KEY` (`key_template_position` ASC) ,
  INDEX `IDX_FK_TEMPLATE_POSITION_TEMPLATE` (`id_template` ASC) ,
  CONSTRAINT `FK_TEMPLATE_POSITION_TEMPLATE`
    FOREIGN KEY (`id_template` )
    REFERENCES `[[prefix]]template` (`id_template` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]menu_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]menu_template` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]menu_template` (
  `id_menu` INT UNSIGNED NOT NULL ,
  `id_template_position` INT UNSIGNED NOT NULL ,
  `order_menu_template` SMALLINT NOT NULL ,
  PRIMARY KEY (`id_menu`, `id_template_position`, `order_menu_template`) ,
  INDEX `IDX_FK_MENU_TEMPLATE_MENU` (`id_menu` ASC) ,
  INDEX `IDX_FK_MENU_TEMPLATE_POSITION` (`id_template_position` ASC) ,
  CONSTRAINT `FK_TEMPLATE_MENU_TEMPLATE`
    FOREIGN KEY (`id_menu` )
    REFERENCES `[[prefix]]menu` (`id_menu` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_TEMPLATE_MENU_TEMPLATE_POSITION`
    FOREIGN KEY (`id_template_position` )
    REFERENCES `[[prefix]]template_position` (`id_template_position` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]layout`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]layout` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]layout` (
  `id_layout` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name_layout` VARCHAR(45) NOT NULL ,
  `position_layout` SMALLINT UNSIGNED NOT NULL ,
  `code_layout` TEXT NOT NULL ,
  PRIMARY KEY (`id_layout`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]page` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]page` (
  `id_page` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_layout` INT UNSIGNED NULL ,
  `alias_page` VARCHAR(250) NOT NULL ,
  `title_page` VARCHAR(250) NOT NULL ,
  `description_page` VARCHAR(250) NOT NULL ,
  `keywords_page` VARCHAR(250) NOT NULL ,
  `robots_page` VARCHAR(250) NOT NULL ,
  `author_page` VARCHAR(250) NOT NULL ,
  PRIMARY KEY (`id_page`) ,
  INDEX `IDX_FK_PAGE_LAYOUT` (`id_layout` ASC) ,
  UNIQUE INDEX `IDX_UQ_PAGE_ALIAS` (`alias_page` ASC) ,
  CONSTRAINT `FK_PAGE_LAYOUT`
    FOREIGN KEY (`id_layout` )
    REFERENCES `[[prefix]]layout` (`id_layout` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]menu_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]menu_item` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]menu_item` (
  `id_menu_item` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_menu` INT UNSIGNED NOT NULL ,
  `id_page` INT UNSIGNED NOT NULL ,
  `name_menu_item` VARCHAR(250) NOT NULL ,
  `order_menu_item` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_menu_item`) ,
  INDEX `IDX_FK_MENU_ITEM_MENU` (`id_menu` ASC) ,
  INDEX `IDX_FK_MENU_ITEM_PAGE` (`id_page` ASC) ,
  CONSTRAINT `FK_MENU_ITEM_MENU`
    FOREIGN KEY (`id_menu` )
    REFERENCES `[[prefix]]menu` (`id_menu` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_MENU_ITEM_PAGE`
    FOREIGN KEY (`id_page` )
    REFERENCES `[[prefix]]page` (`id_page` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]wrapper`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]wrapper` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]wrapper` (
  `id_wrapper` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `key_wrapper` VARCHAR(250) NOT NULL ,
  `name_wrapper` VARCHAR(250) NOT NULL ,
  `description_wrapper` TEXT NOT NULL ,
  `path_wrapper` VARCHAR(250) NOT NULL ,
  PRIMARY KEY (`id_wrapper`) ,
  UNIQUE INDEX `IDX_UQ_WRAPPER_KEY` (`key_wrapper` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]module`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]module` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]module` (
  `id_module` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `key_module` VARCHAR(250) NOT NULL ,
  `name_module` VARCHAR(250) NOT NULL ,
  PRIMARY KEY (`id_module`) ,
  UNIQUE INDEX `IDX_UQ_KEY_MODULE` (`key_module` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]module_page`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]module_page` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]module_page` (
  `id_page` INT UNSIGNED NOT NULL ,
  `order_module_page` SMALLINT NOT NULL ,
  `id_module` INT UNSIGNED NOT NULL ,
  `data_module_page` TEXT NOT NULL ,
  PRIMARY KEY (`id_page`, `order_module_page`) ,
  INDEX `IDX_FK_MODULE_PAGE_PAGE` (`id_page` ASC) ,
  INDEX `IDX_FK_MODULE_PAGE_MODULE` (`id_module` ASC) ,
  CONSTRAINT `FK_MODULE_PAGE_MODULE`
    FOREIGN KEY (`id_module` )
    REFERENCES `[[prefix]]module` (`id_module` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_MODULE_PAGE_PAGE`
    FOREIGN KEY (`id_page` )
    REFERENCES `[[prefix]]page` (`id_page` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]user` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]user` (
  `id_user` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `login_user` VARCHAR(250) NOT NULL ,
  `password_user` VARCHAR(250) NOT NULL ,
  `name_user` VARCHAR(250) NOT NULL ,
  `surname_user` VARCHAR(250) NOT NULL ,
  PRIMARY KEY (`id_user`) ,
  UNIQUE INDEX `IDX_UQ_USER_LOGIN` (`login_user` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]setting`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]setting` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]setting` (
  `id_setting` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `key_setting` VARCHAR(250) NOT NULL ,
  `value_setting` VARCHAR(250) NOT NULL ,
  PRIMARY KEY (`id_setting`) ,
  UNIQUE INDEX `IDX_UQ_SETTING_KEY` (`key_setting` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `[[prefix]]module_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `[[prefix]]module_template` ;

CREATE  TABLE IF NOT EXISTS `[[prefix]]module_template` (
  `id_module` INT UNSIGNED NOT NULL ,
  `id_template_position` INT UNSIGNED NOT NULL ,
  `order_module_template` SMALLINT NOT NULL ,
  PRIMARY KEY (`id_module`, `id_template_position`, `order_module_template`) ,
  INDEX `IDX_FK_MODULE_TEMPLATE_TEMPLATE_POSITION` (`id_template_position` ASC) ,
  INDEX `IDX_FK_MODULE_TEMPLATE_MODULE` (`id_module` ASC) ,
  CONSTRAINT `FK_MODULE_TEMPLATE_MODULE`
    FOREIGN KEY (`id_module` )
    REFERENCES `[[prefix]]module` (`id_module` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FK_MODULE_TEMPLATE_TEMPLATE_POSITION`
    FOREIGN KEY (`id_template_position` )
    REFERENCES `[[prefix]]template_position` (`id_template_position` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
