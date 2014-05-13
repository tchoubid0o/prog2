-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Sam 10 Mai 2014 à 15:45
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `virolle`
--
CREATE DATABASE IF NOT EXISTS `virolle` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `virolle`;

-- --------------------------------------------------------

--
-- Structure de la table `accessociete`
--

CREATE TABLE IF NOT EXISTS `accessociete` (
  `ref` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idSociete` int(11) NOT NULL,
  PRIMARY KEY (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `accessociete`
--

INSERT INTO `accessociete` (`ref`, `idUser`, `idSociete`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idCategorie` int(11) NOT NULL AUTO_INCREMENT,
  `idSociete` int(11) NOT NULL,
  `idParent` int(11) NOT NULL,
  `libelleCategorie` varchar(250) NOT NULL,
  `codeCat` varchar(15) NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `idSociete`, `idParent`, `libelleCategorie`, `codeCat`) VALUES
(1, 1, 0, 'Catégorie1', 'BC'),
(2, 1, 0, 'Catégorie2', 'BL'),
(3, 1, 1, 'SsCat1', 'CAN'),
(4, 1, 2, 'SsCat2', 'CLF');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idSociete` int(11) NOT NULL,
  `dateCommande` datetime NOT NULL,
  `dateLivraison` varchar(25) NOT NULL,
  `commentOrder` text NOT NULL,
  `keyOrder` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `orderdetails`
--

CREATE TABLE IF NOT EXISTS `orderdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idOrder` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `unitPrice` decimal(10,0) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE IF NOT EXISTS `panier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `idProduit` int(11) NOT NULL,
  `idSociete` int(11) NOT NULL,
  `qteProduit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `panier`
--

INSERT INTO `panier` (`id`, `idUser`, `idProduit`, `idSociete`, `qteProduit`) VALUES
(1, 1, 34, 1, 12);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `idSociete` int(11) NOT NULL,
  `idCategorie` varchar(25) NOT NULL,
  `prixProduit` decimal(10,2) NOT NULL,
  `minQte` int(11) NOT NULL,
  `quantiteProduit` int(11) NOT NULL DEFAULT '9999',
  `imgProduit` varchar(250) NOT NULL DEFAULT '1393110376_Picture.png',
  `libelleProduit` varchar(250) NOT NULL,
  `codeProduit` varchar(50) NOT NULL,
  `barCodeProduit` varchar(50) NOT NULL,
  PRIMARY KEY (`idProduit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5229 ;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`idProduit`, `idSociete`, `idCategorie`, `prixProduit`, `minQte`, `quantiteProduit`, `imgProduit`, `libelleProduit`, `codeProduit`, `barCodeProduit`) VALUES
