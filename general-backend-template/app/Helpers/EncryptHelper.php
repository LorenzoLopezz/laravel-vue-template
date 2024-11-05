<?php
namespace App\Helpers;

class EncryptHelper
{

  public static function encrypt($id): string
  {
    $key = env('APP_KEY_ENCRYPTION');
    $iv  = env('APP_IV_ENCRYPTION');
    // Usamos openssl_encrypt para cifrar el ID
    $encryptedId = openssl_encrypt($id, 'aes-128-cbc', $key, 0, $iv);
    return base64_encode($encryptedId);  // Codificamos el resultado en Base64 para facilitar su manejo
  }

  /**
   * Desencripta un ID encriptado usando OpenSSL con AES-128-CBC
   *
   * @param string $encryptedId
   * @return string
   */
  public static function decrypt(string $encryptedId): string
  {
    $key = env('APP_KEY_ENCRYPTION');
    $iv  = env('APP_IV_ENCRYPTION');
    // Decodificamos de Base64 y luego desencriptamos usando openssl_decrypt
    $encryptedId = base64_decode($encryptedId);
    return openssl_decrypt($encryptedId, 'aes-128-cbc', $key, 0, $iv);
  }
}
