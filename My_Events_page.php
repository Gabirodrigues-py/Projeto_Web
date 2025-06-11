<?php
require __DIR__ . '/vendor/autoload.php'; //
session_start(); //
if (!isset($_SESSION['usuario_id'])) { //
    header('Location: login_process.php'); //
    exit; //
}

use \App\Db\Database; //

$uid = $_SESSION['usuario_id']; //

$db = new Database(); //
$stmt = $db->execute( //
    'SELECT e.id, e.titulo, e.descricao, e.imagem, i.id AS inscricao_id
     FROM eventos e
     JOIN inscricoes i ON e.id = i.evento_id
     WHERE i.usuario_id = ?',
     [$uid]
);
$inscricoes = $stmt->fetchAll(PDO::FETCH_ASSOC); //
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/my_events.css">
    <title>Meus Eventos</title>
    <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">
    <style>
        /* Adicionado para estilizar a lista de eventos dinâmicos */
        .evento_card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .evento_card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .evento_card h4 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .evento_card .botoes_container {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
    </style>
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
                    <a href="index.php" class="nav_link">Inicio</a>
                </li>
            </ul>
        </nav>
    </header>

    <section class="titulo_pagina">
        <div class="titulo_eventos">
            <h1 class="titulo_eventos">Meus Eventos</h1>
        </div>
    </section>

    <section class="menu_superior">
        <nav class="lista_menu">
            <ul class="menu_superior_itens">
                <li><a href="profile_page.php" class="Link-Menu-Lateral" id="perfil">PERFIL</a></li>
                <li><a href="My_Events_page.php" class="Link-Menu-Lateral" id="meus_eventos">MEUS EVENTOS</a></li>
                <li><a href="password_page.php" class="Link-Menu-Lateral" id="senhas">SENHAS</a></li>
            </ul>
        </nav>
    </section>

    <div class="container_dinamico">
        <?php if (empty($inscricoes)): ?>
            <p>
                Você ainda não se inscreveu em nenhum evento.
            </p>
        <?php else: ?>
            <?php foreach ($inscricoes as $insc): ?>
                <div class="evento_card">
                    <h4><?= htmlspecialchars($insc['titulo']) ?></h4>
                    <img src="<?= htmlspecialchars($insc['imagem']) ?>" alt="<?= htmlspecialchars($insc['titulo']) ?>">
                    <p><?= htmlspecialchars($insc['descricao']) ?></p>
                    
                    <div class="botoes_container">
                        <a href="my_sing_up_s_details_page.html?inscricao_id=<?= $insc['inscricao_id'] ?>" class="botao_entrar" id="botao_entrar_login">DETALHES</a>

                        <form action="cancelar_inscricao.php" method="post" onsubmit="return confirm('Tem certeza que deseja cancelar a inscrição?')" style="margin: 0;">
                            <input type="hidden" name="inscricao_id" value="<?= $insc['inscricao_id'] ?>">
                            <button type="submit" class="botao_my_events" id="botao_cancelar">CANCELAR</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="footer_columns">
            <div class="footer_column_logo">
                <img src="assets/imagens/logo-225x150.png" alt="logo Ticket.fun" class="footer_column_img footer_column_list">
            </div>

            <div class="footer_column">
                <h4 class="footer_column_titulo">Funcionamento</h4>
                <ul class="footer_column_list">
                    <li>
                        <p>Somos uma plataforma 100% digital!<br> Para suporte ao cliente Segunda a sexta - 08:00 às 18:00</p>
                    </li>
                    <li>
                        <a href="mailto:ticket.fun_suporte@gmail.com" class="footer_column_list_a">ticket.fun_suporte@gmail.com</a>
                    </li>
                    <li>
                        <a href="tel:0800567489" class="footer_column_list_a">0800 567 489</a>
                    </li>
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