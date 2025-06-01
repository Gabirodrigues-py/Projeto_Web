<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/banner.css">
  <link rel="stylesheet" href="assets/css/cartaz.css">
  <link rel="stylesheet" href="assets/css/event_list.css">
  <link rel="stylesheet" href="assets/css/footer.css">

  <title>Inicio</title>
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

  <section class="banner">
    <div class="banner_container">
      <h2 class="banner_titulo">
        Colocando seus <br> personagens mais <br>
        <span class="banner_titulo-colorido"> AMADOS 💗</span>
        <br> em experiências <span class="banner_titulo-colorido">INESQUECÍVEIS!</span>
      </h2>
    </div>
  </section>

  <section class="cartaz">
    <form>
      <input 
        type="search" 
        placeholder="Digite sua busca"
        class="cartaz_pesquisa" 
        aria-label="campo de busca de eventos" />
    </form>

    <h2 class="Eventos_disponíveis">Eventos Disponíveis</h2>

    <ul class="evento_list">
      <li class="evento_list_item">
        <img src="assets/imagens/imersao.png" alt="Imersão Hello Kitty">
        <div class="evento_list_container">
          <h3 class="evento_list_item-titulo">Imersão Hello Kitty</h3>
          <div class="botao_informações">
            <a href="event_imersao_description_page.html" class="texto_informações">Informações</a>
          </div>
        </div>
      </li>

      <li class="evento_list_item">
        <img src="assets/imagens/foodtruck.png" alt="FoodTruck Hello Kitty" class="evento_list_imagem">
        <div class="evento_list_container">
          <h3 class="evento_list_item-titulo">Foodtruck Hello Kitty</h3>
          <div class="botao_informações">
            <a href="event_foodtruck_description_page.html" class="texto_informações">Informações</a>
          </div>
        </div>
      </li>

      <li class="evento_list_item">
        <img src="assets/imagens/aula_de_desenho.png" alt="Oficina de desenho Hello Kitty" class="evento_list_imagem">
        <div class="evento_list_container">
          <h3 class="evento_list_item-titulo">Oficina de desenho Hello Kitty</h3>
          <div class="botao_informações">
            <a href="event_desenho_descripition_page.html" class="texto_informações">Informações</a>
          </div>
        </div>
      </li>

      <li class="evento_list_item">
        <img src="assets/imagens/cinema.png" alt="Cinema Hello Kitty" class="evento_list_imagem">
        <div class="evento_list_container">
          <h3 class="evento_list_item-titulo">Cinema Hello Kitty</h3>
          <div class="botao_informações">
            <a href="event_cinema_description_page.html" class="texto_informações">Informações</a>
          </div>
        </div>
      </li>
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
