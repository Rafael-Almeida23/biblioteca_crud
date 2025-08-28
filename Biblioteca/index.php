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

// ==== Inserir leitor (CREATE) ====
if (isset($_POST['add_leitor'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO leitores (nome, email, telefone) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $telefone]);
    header("Location: index.php"); // recarrega página
    exit;
}

// ==== Deletar leitor (DELETE) ====
if (isset($_GET['delete_leitor'])) {
    $id = $_GET['delete_leitor'];
    $sql = "DELETE FROM leitores WHERE id_leitor = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// ==== Inserir empréstimo (CREATE) ====
if (isset($_POST['add_emprestimo'])) {
    $id_livro = $_POST['id_livro'];
    $id_leitor = $_POST['id_leitor'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'] ?? null;

    // Verificar se o livro já está emprestado
    $check = $pdo->prepare("SELECT COUNT(*) FROM emprestimos WHERE id_livro=? AND data_devolucao IS NULL");
    $check->execute([$id_livro]);
    if ($check->fetchColumn() > 0) {
        die("Este livro já está emprestado!");
    }

    $sql = "INSERT INTO emprestimos (id_livro, id_leitor, data_emprestimo, data_devolucao) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_livro, $id_leitor, $data_emprestimo, $data_devolucao]);
    header("Location: index.php"); // recarrega página
    exit;
}

// ==== Deletar empréstimo (DELETE) ====
if (isset($_GET['delete_emprestimo'])) {
    $id = $_GET['delete_emprestimo'];
    $sql = "DELETE FROM emprestimos WHERE id_emprestimo = ?";
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

// ==== Buscar leitores (READ) ====
$stmt = $pdo->query("SELECT * FROM leitores");
$leitores = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==== Buscar empréstimos (READ) ====
$stmt = $pdo->query("SELECT e.*, l.titulo AS livro, le.nome AS leitor FROM emprestimos e JOIN livros l ON e.id_livro = l.id_livro JOIN leitores le ON e.id_leitor = le.id_leitor");
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==== Buscar todos os autores para o select do formulário de livros ====
$stmt = $pdo->query("SELECT id_autor, nome FROM autores");
$autores_select = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==== Buscar todos os livros para o select do formulário de empréstimos ====
$stmt = $pdo->query("SELECT id_livro, titulo FROM livros");
$livros_select = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==== Buscar todos os leitores para o select do formulário de empréstimos ====
$stmt = $pdo->query("SELECT id_leitor, nome FROM leitores");
$leitores_select = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <!-- FORMULÁRIO PARA CADASTRAR LEITOR -->
    <section>
        <h2>Adicionar Leitor</h2>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome do Leitor" required>
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="telefone" placeholder="Telefone">
            <button type="submit" name="add_leitor">Adicionar</button>
        </form>
    </section>

    <!-- FORMULÁRIO PARA CADASTRAR EMPRÉSTIMO -->
    <section>
        <h2>Adicionar Empréstimo</h2>
        <form method="POST">
            <select name="id_livro" required>
                <option value="">Selecione um Livro</option>
                <?php foreach ($livros_select as $livro): ?>
                    <option value="<?= $livro['id_livro'] ?>"><?= $livro['titulo'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="id_leitor" required>
                <option value="">Selecione um Leitor</option>
                <?php foreach ($leitores_select as $leitor): ?>
                    <option value="<?= $leitor['id_leitor'] ?>"><?= $leitor['nome'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="data_emprestimo" placeholder="Data de Empréstimo" required>
            <input type="date" name="data_devolucao" placeholder="Data de Devolução">
            <button type="submit" name="add_emprestimo">Adicionar</button>
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

    <!-- LISTA DE LEITORES -->
    <section>
        <h2>Lista de Leitores</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($leitores as $leitor): ?>
                <tr>
                    <td><?= $leitor['id_leitor'] ?></td>
                    <td><?= $leitor['nome'] ?></td>
                    <td><?= $leitor['email'] ?></td>
                    <td><?= $leitor['telefone'] ?></td>
                    <td>
                        <a href="update.php?id=<?= $leitor['id_leitor'] ?>&tipo=leitor">Editar</a> | 
                        <a href="?delete_leitor=<?= $leitor['id_leitor'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- LISTA DE EMPRÉSTIMOS -->
    <section>
        <h2>Lista de Empréstimos</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Livro</th>
                <th>Leitor</th>
                <th>Data Empréstimo</th>
                <th>Data Devolução</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($emprestimos as $emprestimo): ?>
                <tr>
                    <td><?= $emprestimo['id_emprestimo'] ?></td>
                    <td><?= $emprestimo['livro'] ?></td>
                    <td><?= $emprestimo['leitor'] ?></td>
                    <td><?= $emprestimo['data_emprestimo'] ?></td>
                    <td><?= $emprestimo['data_devolucao'] ?? 'Não devolvido' ?></td>
                    <td>
                        <a href="update.php?id=<?= $emprestimo['id_emprestimo'] ?>&tipo=emprestimo">Editar</a> | 
                        <a href="?delete_emprestimo=<?= $emprestimo['id_emprestimo'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</body>
</html>
