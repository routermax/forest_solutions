<?php
session_start();

// Configurações do banco de dados
$host = '192.168.59.11';
$db = 'forest_solutions';
$user = 'postgres';
$pass = 'GPp3squ1s@';

// Conecta ao banco de dados
try {
    $conn = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Captura os dados do formulário
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];

// Busca o usuário no banco de dados
$sql = "SELECT * FROM login WHERE usuario = :usuario";
$stmt = $conn->prepare($sql);
$stmt->execute([':usuario' => $usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe e se a senha está correta
if ($user && password_verify($senha, $user['senha'])) {
    // Autenticação bem-sucedida
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['permissoes'] = $user['permissoes'];
    header("Location: dashboard.php"); // Redireciona para a página de dashboard
    exit();
} else {
    // Autenticação falhou
    header("Location: index.php?erro=1");
    exit();
}
?>