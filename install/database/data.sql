-- phpMyAdmin SQL Dump
-- version 3.3.9.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Dim 30 Juin 2013 à 23:31
-- Version du serveur: 5.5.9
-- Version de PHP: 5.3.5

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Base de données: `appweb_cms`
--

--
-- Contenu de la table `cms_layout`
--

INSERT INTO `[[prefix]]layout` (`id_layout`, `name_layout`, `position_layout`, `code_layout`) VALUES
(1, 'Base Layout', 1, '');

--
-- Contenu de la table `cms_menu`
--

INSERT INTO `[[prefix]]menu` (`id_menu`, `key_menu`, `name_menu`) VALUES
(1, 'main', 'Main Menu');

--
-- Contenu de la table `cms_menu_item`
--

INSERT INTO `[[prefix]]menu_item` (`id_menu_item`, `id_menu`, `id_page`, `name_menu_item`, `order_menu_item`) VALUES
(1, 1, 1, 'Home', 1);

--
-- Contenu de la table `cms_menu_template`
--


--
-- Contenu de la table `cms_module`
--

INSERT INTO `[[prefix]]module` (`id_module`, `name_module`, `key_module`) VALUES
(1, 'Simple text', 'TextModule');

--
-- Contenu de la table `cms_module_page`
--

INSERT INTO `[[prefix]]module_page` (`id_module`, `id_page`, `order_module_page`, `data_module_page`) VALUES
(1, 1, 1, 'a:1:{s:7:"content";s:37:"Welcome to your CMS powered website !";}');

--
-- Contenu de la table `cms_module_template`
--


--
-- Contenu de la table `cms_page`
--

INSERT INTO `[[prefix]]page` (`id_page`, `id_layout`, `alias_page`, `title_page`, `description_page`, `keywords_page`, `robots_page`, `author_page`) VALUES
(1, 1, 'home', 'Home', 'Home sweet home', '', '', '');

--
-- Contenu de la table `cms_setting`
--

INSERT INTO `[[prefix]]setting` (`id_setting`, `key_setting`, `value_setting`) VALUES
(1, 'meta_name', '[[information-name]]'),
(2, 'meta_favicon', ''),
(3, 'meta_description', '[[information-description]]'),
(4, 'meta_keywords', '[[information-keywords]]'),
(5, 'meta_robots', 'index, follow'),
(6, 'meta_author', '');

--
-- Contenu de la table `cms_template`
--

INSERT INTO `[[prefix]]template` (`id_template`, `name_template`, `path_template`, `type_template`, `active_template`) VALUES
(1, 'FrontEnd', 'frontend', 'FRONTEND', '1'),
(2, 'BackEnd', 'backend', 'BACKEND', '1');

--
-- Contenu de la table `cms_template_position`
--


--
-- Contenu de la table `cms_user`
--

INSERT INTO `[[prefix]]user` (`id_user`, `login_user`, `password_user`, `name_user`, `surname_user`) VALUES
(1, '[[administration-user]]', '[[administration-password]]', 'Surname', 'Name');

--
-- Contenu de la table `cms_wrapper`
--

INSERT INTO `[[prefix]]wrapper` (`id_wrapper`, `key_wrapper`, `name_wrapper`, `description_wrapper`, `path_wrapper`) VALUES
(1, 'TimeWrapper', 'Time', 'Get current UTC time with {* TimeWrapper::current_utc *}', '');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
