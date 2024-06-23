<?php

namespace ExtendMediaUpload\Pub\View;


class MediaView extends \XF\Mvc\View
{
	
       public function renderJson() {
           
        
        $html = $this->renderTemplate($this->templateName, $this->params);
       
       return [
            'html' => $this->renderer->getHtmlOutputStructure($html)
        ];
       
    }

}
    
