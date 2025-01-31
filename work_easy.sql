-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/01/2025 às 14:05
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `work_easy`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `buscas`
--

CREATE TABLE `buscas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `termo_busca` varchar(255) NOT NULL,
  `data_busca` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `data_comentario` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `messages`
--

INSERT INTO `messages` (`id`, `from_user_id`, `to_user_id`, `message`, `timestamp`) VALUES
(1, 1, 2, 'olá', '2024-12-08 19:37:07'),
(2, 2, 1, 'opa', '2024-12-08 19:55:49'),
(3, 2, 1, 'tudo bom ?', '2024-12-08 20:23:01'),
(4, 2, 1, 'tudo bom ?', '2024-12-08 20:24:04'),
(5, 3, 2, 'olá', '2024-12-08 21:00:59'),
(6, 3, 1, 'opa', '2024-12-08 21:01:26'),
(7, 1, 3, 'olá', '0000-00-00 00:00:00'),
(8, 1, 3, 'opa', '0000-00-00 00:00:00'),
(9, 3, 1, 'opa', '0000-00-00 00:00:00'),
(10, 3, 1, 'vagabundo', '0000-00-00 00:00:00'),
(11, 3, 1, 'oque', '0000-00-00 00:00:00'),
(12, 3, 1, 'oqueee', '0000-00-00 00:00:00'),
(13, 3, 1, 'hahah', '0000-00-00 00:00:00'),
(14, 3, 1, 'opa', '2024-12-08 21:22:06'),
(15, 3, 1, 'olá', '0000-00-00 00:00:00'),
(17, 3, 1, 'asdas', '0000-00-00 00:00:00'),
(18, 3, 1, 'olá', '0000-00-00 00:00:00'),
(19, 3, 1, 'uq', '0000-00-00 00:00:00'),
(20, 3, 1, 'opa', '0000-00-00 00:00:00'),
(21, 3, 1, 'demais', '0000-00-00 00:00:00'),
(22, 2, 1, 'tudo', '0000-00-00 00:00:00'),
(23, 2, 1, 'to', '0000-00-00 00:00:00'),
(24, 2, 1, 'oq', '0000-00-00 00:00:00'),
(25, 2, 1, 'oq', '2024-12-08 22:13:39'),
(26, 2, 1, 'oq', '2024-12-08 22:14:19'),
(27, 2, 1, 'oq', '2024-12-08 22:15:42'),
(28, 2, 1, 'oq', '2024-12-08 22:16:27'),
(29, 2, 1, 'oq', '2024-12-08 22:17:35'),
(30, 2, 1, 'oque', '2024-12-08 22:17:48'),
(31, 2, 1, 'oq', '2024-12-08 22:30:06'),
(32, 2, 1, 'é', '0000-00-00 00:00:00'),
(33, 2, 1, 'é', '2024-12-08 22:38:28'),
(34, 2, 3, 'oi', '0000-00-00 00:00:00'),
(35, 2, 3, 'oi', '2024-12-08 22:38:47'),
(36, 2, 3, 'sorry', '0000-00-00 00:00:00'),
(37, 2, 3, 'isso ai', '0000-00-00 00:00:00'),
(38, 2, 3, 'yes', '0000-00-00 00:00:00'),
(39, 2, 3, 'novo', '0000-00-00 00:00:00'),
(40, 2, 3, 'sodainbhdniokdpla', '0000-00-00 00:00:00'),
(41, 2, 1, 'hellop', '0000-00-00 00:00:00'),
(42, 2, 1, 'oiii', '0000-00-00 00:00:00'),
(43, 2, 1, 'euuuu', '0000-00-00 00:00:00'),
(44, 2, 1, 'aaaaaaaaaaaaaaaaa', '0000-00-00 00:00:00'),
(45, 2, 1, 'bouuuaa', '0000-00-00 00:00:00'),
(46, 2, 1, 'não ficou igual', '0000-00-00 00:00:00'),
(47, 2, 1, 'nada', '0000-00-00 00:00:00'),
(48, 2, 1, 'ai sim', '0000-00-00 00:00:00'),
(49, 2, 1, 'ai simmmmmmmm', '0000-00-00 00:00:00'),
(50, 2, 3, 'aaa', '0000-00-00 00:00:00'),
(51, 2, 1, 'aaaaaaaa', '0000-00-00 00:00:00'),
(52, 2, 1, 'ficou otimo', '0000-00-00 00:00:00'),
(53, 2, 1, 'aa', '0000-00-00 00:00:00'),
(54, 2, 1, 'sadasdasda', '0000-00-00 00:00:00'),
(55, 2, 1, 'laskjfhghakml,', '0000-00-00 00:00:00'),
(56, 2, 1, 'nooo', '0000-00-00 00:00:00'),
(57, 2, 1, 'deu?', '2024-12-08 23:26:01'),
(58, 2, 1, 'inacreditavel', '2024-12-08 23:26:13'),
(59, 2, 1, 'iegal', '2024-12-08 23:28:17'),
(60, 2, 1, 'oiii', '2024-12-08 23:31:00'),
(61, 1, 3, 'é isso ai', '2024-12-08 23:35:40'),
(62, 1, 2, '´´eee', '2024-12-08 23:39:46'),
(63, 1, 2, 'precido que alguem corte minha grama', '2024-12-08 23:54:01'),
(64, 1, 2, 'precido que alguem corte minha grama', '2024-12-08 23:54:01'),
(65, 1, 2, 'mandou 2', '2024-12-08 23:54:12'),
(66, 1, 2, 'mandou 2', '2024-12-08 23:54:12'),
(67, 1, 2, '1', '2024-12-08 23:54:47'),
(68, 1, 2, '1', '2024-12-08 23:54:47'),
(69, 1, 2, '2', '2024-12-08 23:55:06'),
(70, 1, 2, '2', '2024-12-08 23:55:06'),
(71, 1, 2, 'duas', '2024-12-08 23:55:46'),
(72, 1, 2, 'duas', '2024-12-08 23:55:46'),
(73, 1, 2, 'aiii', '2024-12-08 23:57:29'),
(74, 4, 1, 'oii', '2024-12-09 00:03:39'),
(75, 4, 2, 'Boa Noite', '2024-12-09 00:04:45'),
(76, 2, 4, 'hahha', '2024-12-09 12:13:09'),
(77, 1, 4, 'oii', '2024-12-09 13:34:39'),
(78, 2, 1, 'dasfdgddsasdfg', '2024-12-09 19:17:31'),
(79, 2, 4, 'aooo zé mane', '2024-12-09 21:39:09'),
(80, 5, 3, 'cu de cachorro', '2025-01-19 22:13:10'),
(81, 5, 1, 'meu ovo', '2025-01-19 22:13:33'),
(82, 1, 5, 'oque caralho', '2025-01-19 22:15:57'),
(83, 1, 3, 'ascsacasc', '2025-01-19 22:16:05'),
(84, 1, 4, 'opa', '2025-01-23 20:45:27'),
(85, 1, 2, 'yuvbiomp,´ç', '2025-01-26 11:34:42'),
(86, 2, 1, '1', '2025-01-26 12:38:28'),
(87, 2, 1, '2', '2025-01-26 12:38:45'),
(88, 7, 8, 'vai trabalhar', '2025-01-26 22:59:55'),
(89, 8, 7, 'que', '2025-01-26 23:27:52'),
(90, 8, 7, 'não', '2025-01-26 23:33:09'),
(91, 8, 7, 'entendi', '2025-01-26 23:33:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `data_postagem` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `posts`
--

INSERT INTO `posts` (`id`, `id_usuario`, `conteudo`, `imagem`, `data_postagem`) VALUES
(1, 4, 'sadçspoiufba', NULL, '2024-12-09 17:44:13'),
(2, 4, 'Muito Foda', NULL, '2024-12-09 17:45:40'),
(3, 2, 'o gay', NULL, '2024-12-09 19:22:45'),
(4, 5, 'nada', NULL, '2025-01-19 22:14:14'),
(5, 1, 'laçdmojhgfyceyvgbchjnkmw,l.', NULL, '2025-01-26 12:08:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reactions`
--

