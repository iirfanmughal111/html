<?php

namespace FS\Escrow\Service\Escrow;

use XF\Mvc\FormAction;

class EscrowServ extends \XF\Service\AbstractService
{
    public function escrowTransaction($user_id, $amount, $current_amt, $type, $escrow_id,$status=null)
    {
        $newAmount = $this->encrypt($this->encyptData($amount));
        $curr_amount = $this->encrypt($this->encyptData($current_amt));

        $transaction = $this->em()->create('FS\Escrow:Transaction');
        $transaction->user_id = $user_id;
        $transaction->transaction_amount = $newAmount;
        $transaction->current_amount = $curr_amount;
        $transaction->transaction_type = $type;
        $transaction->escrow_id = $escrow_id;
        $transaction->status = $status ?  $status : 0;

        $transaction->save();

        return $transaction;
    }





    public function auctionEscrowCount()
    {
        $visitor = \XF::visitor();

        $finder = $this->finder('FS\Escrow:Escrow')->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]])->where('thread_id', '!=', 0);
        $finderMentioned = clone $finder;
        $finderMy = clone $finder;



        return [
            'all' => [
                'title' => \XF::phrase('fs_escrow_all'),
                'type' => 'all',
                'count' => $finder->total(),
            ],

            'my_escrow' => [
                'title' => \XF::phrase('fs_my_escrow'),
                'type' => 'my',
                'count' => $finderMy->where('user_id', $visitor->user_id)->total(),
            ],

            'mentioned_escrow' => [
                'title' => \XF::phrase('fs_mentioned_escrow'),
                'type' => 'mentioned',
                'count' => $finderMentioned->where('to_user', $visitor->user_id)->total(),
            ],



        ];
    }

    public function auctionStatistics()
    {
        $visitor = \XF::visitor();

        $finder = $this->finder('FS\Escrow:Escrow')->whereOr([['to_user', $visitor->user_id], ['user_id' => $visitor->user_id]])->where('thread_id', '!=', 0);
        $finder0 = clone $finder;
        $finder1 = clone $finder;
        $finder2 = clone $finder;
        $finder3 = clone $finder;
        $finder4 = clone $finder;

        return [

            'status_0' => [
                'title' => \XF::phrase('fs_escrow_status_0'),
                'count' => $finder0->where('escrow_status', 0)->total(),
            ],

            'status_1' => [
                'title' => \XF::phrase('fs_escrow_status_1'),
                'count' => $finder1->where('escrow_status', 1)->total(),
            ],

            'status_2' => [
                'title' => \XF::phrase('fs_escrow_status_2'),
                'count' => $finder2->where('escrow_status', 2)->total(),
            ],

            'status_3' => [
                'title' => \XF::phrase('fs_escrow_status_3'),
                'count' => $finder3->where('escrow_status', 3)->total(),
            ],

            'status_4' => [
                'title' => \XF::phrase('fs_escrow_status_4'),
                'count' => $finder4->where('escrow_status', 4)->total(),
            ],

        ];
    }

   public function encrypt(array $data)
	{
            
          

		$data = json_encode($data);

		$packed = unpack('H*', $data);

		return $packed[1];
	}

	
	public function decrypt($hex)
	{
		$text = pack('H*', $hex);

		if (!$text) {
			return false;
		}

		$data = @json_decode($text, true);

		if (!is_array($data)) {
			return false;
		}

		return $data;
	}

    public function encyptData($amount) {
        $app = \XF::app();
        $type = $app->options()->fs_escrow_currency;
        $data = array(
            'amount' => $amount,
            'amount_type' => $type,
        );

        return $data;
    }

    
    
}