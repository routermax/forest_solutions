<?php
require 'cryptolib.php'; // Inclua o arquivo de criptografia, se necessário
$chave = "Aindaestachovendolaforaeaquifazt"; // Chave de criptografia
$host = '192.168.59.11';
$dbname = 'forest_solutions';
$username = 'postgres';
$password = 'GPp3squ1s@';

try {
    // Conexão com o banco de dados
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //print('tk1: ' . $_GET['tk1']. '<br>');
    $cd_solucao = urldecode(descriptografar($_GET['tk1'],$chave));
    //print('cd_solucao: ' . $cd_solucao);
    // Verifica se o cd_solucao foi enviado via POST

        // Consulta para buscar os dados da solução
        $sql_solucao = "SELECT * FROM solucao WHERE cd_solucao = :cd_solucao";
        $stmt_solucao = $conn->prepare($sql_solucao);
        $stmt_solucao->execute([':cd_solucao' => $cd_solucao]);
        $solucao = $stmt_solucao->fetch(PDO::FETCH_ASSOC);

        if ($solucao) {
            // Consulta para buscar os produtos/serviços relacionados
            $sql_produtos = "SELECT produto_servico.nome_produto_servico, atividade.nome FROM produto_servico_solucao 
                            inner join produto_servico
                            on produto_servico.cd_produto_servico = produto_servico_solucao.cd_produto_servico
                            inner join atividade
                            on atividade.cd_atividade = produto_servico.cd_atividade WHERE cd_solucao = :cd_solucao";
            $stmt_produtos = $conn->prepare($sql_produtos);
            $stmt_produtos->execute([':cd_solucao' => $cd_solucao]);
            $produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);

            // Monta o array de resposta
            $response = [
                'solucao' => $solucao,
                'produtos' => $produtos
            ];

            // Retorna os dados em formato JSON
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            // Se não encontrar a solução, retorna um erro
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Solução não encontrada']);
        }

} catch (PDOException $e) {
    // Em caso de erro na conexão ou consulta, retorna uma mensagem de erro
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>