<?php

namespace FS\Translator\Callback;

class Helper
{
    public static function getAltFlags()
    {
        $app = \XF::app();
        
        $optionAltFlags = $app->options()->fs_t_alternativeFlags;
        
        $alt_flags= [];
        
        foreach ($optionAltFlags as $countryCode => $altFlag)
        {
            if(!$altFlag)  continue;
            
            switch($countryCode) 
            {
                case 'us': $alt_flags['en'] = 'usa'; break;
                case 'ca': $alt_flags['en'] = 'canada'; break;
                case 'br': $alt_flags['pt'] = 'brazil'; break;
                case 'mx': $alt_flags['es'] = 'mexico'; break;
                case 'ar': $alt_flags['es'] = 'argentina'; break;
                case 'co': $alt_flags['es'] = 'colombia'; break;
                case 'qc': $alt_flags['fr'] = 'quebec'; break;
                default: break;
            }

        }  
        
        
//        JSON_UNESCAPED_UNICODE
        $alt_flags = json_encode($alt_flags);
        
//        echo 'ddddddd';exit;
        return $alt_flags;
    }




}