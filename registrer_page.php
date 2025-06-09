<?php

require __DIR__."/vendor/autoload.php";

use \App\entity\Cadastro;

// Validação do POST sem o campo de país
if(isset($_POST["nome"],$_POST["sobrenome"],$_POST["data_nascimento"],$_POST["sexo"],$_POST["telefone"],$_POST["cpf"],$_POST["email"],$_POST["senha"],$_POST["confirmar_senha"])){

    if ($_POST["senha"] !== $_POST["confirmar_senha"]) {
        // Redireciona de volta com erro
        header('location: registrer_page.php?status=error_senha');
        exit;
    }

    $obCadastro = new Cadastro;
    $obCadastro->nome               = $_POST["nome"];
    $obCadastro->sobrenome          = $_POST["sobrenome"];
    $obCadastro->data_de_nascimento = $_POST["data_nascimento"];
    $obCadastro->sexo               = $_POST["sexo"];
    // $obCadastro->pais_residencia foi removido
    $obCadastro->telefone           = $_POST["telefone"];
    $obCadastro->CPF                = $_POST["cpf"];
    $obCadastro->email              = $_POST["email"];
    $obCadastro->senha              = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $obCadastro->cadastrar();

    header('location: index.php?status=success');
    exit;
}

// Inclui o HTML do formulário
require __DIR__."/includes/register.php";

?>