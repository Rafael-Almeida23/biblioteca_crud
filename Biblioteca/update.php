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
    } elseif ($tipo == "leitor") {
        $id_leitor = $_GET['id'];
        
        // Fetch reader data
        $stmt = $pdo->prepare("SELECT * FROM leitores WHERE id_leitor = ?");
        $stmt->execute([$id_leitor]);
        $leitor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$leitor) {
            die("Leitor não encontrado!");
        }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Leitor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Leitor</h1>
    
    <form method="POST">
        <input type="hidden" name="tipo" value="leitor">
        <input type="hidden" name="id_leitor" value="<?= htmlspecialchars($leitor['id_leitor']) ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($leitor['nome']) ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($leitor['email']) ?>"><br><br>
        
        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($leitor['telefone']) ?>"><br><br>
        
        <button type="submit">Atualizar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>

<?php
    } elseif ($tipo == "emprestimo") {
        $id_emprestimo = $_GET['id'];
        
        // Fetch loan data
        $stmt = $pdo->prepare("SELECT * FROM emprestimos WHERE id_emprestimo = ?");
        $stmt->execute([$id_emprestimo]);
        $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$emprestimo) {
            die("Empréstimo não encontrado!");
        }
        
        // Fetch all books for the select dropdown
        $stmt = $pdo->query("SELECT id_livro, titulo FROM livros");
        $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fetch all readers for the select dropdown
        $stmt = $pdo->query("SELECT id_leitor, nome FROM leitores");
        $leitores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Empréstimo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Empréstimo</h1>
    
    <form method="POST">
        <input type="hidden" name="tipo" value="emprestimo">
        <input type="hidden" name="id_emprestimo" value="<?= htmlspecialchars($emprestimo['id_emprestimo']) ?>">
        
        <label for="id_livro">Livro:</label>
        <select id="id_livro" name="id_livro" required>
            <option value="">Selecione um Livro</option>
            <?php foreach ($livros as $livro): ?>
                <option value="<?= $livro['id_livro'] ?>" <?= $livro['id_livro'] == $emprestimo['id_livro'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($livro['titulo']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label for="id_leitor">Leitor:</label>
        <select id="id_leitor" name="id_leitor" required>
            <option value="">Selecione um Leitor</option>
            <?php foreach ($leitores as $leitor): ?>
                <option value="<?= $leitor['id_leitor'] ?>" <?= $leitor['id_leitor'] == $emprestimo['id_leitor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($leitor['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        
        <label for="data_emprestimo">Data de Empréstimo:</label>
        <input type="date" id="data_emprestimo" name="data_emprestimo" value="<?= htmlspecialchars($emprestimo['data_emprestimo']) ?>" required><br><br>
        
        <label for="data_devolucao">Data de Devolução:</label>
        <input type="date" id="data_devolucao" name="data_devolucao" value="<?= htmlspecialchars($emprestimo['data_devolucao']) ?>"><br><br>
        
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

    if ($tipo == "leitor") {
        $stmt = $pdo->prepare("UPDATE leitores SET nome=?, email=?, telefone=? WHERE id_leitor=?");
        $stmt->execute([$_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['id_leitor']]);
        echo "Leitor atualizado!";
        header("Location: index.php");
        exit;
    }

    if ($tipo == "emprestimo") {
        // Verificar se a data de devolução é válida
        $data_emprestimo = $_POST['data_emprestimo'];
        $data_devolucao = $_POST['data_devolucao'];
        
        if ($data_devolucao && $data_devolucao < $data_emprestimo) {
            die("Data de devolução inválida!");
        }

        $stmt = $pdo->prepare("UPDATE emprestimos SET id_livro=?, id_leitor=?, data_emprestimo=?, data_devolucao=? WHERE id_emprestimo=?");
        $stmt->execute([$_POST['id_livro'], $_POST['id_leitor'], $_POST['data_emprestimo'], $_POST['data_devolucao'], $_POST['id_emprestimo']]);
        echo "Empréstimo atualizado!";
        header("Location: index.php");
        exit;
    }
}
?>
