-- Script para adicionar campo comentario na tabela avaliacao (se não existir)
-- Execute este comando no seu banco de dados se o campo comentario não existir

-- Verificar se o campo comentario já existe
-- SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'avaliacao' AND COLUMN_NAME = 'comentario';

-- Se o campo não existir, execute o comando abaixo:
ALTER TABLE avaliacao ADD COLUMN comentario TEXT NULL AFTER nota;

-- Comando para verificar a estrutura da tabela após a alteração:
-- DESCRIBE avaliacao;