<?php
require 'index.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $id = $_POST['id'];

    if ($tipo == "autor") {
        $stmt = $pdo->prepare("DELETE FROM autores WHERE id_autor=?");
        $stmt->execute([$id]);
        echo "Autor excluído!";
    }

    if ($tipo == "livro") {
        $stmt = $pdo->prepare("DELETE FROM livros WHERE id_livro=?");
        $stmt->execute([$id]);
        echo "Livro excluído!";
    }

    if ($tipo == "leitor") {
        $stmt = $pdo->prepare("DELETE FROM leitores WHERE id_leitor=?");
        $stmt->execute([$id]);
        echo "Leitor excluído!";
    }

    if ($tipo == "emprestimo") {
        $stmt = $pdo->prepare("DELETE FROM emprestimos WHERE id_emprestimo=?");
        $stmt->execute([$id]);
        echo "Empréstimo excluído!";
    }
}
?>