<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

abstract class AbstractComment extends AbstractController
{
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XenAddons\Showcase\Entity\Item
	 */
	abstract protected function assertViewableAndCommentableContent(ParameterBag $params);

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XenAddons\Showcase\Entity\Item
	 */
	abstract protected function assertViewableContent(ParameterBag $params);
	
	abstract protected function getLinkPrefix();

	public function actionUnread(ParameterBag $params)
	{
		$content = $this->assertViewableContent($params);

		$visitor = \XF::visitor();

		if (!$visitor->user_id)
		{
			return $this->redirect($this->buildLink('showcase', $content));
		}

		$firstUnreadDate = $content->getVisitorReadDate();

		if ($firstUnreadDate <= (\XF::$time - $this->options()->readMarkingDataLifetime * 86400))
		{
			// We have no read marking data for this person, so we don't know whether they've read this before.
			// More than likely, they haven't so we have to take them to the beginning.
			return $this->redirect($this->buildLink('showcase', $content) . '#comments');
		}

		$commentRepo = $this->getCommentRepo();

		$findFirstUnread = $commentRepo->findNextCommentsInContent($content, $firstUnreadDate);

		if ($visitor->Profile->ignored)
		{
			$findFirstUnread->where('user_id', '<>', array_keys($visitor->Profile->ignored));
		}

		$firstUnread = $findFirstUnread->fetchOne();

		if (!$firstUnread)
		{
			$firstUnread = $content->LastComment;
		}

		if (!$firstUnread)
		{
			// sanity check, probably shouldn't happen
			return $this->redirect($this->buildLink('showcase', $content) . '#comments');
		}

		return $this->redirect($this->buildLink('showcase/comments', $firstUnread));
	}

	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();

		$content = $this->assertViewableAndCommentableContent($params);

