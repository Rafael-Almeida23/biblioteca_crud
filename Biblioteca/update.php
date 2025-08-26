<?php
require 'index.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];

    if ($tipo == "autor") {
        $stmt = $pdo->prepare("UPDATE autores SET nome=?, nacionalidade=?, ano_nascimento=? WHERE id_autor=?");
        $stmt->execute([$_POST['nome'], $_POST['nacionalidade'], $_POST['ano_nascimento'], $_POST['id_autor']]);
        echo "Autor atualizado!";
        header("Location: index.php");
        exit;
    }

    if ($tipo == "livro") {
        $ano = $_POST['ano_publicacao'];
        if ($ano <= 1500 || $ano > date("Y")) {
            die("Ano de publicação inválido!");
        }

        $stmt = $pdo->prepare("UPDATE livros SET titulo=?, genero=?, ano_publicacao=?, id_autor=? WHERE id_livro=?");
        $stmt->execute([$_POST['titulo'], $_POST['genero'], $ano, $_POST['id_autor'], $_POST['id_livro']]);
        echo "Livro atualizado!";
    }

    if ($tipo == "emprestimo") {
        $stmt = $pdo->prepare("SELECT data_emprestimo FROM emprestimos WHERE id_emprestimo=?");
        $stmt->execute([$_POST['id_emprestimo']]);
        $emprestimo = $stmt->fetch();

        if ($_POST['data_devolucao'] < $emprestimo['data_emprestimo']) {
            die("Data de devolução inválida!");
        }

        $stmt = $pdo->prepare("UPDATE emprestimos SET data_devolucao=? WHERE id_emprestimo=?");
        $stmt->execute([$_POST['data_devolucao'], $_POST['id_emprestimo']]);
        echo "Empréstimo atualizado!";
    }
}
?>