<?php

namespace Z61\Classifieds;

use Z61\Classifieds\Entity\Listing;

class Listener
{
    const DEFAULT_NO_IMAGE = '%s/styles/default/z61/classifieds/no_image.png';
    public static function appSetup(\XF\App $app)
    {
        $container = $app->container();

        $container['prefixes.classifieds_listing'] = $app->fromRegistry('classifiedsListingPrefixes',
            function(\XF\Container $c) { return $c['em']->getRepository('Z61\Classifieds:ListingPrefix')->rebuildPrefixCache(); }
        );

        $container['customFields.classifiedsListings'] = $app->fromRegistry('classifiedsListingFields',
            function(\XF\Container $c) { return $c['em']->getRepository('Z61\Classifieds:ListingField')->rebuildFieldCache(); },
            function(array $fields)
            {
                return new \XF\CustomField\DefinitionSet($fields);
            }
        );
    }

    public static function postDispatchThread(
        \XF\Mvc\Controller $controller, $action, \XF\Mvc\ParameterBag $params, \XF\Mvc\Reply\AbstractReply &$reply
    )
    {
        if (!($reply instanceof \XF\Mvc\Reply\View))
        {
            return;
        }

        $template = $reply->getTemplateName();

        /** @var \XF\Entity\Thread $thread */
        $thread = $reply->getParam('thread');

        if ($template != 'thread_view' || !$thread || $thread->discussion_type != 'classifieds_listing')
        {
            return;
        }

        /** @var \Z61\Classifieds\Entity\Listing $listing */
        $listing = \XF::repository('Z61\Classifieds:Listing')->findListingForThread($thread)->fetchOne();
        if (!$listing || !$listing->canView())
        {
            return;
        }

        $reply->setParam('z61ClassifiedsListing', $listing);
    }

    public static function criteriaUser($rule, array $data, \XF\Entity\User $user, &$returnValue)
    {
        switch ($rule)
        {
            case 'listing_count':
                if (isset($user->z61_classifieds_listing_count) && $user->z61_classifieds_listing_count >= $data['listings'])
                {
                    $returnValue = true;
                }
            break;
            case 'feedback_count':
                if ($user->getRelationOrDefault('ClassifiedsFeedbackInfo')->total >= $data['feedback'])
                {
                    $returnValue = true;
                }
                break;
        }
    }

    public static function userContentChangeInit(\XF\Service\User\ContentChange $changeService, array &$updates)
    {
        $updates['xf_z61_classifieds_category_watch'] = ['user_id', 'emptyable' => false];
        $updates['xf_z61_classifieds_user_feedback'] = ['user_id', 'emptyable' => false];
        $updates['xf_z61_classifieds_listing'] = ['user_id', 'username'];
        $updates['xf_z61_classifieds_listing_watch'] = ['user_id', 'emptyable' => false];
    }

    public static function userDeleteCleanInit(\XF\Service\User\DeleteCleanUp $deleteService, array &$deletes)
    {
        $deletes['xf_z61_classifieds_category_watch'] = 'user_id = ?';
        $deletes['xf_z61_classifieds_listing_watch'] = 'user_id = ?';
    }

    public static function userMergeCombine(
        \XF\Entity\User $target, \XF\Entity\User $source, \XF\Service\User\Merge $mergeService
    )
    {
        $target->z61_classifieds_listing_count += $source->z61_classifieds_listing_count;
    }

    public static function userSearcherOrders(\XF\Searcher\User $userSearcher, array &$sortOrders)
    {
        $sortOrders['z61_classifieds_listing_count'] = \XF::phrase('z61_classifieds_listing_count');
        $sortOrders['z61_classifieds_listing_count'] = \XF::phrase('z61_classifieds_listing_count');
    }

    public static function templaterSetup(\XF\Container $container, \XF\Template\Templater &$templater)
    {
        $templater->addFunction('z61_classifieds_listing_thumbnail', function (\XF\Template\Templater $templater, &$escape, \XF\Mvc\Entity\Entity $entity, $additionalClasses = '', $inline = false, $full = false)
        {
            if (!($entity instanceof Listing))
            {
                trigger_error('Thumbnail content must be an Classifieds listing entity.', E_USER_WARNING);
                return '';
            }

            $escape = false;

            $class = 'listingThumbnail listingThumbnail--listing';
            if ($additionalClasses)
            {
                $class .= " $additionalClasses";
            }

            if (!$entity->isVisible())
            {
                $class .= ' listingThumbnail--notVisible listingThumbnail--notVisible--';
                $class .= $entity->listing_state;
            }

            $thumbnailUrl = null;

            if ($entity->cover_image_id && $entity->CoverImage['thumbnail_url'])
            {
                $thumbnailUrl = $entity->CoverImage['thumbnail_url'];
            }

            $outputUrl = null;
            $hasThumbnail = false;

            if ($thumbnailUrl)
            {

                $outputUrl = !$full ? $thumbnailUrl : \XF::app()->router()->buildLink('attachments', $entity->CoverImage);
                $hasThumbnail = true;
            }

            if (!$hasThumbnail)
            {
                $class .= ' listingThumbnail--noThumb';
                $outputUrl = sprintf(self::DEFAULT_NO_IMAGE, \XF::app()->options()->boardUrl);
                if (\XF::options()->z61ClassifiedsShowAvatarIfNoCover)
                {
                    return $templater->fnAvatar($templater, $escape, $entity->User, 'm');
                }
            }

            $title = $templater->filterForAttr($templater, $entity->title, $null);

            if ($inline)
            {
                $tag = 'span';
            } else
            {
                $tag = 'div';
            }

            return "<$tag class='{$class}'>
				<img class='listingThumbnail-image' src='{$outputUrl}' alt='{$title}' />
				<span class='listingThumbnail-icon'></span>
				</$tag>";
        });
    }
}