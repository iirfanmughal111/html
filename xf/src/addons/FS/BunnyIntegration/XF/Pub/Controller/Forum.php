<?php

namespace FS\BunnyIntegration\XF\Pub\Controller;

class Forum extends XFCP_Forum
{
    public function uploadVideo($sourceFile, $abstractPath)
    {
        try {
            \XF\Util\File::copyFileToAbstractedPath($sourceFile, $abstractPath);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function finalizeThreadCreate(\XF\Service\Thread\Creator $creator)
    {
        $thread = $creator->getThread();
        $visitor = \XF::visitor();
        $app = \XF::app();

        $jopParams = [
            'threadId' => $thread->thread_id,
        ];

        $jobID = $visitor->user_id . '_bunnyVideo_' . time();

        $app->jobManager()->enqueueUnique($jobID, 'FS\BunnyIntegration:BunnyUpload', $jopParams, false);
        //    $app->jobManager()->runUnique($jobID, 120);

        return parent::finalizeThreadCreate($creator);
    }
}
