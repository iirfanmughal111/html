<?php


namespace FS\Escrow\Service\Escrow;


use XF\Service\AbstractService;
use FS\Escrow\Entity\WithdrawRequest;

class Approve extends AbstractService
{
   
    protected $request;

    protected $notifyRunTime = 3;

    public function __construct(\XF\App $app, WithdrawRequest $request)
    {
        parent::__construct($app);
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setNotifyRunTime($time)
    {
        $this->notifyRunTime = $time;
    }

    public function approve()
    {
        if ($this->request->request_state == 'moderated')
        {
            $this->request->request_state = 'visible';
            $this->request->save();
            $escrowService = \xf::app()->service('FS\Escrow:Escrow\Bithide');
            $escrowService->requestTransferFunds($this->request);
       //     XF::app()->jobManager()->enqueueUnique('WithDraw', 'FS\Escrow:WithDraw', [], false);
           // $this->app()->jobManager()->runUnique('WithDraw',20);

            return true;
        }
        else
        {
            return false;
        }
    }
}