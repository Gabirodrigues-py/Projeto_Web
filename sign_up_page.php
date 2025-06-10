<?php
require __DIR__ . "/vendor/autoload.php";
session_start();

use \App\Db\Database;

// Passo 1: Verificar se o usuário está logado.
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o login.
    // Ele será enviado de volta para a página do evento após logar.
    header('Location: login_process.php?status=login_necessario&redirect_url=' . urlencode('evento_detalhes.php?id=' . $_GET['evento_id']));
    exit;
}

// Passo 2: Validar o ID do evento da URL.
$evento_id = filter_input(INPUT_GET, 'evento_id', FILTER_VALIDATE_INT);
if (!$evento_id) {
    header('Location: index.php?status=evento_invalido');
    exit;
}

// Passo 3: Processar a inscrição.
$usuario_id = $_SESSION['usuario_id'];
$db = new Database('inscricoes');

// Verifica se o usuário já está inscrito no evento.
$inscricaoExistente = $db->execute(
    "SELECT id FROM inscricoes WHERE usuario_id = ? AND evento_id = ?",
    [$usuario_id, $evento_id]
)->fetch();

if ($inscricaoExistente) {
    // Se já estiver inscrito, redireciona para a página de eventos com uma mensagem de erro.
    header('Location: My_Events_page.php?status=ja_inscrito');
    exit;
} else {
    // Se não estiver inscrito, insere a nova inscrição no banco.
    $db->insert([
        'usuario_id' => $usuario_id,
        'evento_id'  => $evento_id
    ]);

    // Redireciona para a página "Meus Eventos" com uma mensagem de sucesso.
    header('Location: My_Events_page.php?status=inscrito_sucesso');
    exit;
}