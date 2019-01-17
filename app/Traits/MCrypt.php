<?php
/**
 * Created by PhpStorm.
 * User: khaled
 * Date: 17/01/19
 * Time: 21:40
 */

namespace App\Traits;


trait MCrypt{

    static function decrypt($cipher_text_base64){
        # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
        # convert a string into a key
        # key is specified using hexadecimal
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

        # create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);

        $cipher_text_dec = base64_decode($cipher_text_base64);

        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($cipher_text_dec, 0, $iv_size);

        # retrieves the cipher text (everything except the $iv_size in the front)
        $cipher_text_dec = substr($cipher_text_dec, $iv_size);

        # may remove 00h valued characters from end of plain text
        $plain_text_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
            $cipher_text_dec, MCRYPT_MODE_CBC, $iv_dec);
        $plain_text_dec  = preg_replace('/[\x00]/', '', $plain_text_dec );

        $json = json_decode($plain_text_dec);
        return $json;
    }
    static function encrypt($json){
        $plain_text = json_encode($json);

        # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
        # convert a string into a key
        # key is specified using hexadecimal
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

        # create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        $cipher_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
            $plain_text, MCRYPT_MODE_CBC, $iv);

        # prepend the IV for it to be available for decryption
        $cipher_text = $iv . $cipher_text;

        # encode the resulting cipher text so it can be represented by a string
        $cipher_text_base64 = base64_encode($cipher_text);

        return $cipher_text_base64;
    }
}