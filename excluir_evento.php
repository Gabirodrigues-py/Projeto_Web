<?php
// BACK-END CORRIGIDO: Removido o erro de sintaxe e ajustada a conexão com o banco.

require __DIR__."/vendor/autoload.php";
session_start();

use \App\Db\Database;

// Verificação de segurança de administrador.
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php?status=acesso_negado');
    exit;
}

// O seu front-end usa um link (GET), então vamos pegar o ID da URL.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    // A lógica de exclusão agora usa sua classe Database.
    $db = new Database('eventos');
    $db->execute('DELETE FROM eventos WHERE id = ?', [$id]);
}

// Redireciona de volta para o painel de administração.
header('Location: painel_admin.php');
exit;