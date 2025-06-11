<?php
require __DIR__."/vendor/autoload.php";
session_start();

// --- INÍCIO DA VERIFICAÇÃO DE PERMISSÃO ---
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: index.php?status=acesso_negado');
    exit;
}
// --- FIM DA VERIFICAÇÃO DE PERMISSÃO ---

use \App\Db\Database;

$mensagem = '';

// Lógica para processar o formulário
// Removida a obrigatoriedade do banner no isset
if(isset($_POST['titulo'], $_POST['descricao'], $_POST['data_evento'], $_POST['hora_evento'], $_POST['local'], $_POST['endereco'], $_POST['observacoes_evento'], $_FILES['imagem']['name'])){

    // Removida a obrigatoriedade do banner na validação empty()
    if(empty($_POST['titulo']) || empty($_FILES['imagem']['name'])){
        $mensagem = "Título e imagem principal são obrigatórios.";
    } else {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $data_evento = $_POST['data_evento'];
        $hora_evento = $_POST['hora_evento'];
        $local = $_POST['local'];
        $endereco = $_POST['endereco'];
        $observacoes_evento = $_POST['observacoes_evento'];
        $imagem = $_FILES['imagem'];
        $banner = $_FILES['banner'] ?? null; // Banner agora é opcional

        $target_dir = "assets/imagens/eventos/";
        $nome_imagem = '';
        $target_file = ''; // Inicializa para garantir que a variável exista
        $nome_banner = '';
        $target_banner_file_for_db = ''; // Inicializa para garantir que a variável exista

        // Processar imagem principal (ainda obrigatória)
        $imageFileType = strtolower(pathinfo($imagem["name"], PATHINFO_EXTENSION));
        $nome_imagem = 'evento_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $nome_imagem; // Caminho completo para salvar e usar no BD
        $target_upload_path = __DIR__ . '/' . $target_file; // Caminho absoluto para move_uploaded_file

        if (getimagesize($imagem["tmp_name"])) {
            if (!move_uploaded_file($imagem["tmp_name"], $target_upload_path)) {
                $mensagem = "Erro ao fazer upload da imagem principal.";
                $nome_imagem = '';
                $target_file = '';
            }
        } else {
            $mensagem = "O arquivo enviado para imagem principal não é uma imagem válida.";
            $nome_imagem = '';
            $target_file = '';
        }

        // Processar banner (AGORA OPCIONAL)
        if ($banner && $banner['tmp_name'] && !empty($banner['name'])) { // Verifica se um arquivo de banner foi realmente enviado
            $bannerFileType = strtolower(pathinfo($banner["name"], PATHINFO_EXTENSION));
            $nome_banner = 'banner_' . time() . '.' . $bannerFileType;
            $target_banner_file_for_db = $target_dir . $nome_banner; // Caminho completo para salvar no BD
            $target_banner_file_for_upload = __DIR__ . '/' . $target_banner_file_for_db; // Caminho absoluto para move_uploaded_file

            if (getimagesize($banner["tmp_name"])) {
                if (!move_uploaded_file($banner["tmp_name"], $target_banner_file_for_upload)) {
                    $mensagem .= " Erro ao fazer upload do banner.";
                    $nome_banner = '';
                    $target_banner_file_for_db = ''; // Limpar caminho para o BD se houver erro
                }
            } else {
                $mensagem .= " O arquivo enviado para banner não é uma imagem válida.";
                $nome_banner = '';
                $target_banner_file_for_db = '';
            }
        }
        // Se o banner não foi enviado ou houve erro no upload, target_banner_file_for_db permanecerá vazio, o que é o comportamento desejado para opcional.

        // Apenas cadastra se não houver erros no upload da imagem principal
        if (!empty($nome_imagem)) {
            $db = new Database('eventos');
            $db->insert([
                'titulo' => $titulo,
                'descricao' => $descricao,
                'data_evento' => $data_evento,
                'hora_evento' => $hora_evento,
                'local' => $local,
                'endereco' => $endereco,
                'observacoes_evento' => $observacoes_evento,
                'imagem' => $target_file, // Caminho da imagem principal (relativo ao webroot)
                'banner' => $target_banner_file_for_db // Caminho COMPLETO do banner (vazio se não enviado ou com erro)
            ]);
            $mensagem = "Evento cadastrado com sucesso!";
        } else {
            $mensagem = "Erro no cadastro: " . $mensagem;
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
    <link rel="stylesheet" href="assets/css/footer1.css">
    <title>Cadastro de Evento</title>
    <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">

    <style>
        /* Variáveis de estilo, replicadas de styles.css para uso local */
        :root {
            --cor-texto: #1e1e1e;
            --cor-texto-hover: #252530;
            --cor-texto-claro: #ffffff;
            --cor-borda-titulo:#ffc0cb;
            --cor-de-fundo-footer:rgb(215, 236, 255) ;
            --cor-de-fundo-botao-v:#ff3b3f;
            --border-color: #ffe066;

            --fonte-texto: 'Poppins', sans-serif;
            --fonte-titulo: 'Shrikhand', sans-serif;

            --gap-s: 1rem;
            --gap-xl: 3.5rem;

            --padding-s: 1rem;
            --padding-m: 1.5rem;
            --padding-l: 2rem;
            --padding-form: 0.9375rem;

            --font-size-s: 1rem;
            --font-size-m: 1.25rem;
            --font-size-xl: 3rem;

            --max-width-block: 75rem;

            --margin-s: 1rem;
            --margin-m: 1.5rem;
            --margin-l: 2rem;
            --margin-xl: 2.75rem;
        }

        /* Estilos replicados de Registrer_page.css e ajustados para o contexto */
        .titulo_pagina {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: var(--margin-m);
        }

        .titulo_cadastro {
            color: var(--cor-texto-claro);
            text-shadow:
                -3px -3px 0 var(--cor-borda-titulo),
                3px -3px 0 var(--cor-borda-titulo),
                -3px  3px 0 var(--cor-borda-titulo),
                3px  3px 0 var(--cor-borda-titulo);
            font-family: var(--fonte-titulo);
            font-size: var(--font-size-xl);
        }

        .titulo_h3 {
            margin-bottom: var(--margin-m);
            font-size: var(--font-size-m);
            font-family: var(--fonte-texto);
        }

        .formulario_info_cadastro {
            max-width: var(--max-width-block);
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }

        .formulario_campos {
            max-width: var(--max-width-block);
            margin: 0 auto;
            padding: 75px;
            border: 3px solid var(--cor-borda-titulo);
            border-radius: 8px;
            background-color: white;
            color: #333;
            font-size: var(--font-size-s);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .formulario_campos input[type="text"],
        .formulario_campos input[type="date"],
        .formulario_campos input[type="time"],
        .formulario_campos textarea,
        .formulario_campos input[type="file"] {
            width: 100%;
            max-width: 500px;
            height: 60px;
            padding: var(--padding-form);
            border: 3px solid var(--cor-borda-titulo);
            border-radius: 8px;
            background-color: white;
            color: #333;
            font-size: var(--font-size-s);
            margin-bottom: var(--margin-s);
            text-align: left;
        }

        .formulario_campos textarea {
            height: 120px;
        }

        .formulario_campos label[for="imagem"],
        .formulario_campos label[for="banner"] {
            font-family: var(--fonte-texto);
            display: block;
            margin-bottom: 10px;
            text-align: center;
        }

        .botao_cadastro {
            display: flex;
            margin: 0 auto;
            justify-content: center;
            align-items: center;
        }

        .botao_cadastrar {
            width: 400px;
            height: 40px;
            padding: var(--padding-form);
            border: 3px solid var(--cor-de-fundo-botao-v);
            border-radius: 8px;
            background-color: var(--cor-de-fundo-botao-v);
            color: var(--cor-texto-claro);
            font-weight: bold;
            font-size: var(--font-size-s);
            margin-top: var(--margin-m);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilos do rodapé replicados para consistência */
        .footer {
            background-color: var(--cor-de-fundo-footer);
            padding: var(--padding-xl) var(--padding-m);
            color: rgb(170, 169, 169);
            font-family: var(--fonte-texto);
            font-weight: 400;
            font-size: var(--font-size-m);
            line-height: 1.5em;
            margin-top: var(--margin-xl);
        }

        .footer_columns {
            display: flex;
            justify-content: space-around;
            gap: var(--gap-s);
            flex-flow: row wrap;
        }

        .footer_column_titulo {
            font-family: var(--fonte-titulo);
            font-size: var(--font-size-m);
            font-weight: 400;
            line-height: 1.5;
            color: var(--cor-texto-claro);
            text-shadow:
              -1px -1px 0 var(--cor-borda-titulo),
               1px -1px 0 var(--cor-borda-titulo),
              -1px  1px 0 var(--cor-borda-titulo),
               1px  1px 0 var(--cor-borda-titulo);
            text-align: center;
          }

        .footer_column_list {
            margin-top: var(--margin-s);
            list-style-type: none;
            display: flex;
            flex-direction: column;
            gap: var(--gap-xs);
        }

        .footer_column_list_a {
            color: rgb(170, 169, 169);
            text-decoration: none;
        }

        .footer_column_list_redes_sociais {
            flex-direction: row;
            justify-content: space-around;
        }

        .footer_column_img {
            height: auto;
            width: 280px;
        }

        .footer_bottom {
            margin-top: var(--margin-l);
            margin-bottom: var(--margin-l);
            text-align: center;
            align-items: center;
            justify-content: center;
            padding: 15px 10px;
            font-weight: bold;
        }
        /* Media Queries básicas para responsividade, replicadas de Header.css e footer1.css */
        @media screen and (max-width: 1200px) {
            .nav_list {
                row-gap: var(--gap-s);
                gap: var(--gap-s);
            }
            .header {
                padding: 0 var(--padding-m);
            }
            .footer {
                padding: var(--padding-l);
            }
            .footer_column-logo {
                flex-basis: 100%;
                text-align: center;
            }
            .footer_column {
                flex: basis 30%;
                margin-top: 20px;
            }
        }

        @media (max-width: 730px) {
            .nav_link {
                display: none;
            }
            .menu_toggle {
                display: block;
                cursor: pointer;
            }
            .header {
                padding: 0 var(--padding-s);
            }
            .footer_columns {
                flex-direction: column;
                padding: var(--padding-m) var(--padding-s);
            }
            .formulario_campos {
                padding: 40px;
            }
            .formulario_campos input[type="text"],
            .formulario_campos input[type="date"],
            .formulario_campos input[type="time"],
            .formulario_campos textarea,
            .formulario_campos input[type="file"] {
                max-width: 100%;
            }
             .botao_cadastrar {
                width: 100%;
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

            <label for="titulo_evento" style="display: none;">Título do Evento</label>
            <input type="text" placeholder="Título do Evento" name="titulo" id="titulo_evento" required>
            <br><br>

            <label for="descricao_evento" style="display: none;">Descrição do Evento</label>
            <textarea placeholder="Descrição do Evento" name="descricao" id="descricao_evento" required></textarea>
            <br><br>

            <label for="data_evento" style="display: none;">Data do Evento</label>
            <input type="date" name="data_evento" id="data_evento" required>

            <label for="hora_evento" style="display: none;">Hora do Evento</label>
            <input type="time" name="hora_evento" id="hora_evento" required>
            <br><br>

            <label for="local_evento" style="display: none;">Local do Evento</label>
            <input type="text" placeholder="Local do Evento" name="local" id="local_evento" required>
            <br><br>

            <label for="endereco_evento" style="display: none;">Endereço do Evento</label>
            <input type="text" placeholder="Endereço do Evento" name="endereco" id="endereco_evento" required>
            <br><br>

            <label for="observacoes_evento" style="display: none;">Observações do Evento</label>
            <textarea placeholder="Observações e Regras do Evento (Opcional)" name="observacoes_evento" id="observacoes_evento"></textarea>
            <br><br>

            <label for="imagem" style="font-family: var(--fonte-texto); display: block; margin-bottom: 10px;">Imagem Principal do Evento:</label>
            <input type="file" name="imagem" id="imagem" accept="image/png, image/jpeg, image/webp" required>
            <br><br>

            <label for="banner" style="font-family: var(--fonte-texto); display: block; margin-bottom: 10px;">Banner do Evento:</label>
            <input type="file" name="banner" id="banner" accept="image/png, image/jpeg, image/webp">
            <br><br>

            <div class="botao_cadastro">
                <button type="submit" class="botao_cadastrar">Cadastrar Evento</button>
            </div>
        </form>
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
                        <p>Somos uma plataforma 100% digital!<br> Para suporte ao cliente Segunda a Sexta - 08:00 às 18:00</p>
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