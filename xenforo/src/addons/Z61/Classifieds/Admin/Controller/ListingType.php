<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class ListingType extends AbstractController
{
    public function actionIndex(ParameterBag $params)
    {
        $listingTypes = $this->finder('Z61\Classifieds:ListingType')->order('display_order', 'asc')->fetch();

        $viewParmas = [
            'listingTypes' => $listingTypes
        ];

        return $this->view('Z61\Classifieds:ListingType\List', 'z61_classifieds_listing_type_list', $viewParmas);
    }

    public function actionAdd()
    {
        /** @var \Z61\Classifieds\Entity\ListingType $listingType */
        $listingType = $this->em()->create('Z61\Classifieds:ListingType');

        return $this->listingTypeAddEdit($listingType);
    }

    public function actionEdit(ParameterBag $params)
    {
        $listingType = $this->assertListingTypeExists($params->listing_type_id);
        return $this->listingTypeAddEdit($listingType);
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params->listing_type_id)
        {
            $listingType = $this->assertListingTypeExists($params->listing_type_id);
        }
        else
        {
            $listingType = $this->em()->create('Z61\Classifieds:ListingType');
        }

        $this->listingTypeSaveProcess($listingType)->run();

        return $this->redirect($this->buildLink('classifieds/listing-types/'). $this->buildLinkHash($listingType->listing_type_id));
    }

    public function actionDelete(ParameterBag $params)
    {
        $listingType = $this->assertListingTypeExists($params->listing_type_id);

        if ($this->isPost())
        {
            $newListingType =  $this->assertListingTypeExists($this->filter('new_listing_type_id', 'uint'));
            if ($newListingType->listing_type_id == $listingType->listing_type_id)
            {
                $newListingType = $this->finder('Z61\Classifieds:ListingType')
                    ->where('listing_type_id', '!=', $listingType->listing_type_id)
                    ->order('listing_type_id', Finder::ORDER_RANDOM)
                    ->fetchOne();
            }

            $listingType->delete();

            $this->app->jobManager()->enqueue('Z61\Classifieds:ListingTypeRebuild', [
                'listing_type_id' => $listingType->listing_type_id,
                'new_listing_type_id' => $newListingType->listing_type_id
            ]);

            return $this->redirect($this->buildLink('classifieds/listing-types'));
        }
        else
        {
            if ($this->finder('Z61\Classifieds:ListingType')->total() < 2)
            {
                return $this->error(\XF::phrase('z61_classifieds_you_cannot_delete_all_listing_types'));
            }
            if (!$listingType->preDelete())
            {
                return $this->error($listingType->getErrors());
            }

            $listingTypes = $this->finder('Z61\Classifieds:ListingType')
                ->where('listing_type_id', "!=", $listingType->listing_type_id)
                ->order('display_order', 'asc')->keyedBy('listing_type_id')->fetch();

            $viewParams = [
                'listingType' => $listingType,
                'listingTypes' => $listingTypes
            ];
            return $this->view('Z61\Classifieds:ListingType\Delete', 'z61_classifieds_listing_type_delete', $viewParams);
        }
    }

    protected function listingTypeAddEdit(\Z61\Classifieds\Entity\ListingType $listingType)
    {
        $viewParams = [
            'listingType' => $listingType,
        ];

        return $this->view('Z61\Classifieds:ListingType\Edit', 'z61_classifieds_listing_type_edit', $viewParams);
    }

    protected function listingTypeSaveProcess(\Z61\Classifieds\Entity\ListingType $listingType)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'css_class' => 'str',
            'display_order' => 'uint'
        ]);
        $form->basicEntitySave($listingType, $input);

        $titlePhrase = $this->filter('title', 'str');

        $form->validate(function(FormAction $form) use ($titlePhrase)
        {
            if ($titlePhrase === '')
            {
                $form->logError(\XF::phrase('please_enter_valid_title'), 'title');
            }
        });
        $form->apply(function() use ($titlePhrase, $listingType)
        {
            $masterTitle = $listingType->getMasterPhrase();
            $masterTitle->phrase_text = $titlePhrase;
            $masterTitle->save();
        });

        return $form;

    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Z61\Classifieds\Entity\ListingType
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertListingTypeExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Z61\Classifieds:ListingType', $id, $with, $phraseKey);
    }
}