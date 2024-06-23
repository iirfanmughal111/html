<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Download;
use XF\Service\AbstractService;

/**
 * Class Scheduler
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Scheduler extends AbstractService
{
	/** @var \DBTech\eCommerce\Entity\Download  */
	protected $download;
	
	/** @var \XF\Service\Thread\Creator|null */
	protected $threadCreator;
	
	/** @var \XF\Service\Thread\Replier|null */
	protected $threadReplier;
	

	/**
	 * Scheduler constructor.
	 *
	 * @param \XF\App $app
	 * @param Download $download
	 */
	public function __construct(\XF\App $app, Download $download)
	{
		parent::__construct($app);

		$this->download = $download;
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function releaseDownload()
	{
		$this->download->download_state = 'visible';
		$this->download->save();
		
		$user = $this->download->Product->User ?: \XF::visitor();
		\XF::asVisitor($user, function ()
		{
			$this->afterRelease();
		});
	}
	
	/**
	 *
	 */
	public function sendNotifications()
	{
		if ($this->download->isVisible())
		{
			/** @var \DBTech\eCommerce\Service\Download\Notify $notifier */
			$notifier = $this->service('DBTech\eCommerce:Download\Notify', $this->download, 'download');
			$notifier->notifyAndEnqueue(3);
		}
		
		if ($this->threadReplier)
		{
			$this->threadReplier->sendNotifications();
		}
		
		if ($this->threadCreator)
		{
			$this->threadCreator->sendNotifications();
		}
	}
	
	/**
	 * @throws \Exception
	 */
	public function afterRelease()
	{
		$download = $this->download;
		$product = $this->download->Product;
		
		if ($download->isVisible() && $download->release_notes)
		{
			if ($product->Category->product_update_notify == 'reply' && count($product->getProductDownloadIds()) > 1)
			{
				if ($product->discussion_thread_id && $product->Discussion)
				{
					$replier = $this->setupDownloadThreadReply($product->Discussion);
					if ($replier && $replier->validate())
					{
						$existingLastPostDate = $replier->getThread()->last_post_date;
						
						/** @var \XF\Entity\Post $post */
						$post = $replier->save();
						$this->threadReplier = $replier;
						
						$this->afterDownloadThreadReplied($post, $existingLastPostDate);
					}
				}
			}
			elseif ($product->Category->thread_node_id && $product->Category->ThreadForum)
			{
				$creator = $this->setupDownloadThreadCreation($product->Category->ThreadForum);
				if ($creator && $creator->validate())
				{
					/** @var \XF\Entity\Thread $thread */
					$thread = $creator->save();
					$download->fastUpdate('discussion_thread_id', $thread->thread_id);
					$this->threadCreator = $creator;
					
					if ($product->Category->product_update_notify == 'reply')
					{
						$product->fastUpdate('discussion_thread_id', $thread->thread_id);
					}
					
					$this->afterDownloadThreadCreated($thread);
				}
			}
		}
	}
	
	/**
	 * @param \XF\Entity\Thread $thread
	 *
	 * @return \XF\Service\Thread\Replier
	 */
	protected function setupDownloadThreadReply(\XF\Entity\Thread $thread): \XF\Service\Thread\Replier
	{
		/** @var \XF\Service\Thread\Replier $replier */
		$replier = $this->service('XF:Thread\Replier', $thread);
		$replier->setIsAutomated();
		
		$replier->setMessage($this->getThreadMessage(), false);
		$replier->getPost()->message_state = $this->download->download_state;
		
		return $replier;
	}

	/**
	 * @param \XF\Entity\Forum $forum
	 *
	 * @return \XF\Service\Thread\Creator
	 * @throws \Exception
	 */
	protected function setupDownloadThreadCreation(\XF\Entity\Forum $forum): \XF\Service\Thread\Creator
	{
		$product = $this->download->Product;
		
		$isNewProduct = count($product->getProductDownloadIds()) == 1;
		
		/** @var \XF\Service\Thread\Creator $creator */
		$creator = $this->service('XF:Thread\Creator', $forum);
		$creator->setIsAutomated();
		
		$creator->setContent(
			($isNewProduct ? $product->getExpectedThreadTitle() : $this->download->getExpectedThreadTitle()),
			$this->getThreadMessage(),
			false
		);
		$creator->setPrefix($product->Category->thread_prefix_id);
		
		/** @var \XF\Entity\Thread $thread */
		$thread = $creator->getThread();
		$thread->bulkSet([
			'discussion_type' => 'dbtech_ecommerce_' . ($isNewProduct ? 'product' : 'download'),
			'discussion_state' => $isNewProduct ? $product->product_state : $this->download->download_state
		]);
		
		return $creator;
	}
	
	/**
	 * @return mixed|null|string|string[]
	 */
	protected function getThreadMessage()
	{
		$download = $this->download;
		$product = $this->download->Product;
		
		$isNewProduct = count($product->getProductDownloadIds()) == 1;
		
		$phraseParams = [
			'title' => $download->title,
			'product_title' => $product->title,
			'tag_line' => $product->tagline,
			'username' => $product->User ? $product->User->username : $product->username,
			'product_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce', $product),
			'release_link' => $this->app->router('public')->buildLink('canonical:dbtech-ecommerce/release', $download)
		];
		
		if ($isNewProduct)
		{
			$phraseParams['description'] = $this->app->bbCode()->render(
				$product->description_full,
				'bbCodeClean',
				'post',
				null
			);
			
			$phraseParams['extendedInfo'] = $this->app->bbCode()->render(
				$product->product_specification,
				'bbCodeClean',
				'post',
				null
			);
		}
		else
		{
			$phraseParams['releaseNotes'] = $this->app->bbCode()->render(
				$download->release_notes,
				'bbCodeClean',
				'post',
				null
			);
			
			$phraseParams['changeLog'] = $this->app->bbCode()->render(
				$download->change_log,
				'bbCodeClean',
				'post',
				null
			);
		}
		
		$phrase = \XF::phrase('dbtech_ecommerce_product_thread_' . ($isNewProduct ? 'create' : 'update'), $phraseParams);
		
		return $phrase->render('raw');
	}
	
	/**
	 * @param \XF\Entity\Post $post
	 * @param int $existingLastPostDate
	 */
	protected function afterDownloadThreadReplied(\XF\Entity\Post $post, int $existingLastPostDate)
	{
		$thread = $post->Thread;
		
		if (\XF::visitor()->user_id && $post->Thread->getVisitorReadDate() >= $existingLastPostDate)
		{
			/** @var \XF\Repository\Thread $threadRepo */
			$threadRepo = $this->repository('XF:Thread');
			$threadRepo->markThreadReadByVisitor($thread);
		}
	}
	
	/**
	 * @param \XF\Entity\Thread $thread
	 */
	protected function afterDownloadThreadCreated(\XF\Entity\Thread $thread)
	{
		/** @var \XF\Repository\Thread $threadRepo */
		$threadRepo = $this->repository('XF:Thread');
		$threadRepo->markThreadReadByVisitor($thread);
		
		/** @var \XF\Repository\ThreadWatch $threadWatchRepo */
		$threadWatchRepo = $this->repository('XF:ThreadWatch');
		$threadWatchRepo->autoWatchThread($thread, \XF::visitor(), true);
	}
}