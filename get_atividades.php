<?php
    $host = '192.168.59.11';
    $dbname = 'forest_solutions';
    $username = 'postgres';
    $password = 'GPp3squ1s@';

    header('Content-Type: application/json');

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Executa a consulta sem parâmetros desnecessários
        $stmt = $conn->prepare("SELECT cd_atividade, nome FROM atividade ORDER BY nome");
        $stmt->execute();

        $entidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($entidades);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
?>