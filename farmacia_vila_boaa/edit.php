<?php
require_once 'config.php';

$errors = [];
$success = '';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die('ID inválido.');
}

$stmt = $pdo->prepare("SELECT * FROM insumos WHERE id = :id");
$stmt->execute([':id' => $id]);
$insumo = $stmt->fetch();
if (!$insumo) {
    die('Insumo não encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $unidade = trim($_POST['unidade'] ?? '');
    $estoque = $_POST['estoque_atual'] ?? '';
    $preco = $_POST['preco'] ?? '';

    if ($nome === '') $errors[] = 'Nome é obrigatório.';
    if ($unidade === '') $errors[] = 'Unidade é obrigatória.';

    if ($estoque === '' || !is_numeric($estoque) || intval($estoque) < 0) {
        $errors[] = 'Estoque deve ser um número inteiro >= 0.';
    } else {
        $estoque = intval($estoque);
    }

    if ($preco === '' || !is_numeric($preco) || floatval($preco) < 0) {
        $errors[] = 'Preço deve ser um número >= 0.';
    } else {
        $preco = number_format((float)$preco, 2, '.', '');
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE insumos SET nome = :nome, unidade = :unidade, estoque_atual = :estoque, preco = :preco WHERE id = :id");
            $stmt->execute([':nome' => $nome, ':unidade' => $unidade, ':estoque' => $estoque, ':preco' => $preco, ':id' => $id]);
            $success = 'Insumo atualizado com sucesso!';
            $stmt = $pdo->prepare("SELECT * FROM insumos WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $insumo = $stmt->fetch();
        } catch (Exception $e) {
            $errors[] = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Editar Insumo - Farmácia Vila Boa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Editar Insumo</h1>
    <p><a href="index.php">Voltar à lista</a></p>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= h($success) ?></div>
    <?php endif; ?>

    <form method="post" action="edit.php?id=<?= h($insumo['id']) ?>">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= h($insumo['nome']) ?>">

        <label for="unidade">Unidade:</label>
        <input type="text" id="unidade" name="unidade" value="<?= h($insumo['unidade']) ?>">

        <label for="estoque_atual">Estoque atual:</label>
        <input type="number" id="estoque_atual" name="estoque_atual" min="0" value="<?= h($insumo['estoque_atual']) ?>">

        <label for="preco">Preço (ex: 12.34):</label>
        <input type="text" id="preco" name="preco" value="<?= h($insumo['preco']) ?>">

        <button type="submit">Salvar</button>
    </form>

</div>
</body>
</html>
