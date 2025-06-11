<?php
session_start(); //
// BACK-END CORRIGIDO: A verificação de permissão foi padronizada.
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) { //
    header('Location: index.php'); //
    exit; //
}
require __DIR__ . "/vendor/autoload.php"; //
use \App\Db\Database; //

$db = new Database('eventos'); //
// Busca todos os eventos ordenados pela data do evento
$eventos = $db->execute("SELECT * FROM eventos ORDER BY data_evento ASC"); //
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="assets/css/My_Events.css"> <link rel="stylesheet" href="assets/css/Event_list.css"> <title>Painel Administrativo</title>
  <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">
  <style>
    /* Estilos específicos para o painel de admin, combinados com estilos existentes */
    .container_dinamico {
        max-width: var(--max-width-block); /* Usar a variável de largura máxima */
        margin: 0 auto;
        padding: var(--padding-l); /* Padding interno da caixa */
        border: 3px solid var(--cor-borda-titulo); /* Borda rosa */
        border-radius: 8px;
        background-color:white;
        color: #333;
        font-size: var(--font-size-s);
    }
    .titulo_eventos { /* Replicado do My_Events.css para o h2 */
        color: var(--cor-texto-claro);
        text-shadow:
            -3px -3px 0 var(--cor-borda-titulo),
            3px -3px 0 var(--cor-borda-titulo),
            -3px  3px 0 var(--cor-borda-titulo),
            3px  3px 0 var(--cor-borda-titulo);
        font-family: var(--fonte-titulo);
        font-size: var(--font-size-xl);
        font-weight: 400;
        line-height:var(--line-height-l);
        margin-top: var(--margin-l);
        margin-bottom: var(--margin-l);
        text-align: center; /* Centraliza o título */
    }
    .evento_list { /* Estilos da lista de eventos */
        display: flex;
        flex-wrap: wrap;
        gap: var(--gap-m);
        list-style-type: none;
        justify-content: center; /* Centraliza os cards dentro do container */
        padding: 0; /* Remove padding padrão de ul */
    }
    .evento_list_item { /* Estilos de cada card de evento */
        background-color: white;
        flex-grow: 1;
        flex-shrink: 1;
        flex-basis: 300px; /* Largura base para o card */
        max-width: 350px; /* Largura máxima para o card */
        height: auto; /* Altura automática */
        box-shadow: var(--box-shadow-card);
        display: flex;
        flex-direction: column;
        align-items: center; /* CENTRALIZA OS ITENS (IMAGEM, TÍTULO, BOTÃO) HORIZONTALMENTE */
        cursor: pointer;
        border-radius: 8px; /* Bordas arredondadas para o card */
        overflow: hidden; /* Garante que a imagem respeite o border-radius */
        border: 1px solid #ddd; /* Borda suave para o card */
        text-align: center; /* Centraliza o texto (para h3) */
    }
    .filme_list_imagem { /* Estilos da imagem dentro do card */
        width: 100%;
        height: 200px; /* Altura fixa para a imagem do card */
        object-fit: cover;
        display: block;
    }
    .evento_list_item-titulo { /* Estilo do título do evento no card */
        text-align: center;
        margin-top: var(--margin-xs);
        padding: var(--padding-xs);
        font-family: var(--fonte-texto);
        color: var(--cor-texto);
        font-size: var(--font-size-m);
        font-weight: bold;
    }
    .botao_informações { /* Estilo do botão "Excluir" */
        display: block; /* Mudar para block para que margin: auto funcione */
        padding: 10px 20px; /* */
        background-color: var(--cor-de-fundo-botao-v); /* Cor vermelha de ação */
        color: white; /* */
        font-weight: bold; /* */
        text-decoration: none; /* */
        border-radius: 5px; /* */
        border: none; /* Remover borda padrão do botão */
        cursor: pointer; /* */
        margin-top: var(--margin-s); /* Espaçamento acima do botão */
        margin-bottom: var(--margin-m); /* Espaçamento abaixo do botão */
        transition: background-color 0.3s ease; /* Transição suave no hover */
        width: calc(100% - 20px); /* Ocupa quase a largura total do card */
        max-width: 200px; /* Largura máxima para o botão */
        margin-left: auto; /* Centraliza o botão de bloco */
        margin-right: auto; /* Centraliza o botão de bloco */
    }
    .botao_informações:hover { /* */
        background-color: #d12e32; /* Tom mais escuro de vermelho no hover */
    }

    /* Media Queries para responsividade dos cards */
    @media screen and (max-width: 768px) {
        .evento_list_item {
            flex-basis: calc(50% - var(--gap-m)); /* Duas colunas em tablets */
            max-width: calc(50% - var(--gap-m));
        }
    }
    @media screen and (max-width: 480px) {
        .evento_list_item {
            flex-basis: 100%; /* Uma coluna em celulares */
            max-width: 100%;
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

  <section class="titulo_pagina">
    <div class="titulo_senhas">
      <h1 class="titulo_senhas">Painel Administrativo</h1>
    </div>
  </section>

  <div class="container_dinamico">
    <h2 class="titulo_eventos">Eventos Cadastrados</h2>
    <ul class="evento_list">
      <?php while($evento = $eventos->fetchObject()): ?>
        <li class="evento_list_item">
          <img src="<?= htmlspecialchars($evento->imagem) ?>" alt="<?= htmlspecialchars($evento->titulo) ?>" class="filme_list_imagem">
          <h3 class="evento_list_item-titulo"><?= htmlspecialchars($evento->titulo) ?></h3>
          <a href="excluir_evento.php?id=<?= $evento->id ?>" class="botao_informações" onclick="return confirm('Tem certeza que deseja excluir o evento \'<?= htmlspecialchars($evento->titulo) ?>\'? Esta ação é irreversível.')">Excluir evento</a>
        </li>
      <?php endwhile; ?>
      <?php if ($eventos->rowCount() === 0): ?>
          <p style="text-align: center; width: 100%; margin-top: var(--margin-m); font-size: var(--font-size-m); color: var(--cor-texto);">Nenhum evento cadastrado ainda.</p>
      <?php endif; ?>
    </ul>
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