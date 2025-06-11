<?php
// my_sing_up_s_details_page.php

session_start(); //
require __DIR__ . '/vendor/autoload.php'; //

use App\Db\Database; //

// 1) Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) { //
    header('Location: login_process.php'); //
    exit; //
}

$mensagem = '';
$usuario_id_logado = $_SESSION['usuario_id']; //

// 2) Obter o ID da inscrição da URL
$inscricao_id = filter_input(INPUT_GET, 'inscricao_id', FILTER_VALIDATE_INT); //
if (!$inscricao_id) { //
    header('Location: My_Events_page.php?status=inscricao_invalida'); //
    exit; //
}

// 3) Conexão com o banco de dados
$db = new Database(); //

// 4) Lógica para SALVAR ALTERAÇÕES (se o formulário for submetido)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_alteracoes'])) { //
    $nome               = trim($_POST['Nome']                 ?? ''); //
    $sobrenome          = trim($_POST['Sobrenome']            ?? ''); //
    $data_nascimento    = trim($_POST['Data_de_Nascimento']   ?? ''); //
    $sexo               = trim($_POST['Sexo']                 ?? ''); //
    $telefone           = trim($_POST['Telefone']             ?? ''); //
    $cpf                = trim($_POST['CPF']                  ?? ''); //
    $acessibilidade     = trim($_POST['assessibilidade']      ?? 'Não'); //
    $obs_acessibilidade = trim($_POST['Observacoes_assessibilidade'] ?? ''); //

    if (empty($nome) || empty($sobrenome) || empty($data_nascimento) || empty($sexo) || empty($telefone) || empty($cpf)) { //
        $mensagem = 'Preencha todos os campos obrigatórios.'; //
    } else {
        // Atualizar APENAS os dados do usuário (tabela 'usuarios')
        $stmt_update_user = $db->execute( //
            "UPDATE usuarios SET nome = ?, sobrenome = ?, data_de_nascimento = ?, sexo = ?, telefone = ?, cpf = ? WHERE id = ?", //
            [$nome, $sobrenome, $data_nascimento, $sexo, $telefone, $cpf, $usuario_id_logado] //
        );

        // NOVO: Atualizar os campos de acessibilidade na tabela 'inscricoes'
        $stmt_update_inscricao = $db->execute( //
            "UPDATE inscricoes SET acessibilidade = ?, observacao_acessibilidade = ? WHERE id = ? AND usuario_id = ?", //
            [$acessibilidade, $obs_acessibilidade, $inscricao_id, $usuario_id_logado] //
        );

        if ($stmt_update_user->rowCount() > 0 || $stmt_update_inscricao->rowCount() > 0) { //
            $mensagem = 'Dados atualizados com sucesso!'; //
            $_SESSION['usuario_nome'] = $nome; //
        } else {
            $mensagem = 'Nenhuma alteração foi feita ou erro ao atualizar dados.'; //
        }
    }
}


// 5) Buscar os dados da inscrição, do usuário e do evento
$stmt_inscricao = $db->execute( //
    'SELECT
        i.id AS inscricao_id, i.usuario_id, i.evento_id, i.acessibilidade, i.observacao_acessibilidade, /* Novos campos */
        u.nome, u.sobrenome, u.data_de_nascimento, u.sexo, u.telefone, u.CPF,
        e.titulo AS evento_titulo, e.descricao AS evento_descricao, e.data_evento, e.hora_evento, e.local, e.endereco, e.observacoes_evento AS evento_observacoes, e.imagem AS evento_imagem /* Adicionado e.imagem */
     FROM inscricoes i
     JOIN usuarios u ON i.usuario_id = u.id
     JOIN eventos e ON i.evento_id = e.id
     WHERE i.id = ? AND i.usuario_id = ?',
    [$inscricao_id, $usuario_id_logado] //
);
$detalhes = $stmt_inscricao->fetch(PDO::FETCH_ASSOC); //

