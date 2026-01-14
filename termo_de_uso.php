<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termo de Consentimento</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1002;
        }

        .popup-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 30px;
            position: relative;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .popup-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .popup-header h2 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }

        .popup-content {
            line-height: 1.6;
            color: #34495e;
        }

        .popup-content h3 {
            color: #2c3e50;
            margin-top: 25px;
            font-size: 18px;
        }

        .popup-footer {
            margin-top: 30px;
            text-align: center;
        }

        .accept-section {
            display: flex;
            align-items: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #3498db;
            border-radius: 4px;
            margin-right: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;
        }

        .custom-checkbox.checked {
            background-color: #3498db;
        }

        .custom-checkbox.checked::after {
            content: '✓';
            color: white;
            font-size: 14px;
        }

        #acceptCheckbox {
            display: none;
        }

        .accept-text {
            font-weight: bold;
            color: #2c3e50;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 0 10px;
        }

        .btn-primary {
            background-color: #27ae60;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2ecc71;
        }

        .btn-primary:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }

        .btn-secondary {
            background-color: #e74c3c;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #c0392b;
        }

        .researcher-info {
            background-color: #e8f4fc;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #7f8c8d;
        }

        @media (max-width: 600px) {
            .popup-container {
                width: 95%;
                padding: 20px;
            }
            
            .btn {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-container">
            <button class="close-btn" id="closePopupBtn" hidden>×</button>
            <div class="popup-header">
                <h2>TERMO DE CONSENTIMENTO LIVRE E ESCLARECIDO (TCLE)</h2>
            </div>
            <div class="popup-content">
                <div class="researcher-info">
                    <p><strong>Título da Pesquisa:</strong> Caracterização e mapeamento das entidades e suas cadeias produtivas na Amazônia.</p>
                    <p><strong>Pesquisador Responsável:</strong> <span id="nome-pesquisador"></span></p>
                    <p><strong>Setor:</strong> Coordenação de Pesquisa</p>
                    <p><strong>Instituição:</strong> Greenpeace Brasil</p>
                    <p><strong>Contato do Pesquisador:</strong> <span id="contato-pesquisador"></p>
                </div>

                <h3>1. Introdução</h3>
                <p style="text-align: justify;">Você está sendo convidado (a) a participar da pesquisa intitulada "Caracterização e mapeamento das entidades, atividades e/ou cadeias produtivas na Amazônia’’, conduzida pela Coordenação de Pesquisa do Greenpeace Brasil. Antes de decidir se deseja participar, é importante que você compreenda os objetivos, procedimentos, benefícios, compartilhamento e segurança da informação do estudo, descritos abaixo.</p>

                <h3>2. Objetivo da Pesquisa e produtos</h3>
                <p style="text-align: justify;">Esta pesquisa tem como objetivo obter informações uniformes e mapear as atividades sustentáveis desenvolvidas no bioma Amazônia. Seus resultados subsidiarão a campanha global do Greenpeace denominada <strong>Forest Solutions (Soluções da Floresta), que visa a propagação de projetos/atividades consideradas as soluções que ajudam a combater a mudança do clima, ao mesmo tempo que protegem e valorizam os povos indígenas e as comunidades locais e seus territórios.</strong> A campanha também tem como objetivo dar visibilidade aos trabalhos desenvolvidos pelas organizações na Amazônia e apoiará a destinação de recursos financeiros exclusivos e diretos para as populações que manejam e ajudam a manter a floresta em pé, por meio do enquadramento em critérios que valorizam a floresta e sua sociobiodiversidade, além de evidenciar empreendimentos modelos no bioma por meio de um mapa interativo de uso público.</p>
                <br>
                <p style="text-align: justify;">Sua participação contribuirá para o avanço do conhecimento sobre as atividades provenientes da floresta consideradas soluções. Estima-se que os benefícios impactarão diretamente as populações tradicionais, indígenas, quilombolas e demais comunidades envolvidas, além de se somar às políticas ambientais de uso, preservação e conservação da floresta amazônica.</p>

                <h3>3. Procedimentos</h3>
                <p style="text-align: justify;">Caso aceite participar, você responderá a um questionário com perguntas relacionadas às informações básicas da entidade em que faz parte, seus produtos e formas de produção, perguntas sobre infraestruturas, tecnificação, cultura, desafios e ameaças e também sobre os indicadores de sucesso do empreendimento.</p>
                <br>
                <p style="text-align: justify;">As perguntas subsidiarão a seleção dos empreendimentos modelos que constarão no mapa público, que será feita a partir da soma da pontuação das perguntas, que deverá ser majoritariamente positiva às respostas do questionário, baseando-se nos critérios pré estabelecidos das atividades consideradas <strong>“soluções da floresta”</strong> desenvolvidos pelo Greenpeace Brasil.</p> 
                <br>
                <p style="text-align: justify;">Respostas majoritariamente negativas, não constarão no mapa público, e não terão qualquer tipo de uso, julgamento ou prejuízo, sendo excluídas posteriormente. Ressalta-se que caso haja adequações no decorrer do tempo do projeto (3 anos), as entidades adaptadas aos critérios poderão novamente refazer o questionário e constar no mapa caso a pontuação seja majoritariamente positiva.</p>
                <br>
                <p style="text-align: justify;">Também serão coletados dados pessoais, como: nome completo, função e meio de contato, sendo e-mail e telefone. Esses dados serão coletados para identificação da pessoa que está respondendo o questionário como representante da associação, cooperativa ou outra organização coletiva, e para que possamos entrar em contato, caso seja necessário, para esclarecimentos sobre o questionário, respostas dadas ou qualquer outro tema relacionado à pesquisa, respeitando sempre as disposições trazidas pela Lei Geral de Proteção de Dados (Lei Federal nº. 13.709/18 - LGPD) e de todos os regulamentos emitidos, no presente e no futuro, pela Autoridade Nacional de Proteção de Dados (“ANPD”).</p>
                <br>
                <p style="text-align: justify;">O tempo estimado para responder é de aproximadamente 10 – 20 minutos.</p>

                <h3>4. Compartilhamento</h3>
                <p style="text-align: justify;">O produto final da pesquisa será um mapa digital interativo, com informações gerais sobre produção local, famílias envolvidas, localização entre outras. O mapa constará na rede oficial do Greenpeace Brasil para todas as audiências interessadas, e contemplará apenas as informações da entidade participante. Dados pessoais como nome, e-mails, telefone e outros não farão parte do pacote a ser publicizado.</p>

                <h3>5. Armazenamento e segurança da informação</h3>
                <p style="text-align: justify;">Suas respostas serão completamente voluntárias e serão tratadas a nível de entidade representada, em conformidade com os requisitos da LGPD. Os dados serão armazenados em um banco de dados seguro da Coordenação de Pesquisa do Greenpeace Brasil, localizada em Manaus – AM, pelo período necessário para o objetivo que justifica-se a sua coleta ou até que você, como titular, exerça os seus direitos de exclusão. Quando os dados pessoais coletados não forem mais necessários, nós os excluiremos de forma segura ou os tornaremos anônimos na base de dados para que não possam ser associados ou reconectados ao seu titular.</p>

                <h3>6. Voluntariedade e Direito de Retirada</h3>
                <p style="text-align: justify;">A participação nesta pesquisa é totalmente voluntária. Você pode recusar-se a participar ou desistir a qualquer momento, sem qualquer prejuízo, sendo que o Greenpeace Brasil atenderá a solicitação em até 15 (quinze) dias, a contar do seu contato, conforme item 7, abaixo.</p>

                <h3>7. Contato para Dúvidas</h3>
                <p style="text-align: justify;">Caso tenha dúvidas sobre a pesquisa ou queira excluir as suas respostas e não participar mais da pesquisa, entre em contato com a Coordenação de Pesquisa do Greenpeace Brasil pelo e-mail <a href="mailto:pesquisa@greenpeace.org">pesquisa@greenpeace.org</a>.</p>

                <h3>8. Consentimento</h3>
                <p style="text-align: justify;">Declaro que fui informado(a) sobre os objetivos da pesquisa, procedimentos, benefícios, compartilhamento, armazenamento e segurança da informação, e concordo em participar voluntariamente e ter as informações da entidade representante divulgadas conforme a finalidade da pesquisa.</p>

                <div class="accept-section">
                    <label class="checkbox-container" id="checkboxLabel">
                        <input type="checkbox" id="acceptCheckbox">
                        <span class="custom-checkbox" id="customCheckbox"></span>
                        <span class="accept-text">Eu, <span id="nome-contato"></span>, aceito participar da pesquisa</span>
                    </label>
                </div>
            </div>
            <div class="popup-footer">
                <button class="btn btn-secondary" id="declineBtn">Recusar</button>
                <button class="btn btn-primary" id="acceptBtn" disabled>Aceitar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popupOverlay = document.getElementById('popupOverlay');
            const closePopupBtn = document.getElementById('closePopupBtn');
            const acceptCheckbox = document.getElementById('acceptCheckbox');
            const customCheckbox = document.getElementById('customCheckbox');
            const acceptBtn = document.getElementById('acceptBtn');
            const declineBtn = document.getElementById('declineBtn');
            const checkboxLabel = document.getElementById('checkboxLabel');

            // Mostrar popup automaticamente
            popupOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';

            // Fechar popup
            closePopupBtn.addEventListener('click', closePopup);

            function closePopup() {
                popupOverlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            // Controle do checkbox
            acceptCheckbox.addEventListener('change', function() {
                if(this.checked) {
                    customCheckbox.classList.add('checked');
                    acceptBtn.disabled = false;
                } else {
                    customCheckbox.classList.remove('checked');
                    acceptBtn.disabled = true;
                }
            });

            // Permitir clicar em qualquer lugar do label
            checkboxLabel.addEventListener('click', function() {
                acceptCheckbox.checked = !acceptCheckbox.checked;
                const event = new Event('change');
                acceptCheckbox.dispatchEvent(event);
            });

            // Aceitar termos
            acceptBtn.addEventListener('click', function() {
                if(acceptCheckbox.checked) {
                    closePopup();
                    // Aqui você pode adicionar a lógica para prosseguir após a aceitação
                }
            });

            // Redirecionar para cad_recusado.php ao recusar
            declineBtn.addEventListener('click', function() {
                window.location.href = 'cad_recusado.php';
            });

            // Removido o listener que fecha ao clicar fora do popup
        });
    </script>
</body>
</html>