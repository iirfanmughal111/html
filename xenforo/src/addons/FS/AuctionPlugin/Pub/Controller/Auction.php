<?php

namespace FS\AuctionPlugin\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Mvc\RouteMatch;

class Auction extends AbstractController
{

    public function actionIndex(ParameterBag $params)
    {
        return $this->message("Index page. Template not created yet.");

        $viewpParams = [];

        return $this->view('FS\AuctionPlugin', 'index_Auction', $viewpParams);
    }

    public function actionAdd(ParameterBag $params)
    {
        $options = $this->app()->options();

        $forum = $this->finder('XF:Forum')->where('node_id', $options->fs_auction_applicable_forum)->fetchOne();

        return $this->redirect($this->buildLink('forums/post-thread', $forum, ['category_id' => $params->category_id]));
        $routeMatch = new RouteMatch();
        $routeMatch->setController('XF:Forum');
        $routeMatch->setAction('PostThread');
        $routeMatch->setParam('node_id', 32);
        $routeMatch->setResponseType('json');

        return $this->reroute($routeMatch);
        $visitor = \XF::visitor();

        if ($visitor->user_id != null || $visitor->user_id != 0) {
            $data = $this->em()->create('FS\AuctionPlugin:AuctionListing');

            return $this->actionAddEdit($data, $params);
        } else {
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_auction_permission_denied"))
            );
        }
    }

    public function actionBumping(ParameterBag $params)
    {

        $bumping = $this->finder('FS\AuctionPlugin:AuctionListing')->whereId($params['auction_id'])->fetchOne();

        $difference = time() - $bumping->last_bumping;

        $options = \XF::options();
        $allowBumping = $options->fs_auction_bumping_allowed;

        if ($difference <= 86400 && $bumping->bumping_counts != $allowBumping && $bumping->bumping_counts < ($allowBumping + 1)) {
            $bumping->fastUpdate('last_bumping', time());
            $bumping->fastUpdate('bumping_counts', ($bumping->bumping_counts + 1));
        } elseif ($difference > 86400) {
            $bumping->fastUpdate('last_bumping', time());
            $bumping->fastUpdate('bumping_counts', 1);
        } else {
            throw $this->exception(
                $this->error(\XF::phrase("fs_auction_you_already_bumping") . $allowBumping . ' times...!')
            );
        }

        return $this->redirect($this->buildLink('auction'));
    }

    public function actionBidding(ParameterBag $params)
    {
        $input = $this->filter([
            'bidding_amount' => 'int',
        ]);

        if ($input['bidding_amount'] == '0') {
            throw $this->exception(
                $this->notFound(\XF::phrase("fs_auction_select_amount"))
            );
        }
        $auction = $this->finder('FS\AuctionPlugin:AuctionListing')->whereId($params['auction_id'])->fetchOne();

        $highestBidding = $this->Finder('FS\AuctionPlugin:Bidding')->where('auction_id', $params->auction_id)->order('bidding_amount', 'DESC')->fetchOne();
        if ($highestBidding) {
            if ($input['bidding_amount'] <= $highestBidding->bidding_amount) {
                throw $this->exception(
                    $this->notFound(\XF::phrase("fs_auction_enter_correct_amount"))
                );
            }
        } else {
            $thread = $this->finder('XF:Thread')->whereId($auction['thread_id'])->fetchOne();

            if ($input['bidding_amount'] < (intval($thread->custom_fields['starting_bid']) + intval($thread->custom_fields['bid_increament']))) {
                throw $this->exception(
                    $this->notFound(\XF::phrase("fs_auction_enter_correct_amount"))
                );
            }
        }

        $visitor = \XF::visitor();

        $addBidding = $this->em()->create('FS\AuctionPlugin:Bidding');

        $addBidding->user_id = $visitor->user_id;
        $addBidding->auction_id = $params['auction_id'];
        $addBidding->bidding_amount = $input['bidding_amount'];

        $addBidding->save();

        $replier = $this->service('XF:Thread\Replier', $auction->Thread);

        $replier->setMessage($input['bidding_amount']);

        $post = $replier->save();

        $this->auctionWatch($auction->Thread);

        $this->finalizeThreadReply($replier);
        $this->repository('XF:ThreadWatch')->autoWatchThread($auction->Thread, $visitor, false);

        return $this->redirect(
            $this->getDynamicRedirect($this->buildLink('auction/view-auction'), $params)
        );
    }

    public function auctionWatch($thread)
    {
        $visitor = \XF::visitor();

        // $newState = 'watch_email';

        $newState = 'watch_no_email';

        /** @var \XF\Repository\ThreadWatch $watchRepo */
        $watchRepo = $this->repository('XF:ThreadWatch');
        $watchRepo->setWatchState($thread, $visitor, $newState);
    }

    protected function finalizeThreadReply(\XF\Service\Thread\Replier $replier)
    {
        $replier->sendNotifications();

        $thread = $replier->getThread();


        $post = $replier->getPost();


        $visitor = \XF::visitor();

        $setOptions = $this->filter('_xfSet', 'array-bool');
        if ($thread->canWatch()) {
            if (isset($setOptions['watch_thread'])) {
                $watch = $this->filter('watch_thread', 'bool');
                if ($watch) {
                    /** @var \XF\Repository\ThreadWatch $threadWatchRepo */
                    $threadWatchRepo = $this->repository('XF:ThreadWatch');

                    $state = $this->filter('watch_thread_email', 'bool') ? 'watch_email' : 'watch_no_email';
                    $threadWatchRepo->setWatchState($thread, $visitor, $state);
                }
            } else {
                $this->repository('XF:ThreadWatch')->autoWatchThread($thread, $visitor, false);
            }
        }

        if ($thread->canLockUnlock() && isset($setOptions['discussion_open'])) {
            $thread->discussion_open = $this->filter('discussion_open', 'bool');
        }
        if ($thread->canStickUnstick() && isset($setOptions['sticky'])) {
            $thread->sticky = $this->filter('sticky', 'bool');
        }

        $thread->saveIfChanged($null, false);

        if ($visitor->user_id) {
            $readDate = $thread->getVisitorReadDate();
            if ($readDate && $readDate >= $thread->getPreviousValue('last_post_date')) {
                $post = $replier->getPost();
                $this->getThreadRepo()->markThreadReadByVisitor($thread, $post->post_date);
            }

            $thread->draft_reply->delete();

            if ($post->message_state == 'moderated') {
                $this->session()->setHasContentPendingApproval();
            }
        }
    }

    protected function getThreadRepo()
    {
        return $this->repository('XF:Thread');
    }

    public function actionDelete(ParameterBag $params)
    {
        $replyExists = $this->assertDataExists($params->auction_id);

        /** @var \XF\ControllerPlugin\Delete $plugin */
        $plugin = $this->plugin('XF:Delete');

        if ($this->isPost()) {

            $this->deleteBiddings($params->auction_id);

            $this->deletePostAndThread($replyExists);

            return $this->redirect($this->buildLink('auction'));
        }

        return $plugin->actionDelete(
            $replyExists,
            $this->buildLink('auction/categories/delete', $replyExists),
            null,
            $this->buildLink('auction'),
            "{$replyExists->Thread->title}"
        );
    }

    protected function deleteBiddings($auction_id)
    {
        $biddingFounds = $this->finder('FS\AuctionPlugin:Bidding')->where('auction_id', $auction_id)->fetch();

        foreach ($biddingFounds as $bid) {
            $bid->delete();
        }
    }

    protected function deletePostAndThread($auction)
    {
        $threadFound = $this->finder('XF:Thread')->whereId($auction->Thread->thread_id)->fetchOne();

        if ($threadFound) {
            $threadFound->delete();

            \XF::db()->query('update fs_auction_category set auctions_count = auctions_count - 1 where category_id =' . $auction['category_id']);
        }

        $auction->delete();
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \FS\AuctionPlugin\Entity\AuctionListing
     */
    protected function assertDataExists($id, array $extraWith = [], $phraseKey = null)
    {
        return $this->assertRecordExists('FS\AuctionPlugin:AuctionListing', $id, $extraWith, $phraseKey);
    }
}
