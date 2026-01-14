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
if (isset($_GET['tk3']) && isset($_GET['tk4'])) {
    $cd_solucao = urldecode(descriptografar($_GET['tk3'],$chave));
    $cd_produto_servico = urldecode(descriptografar($_GET['tk4'],$chave));
    
    // Conexão com o banco de dados
    $host = '192.168.59.11';
    $dbname = 'forest_solutions';
    $username = 'postgres';
    $password = 'GPp3squ1s@';

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query de DELETE
        $sql_produto_servico = "DELETE FROM produto_servico_solucao
                               WHERE cd_produto_servico = :cd_produto_servico 
                               AND cd_solucao = :cd_solucao";
        
        $stmt_produto_servico = $conn->prepare($sql_produto_servico);
        $stmt_produto_servico->execute([
            ':cd_produto_servico' => $cd_produto_servico,
            ':cd_solucao' => $cd_solucao
        ]);
        
        // Verifica se alguma linha foi afetada
        if ($stmt_produto_servico->rowCount() > 0) {          
            echo "<script>window.location.href = 'https://pesquisa.am/tabelas/forest_solutions/mostra_solucoes.php';</script>";
        } else {
            die("Nenhum registro foi deletado. Verifique os parâmetros.");
        }

    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }
} else {
    die("Parâmetros tk1 e/ou tk2 não fornecidos.");
}
//foreach ($produto_servico as $saida) { 
//    echo htmlspecialchars($saida['cd_produto_servico']);
//    echo htmlspecialchars($saida['cd_solucao']);
//}
?>
