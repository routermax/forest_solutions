<?php
$host = '192.168.59.11';
$dbname = 'forest_solutions';
$username = 'postgres';
$password = 'GPp3squ1s@';

header('Content-Type: application/json');

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Debug: Verificar se o parâmetro está chegando corretamente
    // file_put_contents('debug.log', print_r($_GET, true), FILE_APPEND);

    // Obtém o cd_entidade da URL, se existir
    $atividade = isset($_GET['atividade']) ? trim($_GET['atividade']) : ''; // Mantém como string

    //echo "<script>console.log('Atividade: " . addslashes($atividade) . "');</script>";

    // Ajusta a consulta SQL para filtrar por nome da atividade
    if (!empty($atividade)) {
        $stmt = $conn->prepare("SELECT cd_produto_servico, nome_produto_servico
                                    FROM produto_servico
                                    INNER JOIN atividade ON produto_servico.cd_atividade = atividade.cd_atividade
                                    WHERE atividade.cd_atividade = :atividade
                                    ORDER BY nome_produto_servico");
        $stmt->bindParam(':atividade', $atividade, PDO::PARAM_STR);
    } else {
        $stmt = $conn->prepare("SELECT nome_produto_servico FROM produto_servico ORDER BY nome_produto_servico");
    }

    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($produtos);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