if (!$detalhes) { //
    header('Location: My_Events_page.php?status=inscricao_nao_encontrada'); //
    exit; //
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes Inscrição</title>
    <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">

    <style>
        /* BASE: Variáveis CSS replicadas de styles.css */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Shrikhand&display=swap'); /* Importa Poppins e Shrikhand do Google Fonts */

        :root {
            --cor-texto: #1e1e1e;
            --cor-texto-hover: #252530;
            --cor-texto-claro: #ffffff;
            --cor-borda-titulo: #ffc0cb;
            --cor-de-fundo-footer: rgb(215, 236, 255);
            --cor-de-fundo-botao-v: #ff3b3f;
            --border-color: #ffe066;
            --box-shadow-card: 0px 4px 24px 0px rgba(123, 123, 123, 0.15);

            --fonte-texto: 'Poppins', sans-serif;
            --fonte-titulo: 'Shrikhand', cursive;

            --gap-xs: 0.5rem;
            --gap-s: 1rem;
            --gap-m: 1.5rem;
            --gap-l: 2rem;
            --gap-xl: 3.5rem;

            --padding-xs: 0.5rem;
            --padding-s: 1rem;
            --padding-m: 1.5rem;
            --padding-l: 2rem;
            --padding-xl: 3.5rem;
            --padding-xxl: 5rem;
            --padding-form: 0.9375rem;

            --font-size-xs: 0.75rem;
            --font-size-s: 1rem;
            --font-size-m: 1.25rem;
            --font-size-l: 1.5rem;
            --font-size-xl: 3rem;
            --font-size-xxl: 4.75rem;

            --max-width-block: 75rem;

            --margin-xs: 0.5rem;
            --margin-s: 1rem;
            --margin-m: 1.5rem;
            --margin-l: 2rem;
            --margin-xl: 2.75rem;

            --line-height-xs: 1.2rem;
            --line-height-s: 1.5rem;
            --line-height-m: 1.75rem;
            --line-height-l: 2rem;
            --line-height-xl: 2.5rem;
            --line-height-xxl: 5.7rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-size: 16px; /* Base font size */
        }

        body {
            background-image: linear-gradient(to bottom, #7ebac1, #b2ebf2, #e6fcff);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            font-family: var(--fonte-texto); /* Definir fonte padrão para o body */
            color: var(--cor-texto); /* Definir cor de texto padrão para o body */
        }

        /* HEADER: Estilos replicados de Header.css */
        .header {
            max-width: var(--max-width-block);
            margin: 0 auto;
        }
        .nav_list {
            display: flex;
            gap: var(--gap-xl);
            justify-content: space-evenly;
            align-items: center;
            padding: var(--padding-s) 0;
            list-style-type: none;
            flex-wrap: wrap;
        }
        .imagem_header_logo {
            height: auto;
            width: 200px;
        }
        .imagem_header_sanrio {
            height: auto;
            width: 150px;
        }
        .nav_link {
            background-color: var(--cor-borda-titulo);
            border-radius: 5px;
            padding: 0 var(--padding-s) 0 var(--padding-s);
            text-decoration: none;
            font-family: var(--fonte-titulo);
            color: white;
            font-weight: 200;
            font-size: 26px;
            font-stretch: expanded;
        }
        .menu_toggle {
            display: none;
        }
        @media screen and (max-width:1200px) {
            .nav_list {
                row-gap: var(--gap-s);
                gap: var(--gap-s);
            }
            .header {
                padding: 0 var(--padding-xxl);
            }
        }
        @media (max-width:730px) {
            .nav_link {
                display: none;
            }
            .menu_toggle {
                display: block;
                cursor: pointer;
            }
            .header {
                padding: 0 var(--padding-m);
            }
            .menu_toggle_icon {
                background-color: transparent;
                border: none;
            }
        }

        /* TÍTULOS DE PÁGINA: Estilos replicados de Registrer_page.css (e outros) */
        .titulo_pagina {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: var(--margin-m);
        }
        .titulo_meus_eventos { /* Renomeado de .titulo_cadastro para ser mais específico */
            color: var(--cor-texto-claro);
            text-shadow:
                -3px -3px 0 var(--cor-borda-titulo),
                3px -3px 0 var(--cor-borda-titulo),
                -3px 3px 0 var(--cor-borda-titulo),
                3px 3px 0 var(--cor-borda-titulo);
            font-family: var(--fonte-titulo);
            font-size: var(--font-size-xl);
        }

        /* FORMULÁRIO: Estilos combinados e ajustados de Registrer_page.css e my_sing_ups_details.css */
        .formulario_inscrição {
            max-width: var(--max-width-block);
            margin: 0 auto;
            display: flex;
            justify-content: center; /* Centraliza a caixa do formulário */
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
            align-items: center; /* Centraliza horizontalmente os elementos dentro do formulário */
            text-align: center; /* Centraliza o texto de títulos e parágrafos internos */
        }
        
        /* Estilos PADRÃO para todos os campos (inputs, selects, textareas) */
        .formulario_campos input[type="text"],
        .formulario_campos input[type="date"],
        .formulario_campos input[type="tel"],
        .formulario_campos input[type="email"],
        .formulario_campos input[type="password"],
        .formulario_campos select,
        .formulario_campos textarea {
            width: 480px; /* **TAMANHO PADRÃO ÚNICO PARA TODOS OS CAMPOS** */
            height: 60px;
            padding: var(--padding-form);
            border: 1px solid #ccc; /* Borda mais suave por padrão */
            border-radius: 5px; /* Bordas levemente arredondadas */
            background-color: #f9f9f9; /* Fundo levemente cinza para campos */
            color: var(--cor-texto);
            font-size: var(--font-size-s);
            margin-bottom: var(--margin-s);
            transition: all 0.3s ease; /* Transição suave para foco */
            box-sizing: border-box; /* Garante que padding e borda não aumentem a largura */
            text-align: left; /* Alinha o texto dentro do campo à esquerda */
            display: block; /* Garante que cada campo ocupe sua própria linha */
            margin-left: auto; /* Para centralizar os blocos de 480px */
            margin-right: auto; /* Para centralizar os blocos de 480px */
        }

        /* Estilo para campos em foco */
        .formulario_campos input[type="text"]:focus,
        .formulario_campos input[type="date"]:focus,
        .formulario_campos input[type="tel"]:focus,
        .formulario_campos input[type="email"]:focus,
        .formulario_campos input[type="password"]:focus,
        .formulario_campos select:focus,
        .formulario_campos textarea:focus {
            border-color: var(--cor-borda-titulo); /* Borda rosa no foco */
            box-shadow: 0 0 8px rgba(255, 192, 203, 0.6); /* Sombra rosa no foco */
            outline: none; /* Remove outline padrão do navegador */
            background-color: #fff; /* Fundo branco no foco */
        }

        /* Ajustes específicos para textarea */
        .formulario_campos textarea {
            height: 120px;
            resize: vertical;
        }

        /* Esconder <br> tags se o layout for controlado por flexbox */
        .formulario_campos br {
            display: none;
        }
        /* Ajusta margem entre grupos de campos */
        .formulario_campos input + input,
        .formulario_campos input + select,
        .formulario_campos select + input,
        .formulario_campos select + select,
        .formulario_campos textarea + input,
        .formulario_campos textarea + select {
            margin-top: var(--margin-s); /* Espaçamento entre campos */
        }
        .formulario_campos h3 + input,
        .formulario_campos p + input {
            margin-top: var(--margin-m); /* Espaçamento após títulos ou parágrafos */
        }
        
        /* Botões */
        .botoes_container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            max-width: 600px;
            margin-top: var(--margin-xl);
            gap: var(--gap-m); /* Espaçamento entre os botões */
        }
        .botoes_container a {
            text-decoration: none;
        }
        .botoes_container button {
            padding: var(--padding-form);
            border-radius: 8px;
            font-family: var(--fonte-texto);
            font-weight: bold;
            font-size: var(--font-size-s);
            cursor: pointer;
            width: auto;
            min-width: 150px;
            flex-grow: 1;
            max-width: 250px;
            display: block; /* Garante que o botão preencha a largura do seu contêiner flex */
        }
        /* Estilo dos botões de ação (Salvar e Cancelar) */
        #botao_salvar_alterações, #botao_cancelar_inscrição {
            border: 3px solid var(--cor-de-fundo-botao-v);
            background-color: var(--cor-de-fundo-botao-v);
            color: var(--cor-texto-claro);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        #botao_salvar_alterações:hover, #botao_cancelar_inscrição:hover {
            background-color: #d12e32;
            border-color: #d12e32;
        }

        /* Aviso Backend */
        .aviso_backend {
            margin-top: var(--margin-m);
            margin-bottom: var(--margin-l);
        }
        .texto_aviso_backend {
            font-family: var(--fonte-texto);
            font-weight: bold;
            font-size: var(--font-size-m);
            text-align: center;
            color: var(--cor-texto);
        }


        /* FOOTER: Estilos replicados de Footer.css */
        .footer {
            background-color: var(--cor-de-fundo-footer);
            padding: var(--padding-xl) var(--padding-m);
            color: rgb(170, 169, 169);
            font-family: var(--fonte-texto);
            font-weight: bold;
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
                -1px 1px 0 var(--cor-borda-titulo),
                1px 1px 0 var(--cor-borda-titulo);
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
        @media screen and (max-width: 1200px){
            .footer{
                padding: var(--padding-l);
            }
            .footer_column_logo{
                flex-basis: 100%;
                text-align: center;
            }
            .footer_column{
                flex-basis: 30%;
                margin-top: 20px;
            }
        }
        @media screen and (max-width: 730px){
            .footer_columns{
                flex-direction: column;
                padding: var(--padding-m) var(--padding-xs);
            }
        }


        /* RESPONSIVIDADE GERAL DO FORMULÁRIO */
        @media screen and (max-width: 768px) {
            .formulario_campos {
                padding: var(--padding-l);
            }
            .formulario_campos input[type="text"],
            .formulario_campos input[type="date"],
            .formulario_campos input[type="tel"],
            .formulario_campos input[type="email"],
            .formulario_campos input[type="password"],
            .formulario_campos select,
            .formulario_campos textarea {
                width: 100%; /* Ocupa largura total da caixa do formulário */
                max-width: 100%;
                display: block; /* Garante que cada campo ocupe uma linha */
                margin-right: 0; /* Remove margem direita para empilhamento */
            }
            .formulario_campos input + input,
            .formulario_campos input + select,
            .formulario_campos select + input,
            .formulario_campos select + select {
                margin-top: var(--margin-s); /* Restaura espaçamento normal ao empilhar */
            }
            .botoes_container {
                flex-direction: column;
                gap: var(--gap-s);
            }
            .botoes_container button {
                width: 100%;
                max-width: 300px;
            }
        }
        /* Estilo para a imagem do evento dentro do formulário */
        .evento_imagem_card {
            width: 200px; /* Largura padrão para a imagem do evento */
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: var(--margin-m); /* Espaçamento abaixo da imagem */
            border: 3px solid var(--cor-borda-titulo); /* Borda rosa */
            box-shadow: var(--box-shadow-card); /* Sombra */
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
        <div class="titulo_meus_eventos">
            <h1 class="titulo_meus_eventos">Minha Inscrição</h1>
        </div>
    </section>

    <?php if ($mensagem !== ''): ?>
    <div style="text-align:center; margin:1em 0; color: <?= (strpos($mensagem, 'sucesso') !== false) ? 'green' : 'red' ?>;">
      <?= htmlentities($mensagem, ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php endif; ?>

    <section class="formulario_inscrição">
        <form action="" method="post" class="formulario_campos">
            <img src="<?= htmlspecialchars($detalhes['evento_imagem']) ?>" alt="Imagem do evento <?= htmlspecialchars($detalhes['evento_titulo']) ?>" class="evento_imagem_card">

            <h3 style="text-align: center; margin-bottom: 20px; font-family: var(--fonte-texto); color: var(--cor-texto);">
                Detalhes do Evento: <?= htmlspecialchars($detalhes['evento_titulo']) ?>
            </h3>
            <p style="text-align: center; margin-bottom: 15px; font-family: var(--fonte-texto); color: var(--cor-texto);">
                Data: <?= date('d/m/Y', strtotime($detalhes['data_evento'])) ?> | Hora: <?= date('H:i', strtotime($detalhes['hora_evento'])) ?>
            </p>
            <p style="text-align: center; margin-bottom: 30px; font-family: var(--fonte-texto); color: var(--cor-texto);">
                Local: <?= htmlspecialchars($detalhes['local']) ?> | Endereço: <?= htmlspecialchars($detalhes['endereco']) ?>
            </p>


            <h3 style="text-align: center; margin-bottom: 20px; font-family: var(--fonte-texto); color: var(--cor-texto);">
                Suas Informações Pessoais na Inscrição
            </h3>

            <input type="text" placeholder="Nome" name="Nome" id="primeiro-nome" required value="<?= htmlspecialchars($detalhes['nome']) ?>">

            <input type="text" placeholder="Sobrenome" name="Sobrenome" id="sobrenome" required value="<?= htmlspecialchars($detalhes['sobrenome']) ?>">

            <input type="date" placeholder="Data de Nascimento" name="Data_de_Nascimento" id="data-nascimento" required value="<?= htmlspecialchars($detalhes['data_de_nascimento']) ?>">

            <select id="sexo" name="Sexo" required>
                <option value="">Selecione o Sexo</option>
                <option value="Feminino" <?= ($detalhes['sexo'] === 'Feminino') ? 'selected' : '' ?>>Feminino</option>
                <option value="Masculino" <?= ($detalhes['sexo'] === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                <option value="Não Informado" <?= ($detalhes['sexo'] === 'Não Informado') ? 'selected' : '' ?>>Prefiro não dizer</option>
            </select>

            <input type="tel" placeholder="Telefone" name="Telefone" id="telefone" maxlength="20" required value="<?= htmlspecialchars($detalhes['telefone']) ?>">

            <input type="text" placeholder="CPF" name="CPF" id="cpf" required value="<?= htmlspecialchars($detalhes['CPF']) ?>">

            <select id="assessibilidade" name="assessibilidade">
                <option value="">Precisa de acessibilidade no local?</option>
                <option value="Sim" <?= ($detalhes['acessibilidade'] === 'Sim') ? 'selected' : '' ?>>Sim</option>
                <option value="Não" <?= ($detalhes['acessibilidade'] === 'Não') ? 'selected' : '' ?>>Não</option>
            </select>

            <textarea placeholder="Observações de acessibilidade" name="Observacoes_assessibilidade" id="Observacoes-assessibilidade" maxlength="500"><?= htmlspecialchars($detalhes['observacao_acessibilidade']) ?></textarea>

            <div class="botoes_container">
                <button type="submit" name="salvar_alteracoes" id="botao_salvar_alterações">SALVAR ALTERAÇÕES</button>
                <a href="cancelar_inscricao.php?inscricao_id=<?= $detalhes['inscricao_id'] ?>" onclick="return confirm('Tem certeza que deseja cancelar esta inscrição?')">
                    <button type="button" id="botao_cancelar_inscrição">CANCELAR INSCRIÇÃO</button>
                </a>
            </div>

        </form>
    </section>

    <footer class="footer">
        <div class="footer_columns">
            <div class="footer_column_logo">
                <img src="assets/imagens/logo-225x150.png" alt="logo Ticket.fun" class="imagem_footer_logo">
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
                    <li>
                        <a href="#">
                            <img src="assets/imagens/whatsapp.svg" alt="Whatsapp">
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img src="assets/imagens/instagram.svg" alt="Instagram">
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <img src="assets/imagens/tiktok.svg" alt="Tiktok">
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer_bottom footer_columns">
                <p>© 2025 Feito com 💗 por fãs da Hello Kitty.</p>
            </div>
        </div>
    </footer>
</body>
</html>