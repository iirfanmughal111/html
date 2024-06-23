<?php

namespace FS\Escrow\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\RouteMatch;

class Escrow extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {

        if ($this->filter('search', 'uint')) {
            $finder = $this->getSearchFinder();

        } else {
            $finder = $this->finder('FS\Escrow:Escrow');
        }

        $page = $this->filterPage($params->page);
        $perPage = 25;
        $finder->limitByPage($page, $perPage);

        $viewpParams = [
            'page' => $page,
            'total' => $finder->total(),
            'perPage' => $perPage,
            'escrows' => $finder->order('last_update','DESC')->fetch(),
            'conditions' => $this->filterSearchConditions(),
        ];

        return $this->view('FS\Escrow:Escrow', 'fs_escrow_admin_list', $viewpParams);
    }

    protected function getSearchFinder()
    {
        $conditions = $this->filterSearchConditions();
        $finder = $this->finder('FS\Escrow:Escrow');

        if ($conditions['fs_escrow_start_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_escrow_start_username'])->fetchOne();
            if ($User) {
                $finder->where('user_id', $User['user_id']);
            }
        }
        if ($conditions['fs_escrow_mentioned_username'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_escrow_mentioned_username'])->fetchOne();
            if ($User) {
                $finder->where('to_user', $User['user_id']);
            }
        }

        if ($conditions['fs_escrow_status'] != 'all') {
            if (intval($conditions['fs_escrow_status']) >= 0 && intval($conditions['fs_escrow_status']) <= 4) {
                $finder->where('escrow_status', intval($conditions['fs_escrow_status']));
            }
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
        return $this->view('FS\Escrow:Escrow', 'fs_escrow_search_filter', $viewParams);
    }

    public function actionRefineTransaction(ParameterBag $params)
    {

        $viewParams = [
            'conditions' => $this->filterSearchConditions(),
        ];
        return $this->view('FS\Escrow:Escrow', 'fs_escrow_logs_filter', $viewParams);
    }

    protected function filterSearchConditions()
    {
        return $this->filter([
            'fs_escrow_username' => 'str',
            'fs_escrow_status' => 'str',
            'fs_escrow_start_username' => 'str',
            'fs_escrow_mentioned_username' => 'str',

        ]);
    }
    
      public function actionLog(ParameterBag $params){
        if ($this->filter('search', 'uint')) {
            $finder = $this->getTrasactionFinder();
        } else {
            $finder = $this->finder('FS\Escrow:Transaction')->where('status','!=',0);
        }
        

        $page = $this->filterPage($params->page);
        $perPage = 25;
        $finder->limitByPage($page, $perPage);
        $viewpParams = [
            'page' => $page,
            'total' => $finder->total(),
            'perPage' => $perPage,
            'logs' => $finder->order('transaction_id','DESC')->fetch(),
            'conditions' => $this->filterTransactionConditions(),
        ];

        return $this->view('FS\Escrow:Escrow', 'fs_escrow_admin_log_list', $viewpParams);

        
    }

    protected function getTrasactionFinder()
    {
        $conditions = $this->filterTransactionConditions();
        $finder = $this->finder('FS\Escrow:Transaction');
        if ($conditions['fs_transaction_user'] != '') {

            $User = $this->finder('XF:User')->where('username', $conditions['fs_transaction_user'])->fetchOne();
            

            if ($User) {
                $finder->where('user_id', $User['user_id']);
            }
        }

        if ($conditions['transaction_id'] != '') {
          
                $finder->where('transaction_id', intval($conditions['transaction_id']));
        }

        return $finderfilterTransactionConditions;
    }
    protected function filterTransactionConditions()
    {
        return $this->filter([
            'transaction_id' => 'int',
            'fs_transaction_user' => 'str',
      

        ]);
    }

    public function actionLive(ParameterBag $params){
        
        $constiions = $this->filterLiveConditions();
        $finder = $this->finder('FS\Escrow:BithideTransactionRecord');
        if ($this->filter('search', 'uint')) {
            
            if (isset($constiions['tx_id']) && $constiions['tx_id'] != '') {
                $finder->where('TxId',$constiions['tx_id']);
            }
        }

        $page = $this->filterPage($params->page);
        $perPage = 25;
        $finder->limitByPage($page, $perPage);
        // tx_id
        $viewpParams = [
            'page' => $page,
            'total' => $finder->total(),
            'perPage' => $perPage,
            'logs' => $finder->fetch(),
            'conditions' => $this->filterLiveConditions(),
        ];

        return $this->view('FS\Escrow:Escrow', 'fs_escrow_admin_liveData_list', $viewpParams);
        
    }

    protected function filterLiveConditions()
    {
        return $this->filter([
            'tx_id' => 'str',

        ]);
    }

    public function actionRefineLive(ParameterBag $params)
    {

        $viewParams = [
            'conditions' => $this->filterLiveConditions(),
        ];
        return $this->view('FS\Escrow:Escrow', 'fs_escrow_live_filter', $viewParams);
    }    
    
}