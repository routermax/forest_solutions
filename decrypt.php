<?php
require 'cryptolib.php';
// Chave de criptografia (256 bits = 32 caracteres)
$chave = "Aindaestachovendolaforaeaquifazt"; // Substitua por uma chave segura

// Texto original
$texto_original = "w/+6z0XYvseh6RCSceMoXMAKwUNspbttmmzxKCCk6cg=";
echo "Texto Criptografado: " . $texto_original . "<br>";

// Criptografar
$texto_criptografado = $texto_original;

// Descriptografar
$texto_descriptografado = descriptografar($texto_criptografado, $chave);
echo "Texto Descriptografado: " . $texto_descriptografado . "<br>";

?>

