<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<style>
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
    $sql = "SELECT cd_produto_servico, nome_produto_servico, tipo_produto_servico, sub_tipo_produto_servico, descricao_produto_servico, sistema_produtivo, atividade.nome FROM produto_servico               
            INNER JOIN atividade on produto_servico.cd_atividade =   atividade.cd_atividade ORDER BY nome  ";

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
<div class="container-fluid mt-4">
    <h2 class="text-center">Lista de Produtos</h2>
    
    <table id="tabela" class="display table table-bordered" width="100%">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Produto</th>
                <th>Atividade</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Sub Tipo</th>
                <th>Sistema Produtivo</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $linha) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($linha['cd_produto_servico']); ?></td>
                    <td><?php echo htmlspecialchars($linha['nome_produto_servico']); ?></td>
                    <td><?php echo htmlspecialchars($linha['nome']); ?></td>
                    <td><?php echo htmlspecialchars($linha['descricao_produto_servico']); ?></td>
                    <td><?php echo htmlspecialchars($linha['tipo_produto_servico']); ?></td>
                    <td><?php echo htmlspecialchars($linha['sub_tipo_produto_servico']); ?></td>
                    <td><?php echo htmlspecialchars($linha['sistema_produtivo']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#tabela').DataTable({
            "pageLength": 50,
            "autoWidth": false, 
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Portuguese-Brasil.json"
            }
        });
    });
</script>

</body>
</html>