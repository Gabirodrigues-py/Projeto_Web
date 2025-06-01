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
    $pais               = trim($_POST['país']                 ?? '');
    $telefone           = trim($_POST['Telefone']             ?? '');
    $cpf                = trim($_POST['CPF']                  ?? '');
    $emailUsuario       = trim($_POST['email_login']          ?? '');

    if ($nome === '' || $sobrenome === '' || $emailUsuario === '') {
        $mensagem = 'Nome, Sobrenome e E-mail são obrigatórios.';
    } else {
        // 5.3) UPDATE incluindo data_de_nascimento
        $sql  = "
            UPDATE usuarios SET
                nome               = ?,
                sobrenome          = ?,
                data_de_nascimento = ?, 
                sexo               = ?,
                pais_residencia    = ?,
                telefone           = ?,
                cpf                = ?,
                email              = ?
            WHERE id = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssi",
            $nome,
            $sobrenome,
            $dataDeNascimento,
            $sexo,
            $pais,
            $telefone,
            $cpf,
            $emailUsuario,
            $idUsuario
        );

        if ($stmt->execute()) {
            $mensagem = 'Dados atualizados com sucesso!';
            $_SESSION['usuario_nome'] = $nome;
        } else {
            $mensagem = 'Erro ao atualizar: ' . $stmt->error;
        }

        $stmt->close();
    }
}

// 6) SELECT também precisa buscar data_de_nascimento
$sqlBuscador = "
    SELECT 
        nome, 
        sobrenome, 
        data_de_nascimento,    
        sexo, 
        pais_residencia, 
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

