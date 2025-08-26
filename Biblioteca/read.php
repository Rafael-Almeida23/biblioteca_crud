<?php
require 'index.php';

// Paginação
$pagina = $_GET['pagina'] ?? 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

if ($_GET['tipo'] == "livros") {
    $filtro = [];
    $sql = "SELECT l.*, a.nome AS autor FROM livros l 
            JOIN autores a ON l.id_autor=a.id_autor WHERE 1=1";

    if (!empty($_GET['genero'])) {
        $sql .= " AND l.genero=?";
        $filtro[] = $_GET['genero'];
    }
    if (!empty($_GET['autor'])) {
        $sql .= " AND a.nome LIKE ?";
        $filtro[] = "%".$_GET['autor']."%";
    }
    if (!empty($_GET['ano'])) {
        $sql .= " AND l.ano_publicacao=?";
        $filtro[] = $_GET['ano'];
    }

    $sql .= " LIMIT $limite OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($filtro);

    foreach ($stmt as $row) {
        echo $row['titulo']." - ".$row['autor']."<br>";
    }
}

if ($_GET['tipo'] == "emprestimos") {
    if ($_GET['status'] == "ativos") {
        $sql = "SELECT e.id_emprestimo, l.titulo, le.nome, e.data_emprestimo 
                FROM emprestimos e 
                JOIN livros l ON e.id_livro=l.id_livro 
                JOIN leitores le ON e.id_leitor=le.id_leitor
                WHERE e.data_devolucao IS NULL";
    } else {
        $sql = "SELECT e.id_emprestimo, l.titulo, le.nome, e.data_emprestimo, e.data_devolucao 
                FROM emprestimos e 
                JOIN livros l ON e.id_livro=l.id_livro 
                JOIN leitores le ON e.id_leitor=le.id_leitor
                WHERE e.data_devolucao IS NOT NULL";
    }
    foreach ($pdo->query($sql) as $row) {
        echo $row['titulo']." emprestado a ".$row['nome']."<br>";
    }
}

if ($_GET['tipo'] == "livros_leitor" && !empty($_GET['id_leitor'])) {
    $sql = "SELECT l.titulo FROM emprestimos e 
            JOIN livros l ON e.id_livro=l.id_livro 
            WHERE e.id_leitor=? AND e.data_devolucao IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id_leitor']]);
    foreach ($stmt as $row) {
        echo $row['titulo']."<br>";
    }
}
?>