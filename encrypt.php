<?php
require 'cryptolib.php'; 
// Chave de criptografia (256 bits = 32 caracteres)
$chave = "Aindaestachovendolaforaeaquifazt"; // Substitua por uma chave segura

// Texto original
$texto_original = "234";
echo "Texto Criptografado: " . $texto_original . "<br>";

// Criptografar
$texto_criptografado = criptografar($texto_original, $chave);
echo "Texto Criptografado: " . $texto_criptografado . "<br>";

// Descriptografar
$texto_descriptografado = descriptografar($texto_criptografado, $chave);
echo "Texto Descriptografado: " . $texto_descriptografado . "<br>";

?>

