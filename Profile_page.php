<?php
session_start();

// 1) Se não houver usuário logado, redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login_process.php');
    exit;
}

// 2) Conexão com o banco (mysqli)
$servidor  = "localhost";
$usuarioDB = "root";
$senhaDB   = "";
$bancoDB   = "hello_kitty";

$conn = new mysqli($servidor, $usuarioDB, $senhaDB, $bancoDB);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// 3) ID do usuário logado
$idUsuario = $_SESSION['usuario_id'];

// 4) Variável para mensagem de feedback
$mensagem = '';

// 5) Se for POST (clicou em “SALVAR”), processa atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome               = trim($_POST['Nome']                 ?? '');
    $sobrenome          = trim($_POST['Sobrenome']            ?? '');
    $dataDeNascimento   = trim($_POST['data_de_nascimento']   ?? '');
    $sexo               = trim($_POST['Sexo']                 ?? '');
    // $pais foi removido
    $telefone           = trim($_POST['Telefone']             ?? '');
    $cpf                = trim($_POST['CPF']                  ?? '');
    $emailUsuario       = trim($_POST['email_login']          ?? '');

    if ($nome === '' || $sobrenome === '' || $emailUsuario === '') {
        $mensagem = 'Nome, Sobrenome e E-mail são obrigatórios.';
    } else {
        // 5.3) UPDATE sem pais_residencia
        $sql  = "
            UPDATE usuarios SET
                nome               = ?,
                sobrenome          = ?,
                data_de_nascimento = ?,
                sexo               = ?,
                telefone           = ?,
                cpf                = ?,
                email              = ?
            WHERE id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $nome,
            $sobrenome,
            $dataDeNascimento,
            $sexo,
            $telefone,
            $cpf,
            $emailUsuario,
            $idUsuario
        );

        if ($stmt->execute()) {
            $mensagem = 'Dados atualizados com sucesso!';
            $_SESSION['usuario_nome'] = $nome; // Atualiza o nome na sessão
        } else {
            $mensagem = 'Erro ao atualizar: ' . $stmt->error;
        }

        $stmt->close();
    }
}

// 6) SELECT sem pais_residencia
$sqlBuscador = "
    SELECT
        nome,
        sobrenome,
        data_de_nascimento,
        sexo,
        telefone,
        cpf,
        email
    FROM usuarios
    WHERE id = ?
";
$stmt2 = $conn->prepare($sqlBuscador);
$stmt2->bind_param("i", $idUsuario);
$stmt2->execute();
$resultado = $stmt2->get_result();
$usuarioDados = $resultado->fetch_assoc();

if (!$usuarioDados) {
    // Se não encontrar o usuário, destrói a sessão e redireciona
    session_unset();
    session_destroy();
    header('Location: login_process.php');
    exit;
}

$stmt2->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer1.css">
  <link rel="stylesheet" href="assets/css/profile_page.css">
  <title>Perfil</title>
  <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">
</head>

