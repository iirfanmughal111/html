<?php
/**
 * @license
 * Copyright 2018 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\InlineMod\Group;

use XF;
use function count;
use XF\Http\Request;
use Truonglv\Groups\App;
use XF\Mvc\Entity\Entity;
use InvalidArgumentException;
use XF\InlineMod\AbstractAction;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Entity\Category;
use XF\Mvc\Entity\AbstractCollection;

class Move extends AbstractAction
{
    /**
     * @var int
     */
    protected $targetCategoryId;
    /**
     * @var Category|null
     */
    protected $targetCategory;

    /**
     * @return \XF\Phrase
     */
    public function getTitle()
    {
        return XF::phrase('tlg_move_groups');
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyToEntity(Entity $entity, array $options, & $error = null)
    {
        if (!($entity instanceof Group)) {
            return false;
        }

        return $entity->canMove($error);
    }

    /**
     * @param Entity $entity
     * @param array $options
     * @throws \XF\PrintableException
     * @return void
     */
    protected function applyToEntity(Entity $entity, array $options)
    {
        if (!($entity instanceof Group)) {
            throw new InvalidArgumentException('Invalid entity provided.');
        }

        $category = $this->getTargetCategory($options['category_id']);
        if ($category === null) {
            throw new InvalidArgumentException('No category specified.');
        }

        $entity->category_id = $category->category_id;
        $entity->save();

        $this->returnUrl = $this->app()->router('public')->buildLink('group-categories', $category);
    }

    /**
     * @param AbstractCollection $entities
     * @param array $options
     * @param mixed $error
     * @return bool
     */
    protected function canApplyInternal(AbstractCollection $entities, array $options, & $error)
    {
        $result = (bool) parent::canApplyInternal($entities, $options, $error);

        if ($result !== false) {
            if ($options['category_id'] > 0) {
                $category = $this->getTargetCategory($options['category_id']);
                if ($category === null) {
                    return false;
                }

                if ($options['check_viewable'] !== false && !$category->canView($error)) {
                    return false;
                }

                if ($options['check_all_same'] !== false) {
                    $allSame = true;
                    foreach ($entities as $entity) {
                        /** @var \Truonglv\Groups\Entity\Group $entity */
                        if ($entity->category_id != $options['category_id']) {
                            $allSame = false;

                            break;
                        }
                    }

                    if ($allSame) {
                        $error = XF::phraseDeferred('tlg_all_groups_in_destination_category');

                        return false;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getBaseOptions()
    {
        return [
            'category_id' => 0,
            'check_viewable' => true,
            'check_all_same' => true,
        ];
    }

    /**
     * @param AbstractCollection $entities
     * @param Request $request
     * @return array
     */
    public function getFormOptions(AbstractCollection $entities, Request $request)
    {
        return [
            'category_id' => $request->filter('category_id', 'uint')
        ];
    }

    /**
     * @param AbstractCollection $entities
     * @param \XF\Mvc\Controller $controller
     * @return \XF\Mvc\Reply\View
     */
    public function renderForm(AbstractCollection $entities, \XF\Mvc\Controller $controller)
    {
        $viewParams = [
            'groups' => $entities,
            'total' => count($entities),
            'nodeTree' => App::categoryRepo()->createCategoryTree()
        ];

        return $controller->view(
            '',
            'tlg_inline_mod_group_move',
            $viewParams
        );
    }

    /**
     * @param int $categoryId
     * @return Category|null
     */
    protected function getTargetCategory($categoryId)
    {
        $categoryId = intval($categoryId);

        if ($this->targetCategoryId > 0 && $this->targetCategoryId === $categoryId) {
            return $this->targetCategory;
        }

        if ($categoryId <= 0) {
            return null;
        }

        /** @var \Truonglv\Groups\Entity\Category|null $category */
        $category = $this->app()->em()->find('Truonglv\Groups:Category', $categoryId);
        if ($category === null) {
            throw new InvalidArgumentException('Invalid target category (' . $categoryId . ')');
        }

        $this->targetCategoryId = $categoryId;
        $this->targetCategory = $category;

        return $this->targetCategory;
    }
}
