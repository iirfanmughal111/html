<?php

namespace DBTech\eCommerce\Service\Download;

use DBTech\eCommerce\Entity\Product;
use DBTech\eCommerce\Download\AbstractHandler;

/**
 * Class Create
 *
 * @package DBTech\eCommerce\Service\Download
 */
class Create extends \XF\Service\AbstractService
{
	use \XF\Service\ValidateAndSavableTrait;

	/** @var \DBTech\eCommerce\Entity\Product */
	protected $product;

	/** @var \DBTech\eCommerce\Entity\Download */
	protected $download;
	
	/** @var \DBTech\eCommerce\Service\Download\Preparer */
	protected $changeLogPreparer;

	/** @var \DBTech\eCommerce\Service\Download\Preparer */
	protected $releaseNotesPreparer;

	/** @var \XF\Service\Thread\Creator|null */
	protected $threadCreator;
	
	/** @var \XF\Service\Thread\Replier|null */
	protected $threadReplier;
	
	/** @var \DBTech\eCommerce\Download\AbstractHandler */
	protected $handler;
	
	/** @var array */
	protected $versionPreparers = [];
	
	/** @var bool */
	protected $performValidations = true;


	/**
	 * Create constructor.
	 *
	 * @param \XF\App $app
	 * @param \DBTech\eCommerce\Entity\Product $product
	 * @param \DBTech\eCommerce\Download\AbstractHandler $handler
	 */
	public function __construct(\XF\App $app, Product $product, AbstractHandler $handler)
	{
		parent::__construct($app);

		$this->product = $product;
		$this->download = $product->getNewDownload();
		$this->changeLogPreparer = $this->service('DBTech\eCommerce:Download\Preparer', $this->download, 'change_log');
		$this->releaseNotesPreparer = $this->service('DBTech\eCommerce:Download\Preparer', $this->download, 'release_notes');
		$this->handler = $handler;
		$this->createVersionPreparers();
		
		$this->setupDefaults();
	}
	
	/**
	 *
	 */
	protected function setupDefaults()
	{
		$category = $this->product->Category;
		$this->download->download_state = $category->getNewContentState($this->product);
	}
	
	/**
	 * @return Product
	 */
	public function getProduct(): Product
	{
		return $this->product;
	}
	
	/**
	 * @return mixed
	 */
	public function getDownload(): \DBTech\eCommerce\Entity\Download
	{
		return $this->download;
	}
	
	/**
	 * @return AbstractHandler|null
	 */
	public function getHandler(): ?AbstractHandler
	{
		return $this->handler;
	}
	
	/**
	 * @return Preparer|\XF\Service\AbstractService
	 */
	public function getChangeLogPreparer()
	{
		return $this->changeLogPreparer;
	}
	
	/**
	 * @return Preparer|\XF\Service\AbstractService
	 */
	public function getReleaseNotesPreparer()
	{
		return $this->releaseNotesPreparer;
	}
	
	/**
	 * @return array
	 */
	public function getVersionPreparers(): array
	{
		return $this->versionPreparers;
	}