<body>
  <header class="header">
    <nav class="nav" aria-label="navegação principal">
      <ul class="nav_list">
        <li class="menu_toggle">
          <button class="menu_toggle_icon" aria-label="menu hambúrguer">
            <img src="assets/imagens/menu.svg" alt="menu hambúrguer">
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
    <div class="titulo_senhas">
      <h1 class="titulo_senhas">Meu Perfil</h1>
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

  <?php if ($mensagem !== ''): ?>
    <div style="text-align:center; margin:1em 0;
                color: <?= (strpos($mensagem, 'sucesso') !== false) ? 'green' : 'red' ?>;">
      <?= htmlentities($mensagem, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <section class="formulario_info_cadastro">
    <form action="profile_page.php" method="post" class="formulario_campos">
      <div class="titulo_h3">
        <h3 class="titulo_h3">Informações de Cadastro</h3>
      </div>

      <label for="primeiro-nome"></label>
      <input
        type="text"
        placeholder="Nome"
        name="Nome"
        id="primeiro-nome"
        class="campo-informaçõese"
        maxlength="100"
        required
        value="<?= htmlentities($usuarioDados['nome'], ENT_QUOTES, 'UTF-8') ?>"
      >

      <label for="sobrenome"></label>
      <input
        type="text"
        placeholder="Sobrenome"
        name="Sobrenome"
        id="sobrenome"
        class="campo-informaçõesd"
        maxlength="100"
        required
        value="<?= htmlentities($usuarioDados['sobrenome'], ENT_QUOTES, 'UTF-8') ?>"
      >

      <br><br>

      <label for="data-nascimento"></label>
      <input
        type="date"
        name="data_de_nascimento"
        id="data-nascimento"
        class="campo-informaçõese"
        required
        value="<?= htmlentities($usuarioDados['data_de_nascimento'], ENT_QUOTES, 'UTF-8') ?>"
      >

      <label for="sexo"></label>
      <select
        id="sexo"
        name="Sexo"
        class="campo-informaçõesd"
        required
      >
        <option value="">Selecione o Sexo</option>
        <option value="Feminino"    <?= ($usuarioDados['sexo'] === 'Feminino')    ? 'selected' : '' ?>>Feminino</option>
        <option value="Masculino"   <?= ($usuarioDados['sexo'] === 'Masculino')   ? 'selected' : '' ?>>Masculino</option>
        <option value="Não Informado" <?= ($usuarioDados['sexo'] === 'Não Informado') ? 'selected' : '' ?>>Prefiro não dizer</option>
      </select>
      <br><br>

      <label for="telefone"></label>
      <input
        type="text"
        placeholder="Telefone"
        name="Telefone"
        id="telefone"
        class="campo-informaçõesd"
        maxlength="20"
        required
        value="<?= htmlentities($usuarioDados['telefone'], ENT_QUOTES, 'UTF-8') ?>"
      >

      <label for="cpf"></label>
      <input
        type="text"
        placeholder="CPF"
        name="CPF"
        id="cpf"
        class="campo-informções-final"
        maxlength="14"
        required
        value="<?= htmlentities($usuarioDados['cpf'], ENT_QUOTES, 'UTF-8') ?>"
      >

      <div class="titulo_h3" id="h3_final">
        <h3 class="titulo_h3">Informações de Login</h3>
      </div>

      <label for="email-login"></label>
      <input
        type="email"
        placeholder="E-mail"
        name="email_login"
        id="email_login"
        class="campo_email"
        maxlength="100"
        required
        value="<?= htmlentities($usuarioDados['email'], ENT_QUOTES, 'UTF-8') ?>"
      >
      <br><br>

      <button
        type="submit"
        id="botao_criar_login"
        class="botao_salvar"
      >
        SALVAR
      </button>

    </form>
  </section>

  <footer class="footer">
    <div class="footer_columns">
      <div class="footer_column_logo">
        <img
          src="assets/imagens/logo-225x150.png"
          alt="logo Ticket.fun"
          class="footer_column_img footer_column_list">
      </div>

      <div class="footer_column">
        <h4 class="footer_column_titulo">Funcionamento</h4>
        <ul class="footer_column_list">
          <li>
            <p>Somos uma plataforma 100% digital!<br> Para suporte ao cliente Segunda a sexta - 08:00 às 18:00</p>
          </li>
          <li>
            <a href="mailto:ticket.fun_suporte@gmail.com" class="footer_column_list_a">
              ticket.fun_suporte@gmail.com
            </a>
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
            <a href="#"><img src="assets/imagens/whatsapp.svg" alt="Whatsapp"></a>
          </li>
          <li>
            <a href="#"><img src="assets/imagens/instagram.svg" alt="Instagram"></a>
          </li>
          <li>
            <a href="#"><img src="assets/imagens/tiktok.svg" alt="Tiktok"></a>
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