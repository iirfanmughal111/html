<?php

namespace FS\PGPEncryption\XF\Pub\View\Item;

use XF\Mvc\View;

class check extends View
{
	public function renderJson()
	{
	
          
            

//            var_dump($this->params['encrypt_message']);exit;

                        return [
                                'response' => $this->params['response'],
                                'message' => $this->params['encrypt_message']

                        ];

                
	}
}