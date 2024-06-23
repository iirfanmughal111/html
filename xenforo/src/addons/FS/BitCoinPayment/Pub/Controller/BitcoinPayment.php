<?php

namespace FS\BitCoinPayment\Pub\Controller;
use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;



class BitcoinPayment extends AbstractController
{

    
    public function actionIndex(ParameterBag $params)
    {
      
        if (\Xf::visitor()->user_id!=0){
          return $this->view('FS\BitCoinPayment', 'fs_BitCoinPayment_deposit');
        }
        return $this->redirect($this->buildLink('login'));
 
    }

    public function encryptionService(){
      return \xf::app()->service('FS\EncryptIp:Encryption');
 
    }
    public function actionTest(ParameterBag $params)
    {
     

    $encryption = $this->encryptionService(); 
    $a = $encryption->encrypt_ip( "39.35.65.232");
    // var_dump($a);echo '<br>';
    $id = 20;
    for ($i=1; $i<32;$i++){
      echo '<br>';
      echo ($i.'='.$i);
    }
    exit;
    var_dump(substr(hash('sha256', mt_rand()  . $id . time()), 0, 20) );

   
exit;


      $inputString = $this->request->getIp();
      $md5Hash = hash("md5", $inputString);

      echo '<pre>';
     // $hexa = 0x7447beef;
      $hexa = 0x2723827e;
      
     // $hexa = 0x2723bace;
      $ip =  long2ip($hexa);
      var_dump($md5Hash);exit;
      return $this->view('XF:Xenforo', 'test');
    }
    // public function actionSave1(ParameterBag $params)
    // {
    //   $data = [];
    //   $viewParams = ['status' => 1234];
    //   return ($viewParams);
    //   return $this->view('XF:Xenforo', 'test_save' , $viewParams);
    // }


    // public function actionSave()
    // {
    
    //     $this->bunnyAccessKey = 'c917b5bc-895e-4f58-a2b8b8314d28-bf45-4549';
    //     $libId = 160894;
    //     $videoId = 'c396e540-b8d4-4dfd-8c49-da33eb8df103'; //complete
    //    // $videoId = '8db9dbbc-33e7-4690-b7bf-448c586160d3'; //pending
        
    //     $curl = curl_init();

    //     curl_setopt($curl, CURLOPT_URL, "https://video.bunnycdn.com/library/" . $libId . "/videos/" . $videoId);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($curl, CURLOPT_HTTPHEADER, [
    //         'AccessKey: ' . $this->bunnyAccessKey,
    //     ]);

    //     $server_output = curl_exec($curl);

    //     // $resCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    //     // $this->CheckRequestError($resCode);

    //     curl_close($curl);
    //     $getVideoRes = json_decode($server_output, true);
       
    //     $status = (isset($getVideoRes["encodeProgress"]) && $getVideoRes["encodeProgress"] >= 63) ? true : false;
    
        
    //   $viewParams = [
    //     'status' => $status
    //   ];    
    //   $this->setResponseType('json');
      
    //  $view = $this->view();
		//  $view->setJsonParam('data', $viewParams);
		//  return $view; 
    
    //   // return $this->redirect($this->buildLink('upgrade'));

      

    //   // return $viewParams;
    //    //return $this->view('FS:BitCoinPayment\VideoStatus', '', $viewParams);
    // }


    


   





    
}