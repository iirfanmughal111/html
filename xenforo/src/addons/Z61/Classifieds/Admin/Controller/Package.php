<?php

namespace Z61\Classifieds\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

class Package extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('classifieds');
    }

    public function actionIndex()
    {
        $packageRepo = $this->getPackageRepo();
        $packages = $packageRepo->findPackagesForList();

        $viewParams = [
            'packages' => $packages->fetch(),
        ];
        return $this->view('Z61\Classifieds:Packages\Listing', 'z61_classifieds_package_list', $viewParams);
    }

    public function packageAddEdit(\Z61\Classifieds\Entity\Package $package)
    {
        $paymentRepo = $this->repository('XF:Payment');
        $paymentProfiles = $paymentRepo->findPaymentProfilesForList()->fetch();

        $viewParams = [
            'package' => $package,
            'profiles' => $paymentProfiles,
            'totalProfiles' => $paymentProfiles->count(),
        ];
        return $this->view('Z61\Classifieds:Package\Edit', 'z61_classifieds_package_edit', $viewParams);
    }

    public function actionEdit(ParameterBag $params)
    {
        $package = $this->assertPackageExists($params['package_id']);
        return $this->packageAddEdit($package);
    }

    public function actionAdd()
    {
        $package = $this->em()->create('Z61\Classifieds:Package');
        return $this->packageAddEdit($package);
    }

    protected function packageSaveProcess(\Z61\Classifieds\Entity\Package $package)
    {
        $entityInput = $this->filter([
            'display_order' => 'uint',
            'cost_amount' => 'unum',
            'cost_currency' => 'str',
            'length_amount' => 'uint',
            'length_unit' => 'string',
            'payment_profile_ids' => 'array-uint',
            'active' => 'bool'
        ]);

        $form = $this->formAction();
        $form->basicEntitySave($package, $entityInput);

        $titlePhrase = $this->filter('title', 'str');

        $form->validate(function(FormAction $form) use ($titlePhrase)
        {
            if ($titlePhrase === '')
            {
                $form->logError(\XF::phrase('please_enter_valid_title'), 'title');
            }
        });
        $form->apply(function() use ($titlePhrase, $package)
        {
            $masterTitle = $package->getMasterPhrase();
            $masterTitle->phrase_text = $titlePhrase;
            $masterTitle->save();
        });

        return $form;
    }

    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params['package_id'])
        {
            $package = $this->assertPackageExists($params['package_id']);
        }
        else
        {
            $package = $this->em()->create('Z61\Classifieds:Package');
        }

        $this->packageSaveProcess($package)->run();

        return $this->redirect($this->buildLink('classifieds/packages'));
    }

    public function actionDelete(ParameterBag $params)
    {
        $package = $this->assertPackageExists($params['package_id']);
        if ($this->isPost())
        {
            $package->delete();
            return $this->redirect($this->buildLink('classifieds/packages'));
        }
        else
        {
            $viewParams = [
                'package' => $package
            ];
            return $this->view('Z61\Classifieds:Package\Delete', 'z61_classifieds_package_delete', $viewParams);
        }
    }

    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('Z61\Classifieds:Package');
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \Z61\Classifieds\Entity\Package
     */
    protected function assertPackageExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('Z61\Classifieds:Package', $id, $with, $phraseKey);
    }

    /**
     * @return \Z61\Classifieds\Repository\Package
     */
    protected function getPackageRepo()
    {
        return $this->repository('Z61\Classifieds:Package');
    }
}