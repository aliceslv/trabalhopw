CREATE DATABASE IF NOT EXISTS cladaly CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE cladaly;

CREATE TABLE IF NOT EXISTS categorias (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,     
    slug VARCHAR(50) NOT NULL UNIQUE     
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS fornecedores (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nome    VARCHAR(100) NOT NULL,
    cnpj    VARCHAR(18)  NOT NULL UNIQUE,
    email   VARCHAR(100),
    telefone VARCHAR(20),
    contato VARCHAR(100)               
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS clientes (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    nome           VARCHAR(100) NOT NULL,
    email          VARCHAR(100) NOT NULL UNIQUE,
    senha          VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS produtos (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(100) NOT NULL,
    descricao     VARCHAR(255) NOT NULL,
    categoria_id  INT NOT NULL,
    fornecedor_id INT,
    preco         DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_cat  FOREIGN KEY (categoria_id)  REFERENCES categorias(id)   ON DELETE RESTRICT,
    CONSTRAINT fk_forn FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE SET NULL
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS estoque (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    produto_id  INT NOT NULL UNIQUE,
    quantidade  INT NOT NULL DEFAULT 0,
    minimo      INT NOT NULL DEFAULT 5,   
    atualizado  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_est_prod FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'Pendente',
    CONSTRAINT fk_pedido_cliente
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
    ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    forma_pagamento VARCHAR(50) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'Aguardando',
    data_pagamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pagamento_pedido
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS entregas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    status VARCHAR(30) DEFAULT 'Preparando',
    codigo_rastreio VARCHAR(100),
    data_envio DATETIME NULL,
    CONSTRAINT fk_entrega_pedido
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
    ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4;



INSERT INTO categorias (nome, slug) VALUES
('Cabelo',    'cabelo'),
('Skincare',  'skincare'),
('Maquiagem', 'maquiagem');

INSERT INTO fornecedores (nome, cnpj, email, telefone, contato) VALUES
('BelezaPro Distribuidora',   '12.345.678/0001-90', 'contato@belezapro.com.br',   '(11) 91234-5678', 'Ana Lima'),
('NaturalCosmetics Ltda',     '98.765.432/0001-10', 'vendas@naturalcosmetics.com', '(21) 99876-5432', 'Carlos Souza'),
('GlamourImports S/A',        '55.111.222/0001-33', 'pedidos@glamourimports.com',  '(31) 98765-4321', 'Patrícia Rocha');

INSERT INTO produtos (nome, descricao, categoria_id, fornecedor_id, preco) VALUES

('Shampoo Hidratante',       'Limpeza suave com hidratação profunda para todos os tipos de cabelo.', 1, 1, 39.90),
('Condicionador Nutritivo',  'Nutre e desembaraça os fios sem pesar.',                               1, 1, 34.90),
('Creme para Pentear',       'Reduz o frizz e define os cachos com toque leve.',                     1, 2, 29.90),
('Óleo Reparador de Pontas', 'Sela as pontas e devolve o brilho natural dos fios.',                  1, 2, 27.50),

('Sabonete Facial',          'Limpeza diária suave, indicada para todos os tipos de pele.',          2, 2, 24.90),
('Hidratante Facial',        'Hidratação intensa com toque seco, não obstrui os poros.',             2, 2, 34.90),
('Protetor Solar FPS 50',    'Proteção UVA/UVB com toque seco, ideal para uso diário.',              2, 1, 49.90),
('Sérum de Vitamina C',      'Antioxidante, ilumina e uniformiza o tom da pele.',                    2, 3, 59.90),

('Base Líquida',             'Cobertura média, acabamento natural e duradouro.',                     3, 3, 44.90),
('Máscara de Cílios',        'Volume e alongamento sem borrar.',                                     3, 3, 32.90),
('Paleta de Sombras',        'Cores versáteis para looks do dia a dia e produções especiais.',        3, 3, 54.90),
('Batom Líquido Matte',      'Alta fixação e cor intensa por horas.',                               3, 3, 28.90);

INSERT INTO estoque (produto_id, quantidade, minimo) VALUES
(1,  20, 5),
(2,  15, 5),
(3,   0, 5),
(4,  10, 3),
(5,  25, 5),
(6,  18, 5),
(7,  12, 4),
(8,   0, 3),
(9,  14, 5),
(10, 22, 5),
(11,  9, 3),
(12, 16, 5);
