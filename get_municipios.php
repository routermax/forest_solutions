<?php
$host = '192.168.59.11';
$dbname = 'focosdecalor';
$username = 'postgres';
$password = 'GPp3squ1s@';

header('Content-Type: application/json');

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['estado'])) {
        $estado = $_GET['estado'];

        // Converte o nome do estado para UF (se necessário)
        function estadoParaUF($estado) {
            $estados = [
                "Acre" => "AC", "Amapá" => "AP", "Amazonas" => "AM",
                "Maranhão" => "MA", "Mato Grosso" => "MT",'Pará' => 'PA',
                "Rondônia" => "RO", "Roraima" => "RR", "Tocantins" => "TO"
            ];
            return $estados[$estado] ?? "";
        }

        $uf = estadoParaUF($estado);
        if ($uf) {
            $stmt = $conn->prepare("SELECT nm_mun FROM municipio_2021 WHERE sigla = :uf ORDER BY nm_mun");
            $stmt->bindParam(':uf', $uf);
            $stmt->execute();

            $municipios = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo json_encode($municipios);
        } else {
            echo json_encode([]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>