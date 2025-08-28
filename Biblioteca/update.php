<?php
require 'index.php';

// Handle GET request to display edit form for author or book
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    // Verificar o tipo de item a ser editado
    $tipo = $_GET['tipo'] ?? 'autor';
    
    if ($tipo == "autor") {
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
    } elseif ($tipo == "livro") {
        $id_livro = $_GET['id'];
        
        // Fetch book data
        $stmt = $pdo->prepare("SELECT * FROM livros WHERE id_livro = ?");
        $stmt->execute([$id_livro]);
        $livro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$livro) {
            die("Livro não encontrado!");
        }
        
        // Fetch all authors for the select dropdown
        $stmt = $pdo->query("SELECT id_autor, nome FROM autores");
        $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Livro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Livro</h1>
    
    <form method="POST">
        <input type="hidden" name="tipo" value="livro">
        <input type="hidden" name="id_livro" value="<?= htmlspecialchars($livro['id_livro']) ?>">
        
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($livro['titulo']) ?>" required><br><br>
        
        <label for="genero">Gênero:</label>
        <input type="text" id="genero" name="genero" value="<?= htmlspecialchars($livro['genero']) ?>"><br><br>
        
        <label for="ano_publicacao">Ano de Publicação:</label>
        <input type="number" id="ano_publicacao" name="ano_publicacao" value="<?= htmlspecialchars($livro['ano_publicacao']) ?>" required><br><br>
        
        <label for="id_autor">Autor:</label>
        <select id="id_autor" name="id_autor" required>
            <option value="">Selecione um Autor</option>
            <?php foreach ($autores as $autor): ?>
                <option value="<?= $autor['id_autor'] ?>" <?= $autor['id_autor'] == $livro['id_autor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($autor['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <button type="submit">Atualizar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>

<?php
    }
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
        header("Location: index.php");
        exit;
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
