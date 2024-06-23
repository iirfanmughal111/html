<?php

namespace FS\Escrow\Service\Escrow;

use XF\Mvc\FormAction;

class Bithide extends \XF\Service\AbstractService
{

    public function getAddress($user){
        
        if ($user->crypto_address && $user->crypto_address !=null){
            return $user->crypto_address;
        }
        
        $ch = curl_init();
        $data = json_encode(array(
            "ExternalId"=>$user->username.'_'.$user->user_id,
            "Currency"=> $this->app()->options()->fs_escrow_currency,
            "New"=>true,
            "ExpectedAmount"=> 0,
            "CallBackLink"=> ($this->app()->options()->fs_escrow_callback_link.'/bithide_callback.php'),
            "publickey"=> $this->app()->options()->fs_escrow_api,
        ));
        curl_setopt($ch, CURLOPT_URL,$this->app()->options()->fs_escrow_bit_base_url."/address/getaddress");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        $response = json_decode($server_output,true);
        if ($response && $response['Status'] == 'Success') { 
            $user->fastUpdate('crypto_address', ($response['Address']));
            return $response['Address'];
        } 
        else{
            if ($error){
                var_dump($error);
            }
        }
        exit;
        
    }
    
    public function getTransactionList()
    {
        $options = \XF::options();
        $ch = curl_init();
        $data = json_encode(array(
            "publickey"=> $options->fs_escrow_api,
        ));
        curl_setopt($ch, CURLOPT_URL,$options->fs_escrow_bit_base_url."/transaction/list");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($server_output,true);
        return $response;
   }
   public function transferFundsCall($req_id,$source,$destination,$amount){
        $app = \XF::app();
        $amountBtc = $this->convertUSDtoBTC($amount);
        $ch = curl_init();
        $data = json_encode(array(
            "RequestId"=>$req_id,
            "SourceAddress"=> $source,
            'DestinationAddress'=>$destination, 
            "Currency"=> $app->options()->fs_escrow_currency,
            "Amount"=> $amountBtc,
            "IsSenderCommission"=>true,
            "Comment"=> 'Funds transfer',
            "publickey"=> $app->options()->fs_escrow_api,
        ));
        curl_setopt($ch, CURLOPT_URL,$app->options()->fs_escrow_bit_base_url."/transaction/withdraw");
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($server_output,true);
        return $response;
    }

   public function requestTransferFunds($request){
        
        $app = \XF::app();
        $visitor = \XF::visitor();

         $escrowService =  \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
        $response = $this->transferFundsCall($request->req_id,$request->address_from,$request->address_to,$request->amount);
        
        if ( $response && $response['Status'] == 'Success') { 
            $request->Transaction->fastUpdate('transaction_type','Funds Transferd');

        }else{
            $errorMessage = implode(' ',preg_split('/(?=[A-Z])/', $response['Error']['Code']));
            $transaction = $request->Transaction;
         //   $transaction->fastUpdate('transaction_type',$errorMessage);
         $dec_trans_amount = $escrowService->decrypt($transaction->transaction_amount);
         $dec_curr_amount = $escrowService->decrypt($transaction->current_amount);
         $curr_amount = $dec_trans_amount['amount'] + $dec_curr_amount['amount'];
         $enc_curr_amount = $escrowService->encrypt($escrowService->encyptData($curr_amount));

            $transaction->bulkSet([
                'transaction_type'=> $errorMessage,
                'current_amount'=> $enc_curr_amount,
            ]);
            $transaction->save(); 
           
            if ($visitor->deposit_amount){
                $dec_user_amount = $escrowService->decrypt($visitor->deposit_amount);
                $final =  $dec_user_amount['amount']+$dec_trans_amount['amount'];
            }else{
                $final =  $dec_trans_amount['amount'];
            }
            $enc_user_amount = $escrowService->encrypt($escrowService->encyptData($final));
            $visitor->fastUpdate('deposit_amount',$enc_user_amount);

        }

    } 

    public function convertUSDtoBTC($amount){
        $curl = curl_init();
        $app = \XF::app();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $app->options()->fs_escrow_conversion_api.'?base=USD&amount='.$amount,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $serverResponse = curl_exec($curl);
        curl_close($curl);
        
        $response = json_decode($serverResponse);
        
        if ($response && $response->success==true){
            return $response->rates->BTC;
        }else{
             return false;
        }
    }

}