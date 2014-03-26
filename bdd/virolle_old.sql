-- phpMyAdmin SQL Dump
-- version 4.1.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Lun 24 Février 2014 à 00:18
-- Version du serveur :  5.5.24-log
-- Version de PHP :  5.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `virolle`
--

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
-- Structure de la table `activation`
--

CREATE TABLE IF NOT EXISTS `activation` (
  `idUser` int(11) NOT NULL,
  `clee` varchar(32) NOT NULL,
  `actif` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `activation`
--

INSERT INTO `activation` (`idUser`, `clee`, `actif`) VALUES
(1, '906c5301cd3414d55a371a06ca9e2786', 1),
(2, '4056adc86603034ac113d19ff02f60bc', 1),
(6, 'fdhgfghfghfg', 1),
(20, 'sdfd', 1),
(8, 'bnb', 1),
(23, 'a79bd2cdf32ad5afd27700958426fa61', 0),
(24, '2bd98f679261d4be914f4b7e89c6c4a0', 0);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idCategorie` int(11) NOT NULL AUTO_INCREMENT,
  `idSociete` int(11) NOT NULL,
  `idParent` int(11) NOT NULL,
  `libelleCategorie` varchar(250) NOT NULL,
  PRIMARY KEY (`idCategorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idCategorie`, `idSociete`, `idParent`, `libelleCategorie`) VALUES
(1, 1, 0, 'Categorie 1'),
(2, 1, 0, 'Categorie 2'),
(3, 1, 1, 'Ss cat 1'),
(4, 1, 3, 'sous sous cat1'),
(5, 1, 2, 'ss cat2'),
(6, 1, 5, 'ss ss cat2'),
(7, 1, 4, 'sous sous sous cat1'),
(8, 1, 1, 'ss cat1-2');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`id`, `idUser`, `idSociete`, `dateCommande`, `dateLivraison`, `commentOrder`) VALUES
(1, 1, 1, '2014-02-24 01:04:23', '26/02/2014', 'dfgdf'),
(2, 1, 1, '2014-02-24 01:16:00', '28/02/2014', 'test');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `idOrder`, `idProduct`, `unitPrice`, `quantity`) VALUES
(1, 1, 1, '15', 4),
(2, 1, 3, '18', 5),
(3, 2, 5, '60', 14);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `idSociete` int(11) NOT NULL,
  `idCategorie` int(11) NOT NULL,
  `prixProduit` decimal(10,0) NOT NULL,
  `minQte` int(11) NOT NULL,
  `quantiteProduit` int(11) NOT NULL,
  `imgProduit` varchar(250) NOT NULL DEFAULT '1393110376_Picture.png',
  `libelleProduit` varchar(250) NOT NULL,
  `refProduit` varchar(50) NOT NULL,
  PRIMARY KEY (`idProduit`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`idProduit`, `idSociete`, `idCategorie`, `prixProduit`, `minQte`, `quantiteProduit`, `imgProduit`, `libelleProduit`, `refProduit`) VALUES
(1, 1, 1, '15', 4, 200, '1393110376_Picture.png', 'Produit1', '5894632154'),
(2, 1, 3, '10', 4, 200, '1393110376_Picture.png', 'Cravate', '215498'),
(3, 1, 1, '18', 5, 200, '1393110376_Picture.png', 'Chemise', '49785463'),
(4, 1, 1, '20', 2, 50, '1393110376_Picture.png', 'piston', '4987563'),
(5, 1, 1, '60', 1, 50, '1393110376_Picture.png', 'tablette', '46987231'),
(6, 2, 9, '60', 1, 15, '1393110376_Picture.png', 'ProduitSociete2', '457521');

-- --------------------------------------------------------

--
-- Structure de la table `societe`
--

CREATE TABLE IF NOT EXISTS `societe` (
  `idSociete` int(11) NOT NULL AUTO_INCREMENT,
  `nomSociete` varchar(250) NOT NULL,
  PRIMARY KEY (`idSociete`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `societe`
--

INSERT INTO `societe` (`idSociete`, `nomSociete`) VALUES
(1, 'Societe1'),
(2, 'Societe2'),
(3, 'Societe3');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `adresse` varchar(250) NOT NULL DEFAULT '',
  `tel` varchar(25) NOT NULL DEFAULT '',
  `cp` int(5) NOT NULL,
  `ville` varchar(55) NOT NULL,
  `birthday` varchar(25) NOT NULL,
  `imgAvatar` varchar(250) NOT NULL DEFAULT '1383611218_green-35.png',
  `descri` text NOT NULL,
  `adm` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `prenom`, `nom`, `password`, `ip`, `mail`, `adresse`, `tel`, `cp`, `ville`, `birthday`, `imgAvatar`, `descri`, `adm`) VALUES
(1, 'michaël', 'RUPP', '180f068dc45ee81f66123d46d2768c29', '::1', 'michaelrupp@free.fr', '316 rue leon blum', '0698329149', 62232, 'Annezin', '10/11/1992', '1383611218_green-35.png', 'ghjghgh', 1),
(2, 'Prenomtest', 'Nomtest', '180f068dc45ee81f66123d46d2768c29', '::1', 'tst@hotmail.fr', '', '', 0, '', '', '1383611218_green-35.png', '', 0),
(3, 'monprenom', 'test', '180f068dc45ee81f66123d46d2768c29', '::1', 'blabliblou@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(4, 'new', 'moi', '180f068dc45ee81f66123d46d2768c29', '::1', 'boum@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(5, 'mew', 'toum', '180f068dc45ee81f66123d46d2768c29', '::1', 'azerty@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(6, 'tarask', 'pli', '180f068dc45ee81f66123d46d2768c29', '::1', 'ab@hotmail.fr', '', '', 0, '', '09/11/2013', '1383611218_green-35.png', '', 0),
(7, 'wxd', 'cfbvf', '180f068dc45ee81f66123d46d2768c29', '::1', 'w@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(8, 'c', 'c', '180f068dc45ee81f66123d46d2768c29', '::1', 'c@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(9, 'v', 'v', '180f068dc45ee81f66123d46d2768c29', '::1', 'v@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(10, 'b', 'b', '180f068dc45ee81f66123d46d2768c29', '::1', 'b@hotmail.fr', '', '', 0, '', '0000-00-00', 'arena.jpg.jpg', '', 0),
(11, 'n', 'n', '180f068dc45ee81f66123d46d2768c29', '::1', 'n@hotmail.fr', '', '', 0, '', '0000-00-00', '1356288328', '', 0),
(12, 'q', 'q', '180f068dc45ee81f66123d46d2768c29', '::1', 'q@hotmail.fr', '', '', 0, '', '0000-00-00', '1664322395', '', 0),
(13, 's', 's', '180f068dc45ee81f66123d46d2768c29', '::1', 's@hotmail.fr', '', '', 0, '', '0000-00-00', '703446565', '', 0),
(14, 'd', 'd', '180f068dc45ee81f66123d46d2768c29', '::1', 'd@hotmail.fr', '', '', 0, '', '0000-00-00', '909038353', '', 0),
(15, 'f', 'f', '180f068dc45ee81f66123d46d2768c29', '::1', 'f@hotmail.fr', '', '', 0, '', '0000-00-00', '1254606751', '', 0),
(16, 'g', 'g', '180f068dc45ee81f66123d46d2768c29', '::1', 'g@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(17, 'h', 'h', '180f068dc45ee81f66123d46d2768c29', '::1', 'h@hotmail.fr', '', '', 0, '', '0000-00-00', '2005042347.jpg', '', 0),
(18, 'j', 'j', '180f068dc45ee81f66123d46d2768c29', '::1', 'j@hotmail.fr', '', '', 0, '', '0000-00-00', '', '', 0),
(19, 'k', 'k', '180f068dc45ee81f66123d46d2768c29', '::1', 'k@hotmail.fr', '', '', 0, '', '0000-00-00', '1383611218_green-35.png', '', 0),
(20, 'l', 'l', '180f068dc45ee81f66123d46d2768c29', '::1', 'l@hotmail.fr', '23 rue des martyrs', '33642643617', 13000, 'Marseille', '04/11/2013', '702799712.jpg', '', 0),
(21, 'uiop', 'uiop', '180f068dc45ee81f66123d46d2768c29', '::1', 'osef@hotmail.fr', '', '', 0, '', '', '1383611218_green-35.png', '', 0),
(22, 'theo', 'fanchini', '77fac59c9d1db9b83bfab3787de86659', '88.165.159.101', 'theo.fanchini@hei.fr', '', '', 0, '', '', '2117745655.jpeg', '', 0),
(23, 'dfgdfg', 'dfgfdgf', '8ead6e4b55f7813e69c4217a16c24cfb', '::1', 'test@hotmail.fr', '', '', 0, '', '', '1383611218_green-35.png', '', 0),
(24, 'gvnhgh', 'gh,nhg,', '180f068dc45ee81f66123d46d2768c29', '::1', 'abcdefg@hotmail.fr', '', '', 0, '', '', '1383611218_green-35.png', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
