<?php
require __DIR__."/vendor/autoload.php";
session_start();

use \App\Db\Database;

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$db = new Database('eventos');
$resultado = $db->execute("SELECT * FROM eventos WHERE id = ?", [$id]);
$evento = $resultado->fetchObject();

// Se não encontrou o evento, redireciona
if (!$evento) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/event_details_page.css">

  <title><?= htmlspecialchars($evento->titulo) ?></title>
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
            <a href="index.php" class="nav_link">Inicio</a>
          </li>
          <?php if (!isset($_SESSION['usuario_id'])): ?>
            <li class="nav_item">
                <a href="login_process.php" class="nav_link">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
  </header>

  <section class="container_banner">
    <div class="container_img">
      <img src="<?= htmlspecialchars($evento->imagem) ?>" alt="Banner do evento <?= htmlspecialchars($evento->titulo) ?>">
    </div>

    <div class="botao_e_informações">
      <h2 class="informações"><?= htmlspecialchars($evento->titulo) ?></h2>
      <a href="sign_up_page.php?evento_id=<?= $evento->id ?>">
        <button type="button" class="botao_inscrever">INSCREVER-SE</button>
      </a>
    </div>
  </section>

  <section class="foto_e_informações">
    <div class="foto_lateral">
      <img src="<?= htmlspecialchars($evento->imagem) ?>" alt="Foto do evento <?= htmlspecialchars($evento->titulo) ?>" class="foto">
    </div>

    <div class="descrição_evento">
      <h3 class="titulo_descrição">DESCRIÇÃO DO EVENTO</h3>
      <p class="texto_descrição">
        <?= nl2br(htmlspecialchars($evento->descricao)) ?>
      </p>

      <h3 class="titulo_descrição">INFORMAÇÕES GERAIS:</h3>
      <div class="conteudo_informações">
        <ul class="conteudo_informações">
          <li><strong>Data:</strong> <?= date('d/m/Y', strtotime($evento->data_evento)) ?></li>
          <li><strong>Horário do Evento:</strong> <?= date('H:i', strtotime($evento->hora_evento)) ?>h</li>
          <li><strong>Local:</strong> <?= htmlspecialchars($evento->local) ?></li>
        </ul>
      </div>
    </div>
  </section>

  <footer class="footer">
      <div class="footer_columns">
        <div class="footer_column_logo">
          <img src="assets/imagens/logo-225x150.png" alt="logo Ticket.fun" class="footer_column_img footer_column_list">
        </div>
        <div class="footer_column">
          <h4 class="footer_column_titulo">Funcionamento</h4>
          <ul class="footer_column_list">
            <li><p>Somos uma plataforma 100% digital!<br> Para suporte ao cliente Segunda a sexta - 08:00 às 18:00</p></li>
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