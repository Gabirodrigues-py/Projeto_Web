<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/registrer_page.css">
    <title>Cadastro</title>
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
                        <img src="assets/imagens/imagem_superior.png" alt="Logo do Ticket.fun"
                            class="imagem_header_sanrio">
                    </a>
                </li>


                <li class="nav_item">
                    <a href="about_us_page.html" class="nav_link">Sobre nós</a>
                </li>


                <li class="nav_item">
                    <a href="index.php" class="nav_link">Inicio
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <section class="titulo_pagina">
        <div class="titulo_cadastro">
            <h1 class="titulo_cadastro">Cadastro</h1>
        </div>
    </section>


    <section class="formulario_info_cadastro">

        <form action="registrer_page.php" method="post" class="formulario_campos">
            <div class="titulo_h3">
                <h3 class="titulo_h3">Informações de Cadastro</h3>
            </div>

            <label for="primeiro-nome"></label>
            <input type="text" placeholder="Nome" name="nome" id="primeiro-nome" class="campo-informaçõese"
                maxlength="100" required>

            <label for="sobrenome"></label>
            <input type="text" placeholder="Sobrenome" name="sobrenome" id="sobrenome" class="campo-informaçõesd"
                maxlength="100" required>

            <br><br>

            <label for="data-nascimento"></label>
            <input type="date" name="data_nascimento" id="data-nascimento" class="campo-informaçõese" required>

            <label for="sexo"></label>
            <select id="sexo" name="sexo" class="campo-informaçõesd" required>
                <option value="">Selecione o Sexo</option>
                <option value="Feminino">Feminino</option>
                <option value="Masculino">Masculino</option>
                <option value="Não Informado">Prefiro não dizer</option>
            </select>
            <br><br>

            <label for="telefone"></label>
            <input type="text" placeholder="Telefone" name="telefone" id="telefone" class="campo-informaçõesd"
                maxlength="20" required>

            <label for="cpf"></label>
            <input type="text" placeholder="CPF" name="cpf" id="cpf" class="campo-informções-final" maxlength="14"
            required>

            <label for="email-login"></label>
            <input type="email" placeholder="E-mail" name="email" id="email_login" class="campo_email" maxlength="100" required>
            <br><br>

            <label for=""></label>
            <input
                type="password"
                placeholder="Senha"
                name="senha"
                id="senha"
                class="campo_senha"
                maxlength="255"
                required
            >

            <input
                type="password"
                placeholder="Confirmar Senha"
                name="confirmar_senha"
                id="confirmar_senha"
                class="campo_confirmar_senha"
                maxlength="255"
                required
            >

            <br><br>

            <div class="botao_cadastro">
                <button type="submit" class="botao_cadastrar">Cadastrar</button>
            </div>
        </form>
    </section>

    <footer class="footer">
        <div class="footer_content">
            <div class="footer_logo">
                <img src="assets/imagens/logo-500x500.png" alt="Logo Ticket.fun" class="imagem_footer_logo">
            </div>
            <div class="footer_links">
                <ul class="footer_list">
                    <li><a href="index.php" class="footer_link">Início</a></li>
                    <li><a href="about_us_page.html" class="footer_link">Sobre Nós</a></li>
                    <li><a href="#" class="footer_link">Eventos</a></li>
                    <li><a href="#" class="footer_link">Contato</a></li>
                </ul>
            </div>
            <div class="footer_social_media">
                <a href="#" class="footer_link">
                    <img src="assets/imagens/Instagram.svg" alt="Instagram">
                </a>
                <a href="#" class="footer_link">
                    <img src="assets/imagens/Tiktok.svg" alt="Tiktok">
                </a>
                <a href="#" class="footer_link">
                    <img src="assets/imagens/Whatsapp.svg" alt="Whatsapp">
                </a>
            </div>
        </div>
        <div class="footer_copyright">
            &#169; 2024 Todos os direitos reservados
        </div>
    </footer>
</body>
</html>