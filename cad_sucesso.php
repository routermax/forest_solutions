<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Concluído</title>
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos para o container de sucesso */
        .success-container {
            text-align: center;
            margin-top: 100px;
        }

        .success-message {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-top: 20px;
        }

        .success-description {
            font-size: 16px;
            color: #6c757d;
            margin-top: 10px;
        }

        /* Estilos para o botão Voltar */
        .btn-voltar {
            background-color: #16a34a; /* Verde */
            color: white;
            font-weight: bold;
            font-size: 1.25rem; /* 20px */
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px; /* Espaçamento acima do botão */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .btn-voltar:hover {
            background-color: #15803d; /* Verde mais escuro ao passar o mouse */
        }

        /* Centraliza o botão */
        .btn-container {
            text-align: center;
            margin-top: 40px; /* Espaçamento entre o container de sucesso e o botão */
        }
    </style>
</head>
<body>
    <div class="success-container">
        <center><a  target="_blank"><img src="gp-logo-1.jpg" width="150" height="100" /></a></center>
        <!-- Mensagem de sucesso -->
        <div class="success-message">
            Cadastrado com Sucesso!
        </div>
        <!-- Descrição adicional -->
        <div class="success-description">
            Seu cadastro foi concluído com sucesso. Agradecemos por participar de nossa pesquisa.
        </div>
    </div>
    <br>
    <?php 
        session_start();
        if (isset($_SESSION['usuario'])) {
            echo '
        <div class="btn-container">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_solucoes.php" class="btn-voltar">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>';
        }
    ?>
</body>
</html>