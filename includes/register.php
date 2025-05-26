<!-- Página de Cadastro de Novo Usuário no site -->
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
                    <a href="index.html">
                        <img src="assets/imagens/logo-225x150.png" alt="Logo do Ticket.fun" class="imagem_header_logo">
                    </a>
                </li>


                <li class="nav_item">
                    <a href="index.html">
                        <img src="assets/imagens/imagem_superior.png" alt="Logo do Ticket.fun"
                            class="imagem_header_sanrio">
                    </a>
                </li>


                <li class="nav_item">
                    <a href="about_us_page.html" class="nav_link">Sobre nós</a>
                </li>


                <li class="nav_item">
                    <a href="index.html" class="nav_link">Inicio
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

        <form action="" method="post" class="formulario_campos">
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

            <label for="país"></label>
            <select id="país" name="pais_residencia" class="campo-seleçaoe">
                <option value="">Selecione um país</option>
                <option value="Afeganistão">Afeganistão</option>
                <option value="África do Sul">África do Sul</option>
                <option value="Albânia">Albânia</option>
                <option value="Alemanha">Alemanha</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Antígua e Barbuda">Antígua e Barbuda</option>
                <option value="Arábia Saudita">Arábia Saudita</option>
                <option value="Argélia">Argélia</option>
                <option value="Argentina">Argentina</option>
                <option value="Armênia">Armênia</option>
                <option value="Austrália">Austrália</option>
                <option value="Áustria">Áustria</option>
                <option value="Azerbaijão">Azerbaijão</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrein">Bahrein</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Bélgica">Bélgica</option>
                <option value="Belize">Belize</option>
                <option value="Benim">Benim</option>
                <option value="Butão">Butão</option>
                <option value="Bolívia">Bolívia</option>
                <option value="Bósnia e Herzegovina">Bósnia e Herzegovina</option>
                <option value="Botsuana">Botsuana</option>
                <option value="Brasil">Brasil</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgária">Bulgária</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cabo Verde">Cabo Verde</option>
                <option value="Camarões">Camarões</option>
                <option value="Camboja">Camboja</option>
                <option value="Canadá">Canadá</option>
                <option value="Catar">Catar</option>
                <option value="Cazaquistão">Cazaquistão</option>
                <option value="Chade">Chade</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Chipre">Chipre</option>
                <option value="Colômbia">Colômbia</option>
                <option value="Comores">Comores</option>
                <option value="Congo">Congo</option>
                <option value="Coreia do Norte">Coreia do Norte</option>
                <option value="Coreia do Sul">Coreia do Sul</option>
                <option value="Costa do Marfim">Costa do Marfim</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Croácia">Croácia</option>
                <option value="Cuba">Cuba</option>
                <option value="Dinamarca">Dinamarca</option>
                <option value="Djibuti">Djibuti</option>
                <option value="Dominica">Dominica</option>
                <option value="Egito">Egito</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Emirados Árabes Unidos">Emirados Árabes Unidos</option>
                <option value="Equador">Equador</option>
                <option value="Eritreia">Eritreia</option>
                <option value="Eslováquia">Eslováquia</option>
                <option value="Eslovênia">Eslovênia</option>
                <option value="Espanha">Espanha</option>
                <option value="Estados Unidos">Estados Unidos</option>
                <option value="Estônia">Estônia</option>
                <option value="Eswatini">Eswatini</option>
                <option value="Etiópia">Etiópia</option>
                <option value="Fiji">Fiji</option>
                <option value="Filipinas">Filipinas</option>
                <option value="Finlândia">Finlândia</option>
                <option value="França">França</option>
                <option value="Gabão">Gabão</option>
                <option value="Gâmbia">Gâmbia</option>
                <option value="Gana">Gana</option>
                <option value="Geórgia">Geórgia</option>
                <option value="Granada">Granada</option>
                <option value="Grécia">Grécia</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guiana">Guiana</option>
                <option value="Guiné">Guiné</option>
                <option value="Guiné-Bissau">Guiné-Bissau</option>
                <option value="Guiné Equatorial">Guiné Equatorial</option>
                <option value="Haiti">Haiti</option>
                <option value="Holanda">Holanda</option>
                <option value="Honduras">Honduras</option>
                <option value="Hungria">Hungria</option>
                <option value="Iémen">Iémen</option>
                <option value="Ilhas Marshall">Ilhas Marshall</option>
                <option value="Ilhas Salomão">Ilhas Salomão</option>
                <option value="Índia">Índia</option>
                <option value="Indonésia">Indonésia</option>
                <option value="Irã">Irã</option>
                <option value="Iraque">Iraque</option>
                <option value="Irlanda">Irlanda</option>
                <option value="Islândia">Islândia</option>
                <option value="Israel">Israel</option>
                <option value="Itália">Itália</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japão">Japão</option>
                <option value="Jordânia">Jordânia</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Kosovo">Kosovo</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Laos">Laos</option>
                <option value="Lesoto">Lesoto</option>
                <option value="Letônia">Letônia</option>
                <option value="Líbano">Líbano</option>
                <option value="Libéria">Libéria</option>
                <option value="Líbia">Líbia</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lituânia">Lituânia</option>
                <option value="Luxemburgo">Luxemburgo</option>
                <option value="Madagáscar">Madagáscar</option>
                <option value="Malásia">Malásia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldivas">Maldivas</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marrocos">Marrocos</option>
                <option value="Maurício">Maurício</option>
                <option value="Mauritânia">Mauritânia</option>
                <option value="México">México</option>
                <option value="Micronésia">Micronésia</option>
                <option value="Moçambique">Moçambique</option>
                <option value="Moldávia">Moldávia</option>
                <option value="Mônaco">Mônaco</option>
                <option value="Mongólia">Mongólia</option>
                <option value="Montenegro">Montenegro</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Namíbia">Namíbia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Nicarágua">Nicarágua</option>
                <option value="Níger">Níger</option>
                <option value="Nigéria">Nigéria</option>
                <option value="Noruega">Noruega</option>
                <option value="Nova Zelândia">Nova Zelândia</option>
                <option value="Omã">Omã</option>
                <option value="Palau">Palau</option>
                <option value="Palestina">Palestina</option>
                <option value="Panamá">Panamá</option>
                <option value="Papua-Nova Guiné">Papua-Nova Guiné</option>
                <option value="Paquistão">Paquistão</option>
                <option value="Paraguai">Paraguai</option>
                <option value="Peru">Peru</option>
                <option value="Polônia">Polônia</option>
                <option value="Portugal">Portugal</option>
                <option value="Quênia">Quênia</option>
                <option value="Quirguistão">Quirguistão</option>
                <option value="Reino Unido">Reino Unido</option>
                <option value="República Centro-Africana">República Centro-Africana</option>
                <option value="República Checa">República Checa</option>
                <option value="República Democrática do Congo">República Democrática do Congo</option>
                <option value="República Dominicana">República Dominicana</option>
                <option value="Romênia">Romênia</option>
                <option value="Ruanda">Ruanda</option>
                <option value="Rússia">Rússia</option>
                <option value="Samoa">Samoa</option>
                <option value="San Marino">San Marino</option>
                <option value="Santa Lúcia">Santa Lúcia</option>
                <option value="São Cristóvão e Névis">São Cristóvão e Névis</option>
                <option value="São Tomé e Príncipe">São Tomé e Príncipe</option>
                <option value="Senegal">Senegal</option>
                <option value="Serra Leoa">Serra Leoa</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Singapura">Singapura</option>
                <option value="Síria">Síria</option>
                <option value="Somália">Somália</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Suazilândia">Suazilândia</option>
                <option value="Sudão">Sudão</option>
                <option value="Sudão do Sul">Sudão do Sul</option>
                <option value="Suécia">Suécia</option>
                <option value="Suíça">Suíça</option>
                <option value="Suriname">Suriname</option>
                <option value="Svalbard">Svalbard</option>
                <option value="Tadjiquistão">Tadjiquistão</option>
                <option value="Tailândia">Tailândia</option>
                <option value="Tanzânia">Tanzânia</option>
                <option value="Timor-Leste">Timor-Leste</option>
                <option value="Togo">Togo</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad e Tobago">Trinidad e Tobago</option>
                <option value="Tunísia">Tunísia</option>
                <option value="Turcomenistão">Turcomenistão</option>
                <option value="Turquia">Turquia</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="Ucrânia">Ucrânia</option>
                <option value="Uruguai">Uruguai</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vaticano">Vaticano</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnã">Vietnã</option>
                <option value="Zâmbia">Zâmbia</option>
                <option value="Zimbábue">Zimbábue</option>
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
                    <li><a href="index.html" class="footer_link">Início</a></li>
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
