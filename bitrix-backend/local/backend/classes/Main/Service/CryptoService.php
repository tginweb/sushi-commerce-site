<?php

namespace Main\Service;

class CryptoService extends BaseService
{
    const METHOD = 'aes-256-ctr';
    private $key;

    function __construct()
    {
        $this->key = $this->container->getConfigService()->get('MAIN.CRYPTO_KEY');
    }

    /**
     * Encrypts (but does not authenticate) a message
     *
     * @param string|array $message - plaintext message
     * @param boolean $encode - set to TRUE to return a base64-encoded
     * @return string (raw binary)
     */
    public function encrypt($message)
    {
        $encode = true;

        if (is_array($message)) {
            $message = json_encode($message);
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $this->key,
            OPENSSL_RAW_DATA
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce . $ciphertext);
        }
        return $nonce . $ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     *
     * @param string $message - ciphertext message
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public function decrypt($message, $unserialize = false)
    {
        $encoded = true;

        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                return false;
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $this->key,
            OPENSSL_RAW_DATA
        );

        return $unserialize ? json_decode($plaintext, true) : $plaintext;
    }
}