if ($resultado->num_rows === 1) {
    $usuarioDados          = $resultado->fetch_assoc();
    $nomeAtual             = $usuarioDados['nome'];
    $sobrenomeAtual        = $usuarioDados['sobrenome'];
    $dataDeNascimentoAtual = $usuarioDados['data_de_nascimento'];
    $sexoAtual             = $usuarioDados['sexo'];
    $paisAtual             = $usuarioDados['pais_residencia'];
    $telefoneAtual         = $usuarioDados['telefone'];
    $cpfAtual              = $usuarioDados['cpf'];
    $emailAtual            = $usuarioDados['email'];
} else {
    $stmt2->close();
    $conn->close();
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
  <link rel="stylesheet" href="assets/css/footer.css">
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
          <a href="/Projeto_Web/profile_page.php" class="Link-Menu-Lateral" id="perfil">PERFIL</a>
        </li>
        <li>
          <a href="/Projeto_Web/my_events_page.html" class="Link-Menu-Lateral" id="meus_eventos">MEUS EVENTOS</a>
        </li>
        <li>
          <a href="/Projeto_Web/password_page.php" class="Link-Menu-Lateral" id="senhas">SENHAS</a>
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
        value="<?= htmlentities($nomeAtual, ENT_QUOTES, 'UTF-8') ?>"
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
        value="<?= htmlentities($sobrenomeAtual, ENT_QUOTES, 'UTF-8') ?>"
      >

      <br><br>

      <label for="data-nascimento"></label>
      <input 
        type="date" 
        name="data_de_nascimento" 
        id="data-nascimento" 
        class="campo-informaçõese" 
        required
        value="<?= htmlentities($dataDeNascimentoAtual, ENT_QUOTES, 'UTF-8') ?>"
      >

      <label for="sexo"></label>
      <select 
        id="sexo" 
        name="Sexo" 
        class="campo-informaçõesd" 
        required
      >
        <option value="">Selecione o Sexo</option>
        <option value="Feminino"    <?= ($sexoAtual === 'Feminino')    ? 'selected' : '' ?>>Feminino</option>
        <option value="Masculino"   <?= ($sexoAtual === 'Masculino')   ? 'selected' : '' ?>>Masculino</option>
        <option value="Não Informado" <?= ($sexoAtual === 'Não Informado') ? 'selected' : '' ?>>Prefiro não dizer</option>
      </select>
      <br><br>

      <label for="país"></label>
      <select 
        id="país" 
        name="país" 
        class="campo-seleçaoe"
        required
      >
        <option value="">Selecione um país</option>
        <option value="Afeganistão"             <?= ($paisAtual === 'Afeganistão')             ? 'selected' : '' ?>>Afeganistão</option>
        <option value="África do Sul"           <?= ($paisAtual === 'África do Sul')           ? 'selected' : '' ?>>África do Sul</option>
        <option value="Albânia"                 <?= ($paisAtual === 'Albânia')                 ? 'selected' : '' ?>>Albânia</option>
        <option value="Alemanha"                <?= ($paisAtual === 'Alemanha')                ? 'selected' : '' ?>>Alemanha</option>
        <option value="Andorra"                 <?= ($paisAtual === 'Andorra')                 ? 'selected' : '' ?>>Andorra</option>
        <option value="Angola"                  <?= ($paisAtual === 'Angola')                  ? 'selected' : '' ?>>Angola</option>
        <option value="Antígua e Barbuda"       <?= ($paisAtual === 'Antígua e Barbuda')       ? 'selected' : '' ?>>Antígua e Barbuda</option>
        <option value="Arábia Saudita"          <?= ($paisAtual === 'Arábia Saudita')          ? 'selected' : '' ?>>Arábia Saudita</option>
        <option value="Argélia"                 <?= ($paisAtual === 'Argélia')                 ? 'selected' : '' ?>>Argélia</option>
        <option value="Argentina"               <?= ($paisAtual === 'Argentina')               ? 'selected' : '' ?>>Argentina</option>
        <option value="Armênia"                 <?= ($paisAtual === 'Armênia')                 ? 'selected' : '' ?>>Armênia</option>
        <option value="Austrália"               <?= ($paisAtual === 'Austrália')               ? 'selected' : '' ?>>Austrália</option>
        <option value="Áustria"                 <?= ($paisAtual === 'Áustria')                 ? 'selected' : '' ?>>Áustria</option>
        <option value="Azerbaijão"              <?= ($paisAtual === 'Azerbaijão')              ? 'selected' : '' ?>>Azerbaijão</option>
        <option value="Bahamas"                 <?= ($paisAtual === 'Bahamas')                 ? 'selected' : '' ?>>Bahamas</option>
        <option value="Bahrein"                 <?= ($paisAtual === 'Bahrein')                 ? 'selected' : '' ?>>Bahrein</option>
        <option value="Bangladesh"              <?= ($paisAtual === 'Bangladesh')              ? 'selected' : '' ?>>Bangladesh</option>
        <option value="Barbados"                <?= ($paisAtual === 'Barbados')                ? 'selected' : '' ?>>Barbados</option>
        <option value="Belarus"                 <?= ($paisAtual === 'Belarus')                 ? 'selected' : '' ?>>Belarus</option>
        <option value="Bélgica"                 <?= ($paisAtual === 'Bélgica')                 ? 'selected' : '' ?>>Bélgica</option>
        <option value="Belize"                  <?= ($paisAtual === 'Belize')                  ? 'selected' : '' ?>>Belize</option>
        <option value="Benim"                   <?= ($paisAtual === 'Benim')                   ? 'selected' : '' ?>>Benim</option>
        <option value="Butão"                   <?= ($paisAtual === 'Butão')                   ? 'selected' : '' ?>>Butão</option>
        <option value="Bolívia"                 <?= ($paisAtual === 'Bolívia')                 ? 'selected' : '' ?>>Bolívia</option>
        <option value="Bósnia e Herzegovina"    <?= ($paisAtual === 'Bósnia e Herzegovina')    ? 'selected' : '' ?>>Bósnia e Herzegovina</option>
        <option value="Botsuana"                <?= ($paisAtual === 'Botsuana')                ? 'selected' : '' ?>>Botsuana</option>
        <option value="Brasil"                  <?= ($paisAtual === 'Brasil')                  ? 'selected' : '' ?>>Brasil</option>
        <option value="Brunei"                  <?= ($paisAtual === 'Brunei')                  ? 'selected' : '' ?>>Brunei</option>
        <option value="Bulgária"                <?= ($paisAtual === 'Bulgária')                ? 'selected' : '' ?>>Bulgária</option>
        <option value="Burkina Faso"            <?= ($paisAtual === 'Burkina Faso')            ? 'selected' : '' ?>>Burkina Faso</option>
        <option value="Burundi"                 <?= ($paisAtual === 'Burundi')                 ? 'selected' : '' ?>>Burundi</option>
        <option value="Cabo Verde"              <?= ($paisAtual === 'Cabo Verde')              ? 'selected' : '' ?>>Cabo Verde</option>
        <option value="Camarões"                <?= ($paisAtual === 'Camarões')                ? 'selected' : '' ?>>Camarões</option>
        <option value="Camboja"                 <?= ($paisAtual === 'Camboja')                 ? 'selected' : '' ?>>Camboja</option>
        <option value="Canadá"                  <?= ($paisAtual === 'Canadá')                  ? 'selected' : '' ?>>Canadá</option>
        <option value="Catar"                   <?= ($paisAtual === 'Catar')                   ? 'selected' : '' ?>>Catar</option>
        <option value="Cazaquistão"             <?= ($paisAtual === 'Cazaquistão')             ? 'selected' : '' ?>>Cazaquistão</option>
        <option value="Chade"                   <?= ($paisAtual === 'Chade')                   ? 'selected' : '' ?>>Chade</option>
        <option value="Chile"                   <?= ($paisAtual === 'Chile')                   ? 'selected' : '' ?>>Chile</option>
        <option value="China"                   <?= ($paisAtual === 'China')                   ? 'selected' : '' ?>>China</option>
        <option value="Chipre"                  <?= ($paisAtual === 'Chipre')                  ? 'selected' : '' ?>>Chipre</option>
        <option value="Colômbia"                <?= ($paisAtual === 'Colômbia')                ? 'selected' : '' ?>>Colômbia</option>
        <option value="Comores"                 <?= ($paisAtual === 'Comores')                 ? 'selected' : '' ?>>Comores</option>
        <option value="Congo"                   <?= ($paisAtual === 'Congo')                   ? 'selected' : '' ?>>Congo</option>
        <option value="Coreia do Norte"         <?= ($paisAtual === 'Coreia do Norte')         ? 'selected' : '' ?>>Coreia do Norte</option>
        <option value="Coreia do Sul"           <?= ($paisAtual === 'Coreia do Sul')           ? 'selected' : '' ?>>Coreia do Sul</option>
        <option value="Costa do Marfim"         <?= ($paisAtual === 'Costa do Marfim')         ? 'selected' : '' ?>>Costa do Marfim</option>
        <option value="Costa Rica"              <?= ($paisAtual === 'Costa Rica')              ? 'selected' : '' ?>>Costa Rica</option>
        <option value="Croácia"                 <?= ($paisAtual === 'Croácia')                 ? 'selected' : '' ?>>Croácia</option>
        <option value="Cuba"                    <?= ($paisAtual === 'Cuba')                    ? 'selected' : '' ?>>Cuba</option>
        <option value="Dinamarca"               <?= ($paisAtual === 'Dinamarca')               ? 'selected' : '' ?>>Dinamarca</option>
        <option value="Djibuti"                 <?= ($paisAtual === 'Djibuti')                 ? 'selected' : '' ?>>Djibuti</option>
        <option value="Dominica"                <?= ($paisAtual === 'Dominica')                ? 'selected' : '' ?>>Dominica</option>
        <option value="Egito"                   <?= ($paisAtual === 'Egito')                   ? 'selected' : '' ?>>Egito</option>
        <option value="El Salvador"             <?= ($paisAtual === 'El Salvador')             ? 'selected' : '' ?>>El Salvador</option>
        <option value="Emirados Árabes Unidos"  <?= ($paisAtual === 'Emirados Árabes Unidos')  ? 'selected' : '' ?>>Emirados Árabes Unidos</option>
        <option value="Equador"                 <?= ($paisAtual === 'Equador')                 ? 'selected' : '' ?>>Equador</option>
        <option value="Eritreia"                <?= ($paisAtual === 'Eritreia')                ? 'selected' : '' ?>>Eritreia</option>
        <option value="Eslováquia"              <?= ($paisAtual === 'Eslováquia')              ? 'selected' : '' ?>>Eslováquia</option>
        <option value="Eslovênia"               <?= ($paisAtual === 'Eslovênia')               ? 'selected' : '' ?>>Eslovênia</option>
        <option value="Espanha"                 <?= ($paisAtual === 'Espanha')                 ? 'selected' : '' ?>>Espanha</option>
        <option value="Estados Unidos"          <?= ($paisAtual === 'Estados Unidos')          ? 'selected' : '' ?>>Estados Unidos</option>
        <option value="Estônia"                 <?= ($paisAtual === 'Estônia')                 ? 'selected' : '' ?>>Estônia</option>
        <option value="Eswatini"                <?= ($paisAtual === 'Eswatini')                ? 'selected' : '' ?>>Eswatini</option>
        <option value="Etiópia"                 <?= ($paisAtual === 'Etiópia')                 ? 'selected' : '' ?>>Etiópia</option>
        <option value="Fiji"                    <?= ($paisAtual === 'Fiji')                    ? 'selected' : '' ?>>Fiji</option>
        <option value="Filipinas"               <?= ($paisAtual === 'Filipinas')               ? 'selected' : '' ?>>Filipinas</option>
        <option value="Finlândia"               <?= ($paisAtual === 'Finlândia')               ? 'selected' : '' ?>>Finlândia</option>
        <option value="França"                  <?= ($paisAtual === 'França')                  ? 'selected' : '' ?>>França</option>
        <option value="Gabão"                   <?= ($paisAtual === 'Gabão')                   ? 'selected' : '' ?>>Gabão</option>
        <option value="Gâmbia"                  <?= ($paisAtual === 'Gâmbia')                  ? 'selected' : '' ?>>Gâmbia</option>
        <option value="Gana"                    <?= ($paisAtual === 'Gana')                    ? 'selected' : '' ?>>Gana</option>
        <option value="Geórgia"                 <?= ($paisAtual === 'Geórgia')                 ? 'selected' : '' ?>>Geórgia</option>
        <option value="Granada"                 <?= ($paisAtual === 'Granada')                 ? 'selected' : '' ?>>Granada</option>
        <option value="Grécia"                  <?= ($paisAtual === 'Grécia')                  ? 'selected' : '' ?>>Grécia</option>
        <option value="Guatemala"               <?= ($paisAtual === 'Guatemala')               ? 'selected' : '' ?>>Guatemala</option>
        <option value="Guiana"                  <?= ($paisAtual === 'Guiana')                  ? 'selected' : '' ?>>Guiana</option>
        <option value="Guiné"                   <?= ($paisAtual === 'Guiné')                   ? 'selected' : '' ?>>Guiné</option>
        <option value="Guiné-Bissau"            <?= ($paisAtual === 'Guiné-Bissau')            ? 'selected' : '' ?>>Guiné-Bissau</option>
        <option value="Guiné Equatorial"        <?= ($paisAtual === 'Guiné Equatorial')        ? 'selected' : '' ?>>Guiné Equatorial</option>
        <option value="Haiti"                   <?= ($paisAtual === 'Haiti')                   ? 'selected' : '' ?>>Haiti</option>
        <option value="Holanda"                 <?= ($paisAtual === 'Holanda')                 ? 'selected' : '' ?>>Holanda</option>
        <option value="Honduras"                <?= ($paisAtual === 'Honduras')                ? 'selected' : '' ?>>Honduras</option>
        <option value="Hungria"                 <?= ($paisAtual === 'Hungria')                 ? 'selected' : '' ?>>Hungria</option>
        <option value="Iémen"                   <?= ($paisAtual === 'Iémen')                   ? 'selected' : '' ?>>Iémen</option>
        <option value="Ilhas Marshall"          <?= ($paisAtual === 'Ilhas Marshall')          ? 'selected' : '' ?>>Ilhas Marshall</option>
        <option value="Ilhas Salomão"           <?= ($paisAtual === 'Ilhas Salomão')           ? 'selected' : '' ?>>Ilhas Salomão</option>
        <option value="Índia"                   <?= ($paisAtual === 'Índia')                   ? 'selected' : '' ?>>Índia</option>
        <option value="Indonésia"               <?= ($paisAtual === 'Indonésia')               ? 'selected' : '' ?>>Indonésia</option>
        <option value="Irã"                     <?= ($paisAtual === 'Irã')                     ? 'selected' : '' ?>>Irã</option>
        <option value="Iraque"                  <?= ($paisAtual === 'Iraque')                  ? 'selected' : '' ?>>Iraque</option>
        <option value="Irlanda"                 <?= ($paisAtual === 'Irlanda')                 ? 'selected' : '' ?>>Irlanda</option>
        <option value="Islândia"                <?= ($paisAtual === 'Islândia')                ? 'selected' : '' ?>>Islândia</option>
        <option value="Israel"                  <?= ($paisAtual === 'Israel')                  ? 'selected' : '' ?>>Israel</option>
        <option value="Itália"                  <?= ($paisAtual === 'Itália')                  ? 'selected' : '' ?>>Itália</option>
        <option value="Jamaica"                 <?= ($paisAtual === 'Jamaica')                 ? 'selected' : '' ?>>Jamaica</option>
        <option value="Japão"                   <?= ($paisAtual === 'Japão')                   ? 'selected' : '' ?>>Japão</option>
        <option value="Jordânia"                <?= ($paisAtual === 'Jordânia')                ? 'selected' : '' ?>>Jordânia</option>
        <option value="Kiribati"                <?= ($paisAtual === 'Kiribati')                ? 'selected' : '' ?>>Kiribati</option>
        <option value="Kosovo"                  <?= ($paisAtual === 'Kosovo')                  ? 'selected' : '' ?>>Kosovo</option>
        <option value="Kuwait"                  <?= ($paisAtual === 'Kuwait')                  ? 'selected' : '' ?>>Kuwait</option>
        <option value="Laos"                    <?= ($paisAtual === 'Laos')                    ? 'selected' : '' ?>>Laos</option>
        <option value="Lesoto"                  <?= ($paisAtual === 'Lesoto')                  ? 'selected' : '' ?>>Lesoto</option>
        <option value="Letônia"                 <?= ($paisAtual === 'Letônia')                 ? 'selected' : '' ?>>Letônia</option>
        <option value="Líbano"                  <?= ($paisAtual === 'Líbano')                  ? 'selected' : '' ?>>Líbano</option>
        <option value="Libéria"                 <?= ($paisAtual === 'Libéria')                 ? 'selected' : '' ?>>Libéria</option>
        <option value="Líbia"                   <?= ($paisAtual === 'Líbia')                   ? 'selected' : '' ?>>Líbia</option>
        <option value="Liechtenstein"           <?= ($paisAtual === 'Liechtenstein')           ? 'selected' : '' ?>>Liechtenstein</option>
        <option value="Lituânia"                <?= ($paisAtual === 'Lituânia')                ? 'selected' : '' ?>>Lituânia</option>
        <option value="Luxemburgo"              <?= ($paisAtual === 'Luxemburgo')              ? 'selected' : '' ?>>Luxemburgo</option>
        <option value="Macedônia"               <?= ($paisAtual === 'Macedônia')               ? 'selected' : '' ?>>Macedônia</option>
        <option value="Madagascar"              <?= ($paisAtual === 'Madagascar')              ? 'selected' : '' ?>>Madagascar</option>
        <option value="Malásia"                 <?= ($paisAtual === 'Malásia')                 ? 'selected' : '' ?>>Malásia</option>
        <option value="Malaui"                  <?= ($paisAtual === 'Malaui')                  ? 'selected' : '' ?>>Malaui</option>
        <option value="Maldivas"                <?= ($paisAtual === 'Maldivas')                ? 'selected' : '' ?>>Maldivas</option>
        <option value="Mali"                    <?= ($paisAtual === 'Mali')                    ? 'selected' : '' ?>>Mali</option>
        <option value="Malta"                   <?= ($paisAtual === 'Malta')                   ? 'selected' : '' ?>>Malta</option>
        <option value="Marrocos"                <?= ($paisAtual === 'Marrocos')                ? 'selected' : '' ?>>Marrocos</option>
        <option value="Maurícia"                <?= ($paisAtual === 'Maurícia')                ? 'selected' : '' ?>>Maurícia</option>
        <option value="Mauritânia"             <?= ($paisAtual === 'Mauritânia')             ? 'selected' : '' ?>>Mauritânia</option>
        <option value="México"                  <?= ($paisAtual === 'México')                  ? 'selected' : '' ?>>México</option>
        <option value="Moldávia"                <?= ($paisAtual === 'Moldávia')                ? 'selected' : '' ?>>Moldávia</option>
        <option value="Mônaco"                  <?= ($paisAtual === 'Mônaco')                  ? 'selected' : '' ?>>Mônaco</option>
        <option value="Mongólia"                <?= ($paisAtual === 'Mongólia')                ? 'selected' : '' ?>>Mongólia</option>
        <option value="Montenegro"              <?= ($paisAtual === 'Montenegro')              ? 'selected' : '' ?>>Montenegro</option>
        <option value="Moçambique"              <?= ($paisAtual === 'Moçambique')              ? 'selected' : '' ?>>Moçambique</option>
        <option value="Mianmar"                 <?= ($paisAtual === 'Mianmar')                 ? 'selected' : '' ?>>Mianmar</option>
        <option value="Namíbia"                 <?= ($paisAtual === 'Namíbia')                 ? 'selected' : '' ?>>Namíbia</option>
        <option value="Nauru"                   <?= ($paisAtual === 'Nauru')                   ? 'selected' : '' ?>>Nauru</option>
        <option value="Nepal"                   <?= ($paisAtual === 'Nepal')                   ? 'selected' : '' ?>>Nepal</option>
        <option value="Nicarágua"               <?= ($paisAtual === 'Nicarágua')               ? 'selected' : '' ?>>Nicarágua</option>
        <option value="Níger"                   <?= ($paisAtual === 'Níger')                   ? 'selected' : '' ?>>Níger</option>
        <option value="Nigéria"                 <?= ($paisAtual === 'Nigéria')                 ? 'selected' : '' ?>>Nigéria</option>
        <option value="Noruega"                 <?= ($paisAtual === 'Noruega')                 ? 'selected' : '' ?>>Noruega</option>
        <option value="Nova Zelândia"           <?= ($paisAtual === 'Nova Zelândia')           ? 'selected' : '' ?>>Nova Zelândia</option>
        <option value="Omã"                     <?= ($paisAtual === 'Omã')                     ? 'selected' : '' ?>>Omã</option>
        <option value="Países Baixos"           <?= ($paisAtual === 'Países Baixos')           ? 'selected' : '' ?>>Países Baixos</option>
        <option value="Palau"                   <?= ($paisAtual === 'Palau')                   ? 'selected' : '' ?>>Palau</option>
        <option value="Panamá"                  <?= ($paisAtual === 'Panamá')                  ? 'selected' : '' ?>>Panamá</option>
        <option value="Papua-Nova Guiné"        <?= ($paisAtual === 'Papua-Nova Guiné')        ? 'selected' : '' ?>>Papua-Nova Guiné</option>
        <option value="Paquistão"               <?= ($paisAtual === 'Paquistão')               ? 'selected' : '' ?>>Paquistão</option>
        <option value="Paraguai"                <?= ($paisAtual === 'Paraguai')                ? 'selected' : '' ?>>Paraguai</option>
        <option value="Peru"                    <?= ($paisAtual === 'Peru')                    ? 'selected' : '' ?>>Peru</option>
        <option value="Polônia"                 <?= ($paisAtual === 'Polônia')                 ? 'selected' : '' ?>>Polônia</option>
        <option value="Portugal"                <?= ($paisAtual === 'Portugal')                ? 'selected' : '' ?>>Portugal</option>
        <option value="Quênia"                  <?= ($paisAtual === 'Quênia')                  ? 'selected' : '' ?>>Quênia</option>
        <option value="Quirguistão"             <?= ($paisAtual === 'Quirguistão')             ? 'selected' : '' ?>>Quirguistão</option>
        <option value="Reino Unido"             <?= ($paisAtual === 'Reino Unido')             ? 'selected' : '' ?>>Reino Unido</option>
        <option value="República Centro-Africana"<?= ($paisAtual === 'República Centro-Africana')? 'selected' : '' ?>>República Centro-Africana</option>
        <option value="República Checa"          <?= ($paisAtual === 'República Checa')          ? 'selected' : '' ?>>República Checa</option>
        <option value="República Democrática do Congo"<?= ($paisAtual === 'República Democrática do Congo') ? 'selected' : '' ?>>República Democrática do Congo</option>
        <option value="República Dominicana"     <?= ($paisAtual === 'República Dominicana')     ? 'selected' : '' ?>>República Dominicana</option>
        <option value="Romênia"                 <?= ($paisAtual === 'Romênia')                 ? 'selected' : '' ?>>Romênia</option>
        <option value="Ruanda"                  <?= ($paisAtual === 'Ruanda')                  ? 'selected' : '' ?>>Ruanda</option>
        <option value="Rússia"                  <?= ($paisAtual === 'Rússia')                  ? 'selected' : '' ?>>Rússia</option>
        <option value="Samoa"                   <?= ($paisAtual === 'Samoa')                   ? 'selected' : '' ?>>Samoa</option>
        <option value="San Marino"              <?= ($paisAtual === 'San Marino')              ? 'selected' : '' ?>>San Marino</option>
        <option value="Santa Lúcia"             <?= ($paisAtual === 'Santa Lúcia')             ? 'selected' : '' ?>>Santa Lúcia</option>
        <option value="São Cristóvão e Névis"   <?= ($paisAtual === 'São Cristóvão e Névis')   ? 'selected' : '' ?>>São Cristóvão e Névis</option>
        <option value="São Tomé e Príncipe"      <?= ($paisAtual === 'São Tomé e Príncipe')      ? 'selected' : '' ?>>São Tomé e Príncipe</option>
        <option value="Senegal"                 <?= ($paisAtual === 'Senegal')                 ? 'selected' : '' ?>>Senegal</option>
        <option value="Serra Leoa"              <?= ($paisAtual === 'Serra Leoa')              ? 'selected' : '' ?>>Serra Leoa</option>
        <option value="Seychelles"              <?= ($paisAtual === 'Seychelles')              ? 'selected' : '' ?>>Seychelles</option>
        <option value="Singapura"               <?= ($paisAtual === 'Singapura')               ? 'selected' : '' ?>>Singapura</option>
        <option value="Síria"                   <?= ($paisAtual === 'Síria')                   ? 'selected' : '' ?>>Síria</option>
        <option value="Somália"                 <?= ($paisAtual === 'Somália')                 ? 'selected' : '' ?>>Somália</option>
        <option value="Sri Lanka"               <?= ($paisAtual === 'Sri Lanka')               ? 'selected' : '' ?>>Sri Lanka</option>
        <option value="Suazilândia"             <?= ($paisAtual === 'Suazilândia')             ? 'selected' : '' ?>>Suazilândia</option>
        <option value="Sudão"                   <?= ($paisAtual === 'Sudão')                   ? 'selected' : '' ?>>Sudão</option>
        <option value="Sudão do Sul"            <?= ($paisAtual === 'Sudão do Sul')            ? 'selected' : '' ?>>Sudão do Sul</option>
        <option value="Suécia"                  <?= ($paisAtual === 'Suécia')                  ? 'selected' : '' ?>>Suécia</option>
        <option value="Suíça"                   <?= ($paisAtual === 'Suíça')                   ? 'selected' : '' ?>>Suíça</option>
        <option value="Suriname"                <?= ($paisAtual === 'Suriname')                ? 'selected' : '' ?>>Suriname</option>
        <option value="Svalbard"                <?= ($paisAtual === 'Svalbard')                ? 'selected' : '' ?>>Svalbard</option>
        <option value="Tadjiquistão"            <?= ($paisAtual === 'Tadjiquistão')            ? 'selected' : '' ?>>Tadjiquistão</option>
        <option value="Tailândia"               <?= ($paisAtual === 'Tailândia')               ? 'selected' : '' ?>>Tailândia</option>
        <option value="Tanzânia"                <?= ($paisAtual === 'Tanzânia')                ? 'selected' : '' ?>>Tanzânia</option>
        <option value="Timor-Leste"             <?= ($paisAtual === 'Timor-Leste')             ? 'selected' : '' ?>>Timor-Leste</option>
        <option value="Togo"                    <?= ($paisAtual === 'Togo')                    ? 'selected' : '' ?>>Togo</option>
        <option value="Tonga"                   <?= ($paisAtual === 'Tonga')                   ? 'selected' : '' ?>>Tonga</option>
        <option value="Trinidad e Tobago"       <?= ($paisAtual === 'Trinidad e Tobago')       ? 'selected' : '' ?>>Trinidad e Tobago</option>
        <option value="Tunísia"                 <?= ($paisAtual === 'Tunísia')                 ? 'selected' : '' ?>>Tunísia</option>
        <option value="Turcomenistão"           <?= ($paisAtual === 'Turcomenistão')           ? 'selected' : '' ?>>Turcomenistão</option>
        <option value="Turquia"                 <?= ($paisAtual === 'Turquia')                 ? 'selected' : '' ?>>Turquia</option>
        <option value="Tuvalu"                  <?= ($paisAtual === 'Tuvalu')                  ? 'selected' : '' ?>>Tuvalu</option>
        <option value="Uganda"                  <?= ($paisAtual === 'Uganda')                  ? 'selected' : '' ?>>Uganda</option>
        <option value="Ucrânia"                 <?= ($paisAtual === 'Ucrânia')                 ? 'selected' : '' ?>>Ucrânia</option>
        <option value="Uruguai"                 <?= ($paisAtual === 'Uruguai')                 ? 'selected' : '' ?>>Uruguai</option>
        <option value="Vanuatu"                 <?= ($paisAtual === 'Vanuatu')                 ? 'selected' : '' ?>>Vanuatu</option>
        <option value="Vaticano"                <?= ($paisAtual === 'Vaticano')                ? 'selected' : '' ?>>Vaticano</option>
        <option value="Venezuela"               <?= ($paisAtual === 'Venezuela')               ? 'selected' : '' ?>>Venezuela</option>
        <option value="Vietnã"                  <?= ($paisAtual === 'Vietnã')                  ? 'selected' : '' ?>>Vietnã</option>
        <option value="Zâmbia"                  <?= ($paisAtual === 'Zâmbia')                  ? 'selected' : '' ?>>Zâmbia</option>
        <option value="Zimbábue"                <?= ($paisAtual === 'Zimbábue')                ? 'selected' : '' ?>>Zimbábue</option>
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
        value="<?= htmlentities($telefoneAtual, ENT_QUOTES, 'UTF-8') ?>"
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
        value="<?= htmlentities($cpfAtual, ENT_QUOTES, 'UTF-8') ?>"
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
        value="<?= htmlentities($emailAtual, ENT_QUOTES, 'UTF-8') ?>"
      >
      <br><br>

      <button 
        type="submit" 
        id="botao_criar_login" 
        class="botao_salvar"
      >
        SALVAR
      </button>

      <div class="container_dinamico">
        <p>
          Estes campos terão Informações trazidas de forma dinâmica através do backend, 
          é um espaço dedicado para exibição das informações que o cliente cadastrou.
        </p>
      </div>
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