	/**
	 * @param bool $perform
	 *
	 * @return $this
	 */
	public function setPerformValidations(bool $perform): Create
	{
		$this->performValidations = $perform;

		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function getPerformValidations(): bool
	{
		return $this->performValidations;
	}
	
	/**
	 *
	 */
	public function setIsAutomated(): Create
	{
		$this->setPerformValidations(false);

		return $this;
	}
	
	/**
	 * @param string $changeLog
	 * @param bool $format
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setChangeLog(string $changeLog, bool $format = true): bool
	{
		return $this->changeLogPreparer->setMessage($changeLog, $format, $this->performValidations);
	}
	
	/**
	 * @param string $releaseNotes
	 * @param bool $format
	 *
	 * @return bool
	 * @throws \LogicException
	 * @throws \InvalidArgumentException
	 */
	public function setReleaseNotes(string $releaseNotes, bool $format = true): bool
	{
		return $this->releaseNotesPreparer->setMessage($releaseNotes, $format, $this->performValidations, true);
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setChangeLogAttachmentHash(string $hash): Create
	{
		$this->changeLogPreparer->setAttachmentHash($hash);

		return $this;
	}

	/**
	 * @param string $hash
	 *
	 * @return $this
	 */
	public function setReleaseNotesAttachmentHash(string $hash): Create
	{
		$this->releaseNotesPreparer->setAttachmentHash($hash);

		return $this;
	}

	/**
	 * @param int $date
	 * @param string $time
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function setDateTime(int $date, string $time): Create
	{
		$download = $this->download;
		$language = \XF::language();
		
		$dateTime = new \DateTime('@' . $date);
		$dateTime->setTimezone($language->getTimeZone());
		
		if (!$time || strpos($time, ':') === false)
		{
			// We didn't have a valid time string
			$hours = $language->date($download->release_date, 'H');
			$minutes = $language->date($download->release_date, 'i');
		}
		else
		{
			[$hours, $minutes] = explode(':', $time);
			
			// Sanitise hours and minutes to a maximum of 23:59
			$hours = min((int)$hours, 23);
			$minutes = min((int)$minutes, 59);
		}
		
		// Finally set it
		$dateTime->setTime($hours, $minutes);
		
		$download->release_date = $dateTime->getTimestamp();

		return $this;
	}
	
	/**
	 *
	 */
	protected function createVersionPreparers()
	{
		$this->versionPreparers = [];
		foreach ($this->product->product_versions as $version => $text)
		{
			if ($this->download->Product->has_demo)
			{
				$this->versionPreparers[$version . '_demo'] =
					$this->service('DBTech\eCommerce:DownloadVersion\Preparer');
				
				$this->versionPreparers[$version . '_demo']->getVersion()->bulkSet([
					'product_version' => $version,
					'product_version_type' => 'demo'
				]);
			}
			
			$this->versionPreparers[$version . '_full'] =
				$this->service('DBTech\eCommerce:DownloadVersion\Preparer');
			
			$this->versionPreparers[$version . '_full']->getVersion()->bulkSet([
				'product_version' => $version,
				'product_version_type' => 'full'
			]);
		}
	}
	
	/**
	 *
	 */
	public function checkForSpam()
	{
		if ($this->download->download_state == 'visible' && \XF::visitor()->isSpamCheckRequired())
		{
			$this->changeLogPreparer->checkForSpam();
			$this->releaseNotesPreparer->checkForSpam();
		}
	}
	
	/**
	 *
	 */
	protected function finalSetup()
	{
	}
	
	/**
	 * @return array
	 */
	protected function _validate(): array
	{
		$this->finalSetup();
		
		$download = $this->download;

		$download->preSave();
		return $download->getErrors();
	}
	
	/**
	 * @return mixed
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	protected function _save(): \DBTech\eCommerce\Entity\Download
	{
		$download = $this->download;
		
		$db = $this->db();
		$db->beginTransaction();
		
		$this->beforeInsert();
		$this->changeLogPreparer->beforeInsert();
		$this->releaseNotesPreparer->beforeInsert();

		$download->save(true, false);
		
		$this->afterInsert();
		$this->changeLogPreparer->afterInsert();
		$this->releaseNotesPreparer->afterInsert();

		$db->commit();

		return $download;
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
		$creator->setPrefix($this->download->Product->Category->thread_prefix_id);
		
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
		$product = $this->product;
		$download = $this->download;
		
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
	
	/**
	 *
	 */
	public function sendNotifications()
	{
		if ($this->download->isVisible() && $this->download->Product->release_count > 1)
		{
			/** @var Notify $notifier */
			$notifier = $this->service('DBTech\eCommerce:Download\Notify', $this->download, 'download');
			$notifier->setMentionedUserIds($this->_getMentionedUserIds());
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
	 * @return array
	 */
	protected function _getMentionedUserIds(): array
	{
		return array_merge(
			$this->changeLogPreparer->getMentionedUserIds(),
			$this->releaseNotesPreparer->getMentionedUserIds()
		);
	}
	
	public function beforeInsert()
	{
	}
	
	/**
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\PrintableException
	 */
	public function afterInsert()
	{
		$download = $this->download;
		$product = $this->product;
		
		/** @var \DBTech\eCommerce\Service\DownloadVersion\Preparer $versionPreparer */
		foreach ($this->versionPreparers as $versionPreparer)
		{
			$version = $versionPreparer->getVersion();
			$version->download_id = $download->download_id;
			$version->save();
			
			$versionPreparer->postSave();
		}
		
		if (
			$download->isVisible()
			&& $download->release_notes
			&& !$product->isAddOn()
		) {
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
}