(1, 1, 'BC', '0.73', 6, 9999, '1393110376_Picture.png', '6 Boutons en cocotier diam cm 3', 'BC001', '8024273035905'),
(2, 1, 'BC', '0.61', 6, 9999, '1393110376_Picture.png', '4 Boutons en cocotier   diam 3,5', 'BC002', '8024273035912'),
(3, 1, 'BC', '0.79', 6, 9999, '1393110376_Picture.png', '4 Boutons en cocotier carrés cm 3,5x3,5', 'BC003', '8024273035929'),
(4, 1, 'BC', '0.43', 6, 9999, '1393110376_Picture.png', '2 Boutons en cocotier  diam cm 5', 'BC005', '8024273035943'),
(5, 1, 'BC', '0.79', 6, 9999, '1393110376_Picture.png', '8 Boutons en cocotier carré cm 1,5x1,5', 'BC006', '8024273037381'),
(6, 1, 'BC', '0.91', 6, 9999, '1393110376_Picture.png', '4 Boutons en cocotier à fleur cm 3,5', 'BC007', '8024273037398'),
(7, 1, 'BL', '0.79', 6, 9999, '1393110376_Picture.png', '8 Boutons en bois à petites fleurs diam cm 3', 'BL001', '8024273035837'),
(8, 1, 'BL', '1.25', 6, 9999, '1393110376_Picture.png', '12 Boutons en bois lisses diam cm 1,8', 'BL002', '8024273035844'),
(9, 1, 'BL', '0.48', 6, 9999, '1393110376_Picture.png', '8 Boutons en bois  lisses avec borde diam cm 2,5', 'BL003', '8024273035851'),
(10, 1, 'BL', '0.48', 6, 9999, '1393110376_Picture.png', '6 Boutons en bois  avec borde lisses diam 3,5', 'BL004', '8024273035868'),
(11, 1, 'BL', '0.48', 6, 9999, '1393110376_Picture.png', '4 Boutons en bois lisses  diam cm 3,5', 'BL005', '8024273035875'),
(12, 1, 'BL', '0.36', 6, 9999, '1393110376_Picture.png', '2 Boutons en bois avec borde diam cm 5', 'BL006', '8024273035882'),
(13, 1, 'BL', '0.36', 6, 9999, '1393110376_Picture.png', '2 Boutons en bois lisses diam cm 5', 'BL007', '8024273035899'),
(14, 1, 'BL', '1.16', 6, 9999, '1393110376_Picture.png', '14 boutons nounours et fleurs assorties', 'BL008', '8024273062840'),
(15, 1, 'BL', '1.16', 6, 9999, '1393110376_Picture.png', '12 boutons sujets nature assortis ', 'BL009', '8024273062857'),
(16, 1, 'BL', '1.16', 6, 9999, '1393110376_Picture.png', '12 boutons sujets bébés assortis ', 'BL010', '8024273062864'),
(17, 1, 'BL', '1.16', 6, 9999, '1393110376_Picture.png', '12 boutons chats et coeurs assortis ', 'BL011', '8024273062871'),
(18, 1, 'BL', '1.31', 6, 9999, '1393110376_Picture.png', '14 boutons ronds point de piqure assortis 2 mésures', 'BL012', '8024273062888'),
(19, 1, 'BL', '1.31', 6, 9999, '1393110376_Picture.png', '10 boutons fleur assortis 2 mésures ', 'BL013', '8024273062895'),
(20, 1, 'BL', '1.31', 6, 9999, '1393110376_Picture.png', '10 boutons coeur assortis 2 mésures ', 'BL014', '8024273062901'),
(21, 1, 'BL', '1.31', 6, 9999, '1393110376_Picture.png', '18 boutons ronds assortis 3 mésures', 'BL015', '8024273062918'),
(22, 1, 'BL', '1.31', 6, 9999, '1393110376_Picture.png', '10 boutons formes mixtes 2 mésures ', 'BL016', '8024273062925'),
(23, 1, 'BL', '1.05', 6, 9999, '1393110376_Picture.png', 'Conf. 14 boutons country', 'BL017', '8024273167040'),
(24, 1, 'BL', '1.05', 6, 9999, '1393110376_Picture.png', 'Conf. 14 boutons Home sweet home', 'BL018', '8024273167057'),
(25, 1, 'BL', '1.05', 6, 9999, '1393110376_Picture.png', 'Conf. 14 boutons Bébé', 'BL019', '8024273167064'),
(26, 1, 'BLP', '2.29', 6, 9999, '1393110376_Picture.png', '66 boutons assortis nuance rose-vert ', 'BLP001', '8024273054166'),
(27, 1, 'BLP', '2.29', 6, 9999, '1393110376_Picture.png', '66 boutons assortis nuance bleu clair - beige', 'BLP002', '8024273054173'),
(28, 1, 'BLP', '2.29', 6, 9999, '1393110376_Picture.png', '66 boutons assortis nuance jaune-orange ', 'BLP003', '8024273054180'),
(29, 1, 'BLP', '1.31', 6, 9999, '1393110376_Picture.png', '15 Boutons mésures assorties - nuances rose', 'BLP004', '8024273062932'),
(30, 1, 'BLP', '1.31', 6, 9999, '1393110376_Picture.png', '15 Boutons mésures assorties - nuances lilas', 'BLP005', '8024273062949'),
(31, 1, 'BLP', '1.31', 6, 9999, '1393110376_Picture.png', '15 Boutons mésures assorties - nuances vertes', 'BLP006', '8024273062956'),
(32, 1, 'BLP', '1.31', 6, 9999, '1393110376_Picture.png', '15 Boutons mésures assorties - nuances bleu clair ', 'BLP007', '8024273062963'),
(33, 1, 'BSTDJP', '0.39', 12, 9999, '1393110376_Picture.png', 'Sac petit Toile de Joui cm. 12x5x15 h.', 'BSTDJP', '8024273050915'),
(34, 1, 'CAN', '0.39', 12, 9999, '1393110376_Picture.png', 'Bougie cylindre cm. 4,5x5h.', 'CAN02A', '8024273055682'),
(35, 1, 'CAN', '0.99', 6, 9999, '1393110376_Picture.png', 'Bougie ronde grande diam cm. 8', 'CAN04A', '8024273055705'),
(36, 1, 'CAN', '0.39', 12, 9999, '1393110376_Picture.png', 'Bougie ronde petite diam. cm. 5', 'CAN05A', '8024273055712'),
(37, 1, 'CAN', '1.75', 6, 9999, '1393110376_Picture.png', 'Cire à modeler en pain 100 grs', 'CAN100', '8024273055729'),
(38, 1, 'CAN', '7.00', 1, 9999, '1393110376_Picture.png', 'Cire à modeler 1 kg.', 'CAN1000', '8024273055736'),
(39, 1, 'CANB', '1.45', 6, 9999, '1393110376_Picture.png', 'Moule 3D petit oeuf cm. 5,5x6,5 h.', 'CANB01', '8024273055545'),
(40, 1, 'CANB', '1.45', 6, 9999, '1393110376_Picture.png', 'Moule 3D grand oeuf cm.8x7h.', 'CANB02', '8024273055552'),
(41, 1, 'CANB', '0.80', 6, 9999, '1393110376_Picture.png', 'Moule 3D rond diam. cm.5x7,5 h.', 'CANB05', '8024273055583'),
(42, 1, 'CANB', '2.70', 3, 9999, '1393110376_Picture.png', 'Moule 3D grand ondulé diam 7,5x7,5h.', 'CANB06', '8024273055590'),
(43, 1, 'CANB', '2.70', 3, 9999, '1393110376_Picture.png', 'Moule 3D grand carré cm. 7,5x7,5x7,5h.', 'CANB07', '8024273055606'),
(44, 1, 'CANB', '2.70', 3, 9999, '1393110376_Picture.png', 'Moule 3D grand à cylindre diam. 7,5x10,5 h. ', 'CANB08', '8024273055613'),
(45, 1, 'CANP', '1.05', 6, 9999, '1393110376_Picture.png', 'Paraffine + Stéarine 100 gr', 'CANP/ST100', '8024273059086'),
(46, 1, 'CANP', '1.65', 6, 9999, '1393110376_Picture.png', 'Paraffine + Stéarine 250 gr.', 'CANP/ST250', '8024273059079'),
(47, 1, 'CANP', '1.05', 6, 9999, '1393110376_Picture.png', 'Paraffine en grains 100 grs', 'CANP100', '8024273055750'),
(48, 1, 'CANP', '1.65', 6, 9999, '1393110376_Picture.png', 'Paraffine en grains 250 grs', 'CANP250', '8024273055774'),
(49, 1, 'CANP', '3.15', 3, 9999, '1393110376_Picture.png', 'Paraffine en grains 500 grs', 'CANP500', '8024273055781'),
(50, 1, 'CANST', '0.76', 12, 9999, '1393110376_Picture.png', '6 mèches h. 20 cm. avec base', 'CANST01', '8024273055798'),
(51, 1, 'CANST', '1.84', 6, 9999, '1393110376_Picture.png', 'Mèche sans base 5 mt.', 'CANST02', '8024273055804'),
(52, 1, 'CANST', '1.05', 6, 9999, '1393110376_Picture.png', 'Paraffine en grains 100 grs', 'CANST100', '8024273055811'),
(53, 1, 'CANST', '4.95', 1, 9999, '1393110376_Picture.png', 'Stéarine1 kg.', 'CANST1000', '8024273055828'),
(54, 1, 'CANST', '1.65', 6, 9999, '1393110376_Picture.png', 'Paraffine en grains 250 grs', 'CANST250', '8024273055835'),
(55, 1, 'CG', '3.65', 6, 9999, '1393110376_Picture.png', 'Papier de gaze pour TexArt en rouleau 10 mt', 'CG02', '8024273037268'),
(56, 1, 'CG', '1.99', 6, 9999, '1393110376_Picture.png', 'Papier coton noir 66x90 cm', 'CG03', '8024273038340'),
(57, 1, 'CL', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Feuilles Verts type Foug', 'CL09A', '8024273350404'),
(58, 1, 'CL', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Feuilles Verts et Rouges', 'CL09C', '8024273350428'),
(59, 1, 'CL', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents', 'CL09D', '8024273350435'),
(60, 1, 'CL', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Multic', 'CL09E', '8024273350442'),
(61, 1, 'CL', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Jaunes Oranges', 'CL09F', '8024273350459'),
(62, 1, 'CL', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Trèfle à quatre feulles', 'CL09G', '8024273350466'),
(63, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Rose', 'CLFC03', '8024273351142'),
(64, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Rouge', 'CLFC04', '8024273351159'),
(65, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Blanche et Jaun', 'CLFC05', '8024273351166'),
(66, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Bleu', 'CLFC07', '8024273351180'),
(67, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Orange', 'CLFC08', '8024273351197'),
(68, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Orange', 'CLFC11', '8024273351227'),
(69, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Bleu et Jaune', 'CLFC12', '8024273351234'),
(70, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Rose', 'CLFC16', '8024273351272'),
(71, 1, 'CLFC', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Floreale Blanche et Rose', 'CLFC18', '8024273351296'),
(72, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Marguerites Blanches', 'CLFS02', '8024273350817'),
(73, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Trèfles à quatre feulles', 'CLFS04', '8024273350831'),
(74, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Jaun', 'CLFS05', '8024273350848'),
(75, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Orang', 'CLFS08', '8024273350879'),
(76, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Rouges', 'CLFS09', '8024273350886'),
(77, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs et Boutons Rouges', 'CLFS10', '8024273350893'),
(78, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Rose', 'CLFS12', '8024273350916'),
(79, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Violets Jaunes et Violet', 'CLFS15', '8024273350947'),
(80, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Bleu', 'CLFS17', '8024273350961'),
(81, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Bleu', 'CLFS18', '8024273350978'),
(82, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Violets Differents', 'CLFS19', '8024273350985'),
(83, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Feuilles Couleurs Automn', 'CLFS28', '8024273351074'),
(84, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Azurs', 'CLFS33', '8024273904966'),
(85, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Vin', 'CLFS36', '8024273904997'),
(86, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differ. Jaune Vio', 'CLFS37', '8024273905000'),
(87, 1, 'CLFS', '1.44', 6, 9999, '1393110376_Picture.png', 'Conf. Fleurs Differents Orange', 'CLFS38', '8024273905017'),
(88, 1, 'CN', '1.29', 10, 9999, '1393110376_Picture.png', 'Papier coton Teinte Blanque', 'CN01', '8024273390011'),
(89, 1, 'CN', '1.29', 10, 9999, '1393110376_Picture.png', 'Papier coton Teinte Ivoire', 'CN43', '8024273390431'),
(90, 1, 'CN', '0.49', 10, 9999, '1393110376_Picture.png', 'Papier coton Rouge', 'CN51', '8024273390639'),
(91, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - blanc', 'CN54', '8024273167156'),
(92, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - rose', 'CN55', '8024273167163'),
(93, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - bleu clair', 'CN56', '8024273167170'),
(94, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - rouge', 'CN57', '8024273167187'),
(95, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - orange', 'CN58', '8024273167194'),
(96, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - marron', 'CN59', '8024273167200'),
(97, 1, 'CN', '2.34', 10, 9999, '1393110376_Picture.png', 'Papier entaillé à fleur f.t cm. 70x100 - noir', 'CN60', '8024273167217'),
(98, 1, 'CN', '1.44', 10, 9999, '1393110376_Picture.png', 'Papier dentelle en damier blanc', 'CN61', '8024273167224'),
(99, 1, 'CN', '1.44', 10, 9999, '1393110376_Picture.png', 'Papier dentelle tramé blanc', 'CN62', '8024273167231');

-- --------------------------------------------------------

--
-- Structure de la table `societe`
--

CREATE TABLE IF NOT EXISTS `societe` (
  `idSociete` int(11) NOT NULL AUTO_INCREMENT,
  `nomSociete` varchar(250) NOT NULL,
  PRIMARY KEY (`idSociete`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `societe`
--

INSERT INTO `societe` (`idSociete`, `nomSociete`) VALUES
(1, 'Société 1'),
(2, 'Société2');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(32) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `adresse` varchar(250) NOT NULL DEFAULT '',
  `societe` varchar(100) NOT NULL,
  `adm` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `password`, `mail`, `adresse`, `societe`, `adm`) VALUES
(1, 'password', 'admin@gmail.com', '31 ter, rue Colbert à Lille', 'Administrateur', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
