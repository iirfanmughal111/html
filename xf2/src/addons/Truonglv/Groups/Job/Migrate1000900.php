<?php
/**
 * @license
 * Copyright 2019 TruongLuu. All Rights Reserved.
 */

namespace Truonglv\Groups\Job;

use XF;
use XF\Timer;
use Exception;
use XF\Util\File;
use function count;
use function floor;
use XF\Http\Upload;
use function sprintf;
use function basename;
use XF\Job\AbstractJob;
use function json_encode;
use Truonglv\Groups\Entity\Group;
use Truonglv\Groups\Service\Group\AbstractFormUpload;

class Migrate1000900 extends AbstractJob
{
    /**
     * @var array
     */
    protected $defaultData = [
        'avatars' => [],
        'covers' => []
    ];

    /**
     * @return bool
     */
    public function canTriggerByChoice()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canCancel()
    {
        return false;
    }

    /**
     * @param mixed $maxRunTime
     * @return \XF\Job\JobResult
     */
    public function run($maxRunTime)
    {
        $data = $this->data;
        if (count($data['avatars']) === 0 && count($data['covers']) === 0) {
            return $this->complete();
        }

        $timer = new Timer($maxRunTime);

        if (count($data['avatars']) > 0) {
            $avatars = $data['avatars'];
            $this->migrateAvatarOrCover(
                $avatars,
                'Truonglv\Groups:Group\Avatar',
                $timer,
                'data://groups/avatars/%d/%d.jpg'
            );
            $data['avatars'] = $avatars;
        }
        if (count($data['covers']) > 0) {
            $covers = $data['covers'];
            $this->migrateAvatarOrCover(
                $covers,
                'Truonglv\Groups:Group\Cover',
                $timer,
                'data://groups/covers/%d/%d.jpg'
            );
            $data['covers'] = $covers;
        }

        $this->data = $data;

        return $this->resume();
    }

    /**
     * @return \XF\Phrase
     */
    public function getStatusMessage()
    {
        return XF::phrase('tlg_groups...');
    }

    /**
     * @param array $data
     * @param string $serviceName
     * @param Timer $timer
     * @param string $fileTemplate
     * @return void
     */
    protected function migrateAvatarOrCover(array & $data, $serviceName, Timer $timer, $fileTemplate)
    {
        foreach ($data as $index => $groupId) {
            unset($data[$index]);

            /** @var Group|null $group */
            $group = $this->app->em()->find('Truonglv\Groups:Group', $groupId);
            if ($group === null) {
                continue;
            }

            $abstractedPath = sprintf(
                $fileTemplate,
                floor($groupId / 1000),
                $groupId
            );
            $tempFile = File::copyAbstractedPathToTempFile($abstractedPath);

            /** @var AbstractFormUpload $service */
            $service = $this->app->service($serviceName, $group);
            $service->setUpload(new Upload($tempFile, basename($abstractedPath)));

            $errors = null;
            if (!$service->validate($errors)) {
                $this->logError('Failed to migrate group avatar/cover. $errors=' . json_encode($errors));

                continue;
            }

            try {
                $service->upload();
            } catch (Exception $e) {
                $this->logError($e);
            }

            if ($timer->limitExceeded()) {
                break;
            }
        }
    }

    /**
     * @param mixed $message
     * @return void
     */
    protected function logError($message)
    {
        $this->app->logException(
            ($message instanceof Exception) ? $message : new Exception($message),
            false,
            '[tl] Social Groups: '
        );
    }
}
