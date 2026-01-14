<?php
// Dados do novo usuário
$usuario = "pesquisa";
$senha = "Pesquis@2025";

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

// Criptografa a senha
$senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco de dados
$sql = "INSERT INTO login (usuario, senha) VALUES (:usuario, :senha)";
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':usuario' => $usuario,
    ':senha' => $senha_criptografada
]);

echo "Usuário cadastrado com sucesso!";
?>