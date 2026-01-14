<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
//print("Entrou no PHP <br>");
$host = '192.168.59.11';
$dbname = 'forest_solutions';
$username = 'postgres';
$password = 'GPp3squ1s@';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //print("Método: " . $_SERVER["REQUEST_METHOD"] . "<br>");
    //print("Cadastrar: " . isset($_POST['submit_cadastrar']) . "<br>");
    //print("Estado: " . isset($_POST['Estado']). "<br>");
    //print("Entrou no TRY <br>");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $cd_entidade = $_POST['entidade'];

            // Verifica se há pelo menos um contato a ser inserido
            if (!empty($_POST['nome_1'])) {
                //print("cd_entidade: " . $cd_entidade . "<br>");

                // Loop para processar os contatos dinâmicos
                $index = 1; // Começa do índice 1
                while (isset($_POST["nome_{$index}"])) {
                    $nome_contato = $_POST["nome_{$index}"];
                    $funcao_contato = $_POST["funcao_{$index}"] ?? '';
                    $tipo_contato = $_POST["tipo_{$index}"] ?? '';
                    $contato = $_POST["contato_{$index}"] ?? '';

                    // Se o nome do contato estiver vazio, pula para o próximo
                    if (empty($nome_contato)) {
                        $index++;
                        continue;
                    }

                    // Exibe para depuração
                    //print("Nome: " . $nome_contato . "<br>");
                    //print("Função: " . $funcao_contato . "<br>");
                    //print("Tipo: " . $tipo_contato . "<br>");
                    //print("Contato: " . $contato . "<br>");

                    // Insere na tabela contato
                    $sql = "INSERT INTO contato (nome, funcao, tipo, contato) 
                        VALUES (:nome_contato, :funcao_contato, :tipo_contato, :contato) RETURNING cd_contato";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':nome_contato' => $nome_contato,
                        ':funcao_contato' => $funcao_contato,
                        ':tipo_contato' => $tipo_contato,
                        ':contato' => $contato
                    ]);
                    $cd_contato = $stmt->fetchColumn();

                    // Insere na tabela contato_entidade
                    $sql = "INSERT INTO contato_entidade (cd_contato, cd_entidade) 
                        VALUES (:cd_contato, :cd_entidade)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':cd_contato' => $cd_contato,
                        ':cd_entidade' => $cd_entidade,
                    ]);

                    $index++; // Passa para o próximo contato                 
                }
                echo "<script>alert('Cadastrado com sucesso!');window.location.href = 'mostra_entidades.php';</script>";
            } else {
                echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";            
            }
    }
} catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
}
?>