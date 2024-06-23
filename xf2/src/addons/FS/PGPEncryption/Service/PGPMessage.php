<?php

namespace FS\PGPEncryption\Service;

require __DIR__ . '../../openpgpphp/vendor/autoload.php';
require __DIR__ . '../../openpgpphp/lib/openpgp.php';
require __DIR__ . '../../openpgpphp/lib/openpgp_crypt_rsa.php';

class PGPMessage extends \XF\Service\AbstractService {

    public $encryptedMessage = null;
    public $Message = null;

    public function encryptMessage($publicKey) {

        
        $this->checkPublickey($publicKey);

        $plain_text_string = trim(\XF::generateRandomString(10));

        $key = \OpenPGP_Message::parse(\OpenPGP::unarmor($publicKey, "PGP PUBLIC KEY BLOCK"));

        $data = new \OpenPGP_LiteralDataPacket($plain_text_string, array('format' => 'u', 'filename' => 'stuff.txt'));
        $encrypted = \OpenPGP_Crypt_Symmetric::encrypt($key, new \OpenPGP_Message(array($data)));

        $enc = \OpenPGP::enarmor($encrypted->to_bytes(), "PGP MESSAGE");

        $this->Message = $plain_text_string;
        $this->encryptedMessage = $enc;

        return [htmlspecialchars($this->encryptedMessage), $this->Message];
    }

    public function checkPublickey($publickey){
        
 
        $header = strpos($publickey, $this->header());
        
        $footer = strpos($publickey, $this->footer());
        
        if($header==False || $footer==False ){
            
            throw new \XF\PrintableException(\XF::phrase('fs_invalid_public_key'));
        }
        
        
    
    }
    public function getMessage() {

        return $this->Message;
    }
  
     public function footer() {
       
       return  'END PGP PUBLIC KEY BLOCK';
    
  }

   public function header() {
       
       return 'BEGIN PGP PUBLIC KEY BLOCK';
    
  }
  
  public function CheckEncryptMsg($random_message,$message){
      
        if (strcmp($random_message, trim($message)) !== 0) {


            throw new \XF\PrintableException(\XF::phrase('fs_invalid_encrypt_message'));
        }
      
  }


}
