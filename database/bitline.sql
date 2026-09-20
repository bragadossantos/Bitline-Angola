
CREATE DATABASE IF NOT EXISTS bitlinea_ngola
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bitlinea_ngola;

-- ---------------------------------------------------------
-- Tabela: admins
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO admins (nome, email, password, criado_em) VALUES
('Administrador Bitline', 'admin@bitlineangola.co.ao',
 '$2b$10$.t3J5dhdpiDK/eU6oqba5.cLAA1/shYnNZfWEJblUsu2YtCMDDXkW', NOW())
ON DUPLICATE KEY UPDATE email = email;

-- ---------------------------------------------------------
-- Tabela: login_tentativas (proteção contra força bruta no login)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS login_tentativas (
  ip VARCHAR(45) PRIMARY KEY,
  tentativas INT NOT NULL DEFAULT 0,
  ultima_tentativa DATETIME NOT NULL,
  bloqueado_ate DATETIME DEFAULT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabela: portfolio (projetos/casos de estudo)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS portfolio (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  categoria VARCHAR(100) DEFAULT NULL,
  descricao TEXT NOT NULL,
  imagem VARCHAR(255) DEFAULT NULL,
  cliente VARCHAR(150) DEFAULT NULL,
  destaque TINYINT(1) NOT NULL DEFAULT 0,
  ordem INT NOT NULL DEFAULT 0,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabela: depoimentos (testemunhos de clientes)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS depoimentos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  empresa VARCHAR(150) DEFAULT NULL,
  texto TEXT NOT NULL,
  avaliacao TINYINT NOT NULL DEFAULT 5,
  foto VARCHAR(255) DEFAULT NULL,
  publicado TINYINT(1) NOT NULL DEFAULT 1,
  ordem INT NOT NULL DEFAULT 0,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabela: faq (perguntas frequentes)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS faq (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pergunta VARCHAR(300) NOT NULL,
  resposta TEXT NOT NULL,
  categoria VARCHAR(100) DEFAULT NULL,
  ordem INT NOT NULL DEFAULT 0,
  publicado TINYINT(1) NOT NULL DEFAULT 1,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabela: candidaturas (Trabalha Connosco)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS candidaturas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefone VARCHAR(30) DEFAULT NULL,
  area_interesse VARCHAR(150) DEFAULT NULL,
  mensagem TEXT DEFAULT NULL,
  cv_ficheiro VARCHAR(255) DEFAULT NULL,
  status ENUM('nova','vista','aceite','rejeitada') NOT NULL DEFAULT 'nova',
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabela: artigos (Blog)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS artigos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  resumo VARCHAR(300) NOT NULL,
  conteudo LONGTEXT NOT NULL,
  imagem VARCHAR(255) DEFAULT NULL,
  status ENUM('rascunho','publicado') NOT NULL DEFAULT 'rascunho',
  destaque TINYINT(1) NOT NULL DEFAULT 0,
  visualizacoes INT NOT NULL DEFAULT 0,
  autor_id INT DEFAULT NULL,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_artigos_autor FOREIGN KEY (autor_id) REFERENCES admins(id) ON DELETE SET NULL,
  INDEX idx_status (status),
  INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabela: contactos (mensagens recebidas do site)
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS contactos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefone VARCHAR(30) DEFAULT NULL,
  assunto VARCHAR(150) DEFAULT 'Consultoria Geral',
  mensagem TEXT NOT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  user_agent VARCHAR(255) DEFAULT NULL,
  lido TINYINT(1) NOT NULL DEFAULT 0,
  criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_lido (lido),
  INDEX idx_criado_em (criado_em)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Artigo de exemplo (opcional, podes eliminar depois)
-- ---------------------------------------------------------
INSERT INTO artigos (titulo, slug, resumo, conteudo, status, destaque, autor_id, criado_em) VALUES
(
  'Bem-vindo ao Blog da Bitline Angola',
  'bem-vindo-ao-blog-da-bitline-angola',
  'Damos início ao nosso blog, onde vamos partilhar conhecimento técnico, novidades e projetos da Bitline Angola.',
  '<p>É com grande satisfação que lançamos o blog oficial da <strong>Bitline Angola</strong>. Este espaço foi criado para partilhar conhecimento técnico sobre redes, cibersegurança, cloud e desenvolvimento de software, além de mostrar os projetos em que temos trabalhado por todo o país.</p><p>Fica atento — publicaremos regularmente conteúdo útil para empresas que querem crescer com tecnologia segura e eficiente.</p>',
  'publicado',
  1,
  1,
  NOW()
);

-- ---------------------------------------------------------
-- FAQ de exemplo (opcional, edita/apaga no painel admin)
-- ---------------------------------------------------------
INSERT INTO faq (pergunta, resposta, categoria, ordem, publicado) VALUES
('Quanto tempo demora um projeto normalmente?', 'Depende do serviço e da dimensão do projeto. Uma consultoria inicial gratuita permite dar-te um prazo realista logo no primeiro contacto.', 'Geral', 1, 1),
('A Bitline trabalha fora de Luanda?', 'Sim. Trabalhamos remotamente com clientes em qualquer província e deslocamo-nos presencialmente quando o projeto exige.', 'Geral', 2, 1),
('Como funciona o suporte técnico depois do projeto entregue?', 'Oferecemos planos de suporte e manutenção contínua, com atendimento remoto e presencial conforme o plano escolhido.', 'Suporte', 3, 1),
('Preciso de ter conhecimento técnico para pedir os vossos serviços?', 'Não. Explicamos tudo em linguagem simples e ajudamos a decidir a melhor solução para o teu caso específico.', 'Geral', 4, 1),
('Como peço um orçamento?', 'Podes usar o formulário de contacto, ligar para os nossos números ou enviar mensagem pelo WhatsApp. A primeira consultoria é gratuita.', 'Orçamentos', 5, 1);
