<?php

namespace FS\ThreadThumbnail\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread {


    public function actionThumbnail(ParameterBag $params) {


        if (!\xf::visitor()->canChangeThreadThumbnail()) {

            return $this->noPermission();
        }

        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());

        if ($this->isPost()) {

            $upload = $this->request->getFile('upload', false, false);
            $title = $this->filter('thumbnail_title', 'str');
           
            if (!$title || $title == " " ){
                throw $this->exception(
                    $this->notFound(\XF::phrase("fs_thumbnail_title_required"))
                );
            }
            

            if (!$thread->getThumbnailExit() && !$upload) {

                throw $this->exception(
                                $this->notFound(\XF::phrase("fs_thumbnail_image_required"))
                );
            }

           

            if ($upload) {

                $extension = $upload->getExtension();

                if (!in_array($extension, ['jpg', 'png', 'svg', 'jpeg'])) {

                    throw $this->exception(
                                    $this->notFound(\XF::phrase("fs_thread_image_format_required"))
                    );
                }

                if ($upload->getImageWidth() < \xf::options()->thumbnailImageDimensions['width'] || $upload->getImageHeight() < \xf::options()->thumbnailImageDimensions['height']) {

                    throw $this->exception(
                                    $this->notFound(\XF::phrase("fs_thumbnail_image_demension_required", ['width' => \xf::options()->thumbnailImageDimensions['width'], 'height' => \xf::options()->thumbnailImageDimensions['height']]))
                    );
                }

                $uploadService = $this->service('FS\ThreadThumbnail:Upload', $thread);

                if (!$uploadService->setSvgFromUpload($upload)) {

                    return $this->error($uploadService->getError());
                }



                if (!$uploadService->uploadSvg()) {

                    return $this->error(\XF::phrase('fs_thread_image_process_error'));
                }
            }

            $thread->thumbnail_title =  $title ;
            $thread->save();

            return $this->redirect($this->getDynamicRedirect());
        } else {


            $viewParams = [
                'thread' => $thread,
            ];

            return $this->view('FS\ThreadThumbnail:Thread', 'fs_thread_thumbnail', $viewParams);
        }
    }

 
}