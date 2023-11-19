-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/11/2023 às 02:40
-- Versão do servidor: 10.4.27-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `controle_caixas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `caixas`
--

CREATE TABLE `caixas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `saldo_inicial` decimal(12,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `caixas_lancamentos`
--

CREATE TABLE `caixas_lancamentos` (
  `id` int(11) NOT NULL,
  `id_caixa` int(11) NOT NULL,
  `movimento` varchar(10) NOT NULL COMMENT 'entrada | saida',
  `data_movimento` datetime NOT NULL,
  `discriminacao_movimento` varchar(255) NOT NULL,
  `valor_movimento` decimal(12,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `caixas`
--
ALTER TABLE `caixas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `caixas_lancamentos`
--
ALTER TABLE `caixas_lancamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_caixa` (`id_caixa`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `caixas`
--
ALTER TABLE `caixas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `caixas_lancamentos`
--
ALTER TABLE `caixas_lancamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `caixas_lancamentos`
--
ALTER TABLE `caixas_lancamentos`
  ADD CONSTRAINT `caixas_lancamentos_ibfk_1` FOREIGN KEY (`id_caixa`) REFERENCES `caixas` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
