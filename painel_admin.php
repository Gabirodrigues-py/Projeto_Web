<?php
session_start();
// BACK-END CORRIGIDO: A verificação de permissão foi padronizada.
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php');
    exit;
}
require __DIR__ . "/vendor/autoload.php";
use \App\Db\Database;

$db = new Database('eventos');
// Busca todos os eventos ordenados pela data do evento
$eventos = $db->execute("SELECT * FROM eventos ORDER BY data_evento ASC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/My_Events.css">
  <link rel="stylesheet" href="assets/css/Event_list.css">
  <title>Painel Administrativo</title>
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
          <a href="index.php" class="nav_link">Início</a>
        </li>
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <li class="nav_item">
            <a href="profile_page.php" class="nav_link">Perfil</a>
          </li>
          <li class="nav_item">
            <a href="logout.php" class="nav_link">Sair</a>
          </li>
        <?php else: ?>
          <li class="nav_item">
            <a href="login_process.php" class="nav_link">Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <section class="titulo_pagina">
    <div class="titulo_senhas">
      <h1 class="titulo_senhas">Painel Administrativo</h1>
    </div>
  </section>

  <section>
    <h2 class="titulo_eventos">Eventos Cadastrados</h2>
    <ul class="evento_list">
      <?php while($evento = $eventos->fetchObject()): ?>
        <li class="evento_list_item">
          <img src="<?= htmlspecialchars($evento->imagem) ?>" alt="<?= htmlspecialchars($evento->titulo) ?>" class="filme_list_imagem">
          <h3 class="evento_list_item-titulo"><?= htmlspecialchars($evento->titulo) ?></h3>
          <a href="excluir_evento.php?id=<?= $evento->id ?>" class="botao_informações">Excluir evento</a>
        </li>
      <?php endwhile; ?>
    </ul>
  </section>

  <footer class="footer">
    <div class="footer_columns">
      <div class="footer_column_logo">
        <img src="assets/imagens/logo-225x150.png" alt="logo Ticket.fun" class="footer_column_img footer_column_list">
      </div>
      <div class="footer_column">
        <h4 class="footer_column_titulo">Funcionamento</h4>
        <ul class="footer_column_list">
          <li>
            <p>Somos uma plataforma 100% digital!<br>Para suporte ao cliente Segunda a Sexta - 08:00 às 18:00</p>
          </li>
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