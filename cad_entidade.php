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
        if ($_SERVER["REQUEST_METHOD"] != "POST")       
            $estado = "";
            elseif($limpado != "Sim") 
                $estado = $_POST["Estado"];
                else
                    $estado = "";
                        
	                
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
    <h2>Cadastro de Entidades</h2>
    <form action="cadastro.php" method="post" class="form-group"">
        <input type="hidden" name="submit_cadastrar" value="1">
        <h3>A) Informações da entidade</h3>

        <label for="responsavel">1) Nome da Entidade:</label>
        <input type="text" id="nome" name="nome"  required>
        <br>

        <label for="cnpj">2) CNPJ:</label>
        <input type="text" id="cnpj" name="cnpj">

        <label for="historico">3) Histórico:</label>
        <textarea id="historico" name="historico" ></textarea><br>

        <script>
            function toggleOutraCategoria() {
                var select = document.getElementById("categoria");
                var outroInput = document.getElementById("outra_categoria");
                if (select.value === "Outra") {
                    outroInput.style.display = "block";
                } else {
                    outroInput.style.display = "none";
                }
            }
        </script>

        <label for="categoria">4) Categoria:</label>
        <select id="categoria" name="categoria" onchange="toggleOutraCategoria(this)" required>
            <option value="" disabled selected>Selecione uma opção</option>
            <option value="Associação">Associação</option>
            <option value="Cooperativa">Cooperativa</option>
            <option value="OSCIP">OSCIP</option>
            <option value="Outra">Outros</option>
        </select><br>
        <input type="text" id="outra_categoria" name="outra_categoria" placeholder="Qual o nome da categoria?" style="display: none;"><br>

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
    
        <label for="Estado">5) Estado:</label>
        <select id="Estado" name="Estado" required>
                <option value="" disabled selected>Selecione uma opção</option>
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
        
     
        <label for="Municipio">6) Municipio:</label>
        <select id="Municipio" name="Municipio" required>
                <option value="" disabled selected>Selecione uma opção</option>    
        </select>   
     
        
        <label for="cep">7) CEP:</label>
        <input type="text" id="cep" name="cep">       

        <label for="endereco">8) Endereço:</label>
        <input type="text" id="endereco" name="endereco">  
        
        <label for="dados_complementares">9) Dados Complementares:</label>
        <textarea id="dados_complementares" name="dados_complementares" ></textarea><br>

        <label> 10) Contatos</label>

        <div id="contatos-container">
            <table id="tabela-contatos">
                <tr>
                    <td>
                        <div class="contatos">
                            <label for="nome_1">10.1) Nome:</label>
                            <input type="text" id="nome_1" name="nome_1">   
                    
                            <label for="funcao_contato_1">10.2) Função:</label>
                            <input type="text" id="funcao_1" name="funcao_1">   
                    
                            <label for="tipo_contato_1">10.3) Tipo:</label>
                            <select id="tipo_1" name="tipo_1" required>
                                <option value="" disabled selected>Selecione uma opção</option>
                                <option value="Telefone">Telefone</option>
                                <option value="Whatsapp">Whatsapp</option>
                                <option value="E-mail">E-mail</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Site">Site</option>
                            </select>
                    
                            <label for="contato_1">10.4) Contato:</label>
                            <input type="text" id="contato_1" name="contato_1">  

                            <button type="button" class="remover-contatos" style="display: none;" onclick="removerContatos(this)">Remover</button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <button type="button" onclick="adicionarContatos()">Adicionar</button>

        <script>
            let contador = 1; // Controla o número de inputs

            function adicionarContatos() {
                contador++; // Incrementa o contador

                let tabela = document.getElementById("tabela-contatos");
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
                let botaoRemover = novaLinha.querySelector(".remover-contatos");
                botaoRemover.style.display = "inline-block";

                // Adiciona a nova linha à tabela
                tabela.appendChild(novaLinha);
            }

            function removerContatos(botao) {
                let tabela = document.getElementById("tabela-contatos");
                if (tabela.rows.length > 1) { // Mantém pelo menos uma linha
                    botao.closest("tr").remove();
                    atualizarIndices(); // Atualiza os nomes após remoção
                }
            }

            function atualizarIndices() {
                let linhas = document.querySelectorAll("#tabela-contatos tr");
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
