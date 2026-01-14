<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coletar Coordenadas</title>
    <style>
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

        /* Estilo para o texto de carregamento */
        .loading-text {
            display: none; /* Inicialmente oculto */
            text-align: center;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    <h3>Coletar Coordenadas de Localização</h3>
    <label for="localizacao">Você está no local onde a atividade é desenvolvida ou parcialmente desenvolvida? </label>
    <select id="localizacao" name="localizacao" required>
        <option value="Sim">Sim</option>
        <option value="Não">Não</option>
    </select>
    <p id="coordenadas">Coordenadas: </p>
    <div id="spinner" class="spinner"></div>
    <p id="loadingText" class="loading-text">Obtendo coordenadas...</p>

    <script>
        // Referências aos elementos do DOM
        const spinner = document.getElementById('spinner');
        const loadingText = document.getElementById('loadingText');
        const coordenadasElement = document.getElementById('coordenadas');

        // Função para obter as coordenadas
        function obterCoordenadas(posicao) {
            const latitude = posicao.coords.latitude;
            const longitude = posicao.coords.longitude;

            // Oculta o spinner e o texto de carregamento
            spinner.style.display = 'none';
            loadingText.style.display = 'none';

            // Exibe as coordenadas na tela
            coordenadasElement.textContent = `Coordenadas: Latitude ${latitude}, Longitude ${longitude}`;
        }

        // Função para tratar erros
        function erroAoObterLocalizacao(erro) {
            let mensagemErro;
            switch(erro.code) {
                case erro.PERMISSION_DENIED:
                    mensagemErro = "Permissão negada pelo usuário.";
                    break;
                case erro.POSITION_UNAVAILABLE:
                    mensagemErro = "Localização indisponível.";
                    break;
                case erro.TIMEOUT:
                    mensagemErro = "Tempo esgotado ao tentar obter a localização.";
                    break;
                case erro.UNKNOWN_ERROR:
                    mensagemErro = "Erro desconhecido.";
                    break;
            }

            // Oculta o spinner e o texto de carregamento
            spinner.style.display = 'none';
            loadingText.style.display = 'none';

            // Exibe a mensagem de erro
            coordenadasElement.textContent = `Erro: ${mensagemErro}`;
        }

        // Função para verificar a seleção e coletar coordenadas
        function verificarSelecao() {
            const selecao = document.getElementById('localizacao').value;

            if (selecao === "Sim") {
                // Exibe o spinner e o texto de carregamento
                spinner.style.display = 'block';
                loadingText.style.display = 'block';
                coordenadasElement.textContent = "Coordenadas: ";

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(obterCoordenadas, erroAoObterLocalizacao);
                } else {
                    // Oculta o spinner e o texto de carregamento
                    spinner.style.display = 'none';
                    loadingText.style.display = 'none';
                    coordenadasElement.textContent = "Geolocalização não é suportada por este navegador.";
                }
            } else {
                // Oculta o spinner e o texto de carregamento
                spinner.style.display = 'none';
                loadingText.style.display = 'none';
                coordenadasElement.textContent = "Coordenadas: N/A (opção 'Não' selecionada)";
            }
        }

        // Adiciona um listener ao select para verificar a seleção
        document.getElementById('localizacao').addEventListener('change', verificarSelecao);
    </script>
</body>
</html>