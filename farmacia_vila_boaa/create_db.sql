CREATE DATABASE IF NOT EXISTS `farmacia_vila_boaa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `farmacia_vila_boaa`;

CREATE TABLE IF NOT EXISTS `insumos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Chave primária',
  `nome` VARCHAR(100) NOT NULL COMMENT 'Nome do insumo',
  `unidade` VARCHAR(20) NOT NULL COMMENT 'Unidade de medida',
  `estoque_atual` INT NOT NULL DEFAULT 0 COMMENT 'Quantidade em estoque',
  `preco` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Preço do insumo',
  INDEX idx_nome (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;