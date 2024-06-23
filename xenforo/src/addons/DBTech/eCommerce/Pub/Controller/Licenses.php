<?php

namespace DBTech\eCommerce\Pub\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Licenses
 *
 * @package DBTech\eCommerce\Pub\Controller
 */
class Licenses extends AbstractController
{
	/**
	 * @param $action
	 * @param ParameterBag $params
	 *
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function preDispatchController($action, ParameterBag $params)
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		switch ($action)
		{
			case 'Licenses':
				if ($visitor->user_id != $params->user_id && !$visitor->canViewDbtechEcommerceLicenses($error))
				{
					throw $this->exception($this->noPermission($error));
				}
				break;
				
			case 'Generate':
				if (!$visitor->dbtech_ecommerce_is_distributor)
				{
					throw $this->exception($this->noPermission());
				}
				break;
		}
	}

	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\Reroute|\XF\Mvc\Reply\Error
	 */
	public function actionIndex(ParameterBag $params)
	{
		if ($params->user_id)
		{
			return $this->rerouteController(__CLASS__, 'Licenses', $params);
		}
			
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		if ($visitor->user_id)
		{
			$params->user_id = $visitor->user_id;
			return $this->rerouteController('DBTech\eCommerce:Licenses', 'Licenses', $params);
		}
		
		/** @var \XF\Entity\MemberStat $memberStat */
		$memberStat = $this->em()->findOne('XF:MemberStat', ['member_stat_key' => 'dbtech_ecommerce_most_licenses']);

		if ($memberStat && $memberStat->canView())
		{
			return $this->redirectPermanently(
				$this->buildLink('members', null, ['key' => $memberStat->member_stat_key])
			);
		}
		
		return $this->redirect($this->buildLink('dbtech-ecommerce'));
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\View
	 * @throws \InvalidArgumentException
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionLicenses(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		/** @var \XF\Entity\User $user */
		$user = $this->assertRecordExists('XF:User', $params->user_id);

		/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
		$licenseRepo = $this->getLicenseRepo();
		$finder = $licenseRepo->findLicensesByUser($user->user_id);

		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/licenses', $user));

		$licenses = $finder->fetch();
		$licenses = $licenses->filterViewable();
		
		$canInlineMod = $hasExpired = false;
		foreach ($licenses AS $license)
		{
			/** @var \DBTech\eCommerce\Entity\License $license */
			if ($license->Product->canUseInlineModeration())
			{
				$canInlineMod = true;
			}
			
			if ($license->isExpired() && $license->canRenew())
			{
				$hasExpired = true;
			}
		}
		
		$viewParams = [
			'user' => $user,
			'tree' => $licenseRepo->createLicenseTree($licenses),
			'hasExpired' => $hasExpired,
//			'canInlineMod' => $canInlineMod
			'canInlineMod' => false
		];
		return $this->view('DBTech\eCommerce:Licenses\List', 'dbtech_ecommerce_license_list', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \InvalidArgumentException
	 * @throws \LogicException
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 * @throws \XF\PrintableException
	 */
	public function actionView(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$licenseKey = (string)$params->license_key;
		if (!\strlen($licenseKey))
		{
			return $this->rerouteController(__CLASS__, 'index', $params);
		}

		$extraWith = ['User', 'Product', 'Product.LatestVersion'];
		
		$license = $this->assertValidLicense($params->license_key, $extraWith);
		
		if ($this->isPost())
		{
			if (!$license->canEdit($error))
			{
				throw $this->exception($this->noPermission($error));
			}
			
			if ($license->Product->isAddOn())
			{
				if (!$license->parent_license_id)
				{
					$parent = $this->assertValidLicense($this->filter('parent_license', 'str'), $extraWith);
					
					/** @var \DBTech\eCommerce\Entity\Product $child */
					foreach ($parent->Product->Children as $child)
					{
						if ($child->product_id == $license->Product->product_id)
						{
							// Valid
							$license->parent_license_id = $parent->license_id;
							break;
						}
					}
					
					if (!$license->parent_license_id)
					{
						throw $this->exception($this->error(\XF::phrase('dbtech_ecommerce_please_choose_valid_parent_license')));
					}
					
					/** @var \XF\CustomField\Set $fieldSet */
					$fieldSet = $license->license_fields;
					$fieldDefinition = $fieldSet->getDefinitionSet()
						->filterEditable($fieldSet, 'user');
					
					$licenseFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
					
					if ($licenseFieldsShown)
					{
						$fieldSet->bulkSet($parent->license_fields_, $licenseFieldsShown);
					}
					
					$license->save();
				}
			}
			else
			{
				/** @var \XF\CustomField\Set $fieldSet */
				$fieldSet = $license->license_fields;
				$fieldDefinition = $fieldSet->getDefinitionSet()
					->filterEditable($fieldSet, 'user');
				
				$licenseFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
				
				if ($licenseFieldsShown)
				{
					$fieldSet->bulkSet($this->filter('license_fields', 'array'), $licenseFieldsShown);
				}
				
				$license->save();
				
				if ($license->Children)
				{
					/** @var \DBTech\eCommerce\Entity\License $child */
					foreach ($license->Children as $child)
					{
						/** @var \XF\CustomField\Set $fieldSet */
						$fieldSet = $child->license_fields;
						$fieldDefinition = $fieldSet->getDefinitionSet()
							->filterEditable($fieldSet, 'user');
						
						$licenseFieldsShown = array_keys($fieldDefinition->getFieldDefinitions());
						
						if ($licenseFieldsShown)
						{
							$fieldSet->bulkSet($license->license_fields_, $licenseFieldsShown);
						}
						
						$child->save();
					}
				}
			}
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/licenses', $license->User));
		}
		
		$errors = $parentLicenses = [];
		$isValid = $license->hasValidLicenseFields('user', $errors);
		
		$warnings = [];
		if ($license->Product->isAddOn())
		{
			if (!$license->parent_license_id)
			{
				$isValid = false;
				$errors = [\XF::phraseDeferred('dbtech_ecommerce_please_choose_valid_parent_license')];
				
				/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
				$licenseRepo = $this->getLicenseRepo();
				
				$parentLicenses = $licenseRepo->findOtherLicensesByUser($license)
					->where('parent_license_id', 0)
					->fetch()
					->filterViewable()
					->filter(function (\DBTech\eCommerce\Entity\License $parent) use ($license): ?\DBTech\eCommerce\Entity\License
					{
						/** @var \DBTech\eCommerce\Entity\License $childLicense */
						foreach ($parent->Children as $childLicense)
						{
							if ($childLicense->product_id == $license->product_id)
							{
								// Already associated
								return null;
							}
						}
					
						/** @var \DBTech\eCommerce\Entity\Product $childProduct */
						foreach ($parent->Product->Children as $childProduct)
						{
							if ($childProduct->product_id == $license->product_id)
							{
								// Valid
								return $parent;
							}
						}
					
						return null;
					})->pluck(function (\DBTech\eCommerce\Entity\License $license): array
					{
						$title = $license->title;
					
						/** @var \XF\CustomField\Set $fieldSet */
						$fieldSet = $license->license_fields;
						$fieldDefinition = $fieldSet->getDefinitionSet()
						->filterGroup('list');
						$definitions = $fieldDefinition->getFieldDefinitions();
					
						/** @var \XF\CustomField\Definition $definition */
						foreach ($definitions as $fieldDefinition)
						{
							$value = $fieldSet->getFieldValue($fieldDefinition['field_id']);
							$title .= ' - ' . ($value ?: 'N/A');
						}
					
						return [$license->license_key, $title];
					});
			}
			elseif (!$isValid)
			{
				$errors = [\XF::phraseDeferred('dbtech_ecommerce_please_update_parent_license_link', [
					'parentLicense' => $this->buildLink('dbtech-ecommerce/licenses/license', $license->Parent)
				])];
			}
		}
		
		$hasDownload = $hasDiscussion = false;
		$groupedDownloads = [];
		$downloads = [];
		if ($license->Product->LatestVersion)
		{
			$visitor = \XF::visitor();

			$productVersions = $license->Product->product_versions;
			if ($productVersions)
			{
				$limit = max((int)($this->options()->dbtechEcommerceDownloadsPerPage / count($productVersions)), 1);
				foreach (\array_reverse($productVersions) as $key => $text)
				{
					$fullVersion = 'FullVersions|' . $key;
					/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
					$downloadRepo = $this->repository('DBTech\eCommerce:Download');
					$downloadFinder = $downloadRepo->findDownloadsInProduct($license->Product)->with([
						'Discussion',
						'Discussion.Forum',
						'Discussion.Forum.Node',
						'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id,
					])->with($fullVersion, true)
					  ->whereOr([
						  [$fullVersion . '.directories', '!=', ''],
						  [$fullVersion . '.attach_count', '>', 0],
						  [$fullVersion . '.download_url', '!=', '']
					])->limit($limit);

					$downloads = $downloadFinder->fetch()->filterViewable();

					$hasDownload = $hasDiscussion = false;
					foreach ($downloads AS $download)
					{
						/** @var \DBTech\eCommerce\Entity\Download $download */
						if ($download->canDownload($license))
						{
							$hasDownload = true;
						}

						if ($download->hasViewableDiscussion())
						{
							$hasDiscussion = true;
						}
					}
					$groupedDownloads[$key] = $downloads;
				}
				$downloads = null;
			}
			else
			{
				/** @var \DBTech\eCommerce\Repository\Download $downloadRepo */
				$downloadRepo = $this->repository('DBTech\eCommerce:Download');
				$downloadFinder = $downloadRepo->findDownloadsInProduct($license->Product)->with([
					'Discussion',
					'Discussion.Forum',
					'Discussion.Forum.Node',
					'Discussion.Forum.Node.Permissions|' . $visitor->permission_combination_id,
				])->limit($this->options()->dbtechEcommerceDownloadsPerPage);

				$downloads = $downloadFinder->fetch()->filterViewable();

				$hasDownload = $hasDiscussion = false;
				foreach ($downloads AS $download)
				{
					/** @var \DBTech\eCommerce\Entity\Download $download */
					if ($download->canDownload($license))
					{
						$hasDownload = true;
					}

					if ($download->hasViewableDiscussion())
					{
						$hasDiscussion = true;
					}
				}
			}
		}
		
		$viewParams = [
			'license'          => $license,
			'isValid'          => $isValid,
			'validationErrors' => $errors,
			'validationWarnings' => $warnings,
			'parentLicenses'   => $parentLicenses,

			'downloads' => $downloads,
			'groupedDownloads' => $groupedDownloads,
			'hasDownload' => $hasDownload,
			'hasDiscussion' => $hasDiscussion
		];
		
		return $this->view('DBTech\eCommerce:Licenses\Info', 'dbtech_ecommerce_license_view', $viewParams);
	}

	/**
	 * @param \XF\Mvc\ParameterBag $params
	 *
	 * @return \XF\Mvc\Reply\AbstractReply
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionSerialKey(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$licenseKey = (string)$params->license_key;
		if (!\strlen($licenseKey))
		{
			return $this->rerouteController(__CLASS__, 'index', $params);
		}

		$extraWith = ['User', 'Product'];

		$license = $this->assertValidLicense($params->license_key, $extraWith);
		if ($license->Product->product_type !== 'dbtech_ecommerce_key')
		{
			return $this->notFound();
		}

		$errors = [];
		$isValid = $license->hasValidLicenseFields('user', $errors);

		$viewParams = [
			'license'          => $license,
			'isValid'          => $isValid,
			'validationErrors' => $errors
		];
		return $this->view(
			'DBTech\eCommerce:Licenses\SerialKey',
			'dbtech_ecommerce_license_serial_key',
			$viewParams
		);
	}

	/**
	 * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \Exception
	 */
	public function actionRenew()
	{
		/** @var \DBTech\eCommerce\Entity\License[]|\XF\Mvc\Entity\ArrayCollection $licenses */
		$licenses = $this->getLicenseRepo()
			->findLicensesToRenew(\XF::visitor(), PHP_INT_MAX)
			->order('expiry_date', 'DESC')
			->keyedBy('license_key')
			->fetch()
			->filter(function (\DBTech\eCommerce\Entity\License $license): ?\DBTech\eCommerce\Entity\License
			{
				if (!$license->canRenew())
				{
					return null;
				}
				
				return $license;
			})
		;
		
		if ($this->isPost())
		{
			$renewals = $this->filter('renewals', 'array-str');
			if (empty($renewals))
			{
				return $this->error(\XF::phrase('dbtech_ecommerce_please_choose_at_least_one_license_to_renew'));
			}
			
			/** @var \DBTech\eCommerce\Service\Order\Creator $creator */
			$creator = $this->service('DBTech\eCommerce:Order\Creator');
			
			foreach ($renewals as $licenseKey)
			{
				if (!$licenses->offsetExists($licenseKey))
				{
					continue;
				}
				
				/** @var \DBTech\eCommerce\Entity\License $license */
				$license = $licenses->offsetGet($licenseKey);

				/** @var \DBTech\eCommerce\Entity\ProductCost $productCost */
				$productCost = $license->Product->Costs->first();
				
				$creator->addItem(
					$license->Product,
					$productCost,
					$license
				);
			}
			
			if (!$creator->validate($errors))
			{
				return $this->error($errors);
			}
			
			$creator->save();
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/checkout'));
		}
		
		$viewParams = [
			'licenses' => $licenses
		];
		return $this->view('DBTech\eCommerce:Licenses\Renew', 'dbtech_ecommerce_license_list_renew', $viewParams);
	}
	
	/**
	 * @param ParameterBag $params
	 * @return \XF\Mvc\Reply\View
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionInfo(ParameterBag $params): \XF\Mvc\Reply\AbstractReply
	{
		$license = $this->assertValidLicense($params->license_key, ['User']);
		
		$viewParams = [
			'license' => $license
		];
		return $this->view('DBTech\eCommerce:Licenses\Info', 'dbtech_ecommerce_license_info', $viewParams);
	}

	/**
	 * @param \DBTech\eCommerce\Entity\Product $product
	 *
	 * @return \DBTech\eCommerce\Service\License\Create
	 * @throws \Exception
	 * @throws \Exception
	 * @throws \Exception
	 */
	protected function setupLicenseCreate(\DBTech\eCommerce\Entity\Product $product): \DBTech\eCommerce\Service\License\Create
	{
		/** @var \DBTech\eCommerce\Service\License\Create $creator */
		$creator = $this->service('DBTech\eCommerce:License\Create', $product);
		
		$creator->setPurchaseDate(\XF::$time, false);
		
		$dateInput = $this->filter([
			'length_amount' => 'uint',
			'length_unit' => 'str',
		]);
		$creator->setDuration('', $dateInput['length_amount'], $dateInput['length_unit']);
		
		return $creator;
	}
	
	/**
	 * @param \DBTech\eCommerce\Service\License\Create $creator
	 */
	protected function finalizeLicenseCreate(\DBTech\eCommerce\Service\License\Create $creator)
	{
		$creator->sendNotifications();
	}
	
	/**
	 * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
	 * @throws \Exception
	 * @throws \XF\Mvc\Reply\Exception
	 */
	public function actionGenerate()
	{
		/** @var \DBTech\eCommerce\XF\Entity\User $visitor */
		$visitor = \XF::visitor();
		
		/** @var \DBTech\eCommerce\Entity\Distributor $distributor */
		$distributor = $this->assertRecordExists('DBTech\eCommerce:Distributor', $visitor->user_id);
		
		if ($this->isPost())
		{
			/** @var \DBTech\eCommerce\Entity\Product $product */
			$product = $this->assertRecordExists('DBTech\eCommerce:Product', $this->filter('product_id', 'uint'), null, 'dbtech_ecommerce_requested_product_not_found');
			
			$userName = $this->filter('recipient', 'str');
			
			/** @var \XF\Entity\User $user **/
			$user = $this->finder('XF:User')->where('username', $userName)->fetchOne();
			if (!$user)
			{
				throw $this->exception($this->error(\XF::phrase('requested_user_x_not_found', ['name' => $userName])));
			}
			
			$license = \XF::asVisitor($user, function () use ($product, $distributor): \DBTech\eCommerce\Entity\License
			{
				/** @var \DBTech\eCommerce\Service\License\Create $creator */
				$creator = $this->setupLicenseCreate($product);
				//			$creator->checkForSpam();
				
				$creator->setMaxExpiryDate($distributor->getEffectiveMaxLength());
				
				if (!$creator->validate($errors))
				{
					throw $this->exception($this->error($errors));
				}
				
				/** @var \DBTech\eCommerce\Entity\License $license */
				$license = $creator->save();
				$this->finalizeLicenseCreate($creator);
				
				return $license;
			});
			
			/** @var \DBTech\eCommerce\Entity\DistributorLog $distributorLog */
			$distributorLog = $this->em()->create('DBTech\eCommerce:DistributorLog');
			$distributorLog->distributor_id = $visitor->user_id;
			$distributorLog->product_id = $product->product_id;
			$distributorLog->license_id = $license->license_id;
			$distributorLog->user_id = $user->user_id;
			$distributorLog->save(true, false);
			
			/** @var \XF\Repository\Ip $ipRepo */
			$ipRepo = $this->repository('XF:Ip');
			$ipEnt = $ipRepo->logIp($user->user_id, $this->request->getIp(), 'dbtech_ecommerce_license', $distributorLog->distributor_log_id, 'generate');
			if ($ipEnt)
			{
				$distributorLog->fastUpdate('ip_id', $ipEnt->ip_id);
			}
			
			return $this->redirect($this->buildLink('dbtech-ecommerce/licenses'));
		}
		
		$this->assertCanonicalUrl($this->buildLink('dbtech-ecommerce/licenses/generate'));
		
		$viewParams = [
			'distributor' => $distributor
		];
		return $this->view('DBTech\eCommerce:Licenses\Generate', 'dbtech_ecommerce_license_generate', $viewParams);
	}

	/**
	 * @param array $activities
	 *
	 * @return bool|\XF\Phrase
	 */
	public static function getActivityDetails(array $activities)
	{
		return \XF::phrase('dbtech_ecommerce_viewing_licenses');
	}
	
	/**
	 * @return \DBTech\eCommerce\Repository\License|\XF\Mvc\Entity\Repository
	 */
	protected function getLicenseRepo()
	{
		return $this->repository('DBTech\eCommerce:License');
	}
	
	/**
	 * @param string|null $licenseKey
	 * @param array $extraWith
	 *
	 * @return \DBTech\eCommerce\Entity\License|null
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertValidLicense(?string $licenseKey, array $extraWith = []): ?\DBTech\eCommerce\Entity\License
	{
		/** @var \DBTech\eCommerce\Repository\License $licenseRepo */
		$licenseRepo = $this->getLicenseRepo();
		
		/** @var \DBTech\eCommerce\Entity\License $license */
		$license = $licenseRepo->findLicenseByKey($licenseKey)->with($extraWith)->fetchOne();
		
		if (!$license)
		{
			throw $this->exception($this->notFound(\XF::phrase('dbtech_ecommerce_requested_license_not_found')));
		}
		
		if (!$license->isValid($error))
		{
			throw $this->exception($this->noPermission($error));
		}
		
		return $license;
	}
	
	/**
	 * @param int|null $id
	 * @param array|string|null $with
	 * @param null|string $phraseKey
	 *
	 * @return \DBTech\eCommerce\Entity\License
	 * @throws \XF\Mvc\Reply\Exception
	 */
	protected function assertLicenseExists(?int $id, $with = null, ?string $phraseKey = null): \DBTech\eCommerce\Entity\License
	{
		return $this->assertRecordExists('DBTech\eCommerce:License', $id, $with, $phraseKey);
	}
}