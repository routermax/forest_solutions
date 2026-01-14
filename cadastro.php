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
        $camposObrigatorios = ['nome', 'categoria', 'Estado', 'Municipio'];
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
            $cnpj = $_POST['cnpj'] ?? '';
            $historico = $_POST['historico'] ?? '';
            if ($_POST['outra_categoria'] == "") 
                $categoria = $_POST['categoria'];
            else
                $categoria = $_POST['outra_categoria'] ?? '';
            $uf = $_POST['Estado'];
            $municipio = $_POST['Municipio'];
            $cep = $_POST['cep'] ?? '';
            $endereco = $_POST['endereco'] ?? '';
            $dados_complementares = $_POST['dados_complementares'] ?? '';
            
            // Inserção na tabela entidades
            $sql = "INSERT INTO entidade (nome, cnpj, historico, categoria, uf, municipio,  cep, endereco, dados_complementares) 
                    VALUES (:nome, :cnpj, :historico, :categoria, :uf, :municipio, :cep, :endereco, :dados_complementares) RETURNING cd_entidade";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':cnpj' => $cnpj,
                ':historico' => $historico,
                ':categoria' => $categoria,
                ':uf' => $uf,
                ':municipio' => $municipio,
                ':cep' => $cep,
                ':endereco' => $endereco,
                ':dados_complementares' => $dados_complementares
            ]);
            $cd_entidade = $stmt->fetchColumn();

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
                }
                echo "<script>alert('Cadastrado com sucesso!');window.location.href = 'cad_entidade.php';</script>";
            } else {
                echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";
            }
    }
} catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
?>