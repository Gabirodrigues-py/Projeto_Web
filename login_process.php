<?php
session_start();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']   ?? '');
    $senha = trim($_POST['senha']   ?? '');

    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $servidor = "localhost";
        $usuario  = "root";
        $senhaBd  = "";
        $banco    = "hello_kitty";

        $conn = new mysqli($servidor, $usuario, $senhaBd, $banco);
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
        $conn->set_charset("utf8");

        // O SQL busca a nova coluna 'is_admin'
        $sql  = "SELECT id, nome, email, senha, is_admin FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows !== 1) {
            $erro = 'Usuário não encontrado.';
        } else {
            $usuario_db = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario_db['senha'])) {
                // Login bem-sucedido: guarda todos os dados na sessão
                $_SESSION['usuario_id']    = $usuario_db['id'];
                $_SESSION['usuario_nome']  = $usuario_db['nome'];
                $_SESSION['usuario_email'] = $usuario_db['email'];
                // Salva o status de admin na sessão
                $_SESSION['is_admin']      = (bool)$usuario_db['is_admin'];

                $stmt->close();
                $conn->close();

                // Redireciona para a página de perfil após o login
                header("Location: profile_page.php");
                exit;
            } else {
                $erro = 'Senha incorreta.';
            }
        }

        $stmt->close();
        $conn->close();
    }
}

// Se o método não for POST ou se houver erro, exibe o formulário
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/header.css">
  <link rel="stylesheet" href="assets/css/footer.css">
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
  // Inclui o HTML do formulário de login
  include __DIR__ . '/includes/login_page.php';
  ?>

</body>
</html>