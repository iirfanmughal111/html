<?php

namespace ExtendMediaUpload\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;
use ExtendMediaUpload\Entity;
use ExtendMediaUpload\Service;

require __DIR__ . '/../../bunny.php';

class Media extends XFCP_Media {

    public function actionSubmit(ParameterBag $params) {


        return parent::actionSubmit($params);
    }
    
    

    public function actionCategorydata() {



        $media = $this->em()->create('EWR\Medio:Media');

        $viewParams = [
            'categories' => $this->getCategoryRepo()->findCategory()->fetch()
        ];

        return $this->view('ExtendMediaUpload\Media', 'EMU_uploadedit', $viewParams);
    }

    public function actionPoopup() {



        return $this->view('ExtendMediaUpload\Media', 'EMU_orsperator');
    }

    public function actionMedia(ParameterBag $params) {



        $tagFinder = $this->finder('EWR\Medio:Media')->where('uniq_code', '!=', '0');

        $total = $tagFinder->total();

//        echo '<pre>';
//        
//        var_dump($total);exit;



        $viewParams = [
            'uniq_code' => $tagFinder->limit(15)->order('RAND()')->fetch(),
            'per_page' => $total,
        ];

//        echo '<pre>';
//        var_dump($viewParams);exit;



        $data = parent::actionMedia($params);
        $data->setParams($viewParams);

        return $data;
    }
    
    public function actionSaveData(ParameterBag $params) {
         
        $this->assertPostOnly();
        $form = $this->formAction();

        $media = $this->em()->create('EWR\Medio:Media');

        $input = $this->filter([
            'media_title' => 'STR',
            'category_id' => 'UINT',
        ]);

        $input['media_description'] = $this->plugin('XF:Editor')->fromInput('media_description');
//
//        if ($input['category_id'] == ' ') {
//            throw new \XF\PrintableException(\xf::phrase('Select Category'));
//        }
//        if ($input['media_title'] == "") {
//            throw new \XF\PrintableException(\xf::phrase('Fill Title'));
//        }
//        if ($input['media_description'] == "") {
//
//            throw new \XF\PrintableException(\xf::phrase('Fill Description'));
//        }


        $media->category_id = 1;
        $media->media_title = $input['media_title'];
        $media->media_description = $input['media_description'];
        $media->service_id = 15;

        $path = \XF::getRootDirectory().$this->getAbstractDepositvideoPath();

        if (file_exists($path)) {

            $option = \XF::options();

            $bunny = new \BunnyCDNStorage($option->apikey, $option->videolibraryid, $option->collectionid, $option->Domainbunnynet);

//            var_dump($bunny);exit;
            $videoid = $bunny->createvideoobject($input['media_title']);

            $res = $bunny->uploadFile($path, $videoid);

            $duration_in_seconds = $bunny->getvideotime($videoid);

            $media->media_duration = $duration_in_seconds;
            $media->service_val1 = $videoid;
            $media->service_val2 = $videoid;
            $media->uniq_code = $videoid;
            
            
            $this->App()->fs()->delete('data://bunny/video');

            if ($res == 200) {

                $form->basicEntitySave($media, $input);

                $form->run();

                return $this->redirect($this->buildLink('ewr-medio'));
            }
        } else {
             
      
            throw new \XF\PrintableException(\xf::phrase('Video not exit Uplaod  again...'));
        }
    }

    public function getAbstractDepositvideoPath() {

        return '/data/bunny/video';
    }

    public function actionFileData(ParameterBag $params) {
  
 
      
        if ($this->request->getFile('upload', false, false)) {

            $upload = $this->request->getFile('upload', false, false);

          
            $check_ext = $upload->isvideo();
            
         

            if (!$check_ext) {

                echo"File is not uploaded So upload only video...";
                exit;

//                throw new \XF\PrintableException(\xf::phrase('Upload only video'));
            } else {


                $file = $upload->getFileWrapper();

//                $extension = strtolower($file->getExtension());

                $fileDataPath = 'data://bunny/video';

                $sourceFile = $file->getFilePath();

                \XF\Util\File::copyFileToAbstractedPath($sourceFile, $fileDataPath);

                exit;
            }
        }
    }


}
