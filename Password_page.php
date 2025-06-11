<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login_process.php');
    exit;
}

// Conexão com o banco
$servidor  = "localhost";
$usuarioDB = "root";
$senhaDB   = "";
$bancoDB   = "hello_kitty";

$conn = new mysqli($servidor, $usuarioDB, $senhaDB, $bancoDB);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// ID do usuário logado
$idUsuario = $_SESSION['usuario_id'];
$mensagem = "";

// Se houve envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaNova     = trim($_POST['senha_nova'] ?? '');
    $confirmaSenha = trim($_POST['confirma_senha'] ?? '');

    if ($senhaNova === '' || $confirmaSenha === '') {
        $mensagem = "Preencha ambos os campos de senha.";
    } elseif ($senhaNova !== $confirmaSenha) {
        $mensagem = "As senhas não coincidem.";
    } else {
        $senhaHash = password_hash($senhaNova, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $senhaHash, $idUsuario);

        if ($stmt->execute()) {
            $mensagem = "Senha atualizada com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar senha: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/password_reset_page.css">
    <title>Redefinir Senha</title>
    <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">
</head>

<body>
    <header class="header">
        <nav class="nav" aria-label="navegação principal">
            <ul class="nav_list">
                <li class="menu_toggle">
                    <button class="menu_toggle_icon" aria-label="menu hamburguer">
                        <img src="assets/imagens/menu.svg" alt="menu hamburguer">
                    </button>
                </li>
                <li class="nav_item">
                    <a href="index.php">
                        <img src="assets/imagens/logo-225x150.png" alt="Logo do Ticket.fun" class="imagem_header_logo">
                    </a>
                </li>
                <li class="nav_item">
                    <a href="index.php">
                        <img src="assets/imagens/imagem_superior.png" alt="Logo do Ticket.fun" class="imagem_header_sanrio">
                    </a>
                </li>
                <li class="nav_item">
                    <a href="about_us_page.html" class="nav_link">Sobre nós</a>
                </li>
                <li class="nav_item">
                    <a href="index.php" class="nav_link">Início</a>
                </li>
            </ul>
        </nav>
    </header>

    <section class="titulo_pagina">
        <div class="titulo_nova_senha">
            <h1 class="titulo_nova_senha">Nova Senha</h1>
        </div>
    </section>

    <section class="menu_superior">
        <nav class="lista_menu">
            <ul class="menu_superior_itens">
                <li>
                    <a href="profile_page.php" class="Link-Menu-Lateral" id="perfil">PERFIL</a>
                </li>
                <li>
                    <a href="My_Events_page.php" class="Link-Menu-Lateral" id="meus_eventos">MEUS EVENTOS</a>
                </li>
                <li>
                    <a href="password_page.php" class="Link-Menu-Lateral" id="senhas">SENHAS</a>
                </li>
            </ul>
        </nav>
    </section>

    <section class="nova_senha">
        <?php if ($mensagem): ?>
            <div class="mensagem-feedback" style="color: <?= (strpos($mensagem, 'sucesso') !== false) ? 'green' : 'red' ?>; text-align: center; margin-bottom: 15px;">
                <?= htmlentities($mensagem, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="formulario">
            <div class="campo-senha-wrapper">
                <input type="password" placeholder="Nova Senha" name="senha_nova" id="nova_senha" class="campo-nova-senha" maxlength="255" required>
                <button type="button" class="toggle-senha" aria-label="Mostrar senha" onclick="toggleSenha('nova_senha', this)">
                    👁️
                </button>
            </div>
            <br><br>
            <div class="campo-senha-wrapper">
                <input type="password" placeholder="Confirmar Nova Senha" name="confirma_senha" id="confirmar_nova_senha" class="campo-nova-senha" maxlength="255" required>
                <button type="button" class="toggle-senha" aria-label="Mostrar senha" onclick="toggleSenha('confirmar_nova_senha', this)">
                    👁️
                </button>
            </div>
            <br><br>
            <button type="submit" id="botao-salvar-nova-senha" class="botao_salvar nova_senha">SALVAR</button>
        </form>
    </section>

    <script>
        function toggleSenha(idCampo, btn) {
            const campo = document.getElementById(idCampo);
            if (campo.type === 'password') {
                campo.type = 'text';
                btn.textContent = '🙈';
            } else {
                campo.type = 'password';
                btn.textContent = '👁️';
            }
        }
    </script>

    <footer class="footer">
        <div class="footer_columns">
            <div class="footer_column_logo">
                <img src="assets/imagens/logo-225x150.png" alt="logo Ticket.fun" class="footer_column_img footer_column_list">
            </div>
            <div class="footer_column">
                <h4 class="footer_column_titulo">Funcionamento</h4>
                <ul class="footer_column_list">
                    <li><p>Somos uma plataforma 100% digital!<br>Suporte ao cliente: Segunda a sexta - 08:00 às 18:00</p></li>
                    <li><a href="mailto:ticket.fun_suporte@gmail.com" class="footer_column_list_a">ticket.fun_suporte@gmail.com</a></li>
                    <li><a href="tel:0800567489" class="footer_column_list_a">0800 567 489</a></li>
                </ul>
            </div>
            <div class="footer_column">
                <h4 class="footer_column_titulo">Siga nossas redes</h4>
                <ul class="footer_column_list footer_column_list_redes_sociais">
                    <li><a href="#"><img src="assets/imagens/whatsapp.svg" alt="Whatsapp"></a></li>
                    <li><a href="#"><img src="assets/imagens/instagram.svg" alt="Instagram"></a></li>
                    <li><a href="#"><img src="assets/imagens/tiktok.svg" alt="Tiktok"></a></li>
                </ul>
            </div>
            <div class="footer_bottom footer_columns">
                <p>© 2025 Feito com 💗 por fãs da Hello Kitty.</p>
            </div>
        </div>
    </footer>
</body>
</html>