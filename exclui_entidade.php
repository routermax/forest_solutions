<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
require 'cryptolib.php'; // ou use include 'crypto.php';
$chave = "Aindaestachovendolaforaeaquifazt";



// Verifica se o cd_solucao foi passado via GET
if (isset($_GET['tk2'])) {
   $cd_entidade = urldecode(descriptografar($_GET['tk2'],$chave));

    // Conexão com o banco de dados
    $host = '192.168.59.11';
    $dbname = 'forest_solutions';
    $username = 'postgres';
    $password = 'GPp3squ1s@';

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Busca os dados da solução
        $sql_entidade = "DELETE
                        FROM
                            entidade
                        WHERE
                            cd_entidade = :cd_entidade";
        $stmt_entidade = $conn->prepare($sql_entidade);
        $stmt_entidade->execute([':cd_entidade' => $cd_entidade]);
        $entidade = $stmt_entidade->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }
    echo "<script>window.location.href = 'https://pesquisa.am/tabelas/forest_solutions/mostra_entidades.php';</script>";
} else {
    die("cd_solucao não fornecido.");
}
?>
