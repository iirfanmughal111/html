<?php

namespace FS\UpdateUrl\XF\Service\Thread;

class Creator extends XFCP_Creator
{
    protected $url_string;

    public function setUrl_string($title)
    {
        $options = \XF::options();   
        $this->thread->url_string = substr($title,0,$options->fs_updateUrl_limit);
    }
}