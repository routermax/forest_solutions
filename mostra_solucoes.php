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
?>
    <style>
        #myModal {
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 400px;
            text-align: center;
        }
         #myModal2 {
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 400px;
            text-align: center;
        }
        #responsavelSelect {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        #generatedLink {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
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
         .container {
        max-width: 100%;
        width: 100%;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<?php
// Configuração do banco de dados
$host = "192.168.59.11";  // IP do banco de dados
$dbname = "forest_solutions";       // Nome do banco
$username = "postgres";   // Usuário do banco
$password = "GPp3squ1s@"; // Senha do banco

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query para buscar todas as entidades e contatos associados
    $sql = "select t0.cd_solucao, origem, t1.nome as responsavel, t2.nome as entrevistado, t3.nome as entidade, t6.nome as atividade, 
                t5.nome_produto_servico as produto,  t0.uf, t0.municipio, t5.cd_produto_servico as cd_produto_servico, familias_beneficiadas,
                nome_territorio, internet_qualidade, energia_qualidade, energia_fonte, agua_fonte,
                acesso_meio, acesso_qualidade, capacitacao_implementada, capacitacao_interesse,
                tecnologia_implementada, tecnologia_interesse, estrategia_implementada, estrategia_interesse,
                engajamento_comunitario, preservacao_cultural, conflitos_locais, vontade_mudanca,
                dependencia_finaceira_externa, mudanca_meio_ambiente, desafios, melhorias from solucao as t0
            inner join contato as t1
            on cd_responsavel = t1.cd_contato
            inner join contato as t2
            on t0.cd_contato = t2.cd_contato
            inner join entidade as t3
            on t3.cd_entidade = t0.cd_entidade
            inner join produto_servico_solucao as t4
            on t4.cd_solucao = t0.cd_solucao
            inner join produto_servico as t5
            on t5.cd_produto_servico = t4.cd_produto_servico
            inner join atividade as t6
            on t5.cd_atividade = t6.cd_atividade
            order by cd_solucao desc";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Entidades e Contatos</title>
    
    <!-- Importando DataTables e Bootstrap -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <!-- Botão Voltar -->
    <a href="dashboard.php">
        <button class="botao-fixo botao-voltar">Voltar</button>
    </a>



    <script>
        
        // Função para abrir o modal1
        function abrirModal(tk1,tk2) {         
            // Exibe a menssagem de alerta
            document.getElementById('excluir_solucao').textContent  = 'Excluir o questionário ' +tk1+ ' inteiro ?';
            // Envia as chaves
            document.getElementById('excluir_solucao_botao').onclick = () => {
                window.location.href = `exclui_solucao.php?tk2=${tk2}`;
            };
            // Exibe o modal
            document.getElementById('myModal').style.display = 'block';
        }
        
        // Função para abrir o modal2
        function abrirModal2(tk1,tk2,tk3,tk4) {
            // Exibe a menssagem de alerta
            document.getElementById('excluir_produto').textContent  = 'Excluir o produto/serviço ' +tk2+ ' do questionário ' + tk1 + '?';
            // Envia as chaves
            document.getElementById('excluir_produto_botao').onclick = () => {
                window.location.href = `exclui_produto.php?tk3=${tk3}&tk4=${tk4}`;
            };
            // Exibe o modal
            document.getElementById('myModal2').style.display = 'block';
        }
        
        // Função para fechar o modal1
        function fecharModal() {
            document.getElementById('myModal').style.display = 'none';
        }
        
        // Função para fechar o modal2
        function fecharModal2(tk2) {
            document.getElementById('myModal2').style.display = 'none';
        }

    </script>

    <div class="container-fluid mt-4">
    <br>
    <br>

    <h1 class="text-center">Lista de entidades, produtos e serviços</h1>
    
    <table id="tabela" class="display table table-bordered" width="100%">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Ação</th>
                <th>Origem</th>
                <th>Responsável</th>
                <th>Entrevistado</th>
                <th>Entidade</th>
                <th>Estado</th>
                <th>Município</th>
                <th>Atividade</th>
                <th>Produto/Serviço</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $linha) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($linha['cd_solucao']); ?></td>
                    <td style="text-align: center; vertical-align: middle;">
                        <a href="mostra_solucao.php?tk1=<?php echo urlencode(criptografar($linha['cd_solucao'], $chave)); ?>" title="vizualizar">
                            <i class="fas fa-search"></i>
                        </a>&nbsp;
                        <?php if ($_SESSION['permissoes'] === 'total') : ?>
                                    <a href="editar_solucao.php?tk1=<?php echo urlencode(criptografar($linha['cd_solucao'], $chave)); ?>" title="editar">
                                        <i class="fas fa-edit"></i>
                                    </a>&nbsp;
                                    <a href="#" style="text-decoration: none;" onclick="abrirModal(
                                        '<?php echo htmlspecialchars($linha['cd_solucao']); ?>',
                                        '<?php echo urlencode(criptografar($linha['cd_solucao'], $chave)); ?>'
                                        )" title="excluir questionário">
                                        <i class="fas fa-trash" style="color: red;"></i>
                                    </a>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($linha['origem']); ?></td>
                    <td><?php echo htmlspecialchars($linha['responsavel']); ?></td>
                    <td><?php echo htmlspecialchars($linha['entrevistado']); ?></td>
                    <td><?php echo htmlspecialchars($linha['entidade']); ?></td>
                    <td><?php echo htmlspecialchars($linha['uf']); ?></td>
                    <td><?php echo htmlspecialchars($linha['municipio']); ?></td>
                    <td><?php echo htmlspecialchars($linha['atividade']); ?></td>
                    <td>                      
                        <?php if ($_SESSION['permissoes'] === 'total') : ?>
                            <a href="#" style="text-decoration: none;" onclick="abrirModal2(
                                '<?php echo htmlspecialchars($linha['cd_solucao']); ?>',
                             '<?php echo htmlspecialchars($linha['produto']); ?>',
                                '<?php echo urlencode(criptografar($linha['cd_solucao'], $chave)); ?>',
                                '<?php echo urlencode(criptografar($linha['cd_produto_servico'], $chave)); ?>'
                                )" title="excluir produto/serviço">
                                <i class="fas fa-trash" style="color: red;"></i>
                            </a>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($linha['produto']); ?>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

    <!-- Modal1 -->
    <div id="myModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); z-index: 1000; width: 90%; max-width: 500px;">
        <h3 id="excluir_solucao" ></h3>
        <button id="excluir_solucao_botao" onclick="">Excluir</button>
        <button onclick="fecharModal()">Cancelar</button>
    </div>

    <!-- Modal2 -->
    <div id="myModal2" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); z-index: 1000; width: 90%; max-width: 500px;">
        <h3 id ="excluir_produto"></h3>
        <button id="excluir_produto_botao" onclick="">Excluir</button>
        <button onclick="fecharModal2()">Cancelar</button>
    </div>

<script>
    $(document).ready(function() {
        $('#tabela').DataTable({
            "pageLength": 50,
            "autoWidth": false, 
            "responsive": true,
            "order": [[0, "desc"]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Portuguese-Brasil.json"
            }
        });
    });
</script>

</body>
</html>