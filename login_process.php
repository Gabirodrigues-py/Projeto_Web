<?php
// BACK-END CORRIGIDO: A conexão com o banco de dados foi padronizada.

session_start();
require __DIR__ . "/vendor/autoload.php";
use \App\Db\Database;

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']   ?? '');
    $senha = trim($_POST['senha']   ?? '');

    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        // Usa a classe Database para consistência com o resto do projeto.
        $db = new Database('usuarios');
        $resultado = $db->execute("SELECT id, nome, email, senha, is_admin FROM usuarios WHERE email = ?", [$email]);

        if ($resultado->rowCount() !== 1) {
            $erro = 'Usuário não encontrado.';
        } else {
            $usuario_db = $resultado->fetch(PDO::FETCH_ASSOC);

            if (password_verify($senha, $usuario_db['senha'])) {
                $_SESSION['usuario_id']    = $usuario_db['id'];
                $_SESSION['usuario_nome']  = $usuario_db['nome'];
                $_SESSION['usuario_email'] = $usuario_db['email'];
                $_SESSION['is_admin']      = (bool)$usuario_db['is_admin'];

// Verifica se há uma URL de redirecionamento.
                if (isset($_REQUEST['redirect_url'])) {
                    header('Location: ' . $_REQUEST['redirect_url']);
                } else {
                    // Se não houver, redireciona para a página de perfil padrão.
                    header("Location: profile_page.php");
                }
                exit;
            } else {
                $erro = 'Senha incorreta.';
            }
        }
    }
}

// O código abaixo não foi alterado, ele carrega seu front-end original.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer1.css">
  <link rel="stylesheet" href="assets/css/login_page.css">
  <title>Login</title>
  <link rel="shortcut icon" href="assets/imagens/favicon-512x512.png">
</head>
<body>

  <?php if ($erro !== ''): ?>
    <div style="color: red; text-align: center; margin: 1em 0; background-color: #ffdddd; padding: 10px; border-radius: 5px;">
      <?= htmlentities($erro, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <?php
  // Inclui o HTML do seu formulário de login sem alterações.
  include __DIR__ . '/includes/login_page.php';
  ?>

</body>
</html>