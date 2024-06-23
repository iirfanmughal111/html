<?php

namespace FS\ThreadChangeArticle\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Thread extends XFCP_Thread {

    public function actionindex(ParameterBag $params) {

        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());

        $parent = parent::actionindex($params);

        if ($thread->is_view_change) {
            
            if ($parent instanceof \XF\Mvc\Reply\View) {

                $parent->setTemplateName('thread_view');
            }
        }
        return $parent;
    }

    public function actionarticleView(ParameterBag $params) {



        if (!\xf::visitor()->canChangeThreadStyle()) {

            return $this->noPermission();
        }

        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());

        if ($this->isPost()) {



            $upload = $this->request->getFile('upload', false, false);

            if (!$thread->getimageExit() && !$upload) {

                throw $this->exception(
                                $this->notFound(\XF::phrase("fs_image_required"))
                );
            }






            $upload = $this->request->getFile('upload', false, false);

            if ($upload) {

                $extension = $upload->getExtension();

                if (!in_array($extension, ['jpg', 'png', 'svg', 'jpeg'])) {

                    throw $this->exception(
                                    $this->notFound(\XF::phrase("fs_image_format_required..."))
                    );
                }

                if ($upload->getImageWidth() < \xf::options()->headerImageDimensions['width'] || $upload->getImageHeight() < \xf::options()->headerImageDimensions['height']) {


                    throw $this->exception(
                                    $this->notFound(\XF::phrase("image_demension_required", ['width' => \xf::options()->headerImageDimensions['width'], 'height' => \xf::options()->headerImageDimensions['height']]))
                    );
                }

                $uploadService = $this->service('FS\ThreadChangeArticle:Upload', $thread);

                if (!$uploadService->setSvgFromUpload($upload)) {

                    return $this->error($uploadService->getError());
                }



                if (!$uploadService->uploadSvg()) {

                    return $this->error(\XF::phrase('Image Cannot be processed'));
                }
            }

            $thread->is_view_change = 1;
            $thread->save();

            return $this->redirect($this->getDynamicRedirect());
        } else {


            $viewParams = [
                'thread' => $thread,
            ];

            return $this->view('FS\ThreadChangeArticle:Thread', 'fs_thread_header_image', $viewParams);
        }
    }

    public function actionnormalView(ParameterBag $params) {

        if (!\xf::visitor()->canChangeThreadStyle()) {

            return $this->noPermission();
        }

        $thread = $this->assertViewableThread($params->thread_id, $this->getThreadViewExtraWith());
        if ($this->isPost()) {



            $thread->is_view_change = 0;

            $thread->save();

            return $this->redirect($this->getDynamicRedirect());
        }

        $viewParams = [
            'thread' => $thread,
        ];

        return $this->view('FS\ThreadChangeArticle:Thread', 'fs_change_thread_normal', $viewParams);
    }
}
