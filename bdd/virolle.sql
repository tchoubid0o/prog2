-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 05 Mai 2014 à 21:48
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `accessociete`
--

INSERT INTO `accessociete` (`ref`, `idUser`, `idSociete`) VALUES
(17, 1, 1),
(18, 1, 2);

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
(8, 1, 1, 'ss cat1-2'),
(9, 2, 0, 'test Societe2');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `commande`
--

INSERT INTO `commande` (`id`, `idUser`, `idSociete`, `dateCommande`, `dateLivraison`, `commentOrder`, `keyOrder`) VALUES
(3, 1, 1, '2014-02-24 12:33:41', '28/02/2014', 'test', '139324162183150744'),
(4, 1, 1, '2014-02-24 15:08:16', '28/02/2014', 'panier', '139325089690288256'),
(5, 1, 1, '2014-04-15 17:25:12', '16/04/2014', 'commentaire', '139757551222184750');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `idOrder`, `idProduct`, `unitPrice`, `quantity`) VALUES
(4, 3, 1, '15', 4),
(5, 4, 1, '15', 4),
(6, 4, 3, '18', 5),
(7, 4, 4, '20', 2),
(8, 4, 5, '60', 1),
(9, 5, 1, '15', 4),
(10, 6, 1, '15', 52),
(11, 7, 6, '60', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `idProduit` int(11) NOT NULL AUTO_INCREMENT,
  `idSociete` int(11) NOT NULL,
  `idCategorie` int(11) NOT NULL,
  `prixProduit` decimal(10,0) NOT NULL,
  `minQte` int(11) NOT NULL DEFAULT '1',
  `quantiteProduit` int(11) NOT NULL,
  `imgProduit` varchar(250) NOT NULL DEFAULT '1393110376_Picture.png',
  `libelleProduit` varchar(250) NOT NULL,
  `codeProduit` varchar(50) NOT NULL,
  `barCodeProduit` varchar(50) NOT NULL,
  PRIMARY KEY (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(2, 'Societe2');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `password`, `mail`, `adresse`, `societe`, `adm`) VALUES
(1, 'password', 'admin@gmail.com', '31 ter, rue Colbert à Lille', 'societe1', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
