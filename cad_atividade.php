<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Entidades</title>
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
    <title>Formulário Moderno</title>
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
        #limparFormulario {
            background-color: red;
            color: white;
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
    <a href="dashboard.php">
        <button class="botao-fixo botao-voltar">Voltar</button>
    </a>
    <div class="form-container">
    <h2>Cadastro de Atividades</h2>
    <form action="cadastro_atividade.php" method="post" class="form-group"">
        <input type="hidden" name="submit_cadastrar" value="1">
        <h3>A) Informações da atividade</h3>

        <label for="nome">1) Nome da Atividade:</label>
        <input type="text" id="nome" name="nome"  required>
        
        <label for="metodo">2) Método:</label>
        <input type="text" id="metodo" name="metodo">

        <label for="descricao">3) Descrição:</label>
        <textarea id="descricao" name="descricao" ></textarea><br>
      
        <label for="categoria">4) Categoria:</label>
        <input type="text" id="categoria" name="categoria">       

        <label for="sub_categoria">5) Sub-categoria:</label>
        <input type="text" id="sub_categoria" name="sub_categoria">  
        
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const form = document.querySelector("form");
                const botaoLimpar = document.getElementById("limparFormulario");

                // Limpar o formulário e o localStorage ao clicar no botão
                botaoLimpar.addEventListener("click", function () {
                    localStorage.removeItem("formData");
                    console.log("Formulário Limpo !!!");
                    form.reset();
                });
            });
        </script>

        <br><br><br>

    <!-- Container para os botões inferiores -->
    <div class="botoes-inferiores">
        <!-- Botão Cadastrar com ícone ✔ -->
        <button id="botaoCadastrar" class="botao-fixo" type="submit">✔ Cadastrar</button>

        <!-- Botão Limpar Formulário com ícone ✖ -->
        <button id="limparFormulario" class="botao-fixo">✖ Limpar Formulário</button>
    </div>
    </form>
</div>
</body>
</html>
