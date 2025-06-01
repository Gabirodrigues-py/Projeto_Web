<?php
session_start();

// Variável que conterá mensagem de erro (se houver)
$erro = '';

// Se for POST, processa o login; senão (GET) cai direto no include ao final
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Captura e sanitiza os dados vindos do formulário
    $email = trim($_POST['email']   ?? '');
    $senha = trim($_POST['senha']   ?? '');

    if ($email === '' || $senha === '') {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        // 2) Conexão ao banco de dados (mysqli)
        $servidor = "localhost";
        $usuario  = "root";
        $senhaBd  = "";
        $banco    = "hello_kitty";

        $conn = new mysqli($servidor, $usuario, $senhaBd, $banco);
        if ($conn->connect_error) {
            die("Erro na conexão: " . $conn->connect_error);
        }
        $conn->set_charset("utf8");

        // 3) Busca o usuário pelo e-mail
        $sql  = "SELECT id, nome, email, senha FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows !== 1) {
            $erro = 'Usuário não encontrado.';
        } else {
            $usuario = $resultado->fetch_assoc();

            // 4) Agora, em vez de comparar texto puro, usamos password_verify()
            if (password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido: guarda dados na sessão
                $_SESSION['usuario_id']    = $usuario['id'];
                $_SESSION['usuario_nome']  = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];

                // Fecha conexões e redireciona
                $stmt->close();
                $conn->close();

                header("Location: my_events_page.html");
                exit;
            } else {
                $erro = 'Senha incorreta.';
            }
        }

        $stmt->close();
        $conn->close();
    }
}

// Se GET ou se chegou aqui com $erro preenchido, exibimos o formulário novamente
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
    <div style="color: red; text-align: center; margin: 1em 0;">
      <?= htmlentities($erro, ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <?php
  // Inclui o HTML puro do formulário de login (não modifique nada dentro deste arquivo)
  include __DIR__ . '/includes/login_page.php';
  ?>

</body>
</html>
