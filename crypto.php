<?php
// Criando uma instância da classe Crypto
$key = "gP3squ1s@"; // Use a mesma chave
$crypto = new Crypto($key);
class Crypto
{
    private $key;

    public function __construct($key)
    {
        $this->key = hash('sha256', $key, true);
    }

    public function encrypt($text)
    {
        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($text, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $ciphertext);
    }

    public function decrypt($encrypted_text)
    {
        $data = base64_decode($encrypted_text);
        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);
        return openssl_decrypt($ciphertext, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $iv);
    }
}

?>

