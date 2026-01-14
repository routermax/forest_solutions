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
            $cd_entidade = isset($_GET['cd']) ? intval($_GET['cd']) : 0;
            //$cd = urldecode($_GET['cd']);
            //print ('Texto Criptografado: ' . $cd);
            //$cd_contato = descriptografar($cd, $chave);
            //print('Texto Descriptografado: ' . $cd_entidade);
            // Ajusta a consulta SQL para filtrar por cd_entidade (se necessário)
            if ($cd_entidade > 0) {
                $stmt = $conn->prepare("SELECT contato.cd_contato, nome, tipo, funcao, contato FROM contato 
                                        inner join contato_entidade
                                        on contato.cd_contato = contato_entidade.cd_contato
                                        WHERE cd_entidade = :cd_entidade ORDER BY nome");
                $stmt->bindParam(':cd_entidade', $cd_entidade, PDO::PARAM_INT);
            } else {
                // Executa a consulta sem parâmetros desnecessários
                $stmt = $conn->prepare("SELECT cd_contato, nome, tipo, contato FROM contato ORDER BY nome");
            }
        $stmt->execute();

        $entidades = $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna um array de nomes

        echo json_encode($entidades);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
?>