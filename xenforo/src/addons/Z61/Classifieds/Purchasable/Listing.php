<?php

namespace Z61\Classifieds\Purchasable;

use XF\Payment\CallbackState;
use XF\Purchasable\AbstractPurchasable;
use XF\Purchasable\Purchase;

class Listing extends AbstractPurchasable
{
    public function getTitle()
    {
        return \XF::phrase('z61_classifieds_package');
    }

    public function getPurchaseFromRequest(\XF\Http\Request $request, \XF\Entity\User $purchaser, &$error = null)
    {
        /** @var \Z61\Classifieds\Entity\Listing $listing */
        $listing = \XF::em()->find('Z61\Classifieds:Listing', $request->filter('listing_id', 'uint'));

        /** @var \XF\Entity\PaymentProfile $paymentProfile */
        $paymentProfile = \XF::em()->find('XF:PaymentProfile', $request->filter('payment_profile_id', 'uint'));
        if (!$paymentProfile || !$paymentProfile->active)
        {
            $error = \XF::phrase('please_choose_valid_payment_profile_to_continue_with_your_purchase');
            return false;
        }

        $package = $listing->Package;
        if (!$package || !$package->canPurchase())
        {
            $error = \XF::phrase('this_item_cannot_be_purchased_at_moment');
            return false;
        }

        if (!in_array($paymentProfile->payment_profile_id, $package->payment_profile_ids))
        {
            $error = \XF::phrase('selected_payment_profile_is_not_valid_for_this_purchase');
            return false;
        }

        return $this->getPurchaseObject($paymentProfile, $listing, $purchaser);
    }

    public function getPurchasableFromExtraData(array $extraData)
    {
        $output = [
            'link' => '',
            'title' => '',
            'purchasable' => null
        ];
        $listing = \XF::em()->find('Z61\Classifieds:Listing', $extraData['listing_id']);
        if ($listing)
        {
            $output['link'] = \XF::app()->router('public')->buildLink('classifieds/edit', $listing);
            $output['title'] = $listing->title;
            $output['purchasable'] = $listing;
        }
        return $output;
    }

    public function getPurchaseFromExtraData(array $extraData, \XF\Entity\PaymentProfile $paymentProfile, \XF\Entity\User $purchaser, &$error = null)
    {
        $listing = $this->getPurchasableFromExtraData($extraData);
        if (!$listing['purchasable'] || !$listing['purchasable']->canPurchase())
        {
            $error = \XF::phrase('this_item_cannot_be_purchased_at_moment');
            return false;
        }

        if (!in_array($paymentProfile->payment_profile_id, $listing['purchasable']->Package->payment_profile_ids))
        {
            $error = \XF::phrase('selected_payment_profile_is_not_valid_for_this_purchase');
            return false;
        }

        return $this->getPurchaseObject($paymentProfile, $listing['purchasable'], $purchaser);
    }

    public function getPurchaseObject(\XF\Entity\PaymentProfile $paymentProfile, $purchasable, \XF\Entity\User $purchaser)
    {
        $purchase = new Purchase();

        $purchase->title =  $this->getTitle() . ': ' . $purchasable->title;
        $purchase->description = '';
        $purchase->cost = $purchasable->Package->cost_amount;
        $purchase->currency = $purchasable->Package->cost_currency;
        $purchase->recurring = false;
        $purchase->lengthAmount = $purchasable->Package->length_amount;
        $purchase->lengthUnit = $purchasable->Package->length_unit;
        $purchase->purchaser = $purchaser;
        $purchase->paymentProfile = $paymentProfile;
        $purchase->purchasableTypeId = $this->purchasableTypeId;
        $purchase->purchasableId = $purchasable->package_id;
        $purchase->purchasableTitle = $purchasable->Package->title;
        $purchase->extraData = [
            'listing_id' => $purchasable->listing_id
        ];

        $router = \XF::app()->router('public');

        $purchase->returnUrl = $router->buildLink('canonical:classifieds', $purchasable);
        $purchase->cancelUrl = $router->buildLink('canonical:classifieds');

        return $purchase;
    }

    public function completePurchase(CallbackState $state)
    {
        $purchaseRequest = $state->getPurchaseRequest();

        $paymentResult = $state->paymentResult;
        $purchaser = $state->getPurchaser();

        $listing = \XF::em()->find('Z61\Classifieds:Listing', $purchaseRequest->extra_data['listing_id']);

        /** @var \Z61\Classifieds\Service\Purchase\Listing $packageService */
        $packageService = \XF::app()->service('Z61\Classifieds:Purchase\Listing', $listing, $purchaser);

        switch ($paymentResult)
        {
            case CallbackState::PAYMENT_RECEIVED:
                //$packageService->setPurchaseRequestKey($state->requestKey);
                $listing = $packageService->completePurchase();

                $state->logType = 'payment';
                $state->logMessage = 'Payment received, listing active.';
                break;

            case CallbackState::PAYMENT_REINSTATED:
                $state->logType = 'info';
                $state->logMessage = 'OK, no action.';
                break;
        }

        if ($listing && $purchaseRequest)
        {
            $extraData = $purchaseRequest->extra_data;
            $extraData['listing_id'] = $listing->listing_id;
            $purchaseRequest->extra_data = $extraData;
            $purchaseRequest->save();
        }
    }

    public function reversePurchase(CallbackState $state)
    {
	    $purchaseRequest = $state->getPurchaseRequest();
	    $listingId = $purchaseRequest->extra_data['listing_id'];

	    $purchaser = $state->getPurchaser();

	    $listing = \XF::em()->find('Z61\Classifieds:Listing', $listingId);

	    // TODO: revoke

	    $state->logType = 'cancel';
	    $state->logMessage = 'Payment refunded/reversed, revoked.';
    }

    public function getPurchasablesByProfileId($profileId)
    {
	    $finder = \XF::finder('Z61\Classifieds:Package');

	    $quotedProfileId = $finder->quote($profileId);
	    $columnName = $finder->columnSqlName('payment_profile_ids');

	    $router = \XF::app()->router('admin');
	    $packages = $finder->whereSql("FIND_IN_SET($quotedProfileId, $columnName)")->fetch();
	    return $packages->pluck(function(\Z61\Classifieds\Entity\Package $package, $key) use ($router)
	    {
		    return ['z61_classifieds_' . $key, [
			    'title' => $this->getTitle() . ': ' . $package->title,
			    'link' => $router->buildLink('classifieds/packages/edit', $package)
		    ]];
	    }, false);
    }
}