<?php
// PROJECT_WEB/evento_detalhes.php

require __DIR__ . "/vendor/autoload.php";
session_start();

use \App\Db\Database; //

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { //
    header('Location: index.php'); //
    exit; //
}

$id = $_GET['id']; //
$db = new Database('eventos'); //
$resultado = $db->execute("SELECT * FROM eventos WHERE id = ?", [$id]); //
$evento = $resultado->fetchObject(); //

// --- Lógica para verificar a existência do banner no servidor (SEM REDIRECIONAR SE NÃO EXISTIR) ---
$banner_path_from_db = $evento->banner; // Caminho como está no BD (ex: assets/imagens/eventos/...)
$banner_file_exists_on_server = false; // Assume que não existe por padrão

if (!empty($banner_path_from_db)) { //
    // Para file_exists(), precisamos do caminho ABSOLUTO no sistema de arquivos do servidor.
    $full_server_path_to_banner = __DIR__ . '/' . $banner_path_from_db; //
    
    // realpath() tenta resolver o caminho absoluto e verifica se ele é válido e existe.
    $resolved_banner_path = realpath($full_server_path_to_banner); //

    if ($resolved_banner_path && file_exists($resolved_banner_path)) { //
        $banner_file_exists_on_server = true; //
    }
}

// REMOVIDA A LÓGICA DE REDIRECIONAMENTO/DIE AQUI.
// A seção do banner no HTML agora é que decide se mostra ou não.

