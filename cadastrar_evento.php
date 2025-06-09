<?php
require __DIR__."/vendor/autoload.php";
session_start();

// --- INÍCIO DA VERIFICAÇÃO DE PERMISSÃO ---
// Verifica se o usuário está logado E se ele é um administrador.
// A verificação `isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true` garante
// que apenas usuários marcados como admin possam acessar.
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Se não for admin, redireciona para a página inicial.
    header('Location: index.php?status=acesso_negado');
    exit; // Impede a execução do resto da página.
}
// --- FIM DA VERIFICAÇÃO DE PERMISSÃO ---

use \App\Db\Database;

$mensagem = '';

// Lógica para processar o formulário
if(isset($_POST['titulo'], $_POST['descricao'], $_POST['data_evento'], $_POST['hora_evento'], $_POST['local']) && isset($_FILES['imagem'])){

    if(empty($_POST['titulo']) || empty($_FILES['imagem']['name'])){
        $mensagem = "Título e imagem são obrigatórios.";
    } else {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $data_evento = $_POST['data_evento'];
        $hora_evento = $_POST['hora_evento'];
        $local = $_POST['local'];
        $imagem = $_FILES['imagem'];

        $target_dir = "assets/imagens/eventos/";
        $imageFileType = strtolower(pathinfo($imagem["name"], PATHINFO_EXTENSION));
        $nome_imagem = 'evento_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $nome_imagem;

        if (getimagesize($imagem["tmp_name"])) {
            if (move_uploaded_file($imagem["tmp_name"], $target_file)) {
                $db = new Database('eventos');
                $db->insert([
                    'titulo' => $titulo,
                    'descricao' => $descricao,
                    'data_evento' => $data_evento,
                    'hora_evento' => $hora_evento,
                    'local' => $local,
                    'imagem' => $target_file
                ]);
                $mensagem = "Evento cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao fazer upload da imagem.";
            }
        } else {
            $mensagem = "O arquivo enviado não é uma imagem válida.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/registrer_page.css">
    <title>Cadastro de Evento</title>
    <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">
</head>

<body>
    <header class="header">
        <nav class="nav" aria-label="navegação principal">
            <ul class="nav_list">
                <li class="nav_item">
                    <a href="index.php">
                        <img src="assets/imagens/logo-225x150.png" alt="Logo do Ticket.fun" class="imagem_header_logo">
                    </a>
                </li>
                 <li class="nav_item">
                    <a href="index.php" class="nav_link">Inicio</a>
                </li>
                <li class="nav_item">
                    <a href="logout.php" class="nav_link">Sair</a>
                </li>
            </ul>
        </nav>
    </header>

    <section class="titulo_pagina">
        <div class="titulo_cadastro">
            <h1 class="titulo_cadastro">Cadastro de Evento (Admin)</h1>
        </div>
    </section>

    <?php if ($mensagem !== ''): ?>
    <div style="text-align:center; margin:1em 0; color: <?= (strpos($mensagem, 'sucesso') !== false) ? 'green' : 'red' ?>;">
      <?= htmlentities($mensagem, ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php endif; ?>

    <section class="formulario_info_cadastro">
        <form action="cadastrar_evento.php" method="post" class="formulario_campos" enctype="multipart/form-data">
            <div class="titulo_h3">
                <h3 class="titulo_h3">Informações do Evento</h3>
            </div>

            <input type="text" placeholder="Título do Evento" name="titulo" class="campo_email" required> <br><br>
            <textarea placeholder="Descrição do Evento" name="descricao" class="campo_email" style="height: 120px;" required></textarea> <br><br>
            <input type="date" name="data_evento" class="campo-informaçõese" required>
            <input type="time" name="hora_evento" class="campo-informaçõesd" required> <br><br>
            <input type="text" placeholder="Local do Evento" name="local" class="campo_email" required> <br><br>

            <label for="imagem" style="font-family: var(--fonte-texto); display: block; margin-bottom: 10px;">Imagem do Evento:</label>
            <input type="file" name="imagem" id="imagem" accept="image/png, image/jpeg, image/webp" required style="display: block; width: 100%;"> <br><br>

            <div class="botao_cadastro">
                <button type="submit" class="botao_cadastrar">Cadastrar Evento</button>
            </div>
        </form>
    </section>

    <footer class="footer">
        <div class="footer_bottom footer_columns">
            <p>© 2025 Feito com 💗 por fãs da Hello Kitty.</p>
        </div>
    </footer>
</body>
</html>