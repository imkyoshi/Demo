<?php
namespace app\Helpers;

class Cookies
{
    private $encryptionKey = 's0PoPnnVj4poBwZJGboiyD9Gxc/gXPWxnRf16AtcHLM=';

    public function initializeSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '', // Use your domain here
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
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

    public function encrypt($data)
    {
        $iv = substr($this->encryptionKey, 0, 16); // Use the first 16 bytes of the key as IV
        return openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }

    public function decrypt($data)
    {
        $iv = substr($this->encryptionKey, 0, 16); // Use the first 16 bytes of the key as IV
        return openssl_decrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, $iv);
    }
}