		$creator = $this->setupCommentCreate($content);
		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}

		$comment = $creator->getComment();

		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
		
		if ($comment->Item->Category && $comment->Item->Category->canUploadAndManageCommentImages())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_comment', $comment, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
		
		return $this->plugin('XF:BbCodePreview')->actionPreview($comment->message, 'sc_comment', $comment->User, $attachments, $comment->Item->canViewCommentImages());
	}

	public function actionDraft(ParameterBag $params)
	{
		$content = $this->assertViewableAndCommentableContent($params);

		/** @var \XF\ControllerPlugin\Draft $draftPlugin */
		$draftPlugin = $this->plugin('XF:Draft');
		return $draftPlugin->actionDraftMessage($content->draft_comment);
	}

	/**
	 * @param \XF\Mvc\Entity\Entity $content
	 *
	 * @return \XenAddons\Showcase\Service\Comment\Creator
	 */
	protected function setupCommentCreate(\XF\Mvc\Entity\Entity $content)
	{
		$message = $this->plugin('XF:Editor')->fromInput('message');

		/** @var \XenAddons\Showcase\Service\Comment\Creator $creator */
		$creator = $this->service('XenAddons\Showcase:Comment\Creator', $content);
		$creator->setMessage($message);
		
		if ($content->Category->canUploadAndManageCommentImages())
		{
			$creator->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		if ($content->canAddCommentPreReg())
		{
			// only returns true when pre-reg commenting is the only option
			$creator->setIsPreRegAction(true);
		}

		return $creator;
	}

	protected function finalizeCommentCreate(\XenAddons\Showcase\Service\Comment\Creator $creator)
	{
		$creator->sendNotifications();

		$content = $creator->getContent();
		$content->draft_comment->delete();

		$visitor = \XF::visitor();

		/** @var \XenAddons\Showcase\Repository\ItemWatch $watchRepo */
		$watchRepo = $this->repository('XenAddons\Showcase:ItemWatch');
		$watchRepo->autoWatchScItem($content, $visitor);
		
		if ($visitor->user_id)
		{
			/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
			$itemRepo = $this->repository('XenAddons\Showcase:Item');
			$itemRepo->markItemCommentsReadByVisitor($content);
		}
	}

	public function actionComment(ParameterBag $params)
	{
		$content = $this->assertViewableAndCommentableContent($params);

		$defaultMessage = '';

		$quote = $this->filter('quote', 'uint');
		if ($quote)
		{
			/** @var \XenAddons\Showcase\Entity\Comment $comment */
			$comment = $this->em()->find('XenAddons\Showcase:Comment', $quote, 'User');
			if ($comment && $comment->item_id == $content->getEntityId() && $comment->canView())
			{
				$defaultMessage = $comment->getQuoteWrapper(
					$this->app->stringFormatter()->getBbCodeForQuote($comment->message, 'sc_comment')
				);
			}
		}
		else if ($this->request->exists('requires_captcha'))
		{
			$defaultMessage = $this->plugin('XF:Editor')->fromInput('message');
		}
		else
		{
			$defaultMessage = $content->draft_comment->message;
		}

		$viewParams = [
			'content' => $content,
			'defaultMessage' => $defaultMessage,
			'linkPrefix' => $this->getLinkPrefix()
		];
		return $this->view('XenAddons\Showcase:Comment\Comment', 'xa_sc_comment', $viewParams);
	}

	public function actionAddComment(ParameterBag $params)
	{
		$this->assertPostOnly();

		$content = $this->assertViewableAndCommentableContent($params);
		
		$isPreRegComment = $content->canReplyToCommentPreReg();
		
		if (!$content->canReplyToComment($error) && !$isPreRegComment)
		{
			return $this->noPermission($error);
		}

		if (!$isPreRegComment)
		{
			if ($this->filter('no_captcha', 'bool')) // JS is disabled so user hasn't seen Captcha.
			{
				$this->request->set('requires_captcha', true);
				return $this->rerouteController('XenAddons\Showcase:ItemComment', 'comment', $params);
			}
			else if (!$this->captchaIsValid())
			{
				return $this->error(\XF::phrase('did_not_complete_the_captcha_verification_properly'));
			}
		}
		
		$creator = $this->setupCommentCreate($content);
		$creator->checkForSpam();

		if (!$creator->validate($errors))
		{
			return $this->error($errors);
		}
		$this->assertNotFlooding('post');
		
		if ($isPreRegComment)
		{
			$preRegPlugin = $this->plugin('XF:PreRegAction');
			return $preRegPlugin->actionPreRegAction(
				'XenAddons\Showcase:Item\Comment',
				$content,
				$this->getPreRegCommentActionData($creator)
			);
		}
		
		$comment = $creator->save();

		$this->finalizeCommentCreate($creator);

		if ($this->filter('_xfWithData', 'bool') && $this->request->exists('last_date') && $comment->canView())
		{
			$commentRepo = $this->getCommentRepo();

			$limit = 3;
			$lastDate = $this->filter('last_date', 'uint');

			/** @var \XF\Mvc\Entity\Finder $commentList */
			$commentList = $commentRepo->findLatestCommentsForContent($content, $lastDate)->limit($limit + 1);
			$comments = $commentList->fetch();

			// We fetched one more comment than needed, if more than $limit comments were returned,
			// we can show the 'there are more comments' notice
			if ($comments->count() > $limit)
			{
				$firstUnshownComment = $comments->first();

				// Remove the extra post
				$comments = $comments->pop();
			}
			else
			{
				$firstUnshownComment = null;
			}

			// put the comments into oldest-first order
			$comments = $comments->reverse(true);
			$last = $comments->last();
			if ($last)
			{
				$lastDate = $last->comment_date;
			}

			$viewParams = [
				'content' => $content,
				'comments' => $comments,
				'linkPrefix' => $this->getLinkPrefix(),
				'firstUnshownComment' => $firstUnshownComment
			];
			$view = $this->view('XenAddons\Showcase:Comment\NewComments', 'xa_sc_comment_new_comments', $viewParams);
			$view->setJsonParam('lastDate', $lastDate);
			return $view;
		}
		else
		{
			return $this->redirect($this->buildLink('showcase/comments', $comment), \XF::phrase('xa_sc_your_comment_has_been_added'));
		}
	}
	
	protected function getPreRegCommentActionData(\XenAddons\Showcase\Service\Comment\Creator $creator)
	{
		$comment = $creator->getComment();
	
		// note: attachments aren't supported
	
		return [
			'message' => $comment->message
		];
	}

	public function actionMultiQuote(ParameterBag $params)
	{
		$this->assertPostOnly();

		$content = $this->assertViewableAndCommentableContent($params);

		/** @var \XF\ControllerPlugin\Quote $quotePlugin */
		$quotePlugin = $this->plugin('XF:Quote');

		$quotes = $this->filter('quotes', 'json-array');
		if (!$quotes)
		{
			return $this->error(\XF::phrase('no_messages_selected'));
		}
		$quotes = $quotePlugin->prepareQuotes($quotes);

		$commentFinder = $this->finder('XenAddons\Showcase:Comment');

		$comments = $commentFinder
			->where('comment_id', array_keys($quotes))
			->order('comment_date', 'DESC')
			->fetch();

		if ($this->request->exists('insert'))
		{
			$insertOrder = $this->filter('insert', 'array');
			return $quotePlugin->actionMultiQuote($comments, $insertOrder, $quotes, 'sc_comment');
		}
		else
		{
			$viewParams = [
				'quotes' => $quotes,
				'comments' => $comments
			];
			return $this->view('XenAddons\Showcase:Comment\MultiQuote', 'xa_sc_comment_multi_quote', $viewParams);
		}
	}
}