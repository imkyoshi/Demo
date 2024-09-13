<?php
namespace app\Helpers;

class Cookies
{
    private string $encryptionKey = 's0PoPnnVj4poBwZJGboiyD9Gxc/gXPWxnRf16AtcHLM=';

    public function initializeSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '', // Use your domain here
                'secure' => true,
                'httponly' => true,
                'same-site' => 'Strict'
            ]);

            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            session_regenerate_id(true);
        }

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            session_unset();
            session_destroy();
        }
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public function encrypt($data): string
    {
        $cipher = 'aes-256-cbc';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encryptedData = openssl_encrypt($data, $cipher, $this->encryptionKey, 0, $iv);
        if ($encryptedData === false) {
            throw new \RuntimeException('Encryption failed.');
        }
        return base64_encode($encryptedData . '::' . $iv);
    }


    public function decrypt($data): false|string
    {
        $cipher = 'aes-256-cbc';
        list($encryptedData, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, $cipher, $this->encryptionKey, 0, $iv);
    }
}
