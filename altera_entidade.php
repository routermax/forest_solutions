<?php
require 'cryptolib.php'; // ou use include 'crypto.php';
$chave = "Aindaestachovendolaforaeaquifazt";

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
if (isset($_GET['tk1'])) {
    $cd_entidade = urldecode(descriptografar($_GET['tk1'], $chave));
    
    $host = '192.168.59.11';
    $dbname = 'forest_solutions';
    $username = 'postgres';
    $password = 'GPp3squ1s@';

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                $sql = "UPDATE entidade SET
                            nome = :nome,
                            cnpj =  :cnpj,
                            historico = :historico,
                            categoria = :categoria,
                            uf =  :uf,
                            municipio =  :municipio,
                            cep = :cep,
                            endereco= :endereco,
                            dados_complementares = :dados_complementares
                       WHERE cd_entidade = :cd_entidade";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':cd_entidade' => $cd_entidade,
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
                //$cd_entidade = $stmt->fetchColumn();

                // Verifica se há pelo menos um contato a ser inserido
                if (!empty($_POST['nome_1'])) {
                    //print("cd_entidade: " . $cd_entidade . "<br>");
                    // Exclui registros anteriores com o mesmo cd_solucao
                    $sqlDelete = "delete FROM contato
                                        WHERE cd_contato IN (
                                            SELECT cd_contato 
                                            FROM contato_entidade  
                                            WHERE cd_entidade = :cd_entidade);
                                 ";
                    $stmtDelete = $conn->prepare($sqlDelete);
                    $stmtDelete->execute([':cd_entidade' => $cd_entidade]);
                    $sqlDelete = " delete FROM contato_entidade  
                                          WHERE cd_entidade = :cd_entidade;
                                 ";
                    $stmtDelete = $conn->prepare($sqlDelete);
                    $stmtDelete->execute([':cd_entidade' => $cd_entidade]);
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
                    echo "<script>window.location.href = 'edita_entidade.php?tk1=",$_GET['tk1'],"';</script>";
                } else {
                    echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";
                }
            }
        }
    } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
    }
}
?>