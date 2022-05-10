-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10-Maio-2022 às 06:31
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Banco de dados: `loja-exemplo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `endereco`
--

CREATE TABLE IF NOT EXISTS `endereco` (
  `cep` varchar(8) NOT NULL,
  `logradouro` varchar(150) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`cep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE IF NOT EXISTS `produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `quant` int(11) NOT NULL,
  `data_alteracao` timestamp NULL DEFAULT current_timestamp(),
  `valor` decimal(11,2) NOT NULL,
  `largura` decimal(11,3) DEFAULT NULL,
  `altura` decimal(11,3) DEFAULT NULL,
  `comprimento` decimal(11,3) DEFAULT NULL,
  `peso` decimal(11,3) DEFAULT NULL,
  `fotos` text NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id`, `nome`, `descricao`, `quant`, `data_alteracao`, `valor`, `largura`, `altura`, `comprimento`, `peso`, `fotos`, `ativo`) VALUES
(1, 'Celular ', 'Celular 4G', 10, '2022-05-10 00:57:09', '1200.00', '0.000', '0.000', '0.000', '0.000', '', 1),
(2, 'Carregador', 'Carregador 30 W', 10, '2022-05-10 03:05:53', '100.00', '0.000', '0.000', '0.000', '0.000', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cpf` varchar(12) NOT NULL,
  `data_nasc` date DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `cpf`, `data_nasc`, `tel`, `foto_perfil`, `ativo`) VALUES
(23, 'Silvia Cristiana Da Silva', 'sc@email.com', '3f415f27d8797b3003575ee77b0ec225', '12323434522', '0000-00-00', '', 'd00a0bb5897e59483116a919cc64f124.jpg', 1),
(24, 'Pedro Cunha Souza', 'pc@email.com', 'f3c055d289f91aa900ada6066a7c356b', '12323445633', '0000-00-00', '', '367bade5e9b971239f60a9e65ea7941b.jpg', 1),
(25, 'Maria Silva', 'ms@email.com', 'bc0bcfbb85fd4119e3c7bf30c9e6ddf1', '12312366666', '1980-07-20', '5555-55555', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario_endereco`
--

CREATE TABLE IF NOT EXISTS `usuario_endereco` (
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `ehPrincipal` tinyint(1) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cep` varchar(8) DEFAULT NULL,
  KEY `id_usuario` (`id_usuario`),
  KEY `cep` (`cep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `usuario_endereco`
--
ALTER TABLE `usuario_endereco`
  ADD CONSTRAINT `usuario_endereco_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `usuario_endereco_ibfk_2` FOREIGN KEY (`cep`) REFERENCES `endereco` (`cep`);
COMMIT;