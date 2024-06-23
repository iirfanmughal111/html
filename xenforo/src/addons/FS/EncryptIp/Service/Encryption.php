<?php


namespace FS\EncryptIp\Service;

use XF\Service\AbstractService;


class Encryption extends AbstractService
{
    public function encrypt_ip($ip_val){
           
        $ciphering = $this->cipheringMethod();
           
        // Use OpenSSl Encryption method
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = $this->encryption_options();
        
        // Non-NULL Initialization Vector for encryption
        $encryption_iv = $this->encryption_iv();
        
        // Store the encryption key
        $encryption_key = $this->encryption_key();
        
        // Use openssl_encrypt() function to encrypt the data
        return openssl_encrypt($ip_val, $ciphering,
                    $encryption_key, $options, $encryption_iv);

        
    }

    public function decrypt_ip($encrypted_ip){
        
           // Store the cipher method
           $ciphering = $this->cipheringMethod();
           
           // Use OpenSSl Encryption method
           $iv_length = openssl_cipher_iv_length($ciphering);
           $options = $this->encryption_options();
           
           // Store the encryption key
           $encryption_key = $this->encryption_key();
    
           // Non-NULL Initialization Vector for decryption
           $encryption_iv = $this->encryption_iv();

           // Use openssl_decrypt() function to decrypt the data
          return openssl_decrypt ($encrypted_ip, $ciphering, 
                   $encryption_key, $options, $encryption_iv);         
           
       }

        protected function cipheringMethod(){
            return  "AES-128-CTR";
        }

        protected function encryption_iv(){
            return  "9876543211011121";
        }

        protected function encryption_key(){
            return  "FS_ForumSoution";
        }
        protected function encryption_options(){
            return  0;
        }
    
}