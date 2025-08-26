CREATE DATABASE IF NOT EXISTS biblioteca_almeida_ds1;
USE biblioteca_almeida_ds1;

CREATE TABLE autores (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nacionalidade VARCHAR(50),
    ano_nascimento INT
);

CREATE TABLE leitores (
    id_leitor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20)
);

CREATE TABLE livros (
    id_livro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    genero VARCHAR(50),
    ano_publicacao INT,
    id_autor INT,
    FOREIGN KEY (id_autor) REFERENCES autores(id_autor) ON DELETE CASCADE
);

CREATE TABLE emprestimos (
    id_emprestimo INT AUTO_INCREMENT PRIMARY KEY,
    id_livro INT,
    id_leitor INT,
    data_emprestimo DATE NOT NULL,
    data_devolucao DATE,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE,
    FOREIGN KEY (id_leitor) REFERENCES leitores(id_leitor) ON DELETE CASCADE
);

INSERT INTO autores (nome, nacionalidade, ano_nascimento) VALUES
('Machado de Assis', 'Brasileira', 1839),
('Clarice Lispector', 'Brasileira', 1920),
('Jorge Amado', 'Brasileira', 1912),
('George Orwell', 'Britânica', 1903),
('J.K. Rowling', 'Britânica', 1965);

INSERT INTO leitores (nome, email, telefone) VALUES
('João Silva', 'joao@email.com', '(11) 99999-9999'),
('Maria Santos', 'maria@email.com', '(11) 88888-8888'),
('Pedro Costa', 'pedro@email.com', '(11) 77777-7777');

INSERT INTO livros (titulo, genero, ano_publicacao, id_autor) VALUES
('Dom Casmurro', 'Romance', 1899, 1),
('Memórias Póstumas de Brás Cubas', 'Romance', 1881, 1),
('A Hora da Estrela', 'Romance', 1977, 2),
('Capitães da Areia', 'Romance', 1937, 3),
('1984', 'Ficção Científica', 1949, 4),
('Harry Potter e a Pedra Filosofal', 'Fantasia', 1997, 5);