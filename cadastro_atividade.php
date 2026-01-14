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
                $metodo = $_POST['metodo'] ?? '';
                $descricao = $_POST['descricao'] ?? '';
                $categoria = $_POST['categoria'] ?? '';
                $sub_categoria = $_POST['sub_categoria'] ?? '';

                // Inserção na tabela entidades
                $sql = "INSERT INTO atividade (nome, metodo, descricao, categoria, sub_categoria)
                    VALUES (:nome, :metodo, :descricao, :categoria, :sub_categoria)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':metodo' => $metodo,
                    ':descricao' => $descricao,
                    ':categoria' => $categoria,
                    ':sub_categoria' => $sub_categoria,
                ]);
            }
                echo "<script>alert('Cadastrado com sucesso!');window.location.href = 'cad_atividade.php';</script>";
            } else {
                echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";
            }

    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
?>