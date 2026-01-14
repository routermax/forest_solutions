<?php


function criptografar($texto, $chave)
{
    // Verifica se a chave tem o tamanho correto (256 bits = 32 bytes)
    if (strlen($chave) !== 32) {
        throw new Exception("A chave deve ter 32 caracteres (256 bits).");
    }

    // Gera um vetor de inicialização (IV) aleatório
    $iv = random_bytes(16); // 16 bytes para AES-256-CBC

    // Criptografa o texto usando AES-256-CBC
    $texto_criptografado = openssl_encrypt($texto, 'aes-256-cbc', $chave, OPENSSL_RAW_DATA, $iv);

    // Combina o IV com o texto criptografado e codifica em base64
    return base64_encode($iv . $texto_criptografado);
}

function descriptografar($texto_criptografado, $chave)
{
    // Verifica se a chave tem o tamanho correto (256 bits = 32 bytes)
    if (strlen($chave) !== 32) {
        throw new Exception("A chave deve ter 32 caracteres (256 bits).");
    }

    // Decodifica o texto criptografado de base64
    $texto_criptografado = base64_decode($texto_criptografado);

    // Extrai o IV (os primeiros 16 bytes)
    $iv = substr($texto_criptografado, 0, 16);

    // Extrai o texto criptografado real
    $texto_criptografado = substr($texto_criptografado, 16);

    // Descriptografa o texto usando AES-256-CBC
    return openssl_decrypt($texto_criptografado, 'aes-256-cbc', $chave, OPENSSL_RAW_DATA, $iv);
}

?>

