<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$hash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha'])) {
    $senha = $_POST['senha'];
    $hash = password_hash($senha, PASSWORD_DEFAULT);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Hash de Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        input[type="password"], button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            box-sizing: border-box;
        }
        .hash-result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ffe9;
            border: 1px solid #ccc;
            border-radius: 4px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerador de Hash para Senhas</h1>
        <form method="POST">
            <label for="senha">Digite uma senha:</label>
            <input type="password" id="senha" name="senha" required>
            <button type="submit">Gerar Hash</button>
        </form>

        <?php if (!empty($hash)): ?>
        <div class="hash-result">
            <h3>Hash gerado:</h3>
            <p><strong>Senha digitada:</strong> <?php echo htmlspecialchars($_POST['senha']); ?></p>
            <p><strong>Hash (PASSWORD_DEFAULT):</strong> <?php echo htmlspecialchars($hash); ?></p>
            <p><small>Este hash pode ser armazenado no banco de dados e verificado com password_verify()</small></p>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>