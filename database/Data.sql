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

INSERT INTO `cms_layout` (`id_layout`, `name_layout`, `position_layout`, `code_layout`) VALUES
(1, 'Base Layout', 1, '');

--
-- Contenu de la table `cms_menu`
--

INSERT INTO `cms_menu` (`id_menu`, `key_menu`, `name_menu`) VALUES
(1, 'main', 'Main Menu');

--
-- Contenu de la table `cms_menu_item`
--

INSERT INTO `cms_menu_item` (`id_menu_item`, `id_menu`, `id_page`, `name_menu_item`, `order_menu_item`) VALUES
(1, 1, 2, 'Home', 1),
(2, 1, 1, 'Functionalities', 2),
(3, 1, 3, 'Download', 4);

--
-- Contenu de la table `cms_menu_template`
--


--
-- Contenu de la table `cms_module`
--

INSERT INTO `cms_module` (`id_module`, `name_module`, `key_module`) VALUES
(1, 'Simple text', 'TextModule'),
(2, 'Highlight', 'HighlightModule');

--
-- Contenu de la table `cms_module_page`
--

INSERT INTO `cms_module_page` (`id_module`, `id_page`, `order_module_page`, `data_module_page`) VALUES
(2, 1, 1, 'a:3:{s:5:"image";s:20:"bloc-logo-puzzle.png";s:5:"title";s:7:"MODULAR";s:7:"content";s:185:"You can create your own modules and integrate them into your pages. The idea in our CMS is a page is a module assembly. For each module in these pages, you can configure its parameters.";}'),
(2, 1, 2, 'a:3:{s:5:"image";s:18:"bloc-logo-star.png";s:5:"title";s:12:"CUSTOMIZABLE";s:7:"content";s:116:"You can create themes for the FrontEnd and the BackEnd. So you can completely customize your site with these themes.";}'),
(2, 1, 3, 'a:3:{s:5:"image";s:18:"bloc-logo-roue.png";s:5:"title";s:8:"POWERFUL";s:7:"content";s:119:"Designed and optimized with a cache for database bind, our CMS offers the best performance and simplicity for everyone.";}'),
(1, 2, 1, 'a:1:{s:7:"content";s:76:"Welcome to our CMS website ! <br /> Take a look to the functionalities page.";}'),
(1, 3, 1, 'a:1:{s:7:"content";s:120:"Download our CMS project in \r\n<a href="http://isic.lan/projects/appweb1-g3"> our project management web application </a>";}');

--
-- Contenu de la table `cms_module_template`
--


--
-- Contenu de la table `cms_page`
--

INSERT INTO `cms_page` (`id_page`, `id_layout`, `alias_page`, `title_page`, `description_page`, `keywords_page`, `robots_page`, `author_page`) VALUES
(1, 1, 'functionalities', 'Functionalities', '', '', '', ''),
(2, 1, 'home', 'Home', 'Home sweet home', '', '', ''),
(3, 1, 'download', 'Download', '', '', '', '');

--
-- Contenu de la table `cms_setting`
--

INSERT INTO `cms_setting` (`id_setting`, `key_setting`, `value_setting`) VALUES
(1, 'meta_name', 'CMS'),
(2, 'meta_favicon', ''),
(3, 'meta_description', 'Content Management System'),
(4, 'meta_keywords', 'CMS, powerful, beautiful, simple'),
(5, 'meta_robots', 'index, follow'),
(6, 'meta_author', 'Chavjoh');

--
-- Contenu de la table `cms_template`
--

INSERT INTO `cms_template` (`id_template`, `name_template`, `path_template`, `type_template`, `active_template`) VALUES
(1, 'FrontEnd', 'frontend', 'FRONTEND', '1'),
(2, 'BackEnd', 'backend', 'BACKEND', '1');

--
-- Contenu de la table `cms_template_position`
--


--
-- Contenu de la table `cms_user`
--

INSERT INTO `cms_user` (`id_user`, `login_user`, `password_user`, `name_user`, `surname_user`) VALUES
(1, 'admin', '7d517ea178031c5faefd22a9dbd0b08cbf32b8a6', 'Surname', 'Name');

--
-- Contenu de la table `cms_wrapper`
--

INSERT INTO `cms_wrapper` (`id_wrapper`, `key_wrapper`, `name_wrapper`, `description_wrapper`, `path_wrapper`) VALUES
(1, 'TimeWrapper', 'Time', 'Get current UTC time with {* TimeWrapper::current_utc *}', '');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
