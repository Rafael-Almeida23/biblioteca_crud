<?php
// ==== Conexão com o banco ====
$host = "localhost";     
$dbname = "biblioteca_almeida_ds1";   
$username = "root";      
$password = "";          

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// ==== Inserir autor (CREATE) ====
if (isset($_POST['add_autor'])) {
    $nome = $_POST['nome'];
    $nacionalidade = $_POST['nacionalidade'];
    $ano = $_POST['ano'];

    $sql = "INSERT INTO autores (nome, nacionalidade, ano_nascimento) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $nacionalidade, $ano]);
    header("Location: index.php"); // recarrega página
    exit;
}

// ==== Deletar autor (DELETE) ====
if (isset($_GET['delete_autor'])) {
    $id = $_GET['delete_autor'];
    $sql = "DELETE FROM autores WHERE id_autor = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// ==== Inserir livro (CREATE) ====
if (isset($_POST['add_livro'])) {
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $ano = $_POST['ano_publicacao'];
    $id_autor = $_POST['id_autor'];

    // Validação do ano de publicação
    if ($ano <= 1500 || $ano > date("Y")) {
        die("Ano de publicação inválido!");
    }

    $sql = "INSERT INTO livros (titulo, genero, ano_publicacao, id_autor) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $genero, $ano, $id_autor]);
    header("Location: index.php"); // recarrega página
    exit;
}

// ==== Deletar livro (DELETE) ====
if (isset($_GET['delete_livro'])) {
    $id = $_GET['delete_livro'];
    $sql = "DELETE FROM livros WHERE id_livro = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// ==== Buscar autores (READ) ====
$stmt = $pdo->query("SELECT * FROM autores");
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==== Buscar livros (READ) ====
$stmt = $pdo->query("SELECT l.*, a.nome AS autor FROM livros l JOIN autores a ON l.id_autor = a.id_autor");
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==== Buscar todos os autores para o select do formulário de livros ====
$stmt = $pdo->query("SELECT id_autor, nome FROM autores");
$autores_select = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Almeida</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Biblioteca Almeida – Painel CRUD</h1>

    <!-- FORMULÁRIO PARA CADASTRAR AUTOR -->
    <section>
        <h2>Adicionar Autor</h2>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome do Autor" required>
            <input type="text" name="nacionalidade" placeholder="Nacionalidade">
            <input type="number" name="ano" placeholder="Ano de nascimento">
            <button type="submit" name="add_autor">Adicionar</button>
        </form>
    </section>

    <!-- LISTA DE AUTORES -->
    <!-- FORMULÁRIO PARA CADASTRAR LIVRO -->
    <section>
        <h2>Adicionar Livro</h2>
        <form method="POST">
            <input type="text" name="titulo" placeholder="Título do Livro" required>
            <input type="text" name="genero" placeholder="Gênero">
            <input type="number" name="ano_publicacao" placeholder="Ano de publicação" required>
            <select name="id_autor" required>
                <option value="">Selecione um Autor</option>
                <?php foreach ($autores_select as $autor): ?>
                    <option value="<?= $autor['id_autor'] ?>"><?= $autor['nome'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="add_livro">Adicionar</button>
        </form>
    </section>

    <!-- LISTA DE AUTORES -->
    <section>
        <h2>Lista de Autores</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Nacionalidade</th>
                <th>Ano Nascimento</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($autores as $autor): ?>
                <tr>
                    <td><?= $autor['id_autor'] ?></td>
                    <td><?= $autor['nome'] ?></td>
                    <td><?= $autor['nacionalidade'] ?></td>
                    <td><?= $autor['ano_nascimento'] ?></td>
                    <td>
                        <a href="update.php?id=<?= $autor['id_autor'] ?>&tipo=autor">Editar</a> | 
                        <a href="?delete_autor=<?= $autor['id_autor'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- LISTA DE LIVROS -->
    <section>
        <h2>Lista de Livros</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Gênero</th>
                <th>Ano Publicação</th>
                <th>Autor</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($livros as $livro): ?>
                <tr>
                    <td><?= $livro['id_livro'] ?></td>
                    <td><?= $livro['titulo'] ?></td>
                    <td><?= $livro['genero'] ?></td>
                    <td><?= $livro['ano_publicacao'] ?></td>
                    <td><?= $livro['autor'] ?></td>
                    <td>
                        <a href="update.php?id=<?= $livro['id_livro'] ?>&tipo=livro">Editar</a> | 
                        <a href="?delete_livro=<?= $livro['id_livro'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</body>
</html>
