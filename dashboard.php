<!DOCTYPE html>
<?php

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forest Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Esconde ambas as versões por padrão */
        .mobile-view, .desktop-view {
            display: none;
        }

        /* Estilos específicos para versão desktop */
        @media (min-width: 1024px) {
            .desktop-view {
                display: flex;
            }
        }

        /* Estilos específicos para versão mobile */
        @media (max-width: 1023px) {
            .mobile-view {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- Versão Mobile -->
    <div class="mobile-view w-full max-w-sm p-6 bg-white shadow-lg rounded-xl text-center">
        <center><a  target="_blank"><img src="gp-logo-1.jpg" width="150" height="100" /></a></center>
        <h1 class="text-2xl font-bold text-green-600 mb-6">Forest Solutions</h1>

        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_entidade.php" class="w-4/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700">
                🛠️ Cadastro de Entidade
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_entidades.php" class="w-1/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700 text-center">
                🔍 
            </a>
        </div>

        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_atividade.php" class="w-4/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700">
                🛠️ Cadastro de Atividades
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_atividades.php" class="w-1/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700 text-center">
                🔍 
            </a>
        </div>

        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_produto.php" class="w-4/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700">
                🛠️ Cadastro de Produtos
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_produtos.php" class="w-1/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700 text-center">
                🔍 
            </a>
        </div>

        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_solucoes.php" class="w-4/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700">
                ✏️ Questionário
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_solucoes.php" class="w-1/5 bg-green-600 text-white font-semibold text-lg py-4 rounded-lg mb-4 shadow-md hover:bg-green-700 text-center">
                🔍
            </a>
        </div>
        <br>
        <div class="flex justify-center gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/logout.php" class="bg-red-600 text-white font-semibold text-xl py-3 px-6 rounded-lg shadow-md hover:bg-red-700 flex items-center justify-center space-x-2">
                <i class="fas fa-sign-out-alt"></i><!-- Ícone de saída do Font Awesome -->
                <span>Sair</span>
            </a>
        </div>
    </div>

    <!-- Versão Desktop -->
    <div class="desktop-view w-full max-w-3xl p-10 bg-white shadow-lg rounded-xl text-center">
        <center><a  target="_blank"><img src="gp-logo-1.jpg" width="200" height="150" /></a></center>
        <h1 class="text-4xl font-bold text-green-600 mb-6">Forest Solutions</h1>

        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_entidade.php" class="w-4/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700">
                🛠️ Cadastro de Entidade
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_entidades.php" class="w-1/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700 text-center">
                🔍 
            </a>
        </div>
        <br>
        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_atividade.php" class="w-4/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700">
                🛠️ Cadastro de Atividades
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_atividades.php" class="w-1/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700 text-center">
                🔍 
            </a>
        </div>
        <br>
        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_produto.php" class="w-4/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700">
                🛠️ Cadastro de Produtos
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_produtos.php" class="w-1/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700 text-center">
                🔍 
            </a>
        </div>
        <br>
        <div class="flex gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/cad_solucoes.php" class="w-4/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700">
                ✏️ Questionário
            </a>
            <a href="https://pesquisa.am/tabelas/forest_solutions/mostra_solucoes.php" class="w-1/5 bg-green-600 text-white font-semibold text-xl py-6 rounded-lg shadow-md hover:bg-green-700 text-center">
                🔍
            </a>
        </div>
        <br>
        <div class="flex justify-center gap-4">
            <a href="https://pesquisa.am/tabelas/forest_solutions/logout.php" class="bg-red-600 text-white font-semibold text-xl py-3 px-6 rounded-lg shadow-md hover:bg-red-700 flex items-center justify-center space-x-2">
                <i class="fas fa-sign-out-alt"></i><!-- Ícone de saída do Font Awesome -->
                <span>Sair</span>
            </a>
        </div>
    </div>

    <script>
        // Garante que apenas a versão correta seja exibida
        document.addEventListener("DOMContentLoaded", function () {
            const isMobile = window.innerWidth <= 1023;
            document.querySelector(".mobile-view").style.display = isMobile ? "block" : "none";
            document.querySelector(".desktop-view").style.display = isMobile ? "none" : "block";
        });
    </script>

</body>
</html>
