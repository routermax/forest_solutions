<?php
require 'cryptolib.php';
$chave = "Aindaestachovendolaforaeaquifazt";
$host = '192.168.59.11';
$dbname = 'forest_solutions';
$username = 'postgres';
$password = 'GPp3squ1s@';

header('Content-Type: application/json');

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtém o cd_entidade da URL, se existir
    $cd_contato = isset($_GET['cd']) ? intval($_GET['cd']) : 0;
    $cd = urldecode($_GET['cd']);
    $cd_contato = descriptografar($cd, $chave);

    // Ajusta a consulta SQL para filtrar por cd_entidade (se necessário)
    if ($cd_contato > 0) {
        $stmt = $conn->prepare("SELECT nome, cd_contato, contato FROM contato WHERE cd_contato = :cd_contato");
        $stmt->bindParam(':cd_contato', $cd_contato, PDO::PARAM_INT);
    } else {
        // Executa a consulta sem parâmetros desnecessários
        $stmt = $conn->prepare("SELECT nome, contato.cd_contato FROM contato
                                INNER JOIN contato_entidade
                                ON contato.cd_contato = contato_entidade.cd_contato
                                WHERE contato_entidade.cd_entidade = 31
                                ORDER BY nome");
    }
    $stmt->execute();

    // Busca os dados do banco
    $entidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Criptografa a coluna cd_contato antes de enviar a resposta
    foreach ($entidades as &$entidade) {
        $entidade['cd_contato'] = criptografar($entidade['cd_contato'], $chave);
    }

    // Retorna os dados em JSON
    echo json_encode($entidades);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>