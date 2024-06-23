<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Conditions extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    public function actionIndex()
    {
        $conditionRepo = $this->getConditionRepo();
        $conditions = $conditionRepo->findConditionsForList();

        $viewParams = [
            'conditions' => $conditions->fetch()
        ];
        return $this->view('Z61\Classifieds:Conditions\Listing', 'z61_classifieds_condition_list', $viewParams);
    }

    public function conditionAddEdit(\Z61\Classifieds\Entity\Condition $condition)
    {
        $viewParams = [
            'condition' => $condition
        ];
        return $this->view('Z61\Classifieds:Condition\Edit', 'z61_classifieds_condition_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        $condition = $this->assertConditionExists($params['condition_id']);
        return $this->conditionAddEdit($condition);
    }

    public function actionAdd()
    {
        $condition = $this->em()->create('Z61\Classifieds:Condition');
        return $this->conditionAddEdit($condition);
    }

    protected function conditionSaveProcess(\Z61\Classifieds\Entity\Condition $condition)
    {
        $entityInput = $this->filter([
            'display_order' => 'uint'
        ]);

        $form = $this->formAction();
        $form->basicEntitySave($condition, $entityInput);

        $titlePhrase = $this->filter('title', 'str');

        $form->validate(function(FormAction $form) use ($titlePhrase)
        {
            if ($titlePhrase === '')
            {
                $form->logError(\XF::phrase('please_enter_valid_title'), 'title');
            }
        });
        $form->apply(function() use ($titlePhrase, $condition)
        {
            $masterTitle = $condition->getMasterPhrase();
            $masterTitle->phrase_text = $titlePhrase;
            $masterTitle->save();
        });

        return $form;
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params['condition_id'])
        {
            $condition = $this->assertConditionExists($params['condition_id']);
        }
        else
        {
            $condition = $this->em()->create('Z61\Classifieds:Condition');
        }

        $this->conditionSaveProcess($condition)->run();

        return $this->redirect($this->buildLink('classifieds/conditions'));
    }

    public function actionDelete(ParameterBag $params)
    {
        $condition = $this->assertConditionExists($params['condition_id']);
        if ($this->isPost())
        {
            $condition->delete();
            return $this->redirect($this->buildLink('classifieds/conditions'));
        }
        else
        {
            $viewParams = [
                'condition' => $condition
            ];
            return $this->view('Z61\Classifieds:Condition\Delete', 'z61_classifieds_condition_delete', $viewParams);
        }
    }

    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('Z61\Classifieds:Condition');
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Z61\Classifieds\Entity\Condition
     */
    protected function assertConditionExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Z61\Classifieds:Condition', $id, $with, $phraseKey);
    }

    /**
     * @return \Z61\Classifieds\Repository\Condition
     */
    protected function getConditionRepo()
    {
        return $this->repository('Z61\Classifieds:Condition');
    }
}