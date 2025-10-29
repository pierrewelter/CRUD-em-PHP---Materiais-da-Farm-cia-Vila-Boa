<?php
require_once 'config.php';

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

try {
    if ($search !== '') {
        $stmt = $pdo->prepare("SELECT * FROM insumos WHERE nome LIKE :search ORDER BY id DESC");
        $stmt->execute([':search' => "%$search%"]);
    } else {
        $stmt = $pdo->query("SELECT * FROM insumos ORDER BY id DESC");
    }
    $insumos = $stmt->fetchAll();
} catch (Exception $e) {
    $error = 'Erro ao buscar insumos: ' . $e->getMessage();
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Farmácia Vila Boa - Insumos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Insumos - Farmácia Vila Boa</h1>
    <p><a href="create.php">+ Adicionar novo insumo</a></p>

    <form method="get" action="index.php" class="search-form">
        <input type="text" name="search" placeholder="Buscar por nome" value="<?= h($search) ?>">
        <button type="submit">Buscar</button>
        <a href="index.php">Limpar</a>
    </form>

    <?php if (!empty($error)): ?>
        <div class="error"><?= h($error) ?></div>
    <?php endif; ?>

    <?php if (empty($insumos)): ?>
        <p>Nenhum insumo encontrado.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Unidade</th>
                <th>Estoque</th>
                <th>Preço (R$)</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($insumos as $i): ?>
                <tr>
                    <td><?= h($i['id']) ?></td>
                    <td><?= h($i['nome']) ?></td>
                    <td><?= h($i['unidade']) ?></td>
                    <td><?= h($i['estoque_atual']) ?></td>
                    <td><?= number_format($i['preco'], 2, ',', '.') ?></td>
                    <td>
                        <a href="edit.php?id=<?= h($i['id']) ?>">Editar</a> |
                        <a href="delete.php?id=<?= h($i['id']) ?>" onclick="return confirm('Confirma exclusão?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
