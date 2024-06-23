<?php

namespace XenAddons\Showcase\Pub\Controller;

use XF\Mvc\ParameterBag;

class Series extends AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
	
		if (!$visitor->canViewShowcaseItems($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		if (!$visitor->canViewShowcaseSeries($error))
		{
			throw $this->exception($this->noPermission($error));
		}
	
		if ($this->options()->xaScOverrideStyle)
		{
			$this->setViewOption('style_id', $this->options()->xaScOverrideStyle);
		}
	}
	
	public function actionIndex(ParameterBag $params)
	{
		if ($params->series_id)
		{
			return $this->rerouteController(__CLASS__, 'view', $params);
		}

		/** @var \XenAddons\Showcase\ControllerPlugin\SeriesList $seriesListPlugin */
		$seriesListPlugin = $this->plugin('XenAddons\Showcase:SeriesList');

		$categoryParams = $seriesListPlugin->getCategoryListData();
		$viewableCategoryIds = $categoryParams['categories']->keys();
		
		$listParams = $seriesListPlugin->getSeriesListData();
		
		$this->assertValidPage($listParams['page'], $listParams['perPage'], $listParams['total'], 'showcase/series');
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/series', null, $listParams['page']));

		$viewParams = $categoryParams + $listParams;

		return $this->view('XenAddons\Showcase\Series:List', 'xa_sc_series_list', $viewParams);
	}
	
	public function actionFilters()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\Series $seriesListPlugin */
		$seriesListPlugin = $this->plugin('XenAddons\Showcase:SeriesList');
	
		return $seriesListPlugin->actionFilters();
	}
	
	public function actionFeatured()
	{
		/** @var \XenAddons\Showcase\ControllerPlugin\Series $seriesListPlugin */
		$seriesListPlugin = $this->plugin('XenAddons\Showcase:SeriesList');
	
		return $seriesListPlugin->actionFeatured();
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $series
	 *
	 * @return \XenAddons\Showcase\Service\Series\Create
	 */
	protected function setupSeriesCreate(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		/** @var \XenAddons\Showcase\Service\Series\Create $seriesCreator */
		$seriesCreator = $this->service('XenAddons\Showcase:Series\Create', $series);
	
		$seriesCreator->setTitle($this->filter('title', 'str'));
		$seriesCreator->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		$seriesCreator->setDescription($this->filter('description', 'str'));
		
		if ($series->canUploadAndManageSeriesAttachments())
		{
			$seriesCreator->setSeriesAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		$basicFields = $this->filter([
			'og_title' => 'str',
			'meta_title' => 'str',
			'meta_description' => 'str',
			'community_series' => 'bool'
		]);
		$seriesCreator->getSeries()->bulkSet($basicFields);
		
		return $seriesCreator;
	}		
	
	protected function finalizeSeriesCreate(\XenAddons\Showcase\Service\Series\Create $creator)
	{
		$creator->sendNotifications();
	
		$series = $creator->getSeries();
	
		if (\XF::visitor()->user_id)
		{
			if ($series->series_state == 'moderated')
			{
				$this->session()->setHasContentPendingApproval();
			}
		}
	}
	
	public function actionCreateSeries(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if (!$visitor->canCreateShowcaseSeries($error))
		{
			return $this->noPermission($error);
		}
		
		$series = $this->em()->create('XenAddons\Showcase:SeriesItem');
		
		if ($this->isPost())
		{
			$seriesCreator = $this->setupSeriesCreate($series);
			$seriesCreator->checkForSpam();
			
			if (!$seriesCreator->validate($errors))
			{
				return $this->error($errors);
			}
			$this->assertNotFlooding('post');
	
			$series = $seriesCreator->save();
			$this->finalizeSeriesCreate($seriesCreator);
			
			if (!$series->canView())
			{
				return $this->redirect($this->buildLink('showcase/series', null, ['pending_approval' => 1]));
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/series', $series));
			}
		}
		else
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			
			if ($series->canUploadAndManageSeriesAttachments())
			{
				$attachmentData = $attachmentRepo->getEditorData('sc_series', $series);
			}
			else
			{
				$attachmentData = null;
			}
			
			$viewParams = [
				'series' => $series,
				'attachmentData' => $attachmentData,
			];
			return $this->view('XenAddons\Showcase:Series\Create', 'xa_sc_series_create', $viewParams);
		}	
	}
	
	public function actionView(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$series = $this->assertViewableSeries($params->series_id, $this->getSeriesViewExtraWith());
	
		$page = $this->filterPage($params->page);
		$perPage = $this->options()->xaScSeriesItemsPerPage;
	
		$this->assertCanonicalUrl($this->buildPaginatedLink('showcase/series', $series, $page));
	
		$seriesRepo = $this->getSeriesRepo();
		$seriesPartRepo = $this->getSeriesPartRepo();
						
		/** @var \XenAddons\Showcase\Repository\SeriesPart $seriesPartRepo */
		$seriesPartFinder = $seriesPartRepo->findPartsInSeries($series)->with('full');

		$totalSeriesParts = $series->item_count;
		
		$seriesPartFinder->limitByPage($page, $perPage);
		$seriesParts = $seriesPartFinder->fetch();
		$seriesParts = $seriesParts->filterViewable();
	
		$this->assertValidPage($page, $perPage, $totalSeriesParts, 'showcase/series', $series);
	
		$communityContributors = $this->em()->getEmptyCollection();
		if ($series->community_series)
		{
			$communityContributors = $seriesRepo->findCommunityContributors($series)->fetch();
			$communityContributorsIds = $communityContributors->pluckNamed('user_id');
		}
		$totalContributors = $communityContributors->count();
		
		$poll = ($series->has_poll ? $series->Poll : null);
		
		// Only log views by non contributors (contributors include Series Owner and Authors of any items in a community series)
		if (
			($series->user_id == $visitor->user_id) 
			|| ($totalContributors && in_array($visitor->user_id, $communityContributorsIds))
		)
		{
			// do nothing as we don't want to log views for Series Owner's or Authors of any Items in a community series)
		}
		else
		{	
			$seriesRepo->logSeriesView($series);
		}
		
		$viewParams = [
			'series' => $series,
			'seriesParts' => $seriesParts,
			
			'poll' => $poll,
				
			'page' => $page,
			'perPage' => $perPage,
			'total' => $totalSeriesParts,
			
			'communityContributors' => $communityContributors,
			'totalContributors' => $totalContributors			
		];
		return $this->view('XenAddons\Showcase\Series:Series\View', 'xa_sc_series_view', $viewParams);
	}
	
	protected function getSeriesViewExtraWith()
	{
		$extraWith = ['Featured'];
	
		$userId = \XF::visitor()->user_id;
		if ($userId)
		{
			$extraWith[] = 'Watch|' . $userId;
			$extraWith[] = 'Reactions|' . $userId;
			$extraWith[] = 'Bookmarks|' . $userId;
		}
	
		return $extraWith;
	}	
	
	public function actionDetails(ParameterBag $params)
	{
		/** @var \XenAddons\Showcase\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		$series = $this->assertViewableSeries($params->series_id, $this->getSeriesViewExtraWith());
		
		$this->assertCanonicalUrl($this->buildLink('showcase/series/details', $series));
		
		$seriesRepo = $this->getSeriesRepo();
		
		$communityContributors = $this->em()->getEmptyCollection();
		if ($series->community_series)
		{
			$communityContributors = $seriesRepo->findCommunityContributors($series)->fetch();
			$communityContributorsIds = $communityContributors->pluckNamed('user_id');
		}
		$totalContributors = $communityContributors->count();
		
		$poll = ($series->has_poll ? $series->Poll : null);
		
		// Only log views by non contributors (contributors include Series Owner and Authors of any items in a community series)
		if (
		($series->user_id == $visitor->user_id)
		|| ($totalContributors && in_array($visitor->user_id, $communityContributorsIds))
		)
		{
			// do nothing as we don't want to log views for Series Owner's or Authors of any Items in a community series)
		}
		else
		{
			$seriesRepo->logSeriesView($series);
		}
		
		$viewParams = [
			'series' => $series,
			
			'poll' => $poll,
				
			'communityContributors' => $communityContributors,
			'totalContributors' => $totalContributors
		];
		return $this->view('XenAddons\Showcase\Series:Series\Details', 'xa_sc_series_details', $viewParams);		
	}
	
	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $series
	 *
	 * @return \XenAddons\Showcase\Service\Series\Edit
	 */
	protected function setupSeriesEdit(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		/** @var \XenAddons\Showcase\Service\Series\Edit $editor */
		$editor = $this->service('XenAddons\Showcase:Series\Edit', $series);

		$editor->setTitle($this->filter('title', 'str'));
		
		$editor->setMessage($this->plugin('XF:Editor')->fromInput('message'));
		
		$series->edit_date = time();
		
		$basicFields = $this->filter([
			'og_title' => 'str',
			'meta_title' => 'str',
			'description' => 'str',
			'meta_description' => 'str',
			'community_series' => 'bool'
		]);
		$series->bulkSet($basicFields);
		
		if ($series->canUploadAndManageSeriesAttachments())
		{
			$editor->setAttachmentHash($this->filter('attachment_hash', 'str'));
		}
		
		if ($this->filter('author_alert', 'bool') && $series->canSendModeratorActionAlert())
		{
			$editor->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
		}
		
		return $editor;
	}	
	
	public function actionEdit(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canEdit($error))
		{
			return $this->noPermission($error);
		}	

		if ($this->isPost())
		{
			$editor = $this->setupSeriesEdit($series);
			$editor->checkForSpam();
			
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
			
			$series = $editor->save();
			
			if ($this->filter('post_as_update', 'bool'))
			{
				$editor->sendNotifications();
			}

			return $this->redirect($this->buildLink('showcase/series/details', $series));
		}
		else
		{
			if ($series && $series->canUploadAndManageSeriesAttachments())
			{
				/** @var \XF\Repository\Attachment $attachmentRepo */
				$attachmentRepo = $this->repository('XF:Attachment');
				$attachmentData = $attachmentRepo->getEditorData('sc_series', $series);
			}
			else
			{
				$attachmentData = null;
			}
			
			$viewParams = [
				'series' => $series,
				'attachmentData' => $attachmentData,
			];
			return $this->view('XenAddons\Showcase:Series\Edit', 'xa_sc_series_edit', $viewParams);
		}	
	}
	
	public function actionPreview(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		if ($params->series_id)
		{
			$series = $this->assertViewableSeries($params->series_id);
			if (!$series->canEdit($error))
			{
				return $this->noPermission($error);
			}
				
			$editor = $this->setupSeriesEdit($series);
				
			if (!$editor->validate($errors))
			{
				return $this->error($errors);
			}
		}
		else
		{
			$series = $this->em()->create('XenAddons\Showcase:SeriesItem');
				
			$creator = $this->setupSeriesCreate($series);
	
			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
		}
	
		$attachments = [];
		$tempHash = $this->filter('attachment_hash', 'str');
	
		if ($series->canUploadAndManageSeriesAttachments())
		{
			/** @var \XF\Repository\Attachment $attachmentRepo */
			$attachmentRepo = $this->repository('XF:Attachment');
			$attachmentData = $attachmentRepo->getEditorData('sc_series', $series, $tempHash);
			$attachments = $attachmentData['attachments'];
		}
	
		return $this->plugin('XF:BbCodePreview')->actionPreview(
			$series->message, 'sc_series', $series->User, $attachments, $series->canViewSeriesAttachments()
		);
	}
	
	public function actionEditIcon(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canEditIcon($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$this->plugin('XenAddons\Showcase:SeriesIcon')->actionUpload($series);
	
			return $this->redirect($this->buildLink('showcase/series', $series));
		}
		else
		{
			$viewParams = [
				'series' => $series,
			];
			return $this->view('XenAddons\Showcase:SeriesItem\EditIcon', 'xa_sc_series_edit_icon', $viewParams);
		}
	}	
	
	public function actionDelete(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canDelete('soft', $error))
		{
			return $this->noPermission($error);
		}

		if ($this->isPost())
		{
			$type = $this->filter('hard_delete', 'bool') ? 'hard' : 'soft';
			$reason = $this->filter('reason', 'str');
			
			if (!$series->canDelete($type, $error))
			{
				return $this->noPermission($error);
			}
			
			/** @var \XenAddons\Showcase\Service\Series\Deleter $deleter */
			$deleter = $this->service('XenAddons\Showcase:Series\Deleter', $series);
			
			if ($this->filter('author_alert', 'bool'))
			{
				$deleter->setSendAlert(true, $this->filter('author_alert_reason', 'str'));
			}
			
			$deleter->delete($type, $reason);

			$this->plugin('XF:InlineMod')->clearIdFromCookie('sc_series', $series->series_id);
			
			return $this->redirect($this->buildLink('showcase/series'));
		}
		else
		{
			$viewParams = [
				'series' => $series,
			];
			return $this->view('XenAddons\Showcase:Series\Delete', 'xa_sc_series_delete', $viewParams);
		}
	}
	
	public function actionUndelete(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canUndelete($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			if ($series->series_state == 'deleted')
			{
				$series->series_state = 'visible';
				$series->save();
			}
	
			return $this->redirect($this->buildLink('showcase/series', $series));
		}
		else
		{
			$viewParams = [
				'series' => $series,
			];
			return $this->view('XF:SeriesItem\Undelete', 'xa_sc_series_undelete', $viewParams);
		}
	}
	
	public function actionReassign(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canReassign($error))
		{
			return $this->noPermission($error);
		}
	
		if ($this->isPost())
		{
			$user = $this->em()->findOne('XF:User', ['username' => $this->filter('username', 'str')]);
			if (!$user)
			{
				return $this->error(\XF::phrase('requested_user_not_found'));
			}
	
			/** @var \XenAddons\Showcase\Service\Series\Reassign $reassigner */
			$reassigner = $this->service('XenAddons\Showcase:Series\Reassign', $series);
	
			if ($this->filter('alert', 'bool'))
			{
				$reassigner->setSendAlert(true, $this->filter('alert_reason', 'str'));
			}
	
			$reassigner->reassignTo($user);
	
			return $this->redirect($this->buildLink('showcase/series', $series));
		}
		else
		{
			$viewParams = [
				'series' => $series,
			];
			return $this->view('XenAddons\Showcase:Series\Reassign', 'xa_sc_series_reassign', $viewParams);
		}
	}
	
	public function actionQuickFeature(ParameterBag $params)
	{
		$this->assertPostOnly();
	
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canFeatureUnfeature($error))
		{
			return $this->error($error);
		}
	
		/** @var \XenAddons\Showcase\Service\Series\Feature $featurer */
		$featurer = $this->service('XenAddons\Showcase:Series\Feature', $series);
	
		if ($series->Featured)
		{
			$featurer->unfeature();
			$featured = false;
			$text = \XF::phrase('xa_sc_series_quick_feature');
		}
		else
		{
			$featurer->feature();
			$featured = true;
			$text = \XF::phrase('xa_sc_series_quick_unfeature');
		}
	
		$reply = $this->redirect($this->getDynamicRedirect());
		$reply->setJsonParams([
			'text' => $text,
			'featured' => $featured
		]);
		return $reply;
	}
	
	public function actionBookmark(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
	
		/** @var \XF\ControllerPlugin\Bookmark $bookmarkPlugin */
		$bookmarkPlugin = $this->plugin('XF:Bookmark');
	
		return $bookmarkPlugin->actionBookmark(
			$series, $this->buildLink('showcase/series/bookmark', $series)
		);
	}
	
	public function actionTags(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canEditTags($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\Service\Tag\Changer $tagger */
		$tagger = $this->service('XF:Tag\Changer', 'sc_series', $series);
	
		if ($this->isPost())
		{
			$tagger->setEditableTags($this->filter('tags', 'str'));
			if ($tagger->hasErrors())
			{
				return $this->error($tagger->getErrors());
			}
	
			$tagger->save();
				
			if ($this->filter('_xfInlineEdit', 'bool'))
			{
				$viewParams = [
					'series' => $series
				];
				$reply = $this->view('XenAddons\Showcase:SeriesItem\TagsInline', 'xa_sc_series_tags_list', $viewParams);
				$reply->setJsonParam('message', \XF::phrase('your_changes_have_been_saved'));
				return $reply;
			}
			else
			{
				return $this->redirect($this->buildLink('showcase/series', $series));
			}
		}
		else
		{
			$grouped = $tagger->getExistingTagsByEditability();
	
			$viewParams = [
				'series' => $series,
				'editableTags' => $grouped['editable'],
				'uneditableTags' => $grouped['uneditable']
			];
			return $this->view('XenAddons\Showcase:SeriesItem\Tags', 'xa_sc_series_tags', $viewParams);
		}
	}	
	
	public function actionWatch(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canWatch($error))
		{
			return $this->noPermission($error);
		}
	
		$visitor = \XF::visitor();
	
		if ($this->isPost())
		{
			if ($this->filter('stop', 'bool'))
			{
				$action = 'delete';
				$config = [];
			}
			else
			{
				$action = 'watch';
				$config = $this->filter([
					'notify_on' => 'str',
					'send_alert' => 'bool',
					'send_email' => 'bool',
				]);
			}
	
			/** @var \XenAddons\Showcase\Repository\SeriesWatch $watchRepo */
			$watchRepo = $this->repository('XenAddons\Showcase:SeriesWatch');
			$watchRepo->setWatchState($series, $visitor, $action, $config);
	
			$redirect = $this->redirect($this->buildLink('showcase/series', $series));
			$redirect->setJsonParam('switchKey', $action == 'delete' ? 'watch' : 'unwatch');
			return $redirect;
		}
		else
		{
			$viewParams = [
				'series' => $series,
				'isWatched' => !empty($series->Watch[$visitor->user_id])
			];
			return $this->view('XenAddons\Showcase:Series\Watch', 'xa_sc_series_watch', $viewParams);
		}
	}
	
	public function actionIp(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Ip $ipPlugin */
		$ipPlugin = $this->plugin('XF:Ip');
		return $ipPlugin->actionIp($series, $breadcrumbs);
	}
	
	public function actionReport(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canReport($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XF\ControllerPlugin\Report $reportPlugin */
		$reportPlugin = $this->plugin('XF:Report');
		return $reportPlugin->actionReport(
			'sc_series', $series,
			$this->buildLink('showcase/series/report', $series),
			$this->buildLink('showcase/series', $series)
		);
	}
	
	public function actionWarn(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
	
		if (!$series->canWarn($error))
		{
			return $this->noPermission($error);
		}
	
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Warn $warnPlugin */
		$warnPlugin = $this->plugin('XF:Warn');
		return $warnPlugin->actionWarn(
			'sc_series', $series,
			$this->buildLink('showcase/series/warn', $series),
			$breadcrumbs
		);
	}

	public function actionApprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		/** @var \XenAddons\Showcase\Service\Series\Approve $approver */
		$approver = \XF::service('XenAddons\Showcase:Series\Approve', $series);
		$approver->approve();
	
		return $this->redirect($this->buildLink('showcase/series/details', $series));
	}
	
	public function actionUnapprove(ParameterBag $params)
	{
		$this->assertValidCsrfToken($this->filter('t', 'str'));
	
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canApproveUnapprove($error))
		{
			return $this->noPermission($error);
		}
	
		$series->series_state = 'moderated';
		$series->save();
	
		return $this->redirect($this->buildLink('showcase/series/details', $series));
	}
	
	public function actionModeratorActions(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canViewModeratorLogs($error))
		{
			return $this->noPermission($error);
		}
	
		$breadcrumbs = $series->getBreadcrumbs();
		$title = $series->title;
	
		$this->request()->set('page', $params->page);
	
		/** @var \XF\ControllerPlugin\ModeratorLog $modLogPlugin */
		$modLogPlugin = $this->plugin('XF:ModeratorLog');
		return $modLogPlugin->actionModeratorActions(
			$series,
			['showcase/series/moderator-actions', $series],
			$title, $breadcrumbs
		);
	}
	
	public function actionHistory(ParameterBag $params)
	{
		return $this->rerouteController('XF:EditHistory', 'index', [
			'content_type' => 'sc_series',
			'content_id' => $params->series_id
		]);
	}
	
	public function actionReact(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactSimple($series, 'showcase/series');
	}
	
	public function actionReactions(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
	
		$breadcrumbs = $series->getBreadcrumbs();
		$title = \XF::phrase('xa_sc_members_who_reacted_to_series', ['title' => $series->title]);
	
		$this->request()->set('page', $params->page);
	
		/** @var \XF\ControllerPlugin\Reaction $reactionPlugin */
		$reactionPlugin = $this->plugin('XF:Reaction');
		return $reactionPlugin->actionReactions(
			$series,
			'showcase/series/reactions',
			$title, $breadcrumbs
		);
	}	

	public function actionDialogYours()
	{
		$page = $this->filterPage();
		$perPage = $this->options()->xaScSeriesPerPage;
		
		$seriesRepo = $this->getSeriesRepo();
		
		$seriesList = $seriesRepo->findSeriesForSeriesList()
			->where('user_id', \XF::visitor()->user_id)
			->limitByPage($page, $perPage, 1);
		
		$series = $seriesList->fetch();
	
		$hasMore = false;
		if ($series->count() > $perPage)
		{
			$hasMore = true;
			$series = $series->slice(0, $perPage);
		}
	
		$viewParams = [
			'series' => $series,
			'page' => $page,
			'hasMore' => $hasMore
		];
		return $this->view('XenAddons\Showcase:Series\Dialog\Yours', 'xa_sc_dialog_your_series', $viewParams);
	}
	
	public function actionDialogBrowse()
	{
		$page = $this->filterPage();
		$perPage = $this->options()->xaScSeriesPerPage;		
		
		$seriesRepo = $this->getSeriesRepo();
		
		$seriesList = $seriesRepo->findSeriesForSeriesList()
			->where('user_id', '<>', \XF::visitor()->user_id)
			->limitByPage($page, $perPage, 1);
		
		$series = $seriesList->fetch();
	
		$hasMore = false;
		if ($series->count() > $perPage)
		{
			$hasMore = true;
			$series = $series->slice(0, $perPage);
		}
	
		$viewParams = [
			'series' => $series,
			'page' => $page,
			'hasMore' => $hasMore
		];
		return $this->view('XenAddons\Showcase:Series\Dialog\Browse', 'xa_sc_dialog_browse_series', $viewParams);
	}

	public function actionPollCreate(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
	
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionCreate('sc_series', $series, $breadcrumbs);
	}
	
	public function actionPollEdit(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		$poll = $series->Poll;
	
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionEdit($poll, $breadcrumbs);
	}
	
	public function actionPollDelete(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		$poll = $series->Poll;
	
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionDelete($poll, $breadcrumbs);
	}
	
	public function actionPollVote(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		$poll = $series->Poll;
	
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionVote($poll, $breadcrumbs);
	}
	
	public function actionPollResults(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		$poll = $series->Poll;
	
		$breadcrumbs = $series->getBreadcrumbs();
	
		/** @var \XF\ControllerPlugin\Poll $pollPlugin */
		$pollPlugin = $this->plugin('XF:Poll');
		return $pollPlugin->actionResults($poll, $breadcrumbs);
	}	
	
	/**
	 * @param \XenAddons\Showcase\Entity\SeriesItem $series
	 *
	 * @return \XenAddons\Showcase\Service\Series\AddSeriesPart
	 */
	protected function setupAddSeriesPart(\XenAddons\Showcase\Entity\SeriesItem $series)
	{
		/** @var \XenAddons\Showcase\Service\Series\AddSeriesPart $seriesPartAdder */
		$seriesPartAdder = $this->service('XenAddons\Showcase:Series\AddSeriesPart', $series);
		
		$basicFields = $this->filter([
			'display_order' => 'int',
			'item_id' => 'int'
		]);

		if ($itemUrl = $this->filter('item_url', 'string'))
		{
			$item = $this->repository('XenAddons\Showcase:Item')->getItemFromUrl($itemUrl, 'public', $error);
		
			$basicFields['item_id'] = $item->item_id;
		}		
		
		$seriesPartAdder->getSeriesPart()->bulkSet($basicFields);
		
		return $seriesPartAdder;
	}
	
	protected function finalizeAddSeriesPart(\XenAddons\Showcase\Service\Series\AddSeriesPart $seriesPartAdder)
	{
		$seriesPartAdder->sendNotifications(); 
	
		$seriesPart = $seriesPartAdder->getSeriesPart();
	}
	
	public function actionAddItem(ParameterBag $params)
	{
		$series = $this->assertViewableSeries($params->series_id);
		if (!$series->canAddItemToSeries($error))
		{
			return $this->error($error);
		}
		
		$visitor = \XF::visitor();
		
		if ($this->isPost())
		{
			if ($this->filter('item_id', 'int') || $this->filter('item_url', 'string'))
			{
				if ($itemUrl = $this->filter('item_url', 'string'))
				{
					$item = $this->repository('XenAddons\Showcase:Item')->getItemFromUrl($itemUrl, 'public', $error);

					if ($error)
					{
						return $this->error($error);
					}
				}
				
				// continue  
			}
			else 
			{
				return $this->error(\XF::phrase('xa_sc_you_must_select_recent_item_or_set_a_valid_item_url'));
			}
			
			$creator = $this->setupAddSeriesPart($series);
				
			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
				
			$seriesPart = $creator->save();
			
			$this->finalizeAddSeriesPart($creator); 
			
			return $this->redirect($this->buildLink('showcase/series', $series));
		}
		else
		{
			/** @var \XenAddons\Showcase\Repository\Item $itemRepo */
			$itemRepo = $this->getItemRepo();
			$itemFinder = $itemRepo->findItemsForSelectList($visitor->user_id)
				->where('series_part_id', '=', 0)
				->setDefaultOrder('last_update', 'desc');
			
			$items = $itemFinder->fetch(50);
			
			$seriesPart = $series->getNewSeriesPart();
			
			$viewParams = [
				'seriesPart' => $seriesPart,
				'series' => $series,
				'items' => $items
			];
			return $this->view('XenAddons\Showcase:Series\AddItem', 'xa_sc_series_add_item', $viewParams);
		}		
	}

	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities, \XF::phrase('xa_sc_viewing_series'), 'series_id',
			function(array $ids)
			{
				$series = \XF::em()->findByIds(
					'XenAddons\Showcase:SeriesItem',
					$ids
				);
	
				$router = \XF::app()->router('public');
				$data = [];
	
				foreach ($series->filterViewable() AS $id => $series)
				{
					$data[$id] = [
						'title' => $series->title,
						'url' => $router->buildLink('showcase/series', $series)
					];
				}
	
				return $data;
			},
			\XF::phrase('xa_sc_viewing_series')
		);
	}
}