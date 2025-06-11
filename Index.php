<?php
  session_start();
  require __DIR__."/vendor/autoload.php"; // Necessário para o Database
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
  <link rel="stylesheet" href="assets/css/footer1.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>


  <title>Inicio</title>
  <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">

  <style>
    /* Estilos básicos para o carrossel */
    .carousel .slick-slide {
        margin: 0 15px; /* Espaçamento entre os slides */
        box-sizing: border-box; /* Garante que o padding não aumente o tamanho total */
    }
    .carousel .slick-slide img {
        width: 100%;
        height: 250px; /* Altura fixa para a imagem */
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid var(--cor-borda-titulo); /* Adiciona borda rosa às imagens */
        box-shadow: var(--box-shadow-card); /* Adiciona sombra para dar profundidade */
    }
    .carousel .slick-slide h3 {
        text-align: center;
        padding: 10px 5px; /* Ajusta o padding do título */
        font-family: var(--fonte-texto); /* Usa a fonte do projeto */
        color: var(--cor-texto); /* Usa a cor de texto padrão do projeto */
        font-size: var(--font-size-s); /* Tamanho de fonte padrão para itens de lista */
        margin-top: var(--margin-xs); /* Pequena margem superior */
    }
    .carousel a {
        text-decoration: none;
        color: var(--cor-texto);
        display: block; /* Garante que o link ocupe todo o slide para clique */
        padding: 10px; /* Espaçamento dentro do item clicável */
        background-color: white; /* Fundo branco para os cards do carrossel */
        border-radius: 8px; /* Borda arredondada para os cards */
        box-shadow: var(--box-shadow-card); /* Sombra para os cards */
        transition: transform 0.3s ease-in-out; /* Efeito suave ao passar o mouse */
    }
    .carousel a:hover {
        transform: translateY(-5px); /* Levanta o card ligeiramente ao passar o mouse */
    }

    /* Estilização dos botões de navegação (setas) do Slick Carousel */
    .carousel .slick-prev,
    .carousel .slick-next {
        font-size: 0; /* Esconde o texto padrão */
        line-height: 1;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: block;
        width: 30px; /* Largura do botão */
        height: 30px; /* Altura do botão */
        padding: 0;
        cursor: pointer;
        color: transparent;
        border: none;
        outline: none;
        background: var(--cor-borda-titulo); /* Fundo rosa */
        border-radius: 50%; /* Torna os botões circulares */
        z-index: 100; /* Garante que estejam acima das imagens */
        box-shadow: var(--box-shadow-card); /* Sombra para os botões */
    }

    .carousel .slick-prev {
        left: -40px; /* Posição à esquerda fora do carrossel */
    }

    .carousel .slick-next {
        right: -40px; /* Posição à direita fora do carrossel */
    }

    .carousel .slick-prev:before,
    .carousel .slick-next:before {
        font-family: 'slick'; /* Fonte de ícones do Slick */
        font-size: 20px; /* Tamanho do ícone */
        line-height: 1;
        color: var(--cor-texto-claro); /* Cor branca para as setas */
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Ajuste para pontos de navegação (dots) */
    .slick-dots li button:before {
        font-size: 10px; /* Tamanho dos pontos */
        color: var(--cor-borda-titulo); /* Cor rosa para os pontos */
    }
    .slick-dots li.slick-active button:before {
        color: var(--cor-de-fundo-botao-v); /* Cor vermelha para o ponto ativo */
    }

    /* Media Queries para responsividade */
    @media screen and (max-width: 1200px) {
      .carousel .slick-prev { left: -20px; }
      .carousel .slick-next { right: -20px; }
    }

    @media screen and (max-width: 768px) {
      .carousel .slick-prev { left: 5px; } /* Ajusta posição para não sair da tela */
      .carousel .slick-next { right: 5px; }
      .carousel .slick-slide { margin: 0 5px; } /* Menor espaçamento em telas menores */
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

    <h2 class="Eventos_disponíveis">Eventos Disponíveis</h2>

    <div class="carousel">
      <?php
        // Lógica para buscar eventos do banco de dados
        try {
            $db = new \App\Db\Database('eventos');
            // Busca eventos cuja data é hoje ou no futuro, ordenados pela data mais próxima
            $eventos = $db->execute("SELECT * FROM eventos WHERE data_evento >= CURDATE() ORDER BY data_evento ASC, hora_evento ASC");

            while($evento = $eventos->fetchObject()){
                echo '<div>';
                // O link agora aponta para evento_detalhes.php, passando o ID do evento
                echo '  <a href="evento_detalhes.php?id='.$evento->id.'">';
                echo '    <img src="'.$evento->imagem.'" alt="'.htmlspecialchars($evento->titulo).'">';
                echo '    <h3>'.htmlspecialchars($evento->titulo).'</h3>';
                echo '  </a>';
                echo '</div>';
            }
        } catch (Exception $e) {
            echo '<p>Não foi possível carregar os eventos. Tente novamente mais tarde.</p>';
            // Opcional: logar o erro $e->getMessage() para depuração.
        }
      ?>
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

  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function(){
        $('.carousel').slick({
          slidesToShow: 4, // Mostrar 4 slides de uma vez
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 3000,
          dots: true,
          prevArrow: '<button type="button" class="slick-prev"></button>', /* Renderiza o botão personalizado */
          nextArrow: '<button type="button" class="slick-next"></button>', /* Renderiza o botão personalizado */
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
              }
            }
          ]
        });
      });
  </script>

</body>
</html>e