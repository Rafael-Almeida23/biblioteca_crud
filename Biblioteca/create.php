<?php
require 'index.php'; // conexão PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];

    if ($tipo == "autor") {
        $stmt = $pdo->prepare("INSERT INTO autores (nome, nacionalidade, ano_nascimento) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nome'], $_POST['nacionalidade'], $_POST['ano_nascimento']]);
        echo "Autor cadastrado!";
    }

    if ($tipo == "leitor") {
        $stmt = $pdo->prepare("INSERT INTO leitores (nome, email, telefone) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['telefone']]);
        echo "Leitor cadastrado!";
    }

    if ($tipo == "livro") {
        $ano = $_POST['ano_publicacao'];
        if ($ano <= 1500 || $ano > date("Y")) {
            die("Ano de publicação inválido!");
        }

        $stmt = $pdo->prepare("INSERT INTO livros (titulo, genero, ano_publicacao, id_autor) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['titulo'], $_POST['genero'], $ano, $_POST['id_autor']]);
        echo "Livro cadastrado!";
    }

    if ($tipo == "emprestimo") {
        $livro_id = $_POST['id_livro'];
        $leitor_id = $_POST['id_leitor'];
        $data_emprestimo = $_POST['data_emprestimo'];
        $data_devolucao = $_POST['data_devolucao'] ?? null;

        // Verificar se o livro já está emprestado
        $check = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE id_livro=? AND data_devolucao IS NULL");
        $check->execute([$livro_id]);
        if ($check->fetchColumn() > 0) {
            die("Este livro já está emprestado!");
        }