<?php

namespace FS\Escrow\Job;

use XF\Job\AbstractJob;
class VerifyDeposit extends AbstractJob
{

    public function run($maxRunTime)
	{

       
            $this->verifyDeposit();
              
             
         return $this->complete();
            
     
           
	}
        

	public function getStatusMessage()
	{
		return \XF::phrase('deposit...');
	}

	public function canCancel()
	{
		return false;
	}

	public function canTriggerByChoice()
	{
		return false;
	}
    
    public function verifyDeposit()
    {
        $bithideService = \xf::app()->service('FS\Escrow:Escrow\Bithide');
        $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
        
        $response = $bithideService->getTransactionList();
        
        $app = \XF::app();
        if ($response && $response['Status'] == 'Success') { 
            $app->db()->emptyTable('fs_escrow_bithide_transaction');
            foreach ($response['List'] as $key=>$entry){ 
                $temp_fail = json_decode($entry['FailReason']);
                
                $failed = 'null';
                if ($temp_fail!= NULL){
                    $failed = $temp_fail->Code;
                }
                $user_id = explode("_",$entry['ExternalId']);
                $BithideTransactionRecord = $app->em()->create('FS\Escrow:BithideTransactionRecord');
                $BithideTransactionRecord->bulkSet([
                    'Type'=> $entry['Type'],
                    'Date'=> $entry['Date'],
                    'TxId'=> $entry['TxId'] ? $entry['TxId'] : 'NULL',
                    'Cryptocurrency'=> $entry['Cryptocurrency'],
                    'MerchantId'=> $entry['MerchantId'],
                    'MerchantName'=> $entry['MerchantName'],
                    'InitiatorId'=> $entry['InitiatorId'] ? $entry['InitiatorId'] : 'null',
                    'Initiator'=> $entry['Initiator'] ? $entry['Initiator']  :  'null',
                    'Amount'=> $entry['Amount'] ? $entry['Amount'] : 0,
                    'AmountUSD'=> $entry['AmountUSD'] ? $entry['AmountUSD'] : 0,
                    'Rate'=> $entry['Rate'],
                    'Commission'=> $entry['Commission'],
                    'CommissionCurrency'=> $entry['Cryptocurrency'],
                    'SenderAddresses'=> $entry['SenderAddresses'],
                    'DestinationAddress'=> $entry['DestinationAddress'],
                    'ExternalId'=> $entry['ExternalId'] ? $entry['ExternalId'] : 'null',
                    'Comment'=> $entry['Comment'],
                    'Status'=> $entry['Status'],
                    'FailReason'=> $entry['FailReason'] ?  implode(' ',preg_split('/(?=[A-Z])/', $failed)) : 'null',
                    
                ]);
                  $BithideTransactionRecord->save();
                  $record = $app->finder('FS\Escrow:TransactionRecord')->where('status',0)->where('TxId',$BithideTransactionRecord->TxId)->fetchOne();
                    if ($record){   
                        $user = $record->User; 
                        if ($user->deposit_amount){
                            $dec_user_amount = $escrowService->decrypt($user->deposit_amount);
                            $user_amount = $dec_user_amount['amount'];
                        }else{
                            $user_amount = 0;
                        }

                        $enc_amount = $escrowService->encrypt($escrowService->encyptData($BithideTransactionRecord->AmountUSD));
                        $transaction = $app->finder('FS\Escrow:Transaction')->where('user_id', $record->user_id)->where('transaction_amount', $enc_amount)->where('transaction_type','Pending' )->fetchOne(); 
                         /** @var Escrow $notifier */
                      
                        if ($transaction && $BithideTransactionRecord->Status == 2){
                            $dec_trans_amount = $escrowService->decrypt($trans->transaction_amount);
                            $dec_trans_curr_amount = $escrowService->decrypt($trans->current_amount);
                            $temp_enc_curr_amount = ($trans_amount['amount']+$user_amount);
                            $enc_curr_amount = $escrowService->encrypt($escrowService->encyptData($temp_enc_curr_amount));
                            
                            $transaction->bulkSet([
                                'transaction_type'=> 'Deposit',
                                'current_amount'=> $enc_curr_amount,
                            ]);
                            $transaction->save();  
                            
                            $record->fastUpdate('status',1);
                            if ($user){
                                $user->fastUpdate('deposit_amount',$enc_curr_amount);                            
                            } 
                            $notifier = $this->app->notifier('FS\Escrow:Transaction\TransactionAlert', $transaction);
                            $notifier->escrowDepositConfirmAlert(1);
                            
                        }
                        elseif ($BithideTransactionRecord->Status == 2){   
                            if ($user){
                                $final_user_amount = ($user_amount+$BithideTransactionRecord->AmountUSD);
                                $enc_user_amount =  $escrowService->encrypt($escrowService->encyptData($final_user_amount));
                                $user->fastUpdate('deposit_amount',$enc_user_amount);                            
                            }
                            $record->fastUpdate('status',1);
                       
                            $transaction =   $escrowService->escrowTransaction($record->user_id, $BithideTransactionRecord->AmountUSD, $final_user_amount, 'Deposit', 0,2);
                            $notifier = $this->app->notifier('FS\Escrow:Transaction\TransactionAlert', $transaction);
                            $notifier->escrowDepositConfirmAlert(1);
                        }
                    }

            }
        } 
    }

}