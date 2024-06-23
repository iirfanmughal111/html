<?php

namespace FS\Escrow\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;
use db;
include __DIR__ . '/../../qrcode/phpqrcode/qrlib.php';

class Escrow extends AbstractController
{

    public function escrowService(){
         return \xf::app()->service('FS\Escrow:Escrow\EscrowServ');
    
    }
    public function bithideService(){
        return \xf::app()->service('FS\Escrow:Escrow\Bithide');
   }
     public function pgpMesgService(){
        return \xf::app()->service('FS\Escrow:Escrow\PGPMessage');
   }
    public function actionIndex(ParameterBag $params)
    {

        $escrowService = $this->escrowService();

        $visitor = \XF::visitor();
        $rules[] = [
            'message' => \XF::phrase('fs_escrow_rules'),
            "display_image" => "avatar",
            "display_style" => "primary",
        ];

        if ($this->filter('search', 'uint') || $this->filterSearchConditions()) {
            $finder = $this->getSearchFinder();
        } else {
            $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0)->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]]);
        }


        $page = $this->filterPage($params->page);
        $perPage = 15;
        $finder->limitByPage($page, $perPage);
        $viewpParams = [
            'rules' => $rules,
            'page' => $page,
            'total' => $finder->total(),
            'perPage' => $perPage,
            'stats' => $escrowService->auctionStatistics(),
            'escrowsCount' => $escrowService->auctionEscrowCount(),
            'escrows' => $finder->order('last_update', 'desc')->fetch(),
            'conditions' => $this->filterSearchConditions(),
            'isSelected' => $this->filter(['type' => 'str']),

        ];


        return $this->view('FS\Escrow', 'fs_escrow_landing', $viewpParams);
    }

    protected function getSearchFinder()
    {
        $visitor = \XF::visitor();
        $conditions = $this->filterSearchConditions();
        $finder = $this->finder('FS\Escrow:Escrow')->where('thread_id', '!=', 0);
        $search = 0;
        if ($conditions['fs_escrow_mentioned_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_escrow_mentioned_username'])->fetchOne();
            if ($User) {
                $finder->where('user_id', $User['user_id']);
                $finder->where('to_user', $visitor->user_id);
                $search = 1;
            }
        }
        if ($conditions['fs_escrow_status'] != 'all') {
            if (intval($conditions['fs_escrow_status']) > 0 && intval($conditions['fs_escrow_status']) <= 5) {
                $finder->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]]);
                $finder->where('escrow_status', (intval($conditions['fs_escrow_status']) - 1));
                $search = 1;
            }
        }

        if (isset($conditions['type']) && $conditions['type'] != '') {
            if ($conditions['type'] == 'my') {
                $finder->where('user_id', $visitor->user_id);
                $search = 1;
            } else if ($conditions['type'] == 'mentioned') {
                $finder->where('to_user', $visitor->user_id);
                $search = 1;
            }
        }
        if (!$search) {
            $finder->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]]);
        }

        return $finder;
    }

    public function filterPage($page = 0, $inputName = 'page')
    {
        return max(1, intval($page) ?: $this->filter($inputName, 'uint'));
    }

    public function actionRefineSearch(ParameterBag $params)
    {

        $viewParams = [
            'conditions' => $this->filterSearchConditions(),
        ];
        return $this->view('FS\Escrow:Escrow', 'fs_escrow_public_search_filter', $viewParams);
    }

    protected function filterSearchConditions()
    {
        $filters = $this->filter([
            'fs_escrow_status' => 'str',
            'fs_escrow_mentioned_username' => 'str',
            'type' => 'str'

        ]);
        //   if ($filters['type']=='my'){
        //         $filters['isSelected'] = 'my';
        //     }
        //     else if ($filters['type']=='mentioned'){
        //         $filters['isSelected'] = 'mentioned';
        //     }
        return $filters;
    }

    public function actionAdd(ParameterBag $params)
    {

        $options = $this->app()->options();

        $forum = $this->finder('XF:Forum')->where('node_id', intval($options->fs_escrow_applicable_forum))->fetchOne();

        return $this->redirect($this->buildLink('forums/post-thread', $forum));

        $viewpParams = [];
        return $this->view('FS\Escrow', 'fs_escrow_addEdit', $viewpParams);
    }

    public function actionWithdraw(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $bithideService = $this->bithideService();

        $source_address = $bithideService->getAddress($visitor);
        $viewpParams = [
            'source_address' => $source_address,
            'pageSelected' => 'escrow/withdraw',
        ];
        return $this->view('FS\Escrow', 'fs_escrow_wihdraw', $viewpParams);
    }
    public function actionWithdrawRequest(ParameterBag $params)
    {
        if (!$this->isPost()){
            return $this->redirect($this->buildLink('escrow/withdraw'), 'false');
        }
        
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        
        $inputs = $this->filterWithdrawAmountInputs();
        
        if ($this->app()->options()->fs_escrow_pgp_option){
            $this->saveWithdrawRequest($inputs);
            return $this->redirect($this->buildLink('members/'.$visitor->username.'.'.$visitor->user_id.'/#escrow-logs'));
        }
        if ($visitor->public_key){
            $template = 'fs_escrow_pgp_message';
            
            $pgpService = $this->pgpMesgService();
            list($encrypt_message, $message) = $pgpService->encryptMessage($visitor->public_key);
             $visitor->bulkSet([
                 'random_message'=> $message,
                 'encrypt_message'=> $encrypt_message,
             ]);
             $visitor->save();
            
        }else{
            $template = 'fs_escrow_public_key';
        }
 
        $bithideService = $this->bithideService();
        
        $viewpParams = [
            'data'=> $inputs,
            'pageSelected' => 'escrow/withdraw',
        ];

        return $this->view('FS\Escrow', $template, $viewpParams);
    
    }
    public function actionWithdrawVerify(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $inputs = $this->filterWithdrawAmountInputs();
        
        $public_key = $this->filter('public_key', 'str');;
        
        $pgpService = $this->pgpMesgService();
        list($encrypt_message, $message) = $pgpService->encryptMessage($public_key);
         $visitor->bulkSet([
             'random_message'=> $message,
             'encrypt_message'=> $encrypt_message,
         ]);
         $visitor->save();

         $viewpParams = [
            'data'=> $inputs,
            'public_key' => $public_key,
            'pageSelected' => 'escrow/withdraw',
        ];
        
        return $this->view('FS\Escrow', 'fs_escrow_pgp_message', $viewpParams);
         
 
    }

    public function actionWithdrawSave(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        $inputs = $this->filterWithdrawAmountInputs();
        $public_key = $this->filter('public_key', 'str');    
        $random_msg = $this->filter('message', 'str');
        if ($visitor->random_message && $visitor->random_message==$random_msg){
            if (!($visitor->public_key) && $public_key){
                $visitor->fastUpdate('public_key',$public_key);
            }
            $this->saveWithdrawRequest($inputs);
        }else{
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_message_does_not_matched"))
            );
        }

        return $this->redirect(
            $this->buildLink('members/'.$visitor->username.'.'.$visitor->user_id.'/#escrow-logs')
        );
       
    }
    protected function saveWithdrawRequest($inputs){
        $visitor = \XF::visitor();
        $withdrawRequest = $this->em()->create('FS\Escrow:WithdrawRequest');
        
        if ($this->app()->options()->fs_escrow_approval_que_option){
            $request_state = 'visible';
        }else{
            $request_state = 'moderated';
        }

        $escrowService = $this->escrowService();
        $bithideService = $this->bithideService();

        if ($visitor->deposit_amount){
            $dec_user_amount = $escrowService->decrypt($visitor->deposit_amount);
            $user_amount = $dec_user_amount['amount'];
        }else{
            $user_amount =0;
        }
        $newAmount = $user_amount-$inputs['withdraw_amount'];
        
        $enc_user_amount = $escrowService->encrypt($escrowService->encyptData($newAmount));
        $visitor->deposit_amount = $enc_user_amount;
        $visitor->save();   
                                     
        $escrowService = $this->escrowService();
        $transaction = $escrowService->escrowTransaction($visitor->user_id, $inputs['withdraw_amount'], $newAmount, 'Withdraw Request', 0,1);   
            
        $withdrawRequest->bulkSet([
            'user_id'=> $visitor->user_id,
            'amount'=> $inputs['withdraw_amount'],
            'address_from'=> $visitor->crypto_address,
            'address_to'=> $inputs['destination_address'],
       //     'otp'=> $visitor->escrow_otp,
            'transaction_id'=>$transaction->transaction_id,
            'request_state'=> $request_state,

        ]);
        $req =  $withdrawRequest->save();
     
         if ($req && $withdrawRequest->request_state  == 'visible'){
            $bithideService->requestTransferFunds($withdrawRequest);
        //    $this->app()->jobManager()->enqueueUnique('WithDraw', 'FS\Escrow:WithDraw', [], false);
        //    $this->app()->jobManager()->runUnique('WithDraw',20);
        }
        
    }
    
 

    protected function filterWithdrawAmountInputs()
    {
        $input = $this->filter([
            'destination_address' => 'str',
            'withdraw_amount' => 'int',
        ]);
        $visitor = \XF::visitor();
        if (isset($input['destination_address']) &&  ($input['destination_address']=='' || $input['destination_address']==$visitor->crypto_address)) {
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_escrow_address_error"))
            );
        }
        $escrowService = $this->escrowService();
     
        if ($visitor->deposit_amount){
            $dec_user_amount = $escrowService->decrypt($visitor->deposit_amount);
            $user_amount = $dec_user_amount['amount'];

        }else{
            $user_amount = 0;
        }
        
        
        if ($input['withdraw_amount'] > 0 && $input['withdraw_amount'] <= $user_amount) {
            return $input;
        }
        else{
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_escrow_amount_error",['amount'=> $user_amount]))
            );
        }
    }
    public function actionDeposit(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        if ($visitor->user_id == 0) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $bithideService = $this->bithideService();
        
        $address = $bithideService->getAddress($visitor);
       

        if (!file_exists(\XF::getRootDirectory() . '/data/qrcode')) {
            // Create the folder if it doesn't exist
            mkdir(\XF::getRootDirectory() . '/data/qrcode', 0777, true);
        }

        $text = $address;

        $fileName = $visitor->user_id . '.png';
        $path = \XF::getRootDirectory() . '/data/qrcode/' . $fileName;
        
        if (!file_exists($path)){
            $ecc = 'L';
            $pixel_Size = 5;
            $frame_Size = 5;
            \QRcode::png($text, $path, $ecc, $pixel_Size, $frame_Size);
        }


        $viewpParams = [
            'pageSelected' => 'escrow/deposit',
            'address' => $address,
        ];
        return $this->view('FS\Escrow', 'fs_escrow_deposit', $viewpParams);
    }

   
    public function actionDepositSave(ParameterBag $params)
    {
        $this->insertTransaction();
        $visitor = \XF::visitor();

        return $this->redirect(
            $this->buildLink('members/'.$visitor->username.'.'.$visitor->user_id.'#escrow-logs')
           
        );
    }
   

    protected function insertTransaction()
    {
        $inputs = $this->filterDepositeAmountInputs();
        $visitor = \XF::visitor();
        $escrowService = $this->escrowService();
        $enc_amount = $escrowService->encrypt($escrowService->encyptData($inputs['deposit_amount']));
        $newAmount = $inputs['deposit_amount'];
        if ($visitor->deposit_amount){
            $dec_user_amount = $escrowService->decrypt($visitor->deposit_amount);
            $user_amount = $dec_user_amount['amount'];
        }else{
            $user_amount = 0;
        }
        
        $transaction = $escrowService->escrowTransaction($visitor->user_id, $newAmount, $user_amount, 'Pending', 0,2);
        
        $depositRequest = $this->em()->create('FS\Escrow:DepositRequest');
        $depositRequest->bulkSet([
            'user_id'=> $visitor->user_id,
            'external_id'=> $visitor->username.'_'.$visitor->user_id,
            'transaction_id'=> $transaction->transaction_id,
            'amount'=> $inputs['deposit_amount'],
        ]);
        $depositRequest->save();

        return true;
    }

    protected function filterDepositeAmountInputs()
    {
        $input = $this->filter([
            'deposit_amount' => 'int',
            'fee' => 'int',

        ]);

        if ($input['deposit_amount'] > 0) {
            return $input;
        }

        throw $this->exception(
            $this->notFound(\XF::phrase("fs_escrow_amount_required"))
        );
    }


    public function actionCancel(ParameterBag $params)
    {

        $escrow = $this->assertDataExists($params->escrow_id);

        if ($this->isPost()) {

            $this->cancelEscrow($escrow);

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('escrow'), $escrow->Thread)
            );
        } else {

            $viewParams = [
                'escrow' => $escrow,
            ];
            return $this->view('FS\Escrow:Escrow\Cancel', 'fs_escrow_cancel', $viewParams);
        }
    }


    public function actionApprove(ParameterBag $params)
    {
        $escrow = $this->assertDataExists($params->escrow_id);

        if ($this->isPost()) {

            $this->approveEscrow($escrow);

            /** @var Escrow $notifier */
            $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
            $notifier->escrowApproveAlert();

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('escrow'), $escrow->Thread)
            );
        } else {

            $viewParams = [
                'escrow' => $escrow,
            ];
            return $this->view('FS\Escrow:Escrow\Approve', 'fs_escrow_approve', $viewParams);
        }
    }

    public function actionPayments(ParameterBag $params)
    {
        $visitor = \XF::visitor();
        $escrow = $this->assertDataExists($params->escrow_id);

        if (!($escrow->user_id == $visitor->user_id || $visitor->is_admin)) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        

        // /** @var \XF\ControllerPlugin\Delete $plugin */
        // $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->paymentEscrow($escrow);

            /** @var Escrow $notifier */
            $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
            $notifier->escrowPaymentAlert();

            return $this->redirect(
                $this->getDynamicRedirect($this->buildLink('escrow'), $escrow->Thread)
            );
        } else {

            $viewParams = [
                'escrow' => $escrow,
            ];
            return $this->view('FS\Escrow:Escrow\Payments', 'fs_escrow_payment', $viewParams);
        }
    }

    protected function cancelEscrow($escrow)
    {
        $visitor = \XF::visitor();

        if ($escrow->user_id != $visitor->user_id && $escrow->to_user != $visitor->user_id) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
        $escrowService = $this->escrowService();
        if ($escrow->user_id != $visitor->user_id) {
            $visitor = $this->em()->findOne('XF:User', ['user_id' => $escrow->user_id]);
        }

        if ($escrow) {
                  
            $creator = $escrow->Thread->User;
            if ($creator->deposit_amount){
                $dec_creator_amt = $escrowService->decrypt($creator->deposit_amount);
                $creator_amt = $dec_creator_amt['amount'];
                
            }else{
                $creator_amt = 0;
            
            }
            $dec_trans_amount = $escrowService->decrypt($escrow->Transaction->transaction_amount);

            $sum =  $creator_amt + $dec_trans_amount['amount'];
            
            $enc_amount = $escrowService->encrypt($escrowService->encyptData($sum));
           
            $creator->fastUpdate('deposit_amount', $enc_amount); 
            $escrowService->escrowTransaction($escrow->Thread->User->user_id, $dec_trans_amount['amount'], $sum, 'Cancel', $escrow->escrow_id);

            $visitor = \XF::visitor();

            if ($escrow->user_id == $visitor->user_id) {
                $escrow->bulkSet([
                    'escrow_status' => '3',
                    'last_update' => \XF::$time,
                ]);

                /** @var Escrow $notifier */
                $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
                $notifier->escrowCancelByOwnerAlert();
            } else {
                $escrow->bulkSet([
                    'escrow_status' => '2',
                    'last_update' => \XF::$time,
                ]);
                /** @var Escrow $notifier */
                $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
                $notifier->escrowCancelAlert();
            }

            $escrow->save();
            // $escrow->fastUpdate('last_update', \XF::$time);
        }
    }

    protected function approveEscrow($escrow)
    {
        $visitor = \XF::visitor();

        if ($escrow->to_user != $visitor->user_id) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }

        $escrow->bulkSet([
            'escrow_status' => '1',
            'last_update' => \XF::$time,
        ]);
        $escrow->save();
    }

    protected function paymentEscrow($escrow)
    {
        $visitor = \XF::visitor();

        if (!($escrow->user_id == $visitor->user_id || $visitor->is_admin)) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_not_allowed"))
            );
        }
    
        $escrowService = $this->escrowService();
       
        // $user = $this->em()->findOne('XF:User', ['user_id' => $escrow->to_user]);
        if ($escrow->escrow_amount){
            $dec_escrow_amount = $escrowService->decrypt($escrow->escrow_amount);
            $escrw_amount = $dec_escrow_amount['amount'];
        }else{
            $escrw_amount = 0;
        }
        
       $userStatus = $this->transferMoney($escrow,$escrw_amount,'to_user');
       
        if ($userStatus){
            if ($escrow->User->deposit_amount){
                $dec_to_user_amt = $escrowService->decrypt($escrow->User->deposit_amount);
                $to_user_amount = $dec_to_user_amt['amount'];
            }else{
                $to_user_amount = 0;
                
            }

            $final = $to_user_amount+ $dec_escrow_amount['amount'];
            $enc_amount = $escrowService->encrypt($escrowService->encyptData($final));

            $escrow->User->fastUpdate('deposit_amount', $enc_amount);
            $escrowService->escrowTransaction($escrow->User->user_id, $dec_escrow_amount['amount'], $to_user_amount, 'Payment', $escrow->escrow_id,3);
        }

        $percentageUser = $this->em()->findOne('XF:User', ['user_id' => intval($this->app()->options()->fs_escrow_admin_Id)]);

        if ($percentageUser) {
            if ($percentageUser->deposit_amount){
                $dec_percentage_amt = $escrowService->decrypt($percentageUser->deposit_amount);
                $percentage_amount = $dec_percentage_amt['amount'];
            }else{
                $percentage_amount = 0;
            }
            $escrowPercentage = $percentage_amount + (($escrow->admin_percentage / 100) * $dec_escrow_amount['amount']);
            
            $enc_percentage_amount = $escrowService->encrypt($escrowService->encyptData($escrowPercentage));
            
            $status = $this->transferMoney($escrow,$escrowPercentage,'admin');
            if ($status){
                $percentageUser->fastUpdate('deposit_amount', $enc_percentage_amount);
                
                $escrowService->escrowTransaction($percentageUser->user_id, (($escrow->admin_percentage / 100) * $dec_escrow_amount['amount']), $percentage_amount, 'Percentage', $escrow->escrow_id);
                
                /** @var Escrow $notifier */
                $notifier = $this->app->notifier('FS\Escrow:Listing\EscrowAlert', $escrow);
                $notifier->escrowPercentageHolderUserAlert();
            }
            
        }

        $escrow->bulkSet([
            'escrow_status' => '4',
            'last_update' => \XF::$time,
        ]);
        $escrow->save();
        
    }



    public function transferMoney($escrow,$amount,$type)
    {
        $status = false;
        $response  = $this->requestTransferFunds($escrow,$amount,$type);
        if ( $response && $response['Status'] == 'Success') { 
        $escrow->Transaction->fastUpdate('transaction_type','Funds Transferd');
        $status = true;
        }else{
            $errorMessage = implode(' ',preg_split('/(?=[A-Z])/', $response['Error']['Code']));
            $escrow->Transaction->fastUpdate('transaction_type',$errorMessage);
            $escrow->Transaction->fastUpdate('status',3);

        }
        return $status;
    }


    protected function requestTransferFunds($escrow,$amount,$type){
        
        $app = \XF::app();
        $bithideService = $this->bithideService();
        
        if ($escrow->Thread->User->crypto_address && $escrow->Thread->User->crypto_address !=null){
            $source = $escrow->Thread->User->crypto_address;
        }else{
            
            $source =  $bithideService->getAddress($escrow->Thread->User);
        }
        
        if ($type=='to_user'){
            if ($escrow->User->crypto_address && $escrow->User->crypto_address !=null){
                $destination =  $escrow->User->crypto_address;
            }else{
                $destination =  $bithideService->getAddress($escrow->User);
            }
        }
        else if($type=='admin'){
            $percentageUser = $this->em()->findOne('XF:User', ['user_id' => intval($this->app()->options()->fs_escrow_admin_Id)]);
            if ($percentageUser->crypto_address && $percentageUser->crypto_address !=null){
                $destination= $percentageUser->crypto_address;
            }else{
                $destination =  $bithideService->getAddress($percentageUser);
            }
        }
        $req_id = $escrow->User->user_id.'_'.$escrow->User->username;
        $bithideService = $this->bithideService();
        $response = $bithideService->transferFundsCall($req_id,$source,$destination,$amount);
        return $response;
       

    } 
    
    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\Escrow\Entity\Escrow
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\Escrow:Escrow', $id, $extraWith, $phraseKey);
    }
}