<?php

namespace FS\Escrow\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Post extends XFCP_Post
{

    /**
     * @param \XF\Entity\Thread $thread
     * @param array $threadChanges Returns a list of whether certain important thread fields are changed
     *
     * @return \XF\Service\Thread\Editor
     */
    protected function setupFirstPostThreadEdit(\XF\Entity\Thread $thread, &$threadChanges)
    {
        $parent = parent::setupFirstPostThreadEdit($thread, $threadChanges);

        if ($thread->node_id ==  intval($this->app()->options()->fs_escrow_applicable_forum)) {

            $visitor = \XF::visitor();

            if ($thread->user_id != $visitor->user_id) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_not_allowed"))
                );
            }

            $inputs = $this->filter([
                'to_user' => 'str',
                'escrow_amount' => 'int',
            ]);

            if (!$inputs['to_user'] || $inputs['escrow_amount'] < 1) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_enter_valid_data"))
                );
            }

            if ($this->filter('to_user', 'str') == $thread->User->username) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_cant_mention_own_name"))
                );
            }

            $user = $this->em()->findOne('XF:User', ['username' => $this->filter('to_user', 'str')]);

            if (!$user) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_user_not_found"))
                );
            }

            $visitor = \XF::visitor();

            $transaction = null;

            if ($this->filter('escrow_amount', 'uint') == $thread->Escrow->escrow_amount && $thread->Escrow->to_user == $user->user_id) {
                # code...
            } elseif ($this->filter('escrow_amount', 'uint') && $this->filter('escrow_amount', 'uint') < $thread->Escrow->escrow_amount) {

                $depAmount = $thread->Escrow->escrow_amount - $this->filter('escrow_amount', 'uint');

                $visitor->fastUpdate('deposit_amount', ($visitor->deposit_amount + $depAmount));

                $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

                $transaction = $escrowService->escrowTransaction($visitor->user_id, $depAmount, $visitor->deposit_amount, 'Deposit', $thread->escrow_id);

                // $this->updateEscrow($thread, $transaction);
            } elseif ($this->filter('escrow_amount', 'uint') && $this->filter('escrow_amount', 'uint') > $thread->Escrow->escrow_amount) {

                $withdrawAmount = $this->filter('escrow_amount', 'uint') - $thread->Escrow->escrow_amount;

                if ($visitor->deposit_amount < $withdrawAmount) {
                    throw $this->exception(
                        $this->error(\XF::phrase("fs_escrow_not_enough_amount"))
                    );
                }

                $visitor->fastUpdate('deposit_amount', ($visitor->deposit_amount - $withdrawAmount));

                $escrowService = \xf::app()->service('FS\Escrow:Escrow\EscrowServ');

                $transaction = $escrowService->escrowTransaction($visitor->user_id, $withdrawAmount, $visitor->deposit_amount, 'Freeez', $thread->escrow_id);
            }
            $this->updateEscrow($thread, $transaction);
        }
        return $parent;
    }

    public function actionEdit(ParameterBag $params)
    {
        $post = $this->assertViewablePost($params->post_id, ['Thread.Prefix']);
        if (!$post->canEdit($error)) {
            return $this->noPermission($error);
        }

        $thread = $post->Thread;

        if ($thread->node_id ==  intval($this->app()->options()->fs_escrow_applicable_forum)) {
            if (!$post->isFirstPost()) {
                throw $this->exception(
                    $this->error(\XF::phrase("fs_escrow_not_allowed"))
                );
            }
        }

        return parent::actionEdit($params);
    }



    protected function updateEscrow($thread, $transaction)
    {

        $inputs = $this->filter([
            'to_user' => 'str',
            'escrow_amount' => 'int',
        ]);

        $user = $this->em()->findOne('XF:User', ['username' => $inputs['to_user']]);

        if (!$user) {
            throw $this->exception(
                $this->error(\XF::phrase("fs_escrow_user_not_found"))
            );
        } elseif ($thread->Escrow->to_user != $user->user_id) {

            $thread->Escrow->to_user = $user->user_id;
        }

        if ($transaction) {
            $thread->Escrow->transaction_id = $transaction->transaction_id;
        }

        if ($thread->Escrow->escrow_amount != $inputs['escrow_amount']) {

            $thread->Escrow->escrow_amount = $inputs['escrow_amount'];
        }

        $thread->Escrow->save();

        return true;
    }
}
