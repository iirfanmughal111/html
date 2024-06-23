<?php

namespace FS\ThreadThumbnail\Service;

use XF\Entity\Thread;
use XF\Entity\User;

class Upload extends \XF\Service\AbstractService 
{

    protected $Thread;
    protected $error = null;
    protected $fileName;
    protected $extension;
    protected $throwErrors = true; 

    public function __construct(\XF\App $app, Thread $Thread) {
        
        parent::__construct($app);
        
        $this->setThread($Thread);
    }

    protected function setThread(Thread $Thread) {
       

        $this->Thread = $Thread;
        
    }

    public function getError() {
        return $this->error;
    }

    public function setSvgFromUpload($upload) {

         $this->fileName = $upload->getTempFile();
         $this->extension=$upload->getExtension();
         
         return true;
       
    }



    public function uploadSvg() 
    {           
            $this->Thread->fastUpdate('thumbnail_ext',$this->extension);

            $dataFile = $this->Thread->getAbstractedCustomThumbnailSvgPath($this->extension);
            \XF\Util\File::copyFileToAbstractedPath($this->fileName, $dataFile);
                         
        return true;
    }
    
    protected function throwException(\Exception $error)
    {
        if ($this->throwErrors)
        {
            throw $error;
        }
        else
        {
            return false;
        }
    }
}