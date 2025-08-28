<?php
require 'index.php';

// Handle GET request to display edit form for author
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id_autor = $_GET['id'];
    
    // Fetch author data
    $stmt = $pdo->prepare("SELECT * FROM autores WHERE id_autor = ?");
    $stmt->execute([$id_autor]);
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$autor) {
        die("Autor não encontrado!");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Autor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Autor</h1>
    
    <form method="POST">
        <input type="hidden" name="tipo" value="autor">
        <input type="hidden" name="id_autor" value="<?= htmlspecialchars($autor['id_autor']) ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($autor['nome']) ?>" required><br><br>
        
        <label for="nacionalidade">Nacionalidade:</label>
        <input type="text" id="nacionalidade" name="nacionalidade" value="<?= htmlspecialchars($autor['nacionalidade']) ?>"><br><br>
        
        <label for="ano_nascimento">Ano de Nascimento:</label>
        <input type="number" id="ano_nascimento" name="ano_nascimento" value="<?= htmlspecialchars($autor['ano_nascimento']) ?>"><br><br>
        
        <button type="submit">Atualizar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>

<?php
    exit;
}

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