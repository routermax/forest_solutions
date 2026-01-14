<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
require 'cryptolib.php'; // ou use include 'crypto.php';
$chave = "Aindaestachovendolaforaeaquifazt";



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
                            contato.nome AS nome_contato, d.nome AS nome_responsavel, entidade.nome AS nome_entidade,
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
            $sql_produtos = "SELECT ps.nome_produto_servico, a.nome, remocao_florestal
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
    <title>Visualização de Solução</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            background-color: #f9f9f9;
            color: #333;
            pointer-events: none; /* Desabilita a edição */
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .botoes-inferiores {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .botao-fixo {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
        table {
            border-collapse: collapse; /* Faz as bordas colapsarem em uma única borda */
            width: 100%; /* Largura da tabela */
        }

        td {
            border: 1px solid black; /* Borda de 1px sólida e preta */
            padding: 8px; /* Espaçamento interno das células */
            text-align: left; /* Alinhamento do texto */
        }
        #mostrarTCLEBtn {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #mostrarTCLEBtn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
     <?php
        include 'termo_de_uso_download.php';
    ?>
    <div class="form-container">
        <!-- Botão Voltar -->
        <a href="mostra_solucoes.php">
            <button class="botao-fixo botao-voltar">Voltar</button>
        </a>        
        <center><a target="_blank"><img src="gp-logo-1.jpg" width="150" height="100" /></a></center>
        <h2>Visualização de Questionário</h2>
<div style="display: flex; justify-content: center; margin: 20px 0;">
    <button id="mostrarTCLEBtn" onclick="mostrarTCLE()">Mostrar TCLE</button>
</div>
        <div class="form-group">
            <label for="origem">Origem:</label>
            <input type="text" id="origem" name="origem" value="<?= htmlspecialchars($solucao['origem']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="data_coleta">Data/Hora de coleta:</label>
            <input type="text" id="data_coleta" name="data_coleta" value="<?= htmlspecialchars($solucao['data_coleta']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="data_coleta">Coordenadas da coleta:</label>
            <input type="text" id="coordenadas" name="coordenadas" value="<?= htmlspecialchars($solucao['coordenadas']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="entidade">Responsável:</label>
            <input type="text" id="nome_responsavel" name="nome_responsavel" value="<?= htmlspecialchars($solucao['nome_responsavel']) ?>" readonly />
        </div>

        <!-- Seção A: Identificação e Localização -->
        <h3>A) Identificação e Localização</h3>

        <div class="form-group">
            <label for="entidade">1) Nome da entidade:</label>
            <input type="text" id="entidade" name="entidade" value="<?= htmlspecialchars($solucao['nome_entidade']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="entidade">1.1) Nome do contato da entidade:</label>
            <input type="text" id="nome_contato" name="nome_contato" value="<?= htmlspecialchars($solucao['nome_contato']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="familias_beneficiadas">2) Quantas famílias estão envolvidas?:</label>
            <input type="text" id="familias_beneficiadas" name="familias_beneficiadas" value="<?= htmlspecialchars($solucao['familias_beneficiadas']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="nome_territorio">3) Categoria fundiária (velha):</label>
            <input type="text" id="tipo_territorio" name="tipo_territorio" value="<?= htmlspecialchars($solucao['tipo_territorio']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="nome_territorio">3.1) Nome do território (velha):</label>
            <input type="text" id="nome_territorio" name="nome_territorio" value="<?= htmlspecialchars($solucao['nome_territorio']) ?>" readonly>
        </div>

        <!-- Territorios -->
        <?php $indiceT = 1?>
        <div class="form-group">
            <label for="Territorios">3) Categoria fundiária (nova):</label>
            <table id="tabela-territorios">
            
            <?php foreach ($territorios as $territorio): ?>
                <?php echo '<tr><td>';?>
                <label style="font-weight: bold;" for="territorio">Territorio <?=$indiceT?> :</label>
                <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($territorio['tipo']) ?>" readonly>
                <label for="nome">Nome :</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($territorio['nome']) ?>" readonly>
                <?php $indiceT++; ?>
                <?php echo '<tr></td>';?>
            <?php endforeach; ?>
            
            </table>
        </div>

        <div class="form-group">
            <label for="Estado">4) Estado:</label>
            <input type="text" id="Estado" name="Estado" value="<?= htmlspecialchars($solucao['estado']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="Municipio">5) Município:</label>
            <input type="text" id="Municipio" name="Municipio" value="<?= htmlspecialchars($solucao['cidade']) ?>" readonly>
        </div>

        <!-- Produtos/Serviços -->
        <?php $indice = 1?>
        <div class="form-group">
            <label for="ProdutosServiços">6) Produtos/Serviços:</label>
            <table id="tabela-atividades">
            
            <?php foreach ($produtos as $produto): ?>
                <?php echo '<tr><td>';?>
                <label style="font-weight: bold;" for="atividade">Atividade <?=$indice?> :</label>
                <input type="text" id="ProdutosServiços" name="ProdutosServiços" value="<?= htmlspecialchars($produto['nome']) ?>" readonly>
                <label for="produtoServico">Produto/Serviço :</label>
                <input type="text" id="ProdutosServiços" name="ProdutosServiços" value="<?= htmlspecialchars($produto['nome_produto_servico']) ?>" readonly>
                <label for="remocaoFlorestal">Remoção florestal :</label>
                <input type="text" id="ProdutosServiços" name="ProdutosServiços" value="<?= htmlspecialchars($produto['remocao_florestal']) ?>" readonly>
                <?php $indice++; ?>
                <?php echo '<tr></td>';?>
            <?php endforeach; ?>
            
            </table>
        </div>
        
        <!-- Seção B: Infraestrutura -->
        <h3>B) Infraestrutura</h3>
        <div class="form-group">
            <label for="internet_qualidade">7) Qualidade de internet:</label>
            <input type="text" id="internet_qualidade" name="internet_qualidade" value="<?= htmlspecialchars($solucao['internet_qualidade']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="energia_qualidade">8) Qualidade de energia:</label>
            <input type="text" id="energia_qualidade" name="energia_qualidade" value="<?= htmlspecialchars($solucao['energia_qualidade']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="energia_fonte">9) Fonte de energia:</label>
            <input type="text" id="energia_fonte" name="energia_fonte" value="<?= htmlspecialchars($solucao['energia_fonte']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="agua_qualidade">10) Qualidade da água:</label>
            <input type="text" id="agua_qualidade" name="agua_qualidade" value="<?= htmlspecialchars($solucao['agua_qualidade']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="agua_fonte">11) Fonte de água:</label>
            <input type="text" id="agua_fonte" name="agua_fonte" value="<?= htmlspecialchars($solucao['agua_fonte']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="acesso_meio">12) Meio de acesso:</label>
            <input type="text" id="acesso_meio" name="acesso_meio" value="<?= htmlspecialchars($solucao['acesso_meio']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="acesso_qualidade">13) Qualidade de acesso:</label>
            <input type="text" id="acesso_qualidade" name="acesso_qualidade" value="<?= htmlspecialchars($solucao['acesso_qualidade']) ?>" readonly>
        </div>

        <h3>C) Tecnificação e Cultura</h3>

        <div class="form-group">
            <label for="conflitos_locais">14) Capacitação implementada:</label>
            <input type="text" id="capacitacao_implementada" name="capacitacao_implementada" value="<?= htmlspecialchars($solucao['capacitacao_implementada']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="conflitos_locais">15) Tecnologia iplementada:</label>
            <input type="text" id="tecnologia_implementada" name="tecnologia_implementada" value="<?= htmlspecialchars($solucao['tecnologia_implementada']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="conflitos_locais">16) Estratégia implementada:</label>
            <input type="text" id="estrategia_implementada" name="estrategia_implementada" value="<?= htmlspecialchars($solucao['estrategia_implementada']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="conflitos_locais">17) Engajamento comunitário:</label>
            <input type="text" id="engajamento_comunitario" name="engajamento_comunitario" value="<?= htmlspecialchars($solucao['engajamento_comunitario']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="conflitos_locais">18) Preservação cultural:</label>
            <input type="text" id="preservacao_cultural" name="preservacao_cultural" value="<?= htmlspecialchars($solucao['preservacao_cultural']) ?>" readonly />
        </div>

        <!-- Seção C: Desafios e Ameaças -->
        <h3>D) Desafios e Ameaças</h3>
        <div class="form-group">
            <label for="conflitos_locais">19) Conflitos locais:</label>
            <input type="text" id="conflitos_locais" name="conflitos_locais" value="<?= htmlspecialchars($solucao['conflitos_locais']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="vontade_mudanca">20) Vontade de mudança:</label>
            <input type="text" id="vontade_mudanca" name="vontade_mudanca" value="<?= htmlspecialchars($solucao['vontade_mudanca']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="dependencia_finaceira_externa">21) Dependência financeira externa:</label>
            <input type="text" id="dependencia_finaceira_externa" name="dependencia_finaceira_externa" value="<?= htmlspecialchars($solucao['dependencia_finaceira_externa']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="mudanca_meio_ambiente">22) Mudanças no meio ambiente:</label>
            <input type="text" id="mudanca_meio_ambiente" name="mudanca_meio_ambiente" value="<?= htmlspecialchars($solucao['mudanca_meio_ambiente']) ?>" readonly>
        </div>

        <div class="form-group">
            <label for="desafios">23) Desafios:</label>
            <textarea id="desafios" name="desafios" readonly><?= htmlspecialchars($solucao['desafios']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="melhorias">24) Melhorias sugeridas:</label>
            <textarea id="melhorias" name="melhorias" readonly><?= htmlspecialchars($solucao['melhorias']) ?></textarea>
        </div>

        <h3>E) Indicadores de sucesso (Ambientais e Socioeconomicos)</h3>

        <div class="form-group">
            <label for="mudanca_meio_ambiente">25) Benefícios:</label>
            <?php
                // Divide a string em um array usando ';' como delimitador
                $beneficios = explode(';', $solucao['beneficiosfinal']);

                // Remove espaços em branco no início e no final de cada texto
                $beneficios = array_map('trim', $beneficios);

                // Itera sobre o array e cria um input para cada benefício
                foreach ($beneficios as $beneficio) {
                    if (!empty($beneficio)) { // Verifica se o benefício não está vazio
                        if ($beneficio == 'Outro') {
                            echo '<input type="text" class="beneficio-input" value="Outro: ' . htmlspecialchars($solucao['beneficiooutro']) . '" readonly /><br>';
                        } else {
                            echo '<input type="text" class="beneficio-input" value="' . htmlspecialchars($beneficio) . '" readonly /><br>';
                        }
                    }
                }
            ?>
        </div>

        <div class="form-group">
            <label for="mudanca_meio_ambiente">26) Renda suficiente:</label>
            <input type="text" id="renda" name="renda" value="<?= htmlspecialchars($solucao['renda']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="mudanca_meio_ambiente">27) Beneficio Ambiental:</label>
            <input type="text" id="beneficioAmbiental" name="beneficioAmbiental" value="<?= htmlspecialchars($solucao['beneficioambiental']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="mudanca_meio_ambiente">28) Regeneracao de áreas degradadas:</label>
            <input type="text" id="regeneracao" name="regeneracao" value="<?= htmlspecialchars($solucao['regeneracao']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="aumentodiversidade">29) Aumento da diversidade:</label>
            <input type="text" id="aumentodiversidade" name="aumentodiversidade" value="<?= htmlspecialchars($solucao['aumentodiversidade']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="reducaoEmissoes">30) Reducao de Emissoes:</label>
            <input type="text" id="reducaoEmissoes" name="reducaoEmissoes" value="<?= htmlspecialchars($solucao['reducaoemissoes']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="educacaambiental">31) Conscientização ambiental:</label>
            <input type="text" id="educacaambiental" name="educacaambiental" value="<?= htmlspecialchars($solucao['educacaambiental']) ?>" readonly />
        </div>

        <div class="form-group">
            <label for="lideranca">32) Maioria da lideranças:</label>
            <input type="text" id="lideranca" name="lideranca" value="<?= htmlspecialchars($solucao['lideranca']) ?>" readonly />
        </div>

    </div>
</body>
</html>