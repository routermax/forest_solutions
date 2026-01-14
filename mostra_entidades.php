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
    $sql = "SELECT 
                e.cd_entidade, e.nome AS entidade, e.municipio, e.uf, e.historico, e.categoria, e.endereco, e.dados_complementares, e.cep, e.cnpj,
                c.cd_contato, c.nome AS nome, c.funcao, c.tipo, c.contato
            FROM entidade e
            LEFT JOIN contato_entidade ce ON e.cd_entidade = ce.cd_entidade
            LEFT JOIN contato c ON ce.cd_contato = c.cd_contato
            ORDER BY e.cd_entidade DESC";

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

    <!-- Modal -->
    <div id="myModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); z-index: 1000; width: 90%; max-width: 500px;">
        <h3>Selecione o Responsável</h3>
        <select id="responsavel" name="responsavel" required>
        </select>
        <br>
        <button id="gerarLinkBtn" onclick="gerarLink()" disabled>Gerar Link</button>
        <div id="linkContainer" style="margin-top: 20px; display: none;">
            <p>Link gerado:</p>
            <input type="text" id="generatedLink" readonly style="width: 300px;">
            <button onclick="copiarLink()">Copiar Link</button>
        </div>
        <button onclick="fecharModal()" style="margin-top: 10px;">Fechar</button>
    </div>
    <script>
        fetch('get_contatos_gp.php')
        .then(response => response.json())
        .then(data => {
            //console.log("Dados recebidos:", data);
            const responsavelSelect = document.getElementById("responsavel");

            // Limpa o select e adiciona uma opção padrão
            responsavelSelect.innerHTML = '<option value="" disabled selected>Selecione uma opção</option>';

            document.getElementById('responsavel').addEventListener('change', function() {
                const gerarLinkBtn = document.getElementById('gerarLinkBtn');
                // Habilita o botão apenas se um valor válido for selecionado (não vazio)
                gerarLinkBtn.disabled = this.value === '';
            });

            // Itera sobre os dados recebidos
            data.forEach(responsavel => {
                let option = document.createElement("option");

                // Define o valor da opção como o cd_contato
                option.value = responsavel.cd_contato;

                // Define o texto exibido como o nome
                option.textContent = responsavel.nome;

                // Adiciona a opção ao select
                responsavelSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Erro ao carregar contatos:", error));

        function limparModal() {
            // Reseta o select de responsáveis
            document.getElementById('responsavel').selectedIndex = 0;

            // Esconde o container do link gerado
            document.getElementById('linkContainer').style.display = 'none';
            document.getElementById('gerarLinkBtn').disabled = true;
            // Limpa o valor do input do link
            document.getElementById('generatedLink').value = '';
        }

        // Função para abrir o modal
        function abrirModal(tk1, tk2, tk3) {
            // Limpa os dados do modal
            limparModal();

            // Armazena os parâmetros em variáveis globais
            window.tk1 = tk1;
            window.tk2 = tk2;
            window.tk3 = tk3;

            // Exibe o modal
            document.getElementById('myModal').style.display = 'block';
        }

        // Função para abrir o modal2
        function abrirModal2(tk1,tk2) {         
            // Exibe a menssagem de alerta
            document.getElementById('excluir_entidade').textContent  = 'Excluir a entidade ' +tk2+ ' ?';
            // Envia as chaves
            document.getElementById('excluir_entidade_botao').onclick = () => {
                window.location.href = `exclui_entidade.php?tk2=${tk1}`;
            };
            // Exibe o modal
            document.getElementById('myModal2').style.display = 'block';
        }

        // Função para gerar o link com base no responsável selecionado
        function gerarLink() {
            // Obtém o responsável selecionado
            const responsavel = document.getElementById('responsavel').value;
            // Gera o link com os parâmetros e o responsável
            const link = `https://pesquisa.am/tabelas/forest_solutions/questionario.php?tk1=${window.tk1}&tk2=${responsavel}&tk3=${window.tk3}&responsavel=${responsavel}`;
            document.getElementById('gerarLinkBtn').disabled = true;
            // Exibe o link no input
            document.getElementById('generatedLink').value = link;
            document.getElementById('linkContainer').style.display = 'block';
        }

        // Função para fechar o modal
        function fecharModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        // Função para fechar o modal2
        function fecharModal2(tk2) {
            document.getElementById('myModal2').style.display = 'none';
        }

        // Função para copiar o link para a área de transferência
        function copiarLink() {
            const linkInput = document.getElementById('generatedLink');
            linkInput.select();
            document.execCommand('copy');
            alert('Link copiado para a área de transferência!');
            fecharModal();
        }
    </script>

<div class="container-fluid mt-4">
    <br>
    <br>

    <h1 class="text-center">Lista de Entidades e Contatos</h1>
    
    <table id="tabela" class="display table table-bordered" width="100%">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Ação</th>
                <th>Entidade</th>
                <th>CNPJ</th>
                <th>Categoria</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th>Endereço</th>
                <th>CEP</th>
                <th>Dados Complementares</th>
                <th>Contato</th>
                <th>Função</th>
                <th>Tipo</th>
                <th>Informação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $linha) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($linha['cd_entidade']); ?></td>                 
                    <td>
                        <a href="#" style="text-decoration: none;" onclick="abrirModal(
                            '<?php echo urlencode(criptografar($linha['cd_entidade'], $chave)); ?>',
                            '<?php echo urlencode(criptografar($linha['cd_contato'], $chave)); ?>',
                            '<?php echo urlencode(criptografar($linha['cd_contato'], $chave)); ?>'
                            )" title="gerar link">
                                <i class="fas fa-link"></i>
                        </a>
                        &nbsp;&nbsp;
                        <a href="add_contato.php?tk1=<?php echo urlencode(criptografar($linha['cd_entidade'], $chave)); ?>" title="adicionar contato">
                                <i class="fas fa-user"></i>
                        </a>
                        </a>&nbsp;
                        <?php if ($_SESSION['permissoes'] === 'total') : ?>
                                    <a href="edita_entidade.php?tk1=<?php echo urlencode(criptografar($linha['cd_entidade'], $chave)); ?>" title="editar">
                                        <i class="fas fa-edit"></i>
                                    </a>&nbsp;
                                    <a href="#" style="text-decoration: none;" onclick="abrirModal2(
                                        '<?php echo htmlspecialchars(criptografar($linha['cd_entidade'], $chave)); ?>',
                                        '<?php echo htmlspecialchars($linha['entidade']); ?>'
                                        )" title="excluir entidade">
                                        <i class="fas fa-trash" style="color: red;"></i>
                                    </a>
                        <?php endif; ?>
                    </td>
                    <td>
                    <?php echo htmlspecialchars($linha['entidade']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($linha['cnpj']); ?></td>
                    <td><?php echo htmlspecialchars($linha['categoria']); ?></td>
                    <td><?php echo htmlspecialchars($linha['municipio']); ?></td>
                    <td><?php echo htmlspecialchars($linha['uf']); ?></td>
                    <td><?php echo htmlspecialchars($linha['endereco']); ?></td>
                    <td><?php echo htmlspecialchars($linha['cep']); ?></td>
                    <td><?php echo htmlspecialchars($linha['dados_complementares']); ?></td>
                    <td><?php echo htmlspecialchars($linha['nome']); ?></td>
                    <td><?php echo htmlspecialchars($linha['funcao']); ?></td>
                    <td><?php echo htmlspecialchars($linha['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($linha['contato']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

    <!-- Modal1 -->
    <div id="myModal2" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); z-index: 1000; width: 90%; max-width: 500px;">
        <h3 id="excluir_entidade" ></h3>
        <button id="excluir_entidade_botao" onclick="">Excluir</button>
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