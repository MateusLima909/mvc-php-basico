CREATE TABLE usuario
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE fornecedor
( 
    id int not null PRIMARY KEY, 
    nome varchar(100) not null, 
    nomeFantasia varchar(100) not null, 
    cnpj varchar(100) not null, 
    inscricaoEstadual varchar(100) not null, 
    endereco varchar(100) not null, 
    tipoDeServico varchar(200) not null, 
    telefone varchar(20) not null 
);

CREATE TABLE cliente 
( 
    id int not null PRIMARY KEY, 
    nome varchar(100) not null, 
    dtnasc date not null, 
    cpf varchar(20) not null, 
    telefone varchar (20) not null
);

ALTER TABLE usuario 
ADD senha VARCHAR(255) NOT NULL, 
ADD nivel_acesso VARCHAR(20) NOT NULL DEFAULT 'usuario';

ALTER TABLE `cliente`
ADD COLUMN `id_usuario` INT NULL DEFAULT NULL AFTER `telefone`,
ADD UNIQUE INDEX `id_usuario_UNIQUE` (`id_usuario` ASC),
ADD CONSTRAINT `fk_cliente_usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `mvc_php_basico`.`usuario` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

ALTER TABLE `fornecedor`
ADD COLUMN `id_usuario` INT NULL DEFAULT NULL AFTER `telefone`,
ADD UNIQUE INDEX `id_usuario_UNIQUE` (`id_usuario` ASC),
ADD CONSTRAINT `fk_fornecedor_usuario`
  FOREIGN KEY (`id_usuario`)
  REFERENCES `mvc_php_basico`.`usuario` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;