<?php
// ==== Conexão com o banco ====
$host = "localhost";     
$dbname = "biblioteca_almeida_ds1";   
$username = "root";      
$password = "root";          

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

// ==== Buscar autores (READ) ====
$stmt = $pdo->query("SELECT * FROM autores");
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <a href="edit_autor.php?id=<?= $autor['id_autor'] ?>">Editar</a> | 
                        <a href="?delete_autor=<?= $autor['id_autor'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</body>
</html>