<?php

namespace FS\Escrow\Job;

use XF\Job\AbstractJob;
class WithDraw extends AbstractJob
{

    public function run($maxRunTime)
	{
            $this->transferMoney();
              
         return $this->complete();
          
	}
        

	public function getStatusMessage()
	{
		return \XF::phrase('exporting_job...');
	}

	public function canCancel()
	{
		return false;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
    
    

    public  function transferMoney()
    {
        $app = \XF::app();

        $conditions = [
            ['request_state'=>'visible'],
            ['request_state'=>'deleted'],
            
            ];
        $requests = $app->finder('FS\Escrow:WithdrawRequest')->where('is_proceed',0)->whereOr($conditions)->fetch();
        foreach($requests as $req){
            $status = 0;
            
            if ($req->request_state=='visible'){
                $response  = $this->requestTransferFunds($req);
                if ($response && $response['Status'] == 'Success') { 
                    $req->Transaction->fastUpdate('transaction_type','Funds Transferd');
                    if ($req->Transaction){
                        /** @var Escrow $notifier */
                        $notifier = $this->app->notifier('FS\Escrow:Transaction\TransactionAlert', $req->Transaction);
                        $notifier->escrowWithdrawConfirmAlert(1);
                    }  
                } 
                else if ($response['Status'] == 'Error'){ 
                    $errorMessage = implode(' ',preg_split('/(?=[A-Z])/', $response['Error']['Code']));
                    // $address = '';
                    // if ($response['Error']['Code']=='ValidateAddressFailedError' || $response['Error']['Code']=='AddressNotFoundError'){
                    //     $address.= $response['Error']['Address'];
                    // }else if ($response['Error']['Code']=='InsufficientFundsError'){
                    //     $address.= $response['Error']['Addresses'][0];
                    // };
                    $this->updateTransaction($req,$errorMessage,0);
                }
            }
            if($req->request_state=='deleted'){
                $this->updateTransaction($req,'Withdraw Rejected',0);
            }
            $req->fastUpdate('is_proceed',1);

        }
        
        
    }


    public  function requestTransferFunds($req){
        $app = \XF::app();
        $ch = curl_init();
        
        $data = json_encode(array(
            "RequestId"=>$req->user_id.'_'.$req->User->username,
            "SourceAddress"=> $req->address_from,
            'DestinationAddress'=>$req->address_to, //FS
            "Currency"=> $app->options()->fs_escrow_currency,
            "Amount"=> $req->amount,
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
    public function updateTransaction($req,$message,$status){
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
        $transaction = $req->Transaction;
        $user = $req->User;
        
        if ($user->deposit_amount){
            $dec_user_amount = $escrowService->decrypt($user->deposit_amount);
            $user_amount = $dec_user_amount['amount'];
        }else{
            $user_amount =0;
        }

        $newAmount = $user_amount+$req->amount;

        $enc_user_amount = $escrowService->encrypt($escrowService->encyptData($newAmount));
        $enc_trans_amount = $escrowService->encrypt($escrowService->encyptData($req->amount));


        $user->fastUpdate('deposit_amount',$enc_user_amount);
        
        $transaction->bulkSet([
            'transaction_amount'=> $enc_trans_amount,
            'transaction_type'=> $message,
            'current_amount'=> $enc_user_amount,
            'status'=> 1,
            'created_at'=> (\XF::$time),

        ]);
        $trans = $transaction->save();
        if ($trans){
            /** @var Escrow $notifier */
       //     $notifier = $this->app->notifier('FS\Escrow:Transaction\TransactionAlert', $transaction);
         //   $notifier->escrowWithdrawConfirmAlert($status);
        }  
        
    }
}