<?php

namespace FS\BunnyIntegration\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

require __DIR__ . '/../../../vendor/autoload.php';
use Aws\S3\S3Client;


class Post extends XFCP_Post
{
    public function actionEdit(ParameterBag $params)
    {

        
        $s3 = new S3Client([
            
            'credentials' => [
                'key' => 'KJRCDY7LL4EWSUOPIE5J',
                'secret' => 'EpW5Heur+vORd9NhyQRLygiCWRl4ZQIP6c2l3hF78Cw'
            ],
            'region' => 'ams3',
            'version' => 'latest',
            'endpoint' => 'https://ams3.digitaloceanspaces.com',
        ]);
        

        $objectsListResponse = $s3->listObjects(['Bucket' => "e-dewan"]);
        $objects = $objectsListResponse['Contents'] ?? [];
        echo '<pre>';
        foreach ($objects as $object) {
            echo $object['Key'] . "\t" . $object['Size'] . "\t" . $object['LastModified'] . "\n";
        }

        //$s3->deleteObject(['Bucket' => 'e-dewan', 'Key' => 'data/BunnyIntegration/testbunmp4']);

        var_dump("test");exit;

        
        $post = $this->assertViewablePost($params->post_id, ['Thread.Prefix']);

        if ($this->isPost() && $post->isFirstPost()) {
            $visitor = \XF::visitor();
            $app = \XF::app();

            $jopParams = [
                'threadId' => $post->Thread->thread_id,
            ];

            $jobID = $visitor->user_id . '_bunnyVideo_' . time();

            $app->jobManager()->enqueueUnique($jobID, 'FS\BunnyIntegration:BunnyUpload', $jopParams, false);
        }

        return parent::actionEdit($params);
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