<?php
// BACK-END CORRIGIDO: Este novo arquivo faz o botão "Cancelar Inscrição" funcionar.

require __DIR__ . '/vendor/autoload.php';
session_start();
use \App\Db\Database;

$usuarioId = $_SESSION['usuario_id'] ?? null;
$inscId    = filter_input(INPUT_POST, 'inscricao_id', FILTER_VALIDATE_INT);

if ($usuarioId && $inscId) {
    $db = new Database('inscricoes'); // Define a tabela a ser usada
    $db->execute('DELETE FROM inscricoes WHERE id = ? AND usuario_id = ?', [$inscId, $usuarioId]);
}

header('Location: My_Events_page.php');
exit;