<?php

class BunnyCDNStorage {

    public $apiAccessKey = '';
    public $libraryid = '';
    Public $collectionid = '';
    public $domainname = '';

    /**
      Initializes a new instance of the BunnyCDNStorage class
     */
    public function __construct($apiAccessKey, $libraryid, $collectionid, $domainname) {

        $this->apiAccessKey = $apiAccessKey;
        $this->libraryid = $libraryid;
        $this->collectionid = $collectionid;
        $this->domainname = $domainname;
    }

    public function createvideoobject($title) {



        if ($title == ' ') {

            throw new \XF\PrintableException(\xf::hrase('First set  video title'));
        } else {


            $post = [
                'title' => $title,
                'collectionId' => $this->collectionid,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->domainname . "/library/" . $this->libraryid . "/videos");
            ;
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "AccessKey:  $this->apiAccessKey",
                "Content-Type: application/*+json"
            ));

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

            $output = curl_exec($ch);
            $curlError = curl_errno($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
            $json = json_decode($output, true);
            
          
//            var_dump($curlError);exit;

            

            if ($curlError) {


                throw new BunnyCDNStorageException("An unknown error has occured during the request. Status code: " . $curlError);
            }

            if ($responseCode == 401) {
                throw new BunnyCDNStorageAuthenticationException($this->apiAccessKey);
            } else if ($responseCode < 200 || $responseCode > 299) {
                throw new BunnyCDNStorageException("An unknown error has occured during the request. Status code: " . $responseCode);
            }
            return $json["guid"];
            
        }
    }
    public function uploadFile($localpath, $videoid) {
        $fileStream = fopen($localpath, "r");

        if ($fileStream == false) {
            throw new BunnyCDNStorageException("The local file could not be opened.");
        }
        $dataLength = filesize($localpath);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->domainname . "/library/" . $this->libraryid . "/videos/" . $videoid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "AccessKey: $this->apiAccessKey",
           'Content-Type: application/*+json'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_INFILE, $fileStream);
        curl_setopt($ch, CURLOPT_INFILESIZE, $dataLength);

        $output = curl_exec($ch);
        $curlError = curl_errno($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $json = json_decode($output, true);
        
        
        
 

        if ($curlError) {
            throw new BunnyCDNStorageException("An unknown error has occured during the request. Status code: " . $curlError);
        }

        if ($responseCode == 401) {
            throw new BunnyCDNStorageAuthenticationException($this->apiAccessKey);
        } else if ($responseCode < 200 || $responseCode > 299) {
            throw new BunnyCDNStorageException("An unknown error has occured during the request. Status code: " . $responseCode);
        }
        return $json['statusCode'];
    }

    public function getvideotime($videoid) {


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->domainname . "/library/" . $this->libraryid . "/videos/" . $videoid);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "AccessKey: $this->apiAccessKey",
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        $curlError = curl_errno($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $json = json_decode($output, true);

       
        

        if ($curlError) {
            throw new BunnyCDNStorageException("An unknown error has occured during the request. Status code: " . $curlError);
        }

        if ($responseCode == 401) {
            throw new BunnyCDNStorageAuthenticationException($this->apiAccessKey);
        } else if ($responseCode < 200 || $responseCode > 299) {
            throw new BunnyCDNStorageException("An unknown error has occured during the request. Status code: " . $responseCode);
        }
        
         
       return $json['length'];
        
    }

}

/**
 * An exception thrown by BunnyCDNStorage
 */
class BunnyCDNStorageException extends Exception {

    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": {$this->message}\n";
    }

}

/**
 * An exception thrown by BunnyCDNStorage caused by authentication failure
 */
class BunnyCDNStorageAuthenticationException extends BunnyCDNStorageException {

    public function __construct($accessKey) {
        parent::__construct("Authentication failed for storage zone  with access key '{$accessKey}'.");
    }

    public function __toString() {
        return __CLASS__ . ": {$this->message}\n";
    }

}

?>
