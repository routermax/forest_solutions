<?php
require 'cryptolib.php'; // ou use include 'crypto.php';
$chave = "Aindaestachovendolaforaeaquifazt";
?>
<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o cd_solucao foi passado via GET
if (isset($_GET['tk1'])) {
   $cd_solucao = urldecode(descriptografar($_GET['tk1'],$chave));

    // Conexão com o banco de dados
    $host = '192.168.59.11';
    $dbname = 'forest_solutions';
    $username = 'postgres';
    $password = 'GPp3squ1s@';

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Busca os dados da solução
        $sql_solucao = "SELECT
                            contato.nome AS nome_contato, solucao.cd_solucao, d.nome AS nome_responsavel, entidade.nome AS nome_entidade, solucao.cd_entidade as cd_entidade,
                            solucao.uf AS estado, solucao.municipio AS cidade, d.contato as contato_responsavel, solucao.* 
                        FROM
                            solucao
                        INNER JOIN
                            entidade ON solucao.cd_entidade = entidade.cd_entidade
                        INNER JOIN
                            contato ON solucao.cd_contato = contato.cd_contato
                        INNER JOIN
                            contato AS d ON solucao.cd_responsavel = d.cd_contato
                        WHERE
                            solucao.cd_solucao = :cd_solucao";
        $stmt_solucao = $conn->prepare($sql_solucao);
        $stmt_solucao->execute([':cd_solucao' => $cd_solucao]);
        $solucao = $stmt_solucao->fetch(PDO::FETCH_ASSOC);

        if ($solucao) {
            // Busca os produtos/serviços relacionados
            $sql_produtos = "SELECT ps.nome_produto_servico, a.cd_atividade, ps.cd_produto_servico, a.nome, remocao_florestal, tempo_produto, qtde_remocao, beneficio
                             FROM produto_servico_solucao pss
                             JOIN produto_servico ps ON pss.cd_produto_servico = ps.cd_produto_servico
                             JOIN atividade a ON ps.cd_atividade = a.cd_atividade
                             WHERE pss.cd_solucao = :cd_solucao";
            $stmt_produtos = $conn->prepare($sql_produtos);
            $stmt_produtos->execute([':cd_solucao' => $cd_solucao]);
            $produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);
        } else {
            die("Solução não encontrada.");
        }

        if ($solucao) {
            // Busca os territorios relacionados
            $sql_territorios = "SELECT * from solucao_territorio
                             WHERE cd_solucao = :cd_solucao";
            $stmt_territorios = $conn->prepare($sql_territorios);
            $stmt_territorios->execute([':cd_solucao' => $cd_solucao]);
            $territorios = $stmt_territorios->fetchAll(PDO::FETCH_ASSOC);
        } else {
            die("Territorio não encontrado.");
        }

    } catch (PDOException $e) {
        die("Erro no banco de dados: " . $e->getMessage());
    }
} else {
    die("cd_solucao não fornecido.");
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="Questionário">
    <meta property="og:description" content="Questionário socio ambiental de soluções">
    <meta property="og:image" content="https://pesquisa.am//tabelas/forest_solutions/gp-logo-1.jpg">    
    <title>Questionário</title>
    <style>
        table {
            border-collapse: collapse; /* Faz as bordas colapsarem em uma única borda */
            width: 100%; /* Largura da tabela */
        }
        table, th, td {
            border: 1px solid black; /* Borda de 1px sólida e preta */
        }
        th, td {
            padding: 8px; /* Espaçamento interno das células */
            text-align: left; /* Alinhamento do texto */
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionário</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        button_ok {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .form-container h2 {
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group button {
            width: 100%;
            padding: 1rem;
            background-color: #6c63ff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #574fdb;
        }

        /* Responsividade */
        @media (max-width: 600px) {
            .form-container {
                padding: 1.5rem;
            }

            .form-container h2 {
                font-size: 1.5rem;
            }
        }
    body {
        padding-top: 60px; /* Espaço para o botão Cadastrar */
        padding-bottom: 80px; /* Espaço extra para as mensagens de erro */
    }

        /* Estilo base para os botões */
        .botao-fixo {
            padding: 15px;
            font-weight: bold;
            text-align: center;
            border: none;
            cursor: pointer;
            font-size: 16px;
            flex: 1; /* Ocupa o espaço disponível igualmente */
            margin: 0 5px; /* Espaçamento entre os botões */
        }

        /* Botão Voltar */
        .botao-voltar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: green;
            color: white;
            z-index: 1001; /* Mantém o botão acima do conteúdo */
        }

        /* Container para os botões inferiores */
        .botoes-inferiores {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 10px; /* Espaçamento ao redor dos botões */
            box-sizing: border-box; /* Garante que o padding não aumente a largura total */
            z-index: 1001; /* Mantém os botões acima do conteúdo */
        }

        /* Botão Cadastrar */
        #botaoCadastrar {
            background-color: #007bff;
            color: white;
        }

        /* Botão Limpar Formulário */
        #cancelarFormulario {
            background-color: red;
            color: white;
        }
        /* Estilo para o relógio animado */
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #09f;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            display: none; /* Inicialmente oculto */
            margin: 10px auto;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Estilo básico para a página */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .checkbox-group {
            margin-bottom: 15px;
        }
        .checkbox-group label {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            width: 16px; /* Tamanho fixo para o checkbox */
            height: 16px; /* Tamanho fixo para o checkbox */
        }
        .outro-beneficio {
            display: none; /* Inicialmente oculto */
            margin-top: 10px;
        }
        .outro-beneficio input {
            width: 100%;
            max-width: 300px;
            padding: 5px;
            font-size: 14px;
        }
        .maps-link {
            display: flex; /* Usa flexbox para centralizar */
            align-items: center; /* Centraliza verticalmente */
            justify-content: center; /* Centraliza horizontalmente */
            text-decoration: none; /* Remove sublinhado do link */
            color: #007BFF; /* Cor do texto */
            font-size: 16px; /* Tamanho da fonte */
            gap: 8px; /* Espaço entre o ícone e o texto */
        }

        .maps-link:hover {
            color: #0056b3; /* Cor do texto ao passar o mouse */
        }

        .fa-map-marker-alt {
            color: #FF0000; /* Cor do ícone (vermelho, como um marcador de mapa) */
        }
    </style>
</head>
    <?php
        $dataAtual = new DateTime();                      
	                
        function estadoPorExtensoParaUF($estadoPorExtenso)
        {
            $estados = array(
                'Acre' => 'AC',
                'Alagoas' => 'AL',
                'Amapá' => 'AP',
                'Amazonas' => 'AM',
                'Bahia' => 'BA',
                'Ceará' => 'CE',
                'Distrito Federal' => 'DF',
                'Espírito Santo' => 'ES',
                'Goiás' => 'GO',
                'Maranhão' => 'MA',
                'Mato Grosso' => 'MT',
                'Mato Grosso do Sul' => 'MS',
                'Minas Gerais' => 'MG',
                'Pará' => 'PA',
                'Paraíba' => 'PB',
                'Paraná' => 'PR',
                'Pernambuco' => 'PE',
                'Piauí' => 'PI',
                'Rio de Janeiro' => 'RJ',
                'Rio Grande do Norte' => 'RN',
                'Rio Grande do Sul' => 'RS',
                'Rondônia' => 'RO',
                'Roraima' => 'RR',
                'Santa Catarina' => 'SC',
                'São Paulo' => 'SP',
                'Sergipe' => 'SE',
                'Tocantins' => 'TO'
            );
            return isset($estados[$estadoPorExtenso]) ? $estados[$estadoPorExtenso] : null;
        }
    ?>
<body>
    <!-- Botão Voltar -->
    <a href="mostra_solucoes.php">
        <button class="botao-fixo botao-voltar">Voltar</button>
    </a>  
    <div class="form-container">
    <br>
    <br>
    <br>
        <center><a  target="_blank"><img src="gp-logo-1.jpg" width="150" height="100" /></a></center>
    <h2>Questionário</h2>
    <form action="altera_solucao.php?tk1=<?php echo $_GET['tk1']; ?>>" method="post" class="form-group">

        <!------------------------------------------ CABEÇALHO --------------------------------------- --> 
        <label for="origem">Origem:</label>
        <input type="text" id="origem" name="origem" value="<?= htmlspecialchars($solucao['origem']) ?>" readonly required><br>

        <div class="form-group">
            <label for="data_coleta">Data/Hora de coleta:</label>
            <input type="text" id="data_coleta" name="data_coleta" value="<?= htmlspecialchars($solucao['data_coleta']) ?>" >
        </div>

        <div class="form-group">
            <label for="data_coleta">Coordenadas da coleta:</label>
            <input type="text" id="coordenadas" name="coordenadas" value="<?= htmlspecialchars($solucao['coordenadas']) ?>"  />
        </div>
                                
        <label for="responsavel">Responsável pelo Questionário:</label>
        <select id="responsavel" name="responsavel" required>
        </select><br>          
        <br>

        <h3>A) Identificação e localização</h3>

        <script>
            const urlParams = new URLSearchParams(window.location.search);

            //const cd_entidade = urlParams.get('tk1');
            fetch(`get_entidades.php`)
            .then(response => response.json())
            .then(data => {
                //console.log("Dados recebidos:", data);
                const entidadeSelect = document.getElementById("entidade");
                data.forEach(entidade => {
                    let option = document.createElement("option");
                    option.value = entidade.cd_entidade;
                    option.textContent = entidade.nome;
                    if (entidade.nome === <?= json_encode($solucao['nome_entidade']) ?>) {
                        option.selected = true;
                    }
                    entidadeSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar entidades:", error));

            const cd_responsavel = urlParams.get('tk2');
            fetch(`get_contatos_gp.php?cd=${cd_responsavel}`)
            .then(response => response.json())
            .then(data => {
                //console.log("Dados recebidos:", data);
                const responsavelSelect = document.getElementById("responsavel");              
                data.forEach(responsavel => {
                    let option = document.createElement("option");
                    option.value = responsavel.cd_contato;
                    option.textContent = responsavel.nome;
                    if (responsavel.nome === <?= json_encode($solucao['nome_responsavel']) ?>) {
                        option.selected = true;
                    }
                    responsavelSelect.appendChild(option);
                });
            })
            .catch(error => console.error("Erro ao carregar contatos:", error));

            const entidadeSelect = document.getElementById("entidade");
            const contatoSelect = document.getElementById("contato");
            const entidadeSelecionada = <?= json_encode($solucao['cd_entidade']) ?>;
            fetch(`get_contatos_detalhe.php?cd=${entidadeSelecionada}`)
                        .then(response => response.json())
                        .then(data => {
                            //console.log("Dados recebidos:", data);
                            const contatoSelect = document.getElementById("contato");
                            //contatoSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';
                            data.forEach(contato => {
                                let option = document.createElement("option");
                                option.value = contato.cd_contato;
                                option.textContent = `${contato.nome}  ${contato.tipo}  ${contato.contato}`;
                                if (contato.nome === <?= json_encode($solucao['nome_contato']) ?>) {
                                    option.selected = true;
                                }
                                contatoSelect.appendChild(option);
                            });
                        })
            .catch(error => console.error("Erro ao carregar contatos:", error));

            document.addEventListener("DOMContentLoaded", function() {
                const entidadeSelect = document.getElementById("entidade");
                const contatoSelect = document.getElementById("contato");

                entidadeSelect.addEventListener("change", function() {
                    const entidadeSelecionada = entidadeSelect.value;

                    if (entidadeSelecionada) {
                        fetch(`get_contatos_detalhe.php?cd=${entidadeSelecionada}`)
                        .then(response => response.json())
                        .then(data => {
                            //console.log("Dados recebidos:", data);
                            const contatoSelect = document.getElementById("contato");
                            contatoSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';
                            data.forEach(contato => {
                                let option = document.createElement("option");
                                option.value = contato.cd_contato;
                                option.textContent = `${contato.nome}  ${contato.tipo}  ${contato.contato}`;
                                if (contato.nome === <?= json_encode($solucao['nome_contato']) ?>) {
                                    option.selected = true;
                                }
                                contatoSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Erro ao carregar contatos:", error));
                    }
                });
            });

        </script>

        <!------------------------------------------ QUESTÃO 1 --------------------------------------- -->
        <label for="entidade">1) Nome da entidade:</label>
        <select id="entidade" name="entidade" required>
        </select> 
        <label for="contato">1.1) Nome do Contato:</label>
        <select id="contato" name="contato" required>             
        </select> 
        
        <!------------------------------------------ QUESTÃO 2 --------------------------------------- -->
        <br>
        <label for="familias_beneficiadas">2) Quantas famílias estão envolvidas na associação/cooperativa?:</label>
        <input type="text" id="familias_beneficiadas" value="<?= htmlspecialchars($solucao['familias_beneficiadas']) ?>" name="familias_beneficiadas" required><br>

        <!------------------------------------------ QUESTÃO 3 --------------------------------------- -->        
        <div id="territorio-container">
            <table id="tabela-territorios">
            
            
            <?php $indiceT = 1?>
            <?php foreach ($territorios as $territorio): ?>
                <tr>
                <td>
                <div class="territorio">
                    <label for="territorio_<?=$indiceT?>">3) Quais as categorias fundiárias estão contempladas nas atividades da associação/cooperativa:</label>
                    <select id="territorio_<?=$indiceT?>" name="territorio_<?=$indiceT?>" required>
                        <option value="<?= htmlspecialchars($territorio['tipo']) ?>" selected><?= htmlspecialchars($territorio['tipo']) ?></option>
                        <option value="Unidades de Conservação">Unidades de Conservação</option>
                        <option value="CDRU ">CDRU</option>
                        <option value="Território Indígena">Território Indígena</option>
                        <option value="Território Quilombola">Território Quilombola</option>
                        <option value="Particular ">Particular </option>
                        <option value="Outro">Outro</option>
                    </select> 
                    <textarea id="nometerritorio_<?=$indiceT?>" name="nometerritorio_<?=$indiceT?>"  required><?= htmlspecialchars($territorio['nome']) ?></textarea><br>
                    <br>
                    <button type="button" class="remover-territorio" <?php if($indiceT==1) echo 'style="display: none;"' ?> onclick="removerTerritorio(this)">Remover</button>           
                </div>
                </td>
                </tr>
                <?php $indiceT++; ?>
            <?php endforeach; ?>


            </table>
        </div>

        <button type="button" onclick="adicionarTerritorio()">Adicionar</button>

        <script>
            let contadorT = 1; // Controla o número de inputs

            function adicionarTerritorio() {
                contadorT++; // Incrementa o contador

                let tabela = document.getElementById("tabela-territorios");
                let linhaOriginal = tabela.querySelector("tr");
                let novaLinha = linhaOriginal.cloneNode(true); // Clona a linha

                // Atualiza os nomes e IDs dos novos inputs
                let inputs = novaLinha.querySelectorAll("textarea, select");
                inputs.forEach(input => {
                    let campoBase = input.name.split("_")[0]; // Obtém o nome base do campo
                    let novoNome = campoBase + "_" + contadorT; // Novo nome único
                    input.name = novoNome;
                    input.id = novoNome;
                    input.value = ""; // Limpa o valor do campo
                });

                // Mostra o botão "Remover" na nova linha
                let botaoRemover = novaLinha.querySelector(".remover-territorio");
                botaoRemover.style.display = "inline-block";

                // Adiciona a nova linha à tabela
                tabela.appendChild(novaLinha);

                // Reaplica o evento de change ao novo select de atividade
                const novoTerritorioSelect = novaLinha.querySelector('select[id^="territorio_"]');
                adicionarEventoChange(novoTerritorioSelect);

                // Recarrega as atividades no novo select
                // carregarTerritorios(contador);
            }

            function removerTerritorio(botao) {
                let tabela = document.getElementById("tabela-territorios");
                if (tabela.rows.length > 1) { // Mantém pelo menos uma linha
                    botao.closest("tr").remove();
                    atualizarIndices(); // Atualiza os nomes após remoção
                }
            }

            function atualizarIndices() {
                let linhas = document.querySelectorAll("#tabela-territorios tr");
                contador = 0;

                linhas.forEach((linha, index) => {
                    let inputs = linha.querySelectorAll("input");
                    contador = index + 1;
                    inputs.forEach(input => {
                        let campoBase = input.name.split("_")[0]; 
                        let novoNome = campoBase + "_" + contadorT;
                        input.name = novoNome;
                        input.id = novoNome;
                    });
                });
            }

            document.addEventListener("DOMContentLoaded", function () {
                const botaoCancelar = document.getElementById("cancelarFormulario");
                botaoCancelar.addEventListener("click", function () {
                    // Redirecionar para a página mostra_solucoes.php
                    location.reload();
                });
            });

        </script>

        <br><br><br>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const estadoSelect = document.getElementById("Estado");
                const municipioSelect = document.getElementById("Municipio");
                estadoSelect.addEventListener("change", function() {
                    const estadoSelecionado = estadoSelect.value;

                    if (estadoSelecionado) {
                        fetch(`get_municipios.php?estado=${estadoSelecionado}`)
                            .then(response => response.json())
                            .then(data => {
                                    municipioSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';
                                    data.forEach(municipio => {
                                        let option = document.createElement("option");
                                        option.value = municipio;
                                        option.textContent = municipio;
                                        municipioSelect.appendChild(option);
                                    });
                            })
                            .catch(error => console.error("Erro ao carregar municípios:", error));
                    }
                });
            });
        </script>
        <!------------------------------------------ QUESTÃO 4 --------------------------------------- -->        
        <label for="Estado">4) Estado da sede da entidade:</label>
        <select id="Estado" name="Estado" required>
                <option value="<?= htmlspecialchars($solucao['estado']) ?>" selected><?= htmlspecialchars($solucao['estado']) ?></option>
                <option value="Acre" '; if ($estado =="Acre") echo 'selected' ; echo '>Acre</option>
                <option value="Amapá" '; if ($estado =="Amapá") echo 'selected' ; echo '>Amapá</option>
                <option value="Amazonas" '; if ($estado =="Amazonas") echo 'selected' ; echo '>Amazonas</option>
                <option value="Maranhão" '; if ($estado =="Maranhão") echo 'selected' ; echo '>Maranhão</option>
                <option value="Mato Grosso" '; if ($estado =="Mato Grosso") echo 'selected' ; echo '>Mato Grosso</option>
                <option value="Pará" '; if ($estado =="Pará") echo 'selected' ; echo '>Pará</option>
                <option value="Rondônia" '; if ($estado =="Rondônia") echo 'selected' ; echo '>Rondônia</option>
                <option value="Roraima" '; if ($estado =="Roraima") echo 'selected' ; echo '>Roraima</option>        
                <option value="Tocantins" '; if ($estado =="Tocantins") echo 'selected' ; echo '>Tocantins</option>
        </select>
        
        <!------------------------------------------ QUESTÃO 5 --------------------------------------- -->     
        <label for="Municipio">5) Municipio da sede da entidade:</label>
        <select id="Municipio" name="Municipio" required>
                <option value="<?= htmlspecialchars($solucao['cidade']) ?>" selected><?= htmlspecialchars($solucao['cidade']) ?></option>    
        </select>           



        <script>
            // Função para carregar atividades em todos os selects de atividade
            function carregarAtividades(contador) {
                fetch(`get_atividades.php`)
                    .then(response => response.json())
                    .then(data => {
                        // Seleciona todos os selects de atividade
                        selectAtividadeUnica = 'select[id^="atividade_'+contador+'"]';
                        const atividadeSelects = document.querySelectorAll(selectAtividadeUnica);
                        //console.log (contador);
                        //console.log (selectAtividadeUnica);
                        // Itera sobre cada select de atividade
                        atividadeSelects.forEach(atividadeSelect => {
                            // Limpa o select e adiciona uma opção padrão

                            //atividadeSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';


                            // Adiciona as opções de atividade
                            data.forEach(atividade => {
                                if (atividade.nome !== atividadeSelect.value) {
                                    let option = document.createElement("option");
                                    option.value = atividade.cd_atividade;
                                    option.textContent = atividade.nome;
                                    atividadeSelect.appendChild(option);
                                }
                            });

                            // Adiciona um evento de change ao select de atividade
                            adicionarEventoChange(atividadeSelect);
                        });
                    })
                    .catch(error => console.error("Erro ao carregar atividades:", error));
            }

            // Função para adicionar o evento de change a um select de atividade
            function adicionarEventoChange(atividadeSelect) {
                atividadeSelect.addEventListener("change", function() {
                    const atividadeSelecionada = this.value;
                    const idAtividade = this.id; // Pega o ID do select de atividade (ex: "atividade_1")
                    const numero = idAtividade.split("_")[1]; // Extrai o número (ex: "1")
                    const produtoSelect = document.getElementById(`produto_${numero}`); // Seleciona o produto correspondente

                    if (atividadeSelecionada) {
                        // Carrega os produtos com base na atividade selecionada
                        fetch(`get_produtos.php?atividade=${atividadeSelecionada}`)
                            .then(response => response.json())
                            .then(data => {
                                // Limpa o select de produto e adiciona uma opção padrão
                                produtoSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';

                                // Adiciona as opções de produto
                                data.forEach(produto => {
                                    let option = document.createElement("option");
                                    option.value = produto.cd_produto_servico;
                                    option.textContent = produto.nome_produto_servico;
                                    produtoSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error("Erro ao carregar produtos:", error));
                    } else {
                        // Se nenhuma atividade for selecionada, limpa o select de produto
                        produtoSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';
                    }
                });
            }

            // Executa a função quando o DOM estiver carregado
            document.addEventListener("DOMContentLoaded", carregarAtividades(1));
        </script>
    
        <!------------------------------------------ QUESTÃO 6 --------------------------------------- -->
        <label> 6) Atividades, Produtos e Serviços</label>

        <div id="atividades-container">
            <table id="tabela-atividades">
            <?php $indice = 1?>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                <td>
                <div class="atividade">
                    <label for="atividade_<?=$indice?>">6.1) Em qual ou quais categorias as atividades/produtos desenvolvidos se enquadram?</label>
                    <select id="atividade_<?=$indice?>" name="atividade_<?=$indice?>" required>
                        <option value="<?= htmlspecialchars($produto['cd_atividade']) ?>" selected><?= htmlspecialchars($produto['nome']) ?></option>    
                    </select> 

                    <?php if ($indice > 1) { ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const atividadeSelect = document.getElementById("atividade_<?= $indice ?>");
                            const selectedValue = atividadeSelect.value;

                            fetch('get_atividades.php')
                                .then(response => response.json())
                                .then(data => {
                                    data.forEach(atividade => {
                                        // Evita duplicar a opção já selecionada
                                        if (atividade.nome !== selectedValue) {
                                            let option = document.createElement("option");
                                            option.value = atividade.cd_atividade;
                                            option.textContent = atividade.nome;
                                            atividadeSelect.appendChild(option);
                                        }
                                    });
                                    adicionarEventoChange(atividadeSelect);
                                })
                                .catch(error => console.error("Erro ao carregar atividades:", error));
                        });
                    </script>
                    <?php } ?>

                    <label for="produto_<?=$indice?>">6.2) Insira um produto ou serviço relacionado à atividade:</label>
                    <select id="produto_<?=$indice?>" name="produto_<?=$indice?>" required>
                        <option value="<?= htmlspecialchars($produto['cd_produto_servico']) ?>" selected><?= htmlspecialchars($produto['nome_produto_servico']) ?></option>    
                    </select> 
                    <br>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const atividadeSelect = document.getElementById("atividade_<?= $indice ?>");
                            const atividadeSelectedValue = atividadeSelect.value;

                            const produtoSelect = document.getElementById("produto_<?= $indice ?>");
                            const selectedValue = produtoSelect.value;

                            fetch(`get_produtos.php?atividade=${atividadeSelectedValue}`)
                                .then(response => response.json())
                                .then(data => {
                                    data.forEach(produto => {
                                        // Evita duplicar a opção já selecionada
                                        if (produto.nome !== selectedValue) {
                                            let option = document.createElement("option");
                                            option.value = produto.cd_produto_servico;
                                            option.textContent = produto.nome_produto_servico;
                                            produtoSelect.appendChild(option);
                                        }
                                    });
                                })
                                .catch(error => console.error("Erro ao carregar produtos:", error));
                        });
                    </script>
                    
                    <label for="tempoproduto_<?=$indice?>">6.3) A quanto tempo trabalha com esse produto/serviço?</label>
                    <input value="<?= htmlspecialchars($produto['tempo_produto']) ?>" type="text" id="tempoproduto_<?=$indice?>" name="tempoproduto_<?=$indice?>" required><br>
                    <br>

                    <label for="beneficio_<?=$indice?>">6.4) A coopertiva/associação realiza o beneficiamento ou agrega valor ao produto/serviço?</label>
                    <select id="beneficio_<?=$indice?>" name="beneficio_<?=$indice?>" required>
                        <option value="<?= htmlspecialchars($produto['beneficio']) ?>" selected><?= htmlspecialchars($produto['beneficio']) ?></option>
                        <option value="Sim">Sim</option>
                        <option value="Não">Não</option>
                        <option value="Não se aplica">Não se aplica</option>   
                    </select> 
                    <br>

                    <label for="remocao_<?=$indice?>">6.5) As comunidades precisam retirar floresta pra implantar/realizar as atividades?</label>
                    <select name="remocao_<?=$indice?>" onchange="toggleAreaRemovida(this)" required>
                        <option value="<?= htmlspecialchars($produto['remocao_florestal']) ?>" selected><?= htmlspecialchars($produto['remocao_florestal']) ?></option>
                        <option value="Não, a área já estava aberta para outras finalidades">Não, a área já estava aberta para outras finalidades</option>
                        <option value="Não, a cobertura florestal auxilia a atividade">Não, a cobertura florestal auxilia a atividade</option>
                        <option value="Não se aplica">Não se aplica</option>
                        <option value="Sim, quanto?">Sim, quanto?</option>
                    </select><br>
                    <input type="text" name="areaRemovida_<?=$indice?>" placeholder="Qual a área removida?" style="display: none;">

                    <button type="button" class="remover-atividade" <?php if($indice==1) echo 'style="display: none;"' ?> onclick="removerAtividade(this)">Remover</button>
                </div>
                <?php $indice++; ?>
                </td>
                </tr>
            <?php endforeach; ?>
            </table>
        </div>

        <button type="button" onclick="adicionarAtividade()">Adicionar</button>

        <script>
            let contador = 1; // Controla o número de inputs

            function toggleAreaRemovida(select) {
                let input = select.parentElement.querySelector('input[name^="areaRemovida_"]');
                if (input) {
                    input.style.display = (select.value === "Sim, quanto?") ? "block" : "none";
                }
            }

            function adicionarAtividade() {
                contador++; // Incrementa o contador

                let tabela = document.getElementById("tabela-atividades");
                let linhaOriginal = tabela.querySelector("tr");
                let novaLinha = linhaOriginal.cloneNode(true); // Clona a linha

                // Atualiza os nomes e IDs dos novos inputs
                let inputs = novaLinha.querySelectorAll("input, select");
                inputs.forEach(input => {
                    let campoBase = input.name.split("_")[0]; // Obtém o nome base do campo
                    let novoNome = campoBase + "_" + contador; // Novo nome único
                    input.name = novoNome;
                    input.id = novoNome;
                    input.value = ""; // Limpa o valor do campo
                });

                // Mostra o botão "Remover" na nova linha
                let botaoRemover = novaLinha.querySelector(".remover-atividade");
                botaoRemover.style.display = "inline-block";

                // Adiciona a nova linha à tabela
                tabela.appendChild(novaLinha);

                // Reaplica o evento de change ao novo select de atividade
                const novoAtividadeSelect = novaLinha.querySelector('select[id^="atividade_"]');
                adicionarEventoChange(novoAtividadeSelect);

                // Recarrega as atividades no novo select
                carregarAtividades(contador);
            }

            function removerAtividade(botao) {
                let tabela = document.getElementById("tabela-atividades");
                if (tabela.rows.length > 1) { // Mantém pelo menos uma linha
                    botao.closest("tr").remove();
                    atualizarIndices(); // Atualiza os nomes após remoção
                }
            }

            function atualizarIndices() {
                let linhas = document.querySelectorAll("#tabela-atividades tr");
                contador = 0;

                linhas.forEach((linha, index) => {
                    let inputs = linha.querySelectorAll("input");
                    contador = index + 1;
                    inputs.forEach(input => {
                        let campoBase = input.name.split("_")[0]; 
                        let novoNome = campoBase + "_" + contador;
                        input.name = novoNome;
                        input.id = novoNome;
                    });
                });
            }

            document.addEventListener("DOMContentLoaded", function () {
                const form = document.querySelector("form");
                const botaoLimpar = document.getElementById("limparFormulario");

                // Limpar o formulário e o localStorage ao clicar no botão
                botaoLimpar.addEventListener("click", function () {
                    localStorage.removeItem("formData");
                    //console.log("Formulário Limpo !!!");
                    form.reset();
                });
            });
        </script>

        <br><br><br>
        <h3>B) Infraestrutura</h3>
 
        <!------------------------------------------ QUESTÃO 7 --------------------------------------- -->
        <label for="internet_qualidade">7) A maioria das comunidades associadas têm acesso ou boa qualidade de internet ou sinal telefônico nas infraestruturas relacionadas à atividade?</label>
        </label>
        <select id="internet_qualidade" name="internet_qualidade" required>
            <option value="<?= htmlspecialchars($solucao['internet_qualidade']) ?>" selected><?= htmlspecialchars($solucao['internet_qualidade']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
        </select><br>        

        <!------------------------------------------ QUESTÃO 8 --------------------------------------- -->
        <label for="energia_qualidade">8) A maioria das comunidades associadas têm acesso ou boa qualidade de energia elétrica nas infraestruturas relacionadas à atividade?</label>
        <select id="energia_qualidade" name="energia_qualidade" required>
            <option value="<?= htmlspecialchars($solucao['energia_qualidade']) ?>"  selected><?= htmlspecialchars($solucao['energia_qualidade']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
        </select><br>

        <script>
            function toggleOutraFonte() {
                var select = document.getElementById("energia_fonte");
                var outroInput = document.getElementById("outra_fonte");
                if (select.value === "Outra") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <!------------------------------------------ QUESTÃO 9 --------------------------------------- -->
        <label for="energia_fonte">9) Qual a principal fonte de energia das comunidades associadas?</label>
        <select id="energia_fonte" name="energia_fonte" onchange="toggleOutraFonte()" required>
            <option value="<?= htmlspecialchars($solucao['energia_fonte']) ?>" selected><?= htmlspecialchars($solucao['energia_fonte']) ?></option>
            <option value="Energia da rede pública">Energia da rede pública</option>
            <option value="Placas solares">Placas solares</option>
            <option value="Gerador">Gerador</option>
            <option value="Outra">Outra</option>
        </select><br>
        <input type="text" id="outra_fonte" name="outra_fonte" placeholder="Qual a outra fonte?" style="display: none;" >
 
        <!------------------------------------------ QUESTÃO 10 --------------------------------------- -->
        <label for="agua_qualidade">10) A maioria das comunidades associadas têm acesso ou boa qualidade de água potável?</label>
        <select id="agua_qualidade" name="agua_qualidade" required>
            <option value="<?= htmlspecialchars($solucao['agua_qualidade']) ?>" selected><?= htmlspecialchars($solucao['agua_qualidade']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
        </select><br>

        <!-- <script>
            function toggleOutraAgua() {
                var select = document.getElementById("agua_fonte");
                var outroInput = document.getElementById("outra_agua");
                if (select.value === "Outra") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <!------------------------------------------ QUESTÃO 11 --------------------------------------- -->
        <label for="agua_fonte">11) Qual a principal fonte de água para consumo da comunidade/cooperativa/associação? </label>
        <select id="agua_fonte" name="agua_fonte" onchange="toggleOutraAgua()" required>
            <option value="<?= htmlspecialchars($solucao['agua_fonte']) ?>" selected><?= htmlspecialchars($solucao['agua_fonte']) ?></option>
            <option value="Poço">Poço</option>
            <option value="Rio">Rio</option>
            <option value="Águas da chuva">Águas da chuva</option>
            <option value="Outra">Outra</option>
        </select><br>        
        <input type="text" id="outra_agua" name="outra_agua" placeholder="Qual a outra fonte?" style="display: none;" >
        
        <script>
            function toggleOutroMeio() {
                var select = document.getElementById("acesso_meio");
                var outroInput = document.getElementById("outro_meio");
                if (select.value === "Outro") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>
        <!------------------------------------------ QUESTÃO 12 --------------------------------------- -->
        <label for="acesso_meio">12) Qual o principal meio de acesso para escoamento da produção?</label>
        <select id="acesso_meio" name="acesso_meio" onchange="toggleOutroMeio()" required>
            <option value="<?= htmlspecialchars($solucao['acesso_meio']) ?>" selected><?= htmlspecialchars($solucao['acesso_meio']) ?></option>
            <option value="Rios">Rios</option>
            <option value="Estrada">Estrada</option>
            <option value="Aéreo">Aéreo</option>
            <option value="Outro">Outro</option>
        </select><br>
        <input type="text" id="outro_meio" name="outro_meio" placeholder="Qual o outro meio?" style="display: none;" >
        
        <!------------------------------------------ QUESTÃO 13 --------------------------------------- -->
        <label for="acesso_qualidade">13) As comunidades associadas têm facilidade de escoamento da produção para os compradores?</label>
        <select id="acesso_qualidade" name="acesso_qualidade" required>
            <option value="<?= htmlspecialchars($solucao['acesso_qualidade']) ?>" selected><?= htmlspecialchars($solucao['acesso_qualidade']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>

        <script>
            function toggleOutraCap() {
                var select = document.getElementById("capacitacao_implementada");
                var outroInput = document.getElementById("outra_cap");
                if (select.value === "Não, mas temos interesse") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>
        <br>
        <br>
        <h3>C) Tecnificação e Cultura</h3>

        <!------------------------------------------ QUESTÃO 14 --------------------------------------- -->
        <label for="capacitacao_implementada">14) As pessoas envolvidas nas atividades realizaram capacitação, curso ou necessitaram de assistência técnica?</label>
        <select id="capacitacao_implementada" name="capacitacao_implementada" onchange="toggleOutraCap()" required>
            <option value="<?= htmlspecialchars($solucao['capacitacao_implementada']) ?>" selected><?= htmlspecialchars($solucao['capacitacao_implementada']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não, mas temos interesse">Não, mas temos interesse</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>
        <input type="text" id="outra_cap" name="outra_cap" placeholder="Qual a capacitação de interesse?" style="display: none;">

        <script>
            function toggleOutraTec() {
                var select = document.getElementById("tecnologia_implementada");
                var outroInput = document.getElementById("outra_tec");
                if (select.value === "Não, mas temos interesse") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <!------------------------------------------ QUESTÃO 15 --------------------------------------- -->
        <label for="tecnologia_implementada">15) A associação conta com maquinário ou algum processamento tecnológico para ajudar as atividades ou aumentar a produtividade?</label>
        <select id="tecnologia_implementada" name="tecnologia_implementada" onchange="toggleOutraTec()" required>
            <option value="<?= htmlspecialchars($solucao['tecnologia_implementada']) ?>" selected><?= htmlspecialchars($solucao['tecnologia_implementada']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não, mas temos interesse">Não, mas temos interesse</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>
        <input type="text" id="outra_tec" name="outra_tec" placeholder="Qual a tecnologia de interesse?" style="display: none;" >

        <script>
            function toggleOutraEst() {
                var select = document.getElementById("estrategia_implementada");
                var outroInput1 = document.getElementById("outra_est");
                var outroInput2 = document.getElementById("qual_est");
                if (select.value === "Não, mas temos interesse") {
                    outroInput1.style.display = "block";
                } else {
                    outroInput1.style.display = "none";
                }
                if (select.value === "Sim") {
                    outroInput2.style.display = "block";
                } else {
                    outroInput2.style.display = "none";
                }
            }
        </script>   

        <!------------------------------------------ QUESTÃO 16 --------------------------------------- -->
        <label for="estrategia_implementada">16) A associação utiliza Instagram, Facebook ou sites para divulgação dos produtos e atividades?</label>
        <select id="estrategia_implementada" name="estrategia_implementada" onchange="toggleOutraEst()" required>
            <option value="<?= htmlspecialchars($solucao['estrategia_implementada']) ?>" selected><?= htmlspecialchars($solucao['estrategia_implementada']) ?></option>
            <option value="Parcialmente">Parcialmente</option>            
            <option value="Sim">Sim</option>
            <option value="Não, mas temos interesse">Não, mas temos interesse</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>
        <input type="text" id="outra_est" name="outra_est" placeholder="Qual a rede/site de interesse" style="display: none;" >
        <input type="text" id="qual_est" name="qual_est" placeholder="Qual o nome da rede/site" style="display: none;" >

        <!------------------------------------------ QUESTÃO 17 --------------------------------------- -->
        <label for="engajamento_comunitario">17) Como você avalia a participação da comunidade associada nas atividades ou em qualquer etapa da cadeia produtiva?</label>
        <select id="engajamento_comunitario" name="engajamento_comunitario" required>
            <option value="<?= htmlspecialchars($solucao['engajamento_comunitario']) ?>" selected><?= htmlspecialchars($solucao['engajamento_comunitario']) ?></option>
            <option value="Excelente">Excelente</option>
            <option value="Bom">Bom</option>
            <option value="Regular">Regular</option>
            <option value="Ruim">Ruim</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 18 --------------------------------------- -->         
        <label for="preservacao_cultural">18) Como você avalia as tradições e os modos de vida das comunidades associadas?</label>
        <select id="preservacao_cultural" name="preservacao_cultural" required>
            <option value="<?= htmlspecialchars($solucao['preservacao_cultural']) ?>" selected><?= htmlspecialchars($solucao['preservacao_cultural']) ?></option>
            <option value="A cultura é muito preservada">A cultura é muito preservada</option>
            <option value="A cultura é parcialmente preservada">A cultura é parcialmente preservada</option>
            <option value="A cultura é possui risco de desaparecer">A cultura possui risco de desaparecer</option>
            <option value="Não sei">Não sei</option>
        </select><br>
        
        <br><br>
        <h3>D) Desafios e Ameaças</h3>

        <script>
            function toggleOutraTec() {
                var select = document.getElementById("qual_conflito");
                var outroInput = document.getElementById("qual_conflito");
                if (select.value === "Pequeno (área/localização restrita)" || select.value === "Médio (afetando área maior" || select.value === "Grave (impacto sifnificante na floresta e biodiversidade)" ) {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <!------------------------------------------ QUESTÃO 19 --------------------------------------- -->  
        <label for="conflitos_locais">19) Há conflitos de poder ou ameaças territoriais internas ou externas que prejudicam as ativiades da associação nos territórios?</label>
        <select id="conflitos_locais" name="conflitos_locais" required>
            <option value="<?= htmlspecialchars($solucao['conflitos_locais']) ?>" selected><?= htmlspecialchars($solucao['conflitos_locais']) ?></option>
            <option value="Pequeno (área/localização restrita)">Pequeno (área/localização restrita)</option>
            <option value="Médio (afetando área maior)">Médio (afetando área maior)</option>
            <option value="Grave (impacto sifnificante na floresta e biodiversidade)">Grave (impacto sifnificante na floresta e biodiversidade)</option>
            <option value="Não">Não</option>
        </select><br>
         <input type="text" id="qual_conflito" name="qual_conflito" placeholder="Qual conflito interfere/prejudica as atividades?" style="display: none;" >       
 
        <!------------------------------------------ QUESTÃO 20 --------------------------------------- -->  
        <label for="vontade_mudanca">20) A maioria das pessoas envolvidas nas atividades pensam em mudar para cidade ou grandes centros urbanos?</label>
        <select id="vontade_mudanca" name="vontade_mudanca" required>
            <option value="<?= htmlspecialchars($solucao['vontade_mudanca']) ?>" selected><?= htmlspecialchars($solucao['vontade_mudanca']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Talvez">Talvez</option>
            <option value="Não">Não</option>
        </select><br>

        <script>
            function toggleOutraDep() {
                var select = document.getElementById("dependencia_finaceira_externa");
                var outroInput = document.getElementById("outra_dep");
                if (select.value === "Sim") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <!------------------------------------------ QUESTÃO 21 --------------------------------------- -->  
        <label for="dependencia_finaceira_externa">21) A atividade depende ou ainda depende totalmente de recursos financeiros externos de apoiadores?</label>
        <select id="dependencia_finaceira_externa" name="dependencia_finaceira_externa" onchange="toggleOutraDep()" required>         
            <option value="<?= htmlspecialchars($solucao['dependencia_finaceira_externa']) ?>" selected><?= htmlspecialchars($solucao['dependencia_finaceira_externa']) ?></option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
        </select><br>
        <input type="text" id="outra_dep" name="outra_dep" placeholder="Qual o apoiador?" style="display: none;">

        <script>
            function toggleOutraMud() {
                var select = document.getElementById("mudanca_meio_ambiente");
                var outroInput = document.getElementById("outra_mud");
                if (select.value === "Sim") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <!------------------------------------------ QUESTÃO 22 --------------------------------------- -->  
        <label for="mudanca_meio_ambiente">22) Você tem percebido mudanças no clima como aumento de temperatura, chuvas e secas intensas mais frequentes ou outras mudanças ambientais?</label>
        <select id="mudanca_meio_ambiente" name="mudanca_meio_ambiente" onchange="toggleOutraMud()" required>         
            <option value="<?= htmlspecialchars($solucao['mudanca_meio_ambiente']) ?>" selected><?= htmlspecialchars($solucao['mudanca_meio_ambiente']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Não">Não</option>
            <option value="Não se aplica">Não se aplica</option>
            <option value="Parcialmente">Parcialmente</option>
        </select><br>
        <input type="text" id="outra_mud" name="outra_mud" placeholder="Como isso afeta as atividades/produtividade?" style="display: none;" >

        <!------------------------------------------ QUESTÃO 23 --------------------------------------- -->  
        <label for="desafios">23) Quais são/foram os principais desafios da associação/cooperativa?</label>
        <textarea id="desafios" value="<?= htmlspecialchars($solucao['desafios']) ?>" name="desafios" required><?= htmlspecialchars($solucao['desafios']) ?></textarea><br>

        <!------------------------------------------ QUESTÃO 24 --------------------------------------- -->          
        <label for="melhorias">24) Quais melhorias você pode citar para o futuro?</label>
        <textarea id="melhorias" value="<?= htmlspecialchars($solucao['desafios']) ?>" name="melhorias" required><?= htmlspecialchars($solucao['desafios']) ?></textarea><br>
        <br>
        <br>
        <h3>E) Indicadores de sucesso (Ambientais e Socioeconomicos)</h3>
        <br>

        <!------------------------------------------ QUESTÃO 25 --------------------------------------- -->  
        <?php
                // Divide a string em um array usando ';' como delimitador
                $beneficios = explode(';', $solucao['beneficiosfinal']);

                // Remove espaços em branco no início e no final de cada texto
                $beneficios = array_map('trim', $beneficios);

                // Itera sobre o array e cria um input para cada benefício
                $beneficio_array = [];
                $outro = '';
                foreach ($beneficios as $beneficio) {
                    if (!empty($beneficio)) { // Verifica se o benefício não está vazio
                        if ($beneficio == 'Outro') {
                             $outro = htmlspecialchars($solucao['beneficiooutro']) ;
                        } else {
                             $beneficio_array[] = htmlspecialchars($beneficio) ;
                        }
                    }
                }
        ?>
        <label for="beneficios">25) O empreendimento gerou benefícios para sua comunidade/território? </label>
        <div class="checkbox-group" id="beneficios">
            <label>
                <input type="checkbox" id="beneficios_1" name="beneficios_1" value="Aumento da renda" <?php if (in_array('Aumento da renda', $beneficio_array))
                    echo 'checked' ?>> Aumento da renda
            </label>
            <label>
                <input type="checkbox" id="beneficios_2" name="beneficios_2" value="Qualidade/segurança alimentar" <?php if (in_array('Qualidade/segurança alimentar', $beneficio_array))
                    echo 'checked' ?>> Qualidade/segurança alimentar
            </label>
            <label>
                <input type="checkbox" id="beneficios_3" name="beneficios_3" value="Qualidade na educação" <?php if (in_array('Qualidade na educação', $beneficio_array))
                    echo 'checked' ?>> Qualidade na educação
            </label>
            <label>
                <input type="checkbox" id="beneficios_4" name="beneficios_4" value="Qualidade da saúde" <?php if (in_array('Qualidade da saúde', $beneficio_array))
                    echo 'checked' ?>> Qualidade da saúde
            </label>
            <label>
                <input type="checkbox" id="beneficios_5" name="beneficios_5" value="Qualidade da moradia" <?php if (in_array('Qualidade da moradia', $beneficio_array))
                    echo 'checked' ?>> Qualidade da moradia
            </label>
            <label>
                <input type="checkbox" id="beneficios_6" name="beneficios_6" value="Qualidade emocional ( felicidade, orgulho, pertencimento etc)" <?php if (in_array('Qualidade emocional ( felicidade, orgulho, pertencimento etc)', $beneficio_array))
                    echo 'checked' ?>> Qualidade emocional ( felicidade, orgulho, pertencimento etc)
            </label>
            <label>
                <input type="checkbox" id="beneficios_7" name="beneficios_7" value="Não gerou ou gerou poucos benefícios" <?php if(in_array('Não gerou ou gerou poucos benefícios', $beneficio_array)) echo 'checked'?>> Não gerou ou gerou poucos benefícios
            </label>
            <label>
                <input type="checkbox" name="beneficios_8" value="Outro" id="outroCheckbox" <?php if (!empty($outro)) echo 'checked'; ?>> Outro
            </label>
        </div>
        <div class="outro-beneficio" id="outroBeneficio">
            <label for="outroTexto">Especifique o outro benefício:</label>
            <input type="text" id="outroTexto" value ="<?php echo $outro; ?>" name="outroTexto">
            <br>
        </div>
        <script>
            // Função para mostrar ou ocultar o campo "Outro"
            function toggleOutroBeneficio() {
                const outroCheckbox = document.getElementById('outroCheckbox');
                const outroBeneficio = document.getElementById('outroBeneficio');

                if (outroCheckbox.checked) {
                    outroBeneficio.style.display = 'block';
                } else {
                    outroBeneficio.style.display = 'none';
                }
            }
            // Adiciona um listener ao checkbox "Outro"
            document.addEventListener('DOMContentLoaded', function () {
                // Adiciona o listener
                document.getElementById('outroCheckbox').addEventListener('change', toggleOutroBeneficio);

                // Executa a função uma vez ao carregar a página
                toggleOutroBeneficio();
            });
        </script>

        <!------------------------------------------ QUESTÃO 26 --------------------------------------- -->  
        <label for="renda">26) A renda gerada é suficiente para cobrir as necessidades básicas como alimentação, moradia, educação e saúde? </label>
        <select id="renda" name="renda" required>         
            <option value="<?= htmlspecialchars($solucao['renda']) ?>" selected><?= htmlspecialchars($solucao['renda']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 27 --------------------------------------- -->  
        <label for="beneficioAmbiental">27) A atividade gera benefícios ambientais, proteção da biodiversidade, redução do desmatamento, reflorestamento, proteção de nascentes e rios ou outro? </label>
        <select id="beneficioAmbiental" name="beneficioAmbiental" required>         
            <option value="<?= htmlspecialchars($solucao['beneficioambiental']) ?>"  selected><?= htmlspecialchars($solucao['beneficioambiental']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 28 --------------------------------------- -->  
        <label for="regeneracao">28) Nos territórios de atuação existem áreas  em regeneração ou em processo de recuperação da floresta?? </label>
        <select id="regeneracao" name="regeneracao" required>         
            <option value="<?= htmlspecialchars($solucao['regeneracao']) ?>" selected><?= htmlspecialchars($solucao['regeneracao']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
            <option value="Não se aplica">Não se aplica</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 29 --------------------------------------- -->  
        <label for="aumentodiversidade">29) Você acha que aumentou a frequência de animais ou espécies florestais no território das comunidades? </label>
        <select id="aumentodiversidade" name="aumentodiversidade" required>         
            <option value="<?= htmlspecialchars($solucao['aumentodiversidade']) ?>" selected><?= htmlspecialchars($solucao['aumentodiversidade']) ?></option>
            <option value="Sim, aumentou">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não, está igual a antes">Não</option>
            <option value="Não sei">Não sei</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 30 --------------------------------------- -->  
        <label for="reducaoEmissoes">30) Na sua opinião as atividades contribuem para redução dos efeitos das mudanças climáticas? </label>
        <select id="reducaoEmissoes" name="reducaoEmissoes" required>         
            <option value="<?= htmlspecialchars($solucao['reducaoemissoes']) ?>" selected><?= htmlspecialchars($solucao['reducaoemissoes']) ?></option>
            <option value="Sim">Sim</option>
            <option value="Parcialmente">Parcialmente</option>
            <option value="Não">Não</option>
            <option value="Não sei">Não sei</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 31 --------------------------------------- -->  
        <label for="educacaambiental">31) Qual o nível de conscientização/educação ambiental a maioria das comunidades possuem? </label>
        <select id="educacaambiental" name="educacaambiental" required>         
            <option value="<?= htmlspecialchars($solucao['educacaambiental']) ?>" selected><?= htmlspecialchars($solucao['educacaambiental']) ?></option>
            <option value="Alto, todos entendem a importância da floresta em pé e boas práticas de uso">Alto, todos entendem a importância da floresta em pé e boas práticas de uso</option>
            <option value="Bom, a maioria entende a importância da floresta em pé e boas práticas de uso">Bom, a maioria entende a importância da floresta em pé e boas práticas de uso</option>
            <option value="Médio, parte da comunidade entende a importância da floresta em pé e boas práticas de uso">Médio, parte da comunidade entende a importância da floresta em pé e boas práticas de uso</option>
            <option value="Baixo, poucos entendem a importância da floresta em pé e boas práticas de uso">Baixo, poucos entendem a importância da floresta em pé e boas práticas de uso</option>
            <option value="Muito baixo, quase ninguém entende a importância da floresta em pé e boas práticas de uso">Muito baixo, quase ninguém entende a importância da floresta em pé e boas práticas de uso</option>
        </select><br>

        <!------------------------------------------ QUESTÃO 32 --------------------------------------- -->  
       <label for="lideranca">32) Quem ocupa a maioria dos cargos de liderança, administrativos ou participam de eventos da associação/cooperativa? </label>
        <select id="lideranca" name="lideranca" required>         
            <option value="<?= htmlspecialchars($solucao['lideranca']) ?>" selected><?= htmlspecialchars($solucao['lideranca']) ?></option>
            <option value="Homens">Homens</option>
            <option value="Mulheres">Mulheres</option>
            <option value="Existe equilíbrio entre pessoas de diferentes gêneros">Existe equilíbrio entre pessoas de diferentes gêneros</option>
            <option value="Não sei">Não sei</option>
        </select><br>



        <br>
        <br>
    
    <br>
    <br>
    <br>

        <!-- Container para os botões inferiores -->
        <div class="botoes-inferiores">
            <!-- Botão Cadastrar com ícone ✔ -->
            <button id="botaoAtualizar" class="botao-fixo" type="button">✔ Atualizar</button>

            <!-- Botão Limpar Formulário com ícone ✖ -->
            <button id="cancelarFormulario" class="botao-fixo" type="reset">✖ Cancelar</button>
        </div>

        <!-- Modal -->
        <div id="myModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); z-index: 1000; width: 90%; max-width: 500px;">
            <h3 style="text-align: center;">Questionário atualizado!</h3>
            <button style="padding: 8px 16px; display: block; margin: 20px auto 0 auto;" id="confirmarEnvio">OK</button>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.querySelector("form");
                const botaoAtualizar = document.getElementById("botaoAtualizar");
                const modal = document.getElementById("myModal");
                const confirmarEnvio = document.getElementById("confirmarEnvio");

                // Quando clicar no botão Atualizar
                botaoAtualizar.addEventListener("click", function () {
                    modal.style.display = "block";
                });

                // Quando clicar em OK no modal
                confirmarEnvio.addEventListener("click", function () {
                    modal.style.display = "none";
                    form.submit(); // Agora envia o formulário
                });
            });
        </script>

    </form>
</div>
 
</body>
</html>
