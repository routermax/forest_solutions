<?php
//print("Entrou no PHP <br>");
require 'cryptolib.php'; // ou use include 'crypto.php';
$chave = "Aindaestachovendolaforaeaquifazt";

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o cd_solucao foi passado via GET
if (isset($_GET['tk1'])) {
    $cd_solucao = urldecode(descriptografar($_GET['tk1'], $chave));

    $host = '192.168.59.11';
    $dbname = 'forest_solutions';
    $username = 'postgres';
    $password = 'GPp3squ1s@';
    //print("Entrou no arquivo <br>");
    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //print("Entrou no cadastro <br>");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            #Cabeçalho
            $origem = $_POST['origem'];
            $cd_responsavel = descriptografar($_POST['responsavel'], $chave);
            $cd_contato = $_POST['contato'];
            # 1)
            $cd_entidade = $_POST['entidade'];
            # 2)
            $familias_beneficiadas = $_POST['familias_beneficiadas'];
            # 3)
            $tipo_territorio = $_POST['tipo_territorio'];
            # 3.1)
            $nome_territorio = $_POST['nome_territorio'];
            # 4)
            $uf = $_POST['Estado'];
            # 5)
            $municipio = $_POST['Municipio'];
            # 7)
            $internet_qualidade = $_POST['internet_qualidade'];
            # 8)
            $energia_qualidade = $_POST['energia_qualidade'];
            # 9)
            $energia_fonte = ($_POST['outra_fonte'] == "") ? $_POST['energia_fonte'] : $_POST['outra_fonte'];
            # 10)
            $agua_qualidade = $_POST['agua_qualidade'];
            # 11)
            $agua_fonte = ($_POST['outra_agua'] == "") ? $_POST['agua_fonte'] : $_POST['outra_agua'];
            # 12)
            $acesso_meio = ($_POST['outro_meio'] == "") ? $_POST['acesso_meio'] : $_POST['outro_meio'];
            # 13)
            $acesso_qualidade = $_POST['acesso_qualidade'];
            # 14)
            $capacitacao_implementada = $_POST['capacitacao_implementada'];
            $capacitacao_interesse = ($_POST['outra_cap'] != "") ? $_POST['outra_cap'] : null;
            # 15)
            $tecnologia_implementada = $_POST['tecnologia_implementada'];
            $tecnologia_interesse = ($_POST['outra_tec'] != "") ? $_POST['outra_tec'] : null;
            # 16)
            if ($_POST['estrategia_implementada'] == "Sim") {
                $estrategia_implementada = $_POST['qual_est'];
                $estrategia_interesse = null;
            } else {
                $estrategia_implementada = $_POST['estrategia_implementada'];
                $estrategia_interesse = ($_POST['outra_est'] != "") ? $_POST['outra_est'] : null;
            }
            # 17)
            $engajamento_comunitario = $_POST['engajamento_comunitario'];
            # 18)
            $preservacao_cultural = $_POST['preservacao_cultural'];
            # 19)
            $conflitos_locais = $_POST['conflitos_locais'];
            # 20)
            $vontade_mudanca = $_POST['vontade_mudanca'];
            # 21)
            $dependencia_finaceira_externa = ($_POST['outra_dep'] == "") ? $_POST['dependencia_finaceira_externa'] : $_POST['outra_dep'];
            # 22)
            $mudanca_meio_ambiente = $_POST['mudanca_meio_ambiente'];
            # 23)
            $desafios = $_POST['desafios'];
            # 24)
            $melhorias = $_POST['melhorias'];
            # Data/Hora
            $data_coleta = $dataHora = (new DateTime('now', new DateTimeZone('America/Manaus')))->format('Y-m-d H:i:s');
            # 25)
            $beneficiosFinal = "";
            $camposBeneficios = ['beneficios_1', 'beneficios_2', 'beneficios_3', 'beneficios_4', 'beneficios_5', 'beneficios_6', 'beneficios_7', 'beneficios_8'];
            foreach ($camposBeneficios as $campo) {
                if (!empty($_POST[$campo])) { // Verifica se o campo não está vazio
                    if ($beneficiosFinal != "") {
                        $beneficiosFinal .= " ; "; // Adiciona um separador se já houver valores
                    }
                    $beneficiosFinal .= $_POST[$campo]; // Adiciona o valor do campo
                }
            }
            $beneficioOutro = $_POST['outroTexto'];
            # 26)
            $renda = $_POST['renda'];
            # 27)
            $beneficioAmbiental = $_POST['beneficioAmbiental'];
            # 28)
            $regeneracao = $_POST['regeneracao'];
            # 29)
            $aumentodiversidade = $_POST['aumentodiversidade'];
            # 30)
            $reducaoEmissoes = $_POST['reducaoEmissoes'];
            # 31)
            $educacaambiental = $_POST['educacaambiental'];
            # 32)
            $emregeneracao = $_POST['emregeneracao'];
            # 33)
            $lideranca = $_POST['lideranca'];
            # Localização
            $localizacao = $_POST['localizacao'];
            # Cordenadas
            $coordenadas = $_POST['coordenadas'];


            // Inserção no banco de dados
            $sql = "UPDATE solucao SET
                        origem = :origem,
                        cd_responsavel = :cd_responsavel,
                        cd_contato = :cd_contato,
                        cd_entidade = :cd_entidade,
                        uf = :uf,
                        municipio = :municipio,
                        familias_beneficiadas = :familias_beneficiadas,
                        nome_territorio = :nome_territorio,
                        internet_qualidade = :internet_qualidade,
                        energia_qualidade = :energia_qualidade,
                        energia_fonte = :energia_fonte,
                        agua_fonte = :agua_fonte,
                        acesso_meio = :acesso_meio,
                        acesso_qualidade = :acesso_qualidade,
                        capacitacao_implementada = :capacitacao_implementada,
                        capacitacao_interesse = :capacitacao_interesse,
                        tecnologia_implementada = :tecnologia_implementada,
                        tecnologia_interesse = :tecnologia_interesse,
                        estrategia_implementada = :estrategia_implementada,
                        estrategia_interesse = :estrategia_interesse,
                        engajamento_comunitario = :engajamento_comunitario,
                        preservacao_cultural = :preservacao_cultural,
                        conflitos_locais = :conflitos_locais,
                        vontade_mudanca = :vontade_mudanca,
                        dependencia_finaceira_externa = :dependencia_finaceira_externa,
                        mudanca_meio_ambiente = :mudanca_meio_ambiente,
                        desafios = :desafios,
                        melhorias = :melhorias,
                        data_coleta = :data_coleta,
                        beneficiosFinal = :beneficiosFinal,
                        beneficioOutro = :beneficioOutro,
                        renda = :renda,
                        beneficioAmbiental = :beneficioAmbiental,
                        regeneracao = :regeneracao,
                        aumentodiversidade = :aumentodiversidade,
                        reducaoEmissoes = :reducaoEmissoes,
                        localizacao = :localizacao,
                        coordenadas = :coordenadas,
                        tipo_territorio = :tipo_territorio,
                        agua_qualidade = :agua_qualidade,
                        educacaambiental = :educacaambiental,
                        emregeneracao = :emregeneracao,
                        lideranca = :lideranca
                    WHERE cd_solucao = :cd_solucao";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':cd_solucao' => $cd_solucao,
                ':origem' => $origem,
                ':cd_responsavel' => $cd_responsavel,
                ':cd_contato' => $cd_contato,
                ':cd_entidade' => $cd_entidade,
                ':uf' => $uf,
                ':municipio' => $municipio,
                ':familias_beneficiadas' => $familias_beneficiadas,
                ':nome_territorio' => $nome_territorio,
                ':internet_qualidade' => $internet_qualidade,
                ':energia_qualidade' => $energia_qualidade,
                ':energia_fonte' => $energia_fonte,
                ':agua_fonte' => $agua_fonte,
                ':acesso_meio' => $acesso_meio,
                ':acesso_qualidade' => $acesso_qualidade,
                ':capacitacao_implementada' => $capacitacao_implementada,
                ':capacitacao_interesse' => $capacitacao_interesse,
                ':tecnologia_implementada' => $tecnologia_implementada,
                ':tecnologia_interesse' => $tecnologia_interesse,
                ':estrategia_implementada' => $estrategia_implementada,
                ':estrategia_interesse' => $estrategia_interesse,
                ':engajamento_comunitario' => $engajamento_comunitario,
                ':preservacao_cultural' => $preservacao_cultural,
                ':conflitos_locais' => $conflitos_locais,
                ':vontade_mudanca' => $vontade_mudanca,
                ':dependencia_finaceira_externa' => $dependencia_finaceira_externa,
                ':mudanca_meio_ambiente' => $mudanca_meio_ambiente,
                ':desafios' => $desafios,
                ':melhorias' => $melhorias,
                ':data_coleta' => $data_coleta,
                ':beneficiosFinal' => $beneficiosFinal,
                ':beneficioOutro' => $beneficioOutro,
                ':renda' => $renda,
                ':beneficioAmbiental' => $beneficioAmbiental,
                ':regeneracao' => $regeneracao,
                ':aumentodiversidade' => $aumentodiversidade,
                ':reducaoEmissoes' => $reducaoEmissoes,
                ':localizacao' => $localizacao,
                ':coordenadas' => $coordenadas,
                ':tipo_territorio' => $tipo_territorio,
                ':agua_qualidade' => $agua_qualidade,
                ':educacaambiental' => $educacaambiental,
                ':emregeneracao' => $emregeneracao,
                ':lideranca' => $lideranca

            ]);

            //$cd_solucao = $conn->lastInsertId();

            // Verifica se há pelo menos uma atividade a ser inserida
            if (!empty($_POST['atividade_1'])) {
                // Exclui registros anteriores com o mesmo cd_solucao
                $sqlDelete = "DELETE FROM produto_servico_solucao WHERE cd_solucao = :cd_solucao";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->execute([':cd_solucao' => $cd_solucao]);
                // Loop para processar os contatos dinâmicos
                $index = 1; // Começa do índice 1
                while (isset($_POST["atividade_{$index}"])) {
                    # 6.2)
                    $cd_produto_servico = $_POST["produto_{$index}"];
                    # 6.3)
                    $tempo_produto = $_POST["tempoproduto_{$index}"] ?? '';
                    # 6.4)
                    $beneficio = $_POST["beneficio_{$index}"];
                    # 6.5a)
                    $remocao_florestal = $_POST["remocao_{$index}"] ?? '';
                    # 6.5b)
                    $areaRemovida = $_POST["areaRemovida_{$index}"] ?? '';
                    if (empty($cd_produto_servico)) {
                        $index++;
                        continue;
                    }
                    // Insere na tabela produto_servico_solucao
                    $sql = "INSERT INTO produto_servico_solucao (cd_solucao, cd_produto_servico, beneficio, remocao_florestal,tempo_produto,qtde_remocao)
                            VALUES (:cd_solucao, :cd_produto_servico, :beneficio, :remocao_florestal,:tempo_produto,:areaRemovida)";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':cd_solucao' => $cd_solucao,
                        ':cd_produto_servico' => $cd_produto_servico,
                        ':beneficio' => $beneficio,
                        ':remocao_florestal' => $remocao_florestal,
                        ':tempo_produto' => $tempo_produto,
                        ':areaRemovida' => $areaRemovida
                    ]);

                    $index++; // Passa para o próximo contato
                }
                //echo "<script>window.location.href = 'cad_sucesso.php';</script>";
            } else {
                echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";
            }

            // Verifica se há pelo menos um território a ser inserido
            if (!empty($_POST['territorio_1'])) {
                // Exclui registros anteriores com o mesmo cd_solucao
                $sqlDelete = "DELETE FROM solucao_territorio WHERE cd_solucao = :cd_solucao";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->execute([':cd_solucao' => $cd_solucao]);
                // Loop para processar os contatos dinâmicos
                $index = 1; // Começa do índice 1
                while (isset($_POST["territorio_{$index}"])) {
                    # 3.1)
                    $tipo_territorio = $_POST["territorio_{$index}"];
                    # 3.2)
                    $nome_territorio = $_POST["nometerritorio_{$index}"];
                    if (empty($cd_produto_servico)) {
                        $index++;
                        continue;
                    }
                    // Insere na tabela contato
                    $sql = "INSERT INTO solucao_territorio (cd_solucao, nome, tipo)
                            VALUES (:cd_solucao, :nome, :tipo)";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        ':cd_solucao' => $cd_solucao,
                        ':nome' => $nome_territorio,
                        ':tipo' => $tipo_territorio
                    ]);

                    $index++; // Passa para o próximo contato
                }
                 
            } else {
                echo "<script>alert('Preencha todos os campos obrigatórios antes de cadastrar!');</script>";
            }
        }
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}
?>
