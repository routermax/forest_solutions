<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Solução</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Cadastrado com sucesso !</h2>
    </div>
</body>
</html>