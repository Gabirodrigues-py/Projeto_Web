<?php
// Configurações de conexão
$servidor = "localhost";
$usuario = "root";  // Altere conforme sua configuração
$senha = "";        // Altere conforme sua configuração
$banco = "hello_kitty";

// Criando a conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Definindo charset para evitar problemas com acentuação
$conn->set_charset("utf8");

// Mensagem opcional de sucesso (remova em produção)
echo "✅ Conexão bem-sucedida com o banco de dados!";
?>