// Se chegamos aqui, o banner existe no BD e o arquivo existe no servidor.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/Event_Details_page.css">
  <title><?= htmlspecialchars($evento->titulo) ?></title>
  <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">

  <style>
    /* Variáveis replicadas de styles.css para uso local */
    :root {
        --cor-texto: #1e1e1e;
        --cor-texto-hover: #252530;
        --cor-texto-claro: #ffffff;
        --cor-borda-titulo:#ffc0cb;
        --cor-de-fundo-footer:rgb(215, 236, 255);
        --cor-de-fundo-botao-v:#ff3b3f;
        --border-color: #ffe066;

        --fonte-texto: 'Poppins', sans-serif;
        --fonte-titulo: 'Shrikhand', sans-serif;

        --gap-s: 1rem;
        --gap-xl: 3.5rem;

        --padding-xs: 0.5rem;
        --padding-s: 1rem;
        --padding-m: 1.5rem;
        --padding-l: 2rem;
        --padding-xl: 3.5rem;

        --font-size-s: 1rem;
        --font-size-m: 1.25rem;
        --font-size-xl: 3rem;

        --max-width-block: 75rem;

        --margin-xs: 0.5rem;
        --margin-s: 1rem;
        --margin-m: 1.5rem;
        --margin-l: 2rem;
        --margin-xl: 2.75rem;
    }

    /* Estilos para a seção foto_e_informações e seus filhos */
    .foto_e_informações {
        display: flex;
        max-width: var(--max-width-block);
        margin: 0 auto;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .foto_lateral {
        flex-shrink: 0;
        width: 300px;
        max-width: 100%;
        margin-bottom: var(--margin-m);
    }

    .foto {
        border-radius: 7px;
        width: 100%;
        height: auto;
        display: block;
    }

    .descrição_evento {
        flex-grow: 1;
        margin-left: var(--margin-m);
        background-color: white;
        border-radius: 7px;
        padding: var(--padding-s);
        border: 3px solid var(--cor-borda-titulo);
        line-height: 1.6;
        color: var(--cor-texto);
        font-family: var(--fonte-texto);
        
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .titulo_descrição {
        margin-bottom: var(--margin-m);
        font-size: var(--font-size-m);
        font-family: var(--fonte-texto);
        width: 100%;
    }

    .texto_descrição {
        margin-bottom: var(--margin-m);
        text-align: center;
    }
    .conteudo_informações {
        display: flex;
        flex-direction: column;
        gap: var(--gap-s);
        list-style: none;
        font-family: var(--fonte-texto);
        color: var(--cor-texto);
        width: 100%;
    }
    .conteudo_informações li {
        text-align: center;
    }

    /* Estilo para a imagem do banner principal */
    .banner_hero { /* Seletor para a imagem grande do topo */
        width: 100%;
        height: 400px; /* Altura fixa para banners */
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: var(--margin-m); /* Espaçamento abaixo do banner */
        display: block; /* Garante que a imagem se comporte como um bloco */
        margin-top: var(--margin-m); /* Para o espaçamento superior */
    }

    /* Estilos específicos para a seção superior do evento (onde o banner e o título/botão ficam) */
    .main_event_hero_section {
        max-width: var(--max-width-block);
        margin: 0 auto;
    }
    .main_event_hero_section .event_title_button_area {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--margin-m);
        padding-top: var(--padding-m);
    }

    /* Media queries para responsividade */
    @media screen and (max-width: 900px) {
        .foto_e_informações {
            flex-direction: column;
            align-items: center;
        }
        .foto_lateral {
            margin-right: 0;
            width: 80%;
            margin-bottom: var(--margin-l);
        }
        .descrição_evento {
            margin-left: 0;
            width: 100%;
            max-width: var(--max-width-block);
        }
    }
    @media screen and (max-width: 480px) {
        .foto_lateral {
            width: 100%;
        }
        .descrição_evento {
            padding: var(--padding-s);
        }
        .banner_hero {
            height: 200px; /* Altura menor para banners em mobile */
        }
        .main_event_hero_section .event_title_button_area {
            flex-direction: column;
            text-align: center;
            padding-top: var(--padding-s);
        }
        .main_event_hero_section .event_title_button_area .informações {
            margin-bottom: var(--margin-s);
        }
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

  <?php if (!empty($evento->banner) && $banner_file_exists_on_server): ?>
  <section class="main_event_hero_section">
    <div class="container_img">
      <img src="<?= htmlspecialchars($evento->banner) ?>" alt="Banner do evento <?= htmlspecialchars($evento->titulo) ?>" class="banner_hero">
    </div>
    <div class="event_title_button_area">
      <h2 class="informações"><?= htmlspecialchars($evento->titulo) ?></h2>
      <a href="sign_up_page.php?evento_id=<?= $evento->id ?>">
        <button type="button" class="botao_inscrever">INSCREVER-SE</button>
      </a>
    </div>
  </section>
  <?php else: ?>
      <div style="height: 50px; margin-top: var(--margin-m);"></div>
  <?php endif; ?>

  <section class="foto_e_informações">
    <div class="foto_lateral">
      <img src="<?= htmlspecialchars($evento->imagem) ?>" alt="Foto do evento <?= htmlspecialchars($evento->titulo) ?>" class="foto">
    </div>
    <div class="descrição_evento">
      <h3 class="titulo_descrição">DESCRIÇÃO DO EVENTO</h3>
      <p class="texto_descrição"><?= nl2br(htmlspecialchars($evento->descricao)) ?></p>
      <h3 class="titulo_descrição">INFORMAÇÕES GERAIS:</h3>
      <div class="conteudo_informações">
        <ul class="conteudo_informações">
          <li><strong>Data:</strong> <?= date('d/m/Y', strtotime($evento->data_evento)) ?></li>
          <li><strong>Hora:</strong> <?= date('H:i', strtotime($evento->hora_evento)) ?></li>
          <li><strong>Local:</strong> <?= htmlspecialchars($evento->local) ?></li>
          <li><strong>Endereço:</strong> <?= htmlspecialchars($evento->endereco) ?></li>
        </ul>
      </div>
      <?php if (!empty($evento->observacoes_evento)): ?>
        <h3 class="titulo_descrição">OBSERVAÇÕES E REGRAS DO EVENTO:</h3>
        <p class="texto_descrição"><?= nl2br(htmlspecialchars($evento->observacoes_evento)) ?></p>
      <?php endif; ?>
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