CREATE TABLE `reactions` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_post` int(11) DEFAULT NULL,
  `tipo` enum('like','love','haha','wow','sad','angry') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reactions`
--

INSERT INTO `reactions` (`id`, `id_usuario`, `id_post`, `tipo`) VALUES
(1, 4, 1, 'haha'),
(2, 4, 2, 'love'),
(7, 1, 2, 'like'),
(8, 1, 1, 'like'),
(9, 2, 2, 'haha'),
(10, 2, 3, 'haha'),
(11, 4, 3, 'like'),
(12, 1, 3, 'haha'),
(13, 5, 4, 'like'),
(14, 5, 3, 'like'),
(15, 5, 2, 'like'),
(16, 5, 1, 'love'),
(17, 1, 4, 'love'),
(18, 1, 5, 'love'),
(19, 8, 5, 'love'),
(20, 8, 4, 'love'),
(21, 8, 3, 'love'),
(22, 8, 2, 'love'),
(23, 8, 1, 'love');

-- --------------------------------------------------------

--
-- Estrutura para tabela `seguidores`
--

CREATE TABLE `seguidores` (
  `id` int(11) NOT NULL,
  `id_seguidor` int(11) NOT NULL,
  `id_seguido` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `seguidores`
--

INSERT INTO `seguidores` (`id`, `id_seguidor`, `id_seguido`, `data`) VALUES
(10, 2, 1, '2024-12-09 13:06:14'),
(14, 2, 4, '2024-12-09 13:10:45'),
(21, 4, 1, '2024-12-09 21:41:08'),
(22, 4, 2, '2024-12-09 21:41:15'),
(23, 4, 3, '2024-12-09 21:41:22'),
(24, 5, 3, '2025-01-19 22:12:40'),
(25, 5, 2, '2025-01-19 22:12:46'),
(26, 5, 1, '2025-01-19 22:12:51'),
(31, 1, 2, '2025-01-26 12:40:07'),
(32, 1, 3, '2025-01-26 12:40:13'),
(33, 1, 4, '2025-01-26 12:40:19'),
(34, 1, 5, '2025-01-26 12:40:35'),
(35, 2, 3, '2025-01-26 12:40:52'),
(36, 7, 8, '2025-01-26 23:00:03'),
(37, 7, 3, '2025-01-26 23:02:53'),
(38, 7, 2, '2025-01-26 23:03:01'),
(39, 7, 1, '2025-01-26 23:03:05'),
(40, 8, 2, '2025-01-27 09:26:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `stories`
--

CREATE TABLE `stories` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `data_postagem` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `stories`
--

INSERT INTO `stories` (`id`, `id_usuario`, `foto`, `descricao`, `data_postagem`, `user_id`) VALUES
(1, 4, '1733777518_AAA.jpg', 'novo', '2024-12-09 17:51:58', NULL),
(14, 1, '1733791075_alan.gif', NULL, '2024-12-09 21:37:55', NULL),
(16, 4, '1733791246_bruxada.png', NULL, '2024-12-09 21:40:46', NULL),
(18, 1, '1737901877_gamezone.png', NULL, '2025-01-26 11:31:17', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('comum','empregador','empregado') NOT NULL,
  `idade` int(11) NOT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT current_timestamp(),
  `seguindo` int(11) DEFAULT 0,
  `seguidores` int(11) DEFAULT 0,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `cidade` varchar(255) NOT NULL,
  `descricao` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `nome`, `email`, `senha`, `tipo`, `idade`, `cargo`, `contato`, `foto`, `data_cadastro`, `seguindo`, `seguidores`, `latitude`, `longitude`, `cidade`, `descricao`) VALUES
(1, 'lucas pedreiro', 'lucas@hotmail.com', '$2y$10$AEVgEjV5jlEBWT.5I/NZGOiZs7TA6H4cKmJsModp02AIX75SOfzyq', '', 28, 'ti', '4002 8922', 'outra.jpg', '2024-12-08 19:29:55', 14, 5, 666666, 666666, 'UBERLANDIA', 'tudo simmmmmm'),
(2, 'lucas Cordeiro', 'lucas1@hotmail.com', '$2y$10$rgnA6lgqzvZu5yIOTsCxPeZd44QIPPXHfLJtSceiki5popKmi6bdC', 'empregador', 27, 'Mecanico', '42 99116-7928', 'AAA.jpg', '2024-12-08 19:32:22', 6, 5, -24.325571943411, -50.631221785904, 'Telemaco Borba', 'tudo susse?????????'),
(3, 'Lucas Vagaba', 'lucas2@hotmail.com', '$2y$10$vUAQ0yn9aQ3Izt8jSHP9f.2f0Z.DcGLeR6mXdzdHjTTfKeh1sfLQm', 'empregador', 87, 'Fusileiro', '400222222', 'alan.gif', '2024-12-08 20:54:23', 0, 5, -24.335042, -50.656498, 'imbau', NULL),
(4, 'OTARIO', 'lucas3@hotmail.com', '$2y$10$bmd85n8mz4J4zJXnzRZ2jehxBrXHAR784TueNDZMLAPCHP4I/wx8m', '', 88, 'motorista', '40205151', 'bruxada.png', '2024-12-09 00:03:17', 3, 2, -25.4295, -50.6250279, 'UBERLANDIA', NULL),
(5, 'Cudecachorro', 'lucascudecachorro@hotmail.com', '$2y$10$bcUGBG.oSXwXKDttFljUzOLd5UR80VrB5pF5aXjy31.vcSQG..Nj2', 'empregado', 24, 'pedreiro', '40028922', 'EU1.png', '2025-01-19 22:11:30', 3, 1, -25.4295, -50.6250279, 'telemaco zorba', NULL),
(6, 'seila', 'seila@hotmail.com', '$2y$10$/4miWiDs/BP1mrf5TQmeH.jq5PEBkqWiy1NIMkvEB9tlPN4SPM8Tm', 'empregado', 0, NULL, NULL, NULL, '2025-01-26 11:31:50', 0, 0, NULL, NULL, '', NULL),
(7, 'renata', 'renata@hotmail.com', '$2y$10$.ule0Pftpogd7J3rO80rvu.isi.XkLHlucmPzasQMOiLoFa4uB1Jq', '', 0, 'musica', '21871871', 'latino.webp', '2025-01-26 22:22:50', 4, 0, -24.337936, -50.6250279, 'SP', 'RENATA INGRATA'),
(8, 'seu madrugrogas', 'madruguinha@hotmail.com', '$2y$10$wRjxc.slUsfl2vYOFZzkDuSkO3t/uUF5J.PSHoRmch3HoU8QDO.PO', 'empregado', 80, 'Drogado', '420028922', 'madruga.jpeg', '2025-01-26 22:32:51', 1, 1, -24.337936, -50.610791, 'pindamonhangaba', 'é isso ai');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `buscas`
--
ALTER TABLE `buscas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remetente` (`id_remetente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Índices de tabela `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

--
-- Índices de tabela `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_post` (`id_post`);

--
-- Índices de tabela `seguidores`
--
ALTER TABLE `seguidores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_seguidor` (`id_seguidor`),
  ADD KEY `id_seguido` (`id_seguido`);

--
-- Índices de tabela `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `buscas`
--
ALTER TABLE `buscas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de tabela `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `seguidores`
--
ALTER TABLE `seguidores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `buscas`
--
ALTER TABLE `buscas`
  ADD CONSTRAINT `buscas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`);

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `reactions_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reactions_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`);

--
-- Restrições para tabelas `seguidores`
--
ALTER TABLE `seguidores`
  ADD CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`id_seguidor`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`id_seguido`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `stories_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
