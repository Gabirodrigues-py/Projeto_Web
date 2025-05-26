<?php

require __DIR__."/vendor/autoload.php";

use \App\entity\Cadastro;

// Validação do POST - Verifica se as chaves esperadas existem no array $_POST
// Ajustado para incluir todos os campos do formulário com nomes em minúsculas
if(isset($_POST["nome"],$_POST["sobrenome"],$_POST["data_nascimento"],$_POST["sexo"],$_POST["pais_residencia"],$_POST["telefone"],$_POST["cpf"],$_POST["email"],$_POST["senha"],$_POST["confirmar_senha"])){

    // Validação básica (exemplo: verificar se senhas coincidem)
    if ($_POST["senha"] !== $_POST["confirmar_senha"]) {
        echo "Erro: As senhas não coincidem.";
        // Idealmente, redirecionar de volta ao formulário com uma mensagem de erro
        exit;
    }

    $obCadastro = new Cadastro;
    // Atribui os valores do POST às propriedades do objeto Cadastro
    $obCadastro->nome             = $_POST["nome"];
    $obCadastro->sobrenome        = $_POST["sobrenome"];
    $obCadastro->data_de_nascimento = $_POST["data_nascimento"]; // Propriedade adicionada à classe Cadastro.php
    $obCadastro->sexo             = $_POST["sexo"];
    $obCadastro->pais_residencia  = $_POST["pais_residencia"];
    $obCadastro->telefone         = $_POST["telefone"];
    $obCadastro->CPF              = $_POST["cpf"]; // Atenção: Propriedade na classe é CPF (maiúsculo)
    $obCadastro->email            = $_POST["email"];
    $obCadastro->senha            = password_hash($_POST["senha"], PASSWORD_DEFAULT); // IMPORTANTE: Hash da senha!
    $obCadastro->cadastrar();


    
    echo "<pre>"; print_r($obCadastro); echo "</pre>"; exit;

}-

// Inclui o HTML do formulário (frontend restaurado e com nomes ajustados)
require __DIR__."/includes/register.php";

?>
