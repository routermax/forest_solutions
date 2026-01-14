<?php

function criptografar($texto, $chave)
{
    if (strlen($chave) !== 32) {
        throw new Exception("A chave deve ter 32 caracteres (256 bits).");
    }

    $iv = random_bytes(16);
    $texto_criptografado = openssl_encrypt($texto, 'aes-256-cbc', $chave, OPENSSL_RAW_DATA, $iv);
    $texto_criptografado_base64 = base64_encode($iv . $texto_criptografado);

    // Prepara o texto criptografado para ser seguro em URLs
    return prepararParaUrl($texto_criptografado_base64);
}

function descriptografar($texto_criptografado, $chave)
{
    if (strlen($chave) !== 32) {
        throw new Exception("A chave deve ter 32 caracteres (256 bits).");
    }

    // Reverte a substituição feita para a URL
    $texto_criptografado_base64 = reverterDaUrl($texto_criptografado);

    // Decodifica o texto criptografado de base64
    $texto_criptografado = base64_decode($texto_criptografado_base64);

    $iv = substr($texto_criptografado, 0, 16);
    $texto_criptografado = substr($texto_criptografado, 16);

    return openssl_decrypt($texto_criptografado, 'aes-256-cbc', $chave, OPENSSL_RAW_DATA, $iv);
}

function prepararParaUrl($texto_criptografado)
{
    return strtr($texto_criptografado, '+/=', '-_,');
}

function reverterDaUrl($texto_criptografado)
{
    return strtr($texto_criptografado, '-_,', '+/=');
}

?>

