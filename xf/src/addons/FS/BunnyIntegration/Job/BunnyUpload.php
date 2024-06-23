<?php

namespace FS\BunnyIntegration\Job;

use XF\Job\AbstractJob;

class BunnyUpload extends AbstractJob
{
    protected $defaultData = [];

    public function run($maxRunTime)
    {
        $s = microtime(true);

        $app = \xf::app();

        $threadId = $this->data['threadId'];

        $thread = $app->em()->find('XF:Thread', $threadId);


        if (!empty($thread)) {

            $threadAttachments = $app->finder('XF:Attachment')
                ->where('content_id', $thread->FirstPost["post_id"])
                ->where('bunny_vid_id', '!=', null)
                ->where('is_bunny', '=', 0)
                ->fetch();

            if (!empty($threadAttachments)) {

                $bunnyService = $app->service('FS\BunnyIntegration\XF:BunnyServ');

                foreach ($threadAttachments as $attachmentSingle) {

                    if ($attachmentSingle->isvideo()) {

                        $videoExtenion = $attachmentSingle->getExtension();

                        // $filePath = \XF::getRootDirectory() . "/data/" . $attachmentSingle->Data->getPublicUrlBunnyPath();
                        $filePath = "https://e-dewan.ams3.cdn.digitaloceanspaces.com" . "/data/" . $attachmentSingle->Data->getPublicUrlBunnyPath();

                        $binaryVideo = file_get_contents($filePath);

                        $uploadVideoRes = $bunnyService->uploadBunnyVideo($binaryVideo, $attachmentSingle['bunny_vid_id']);

                        if ($uploadVideoRes["success"] == true && $uploadVideoRes["statusCode"]  == 200) {

                            $oldMessage = $thread->FirstPost["message"];

                            $bunnyBBCode = "[fsbunny=" . \XF::options()->fs_bi_libraryId . "]" . $attachmentSingle['bunny_vid_id'] . "[/fsbunny]";

                            $attachmentId = '[ATTACH type="full"]' . $attachmentSingle->attachment_id . '[/ATTACH]';

                            if (strpos($oldMessage, $attachmentId) !== false) {

                                $newMessage = str_replace($attachmentId, $bunnyBBCode, $oldMessage);

                                $thread->FirstPost->fastUpdate('message', $newMessage);
                            }

                            $attachmentSingle->delete();
                        }
                    }
                }
            }

            return $this->complete();
        }

        return $this->resume();
    }

    public function getStatusMessage()
    {
    }

    public function canCancel()
    {
        return true;
    }

    public function canTriggerByChoice()
    {
        return true;
    }
}
