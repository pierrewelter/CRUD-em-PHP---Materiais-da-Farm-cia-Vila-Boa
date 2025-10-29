<?php
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die('ID inválido.');
}

try {
    $stmt = $pdo->prepare("DELETE FROM insumos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: index.php');
    exit;
} catch (Exception $e) {
    die('Erro ao excluir: ' . $e->getMessage());
}

?>