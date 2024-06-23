<?php

$dir = __DIR__;
require ($dir . '/src/XF.php');

    XF::start($dir);
    $app = XF::setupApp('XF\Pub\App');
    
    file_put_contents("bithid.text",json_decode( file_get_contents('php://input') ));
    $data = json_decode( file_get_contents('php://input'));



   
    if ($data && !$data->Error){
        
    $record = $app->em()->create('FS\Escrow:TransactionRecord');
    $user_id = explode("_",$data->ExternalId);
    $record->bulkSet([
        'user_id'=> $user_id[1],
        'TxId'=> $data->TxId,
        'Amount'=> $data->Amount,

    ]);
   $record->save(); 
   
   $app->jobManager()->enqueueUnique('deposit_bithide', 'FS\Escrow:VerifyDeposit', [], false);
  // $app->jobManager()->runUnique('deposit_bithide',20);
       return true;
    }else{
        var_dump ($data->Error);
    }