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

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_cadastrar']) ) {
            // Verifica se todos os campos obrigatórios estão preenchidos
            $camposObrigatorios = ['nome'];
            $preenchidos = true;
            //print("Entrou no cadastro <br>");
            foreach ($camposObrigatorios as $campo) {
                if (empty($_POST[$campo])) {
                    $preenchidos = false;
                    break;
                }
            }

            if ($preenchidos) {
                // Captura os dados do formulário
                $nome = $_POST['nome'];
                $sistema = $_POST['sistema'] ?? '';
                $descricao = $_POST['descricao'] ?? '';
                $tipo = $_POST['tipo'] ?? '';
                $sub_tipo = $_POST['sub_tipo'] ?? '';
                $atividade = $_POST['atividade'];
                
                // Executa a consulta sem parâmetros desnecessários
                $stmt = $conn->prepare("SELECT cd_atividade FROM atividade where nome ='" . $atividade ."' limit 1");
                $stmt->execute();
                $cd_atividade = $stmt->fetch(PDO::FETCH_COLUMN);


                // Inserção na tabela entidades
                $sql = "INSERT INTO produto_servico (nome_produto_servico, sub_tipo_produto_servico, descricao_produto_servico, sistema_produtivo, tipo_produto_servico, cd_atividade)
                    VALUES (:nome_produto_servico, :sub_tipo_produto_servico, :descricao_produto_servico, :sistema_produtivo, :tipo_produto_servico, :cd_atividade)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':nome_produto_servico' => $nome,
                    ':sub_tipo_produto_servico' => $sub_tipo,
                    ':descricao_produto_servico' => $descricao,
                    ':sistema_produtivo' => $sistema,
                    ':tipo_produto_servico' => $tipo,
                    ':cd_atividade' => $cd_atividade,
                ]);
            }
                echo "<script>alert('Cadastrado com sucesso!');window.location.href = 'cad_produto.php';</script>";
            } else {
                echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";
            }

    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
?>