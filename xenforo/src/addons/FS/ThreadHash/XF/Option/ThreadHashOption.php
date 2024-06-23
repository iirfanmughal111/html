<?php

namespace FS\ThreadHash\XF\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;


use function in_array,intval;

class ThreadHashOption extends AbstractOption
{
    static public function getCron(){
        return  \XF::app()->finder('XF:CronEntry')->where('entry_id','fs_threadHash_cron')->fetchOne();
    }
    
    static public function CronDayType(&$value, \XF\Entity\Option $option){

        $cron = self::getCron();       
        if (!$cron){
            $option->error(\XF::phrase('fs_threadHash_cron_not_found'));
            return false; 
        }
        
       $type = $value['type'];
       $type_value = [];
       foreach($value['day_type'] as $val){
            $type_value[] = intval($val); 
            
            if ( ($type  == 'dom' && (intval($val) < (-1) || intval($val)  > 59)) ||
                 ($type  == 'dow' && (intval($val) < (-1) || intval($val)  > 6))                
            ){
            $option->error(\XF::phrase('please_enter_valid_values'));

                return false; 

            }
        }

        //var_dump(time());

       $prev_rules = $cron->run_rules;
       $prev_rules['day_type'] = $type;
       $prev_rules[$type] = $type_value;
       $cron->run_rules = $prev_rules;
       $cron->save();
       return true;
    }
    static public function CronTime(&$value, \XF\Entity\Option $option){

        $cron = self::getCron();       
        if (!$cron){
            $option->error(\XF::phrase('fs_threadHash_cron_not_found'));

            return false; 
        }   

       $hours = [];
        foreach($value as $val){            
            if (intval($val) < (-1) || intval($val)  > 23){
            $option->error(\XF::phrase('please_enter_valid_values'));

                return false; 
            } 
            
            $hours[] = intval($val); 
        }
        $prev_rules = $cron->run_rules;
        $prev_rules['hours'] = $hours;
        $cron->run_rules = $prev_rules;
        $cron->save();
       
        return true;

     }
     static public function CronMins(&$value, \XF\Entity\Option $option){
        $cron = self::getCron();       
        if (!$cron){
            $option->error(\XF::phrase('fs_threadHash_cron_not_found'));
            return false; 
        }   

       $mins = [];
        foreach($value as $val){            
            if (intval($val) < (-1) || intval($val)  > 59){
             $option->error(\XF::phrase('please_enter_valid_values'));   
             return false; 
            } 
            
            $mins[] = intval($val); 
        } 
        $prev_rules = $cron->run_rules;          
        $prev_rules['minutes'] = $mins;
        $cron->run_rules = $prev_rules;

        $cron->save();
        return true;
        
     }
     static public function CronActive(&$value, \XF\Entity\Option $option){

        $cron = self::getCron();       
        if (!$cron){
            $option->error(\XF::phrase('fs_threadHash_cron_not_found'));
            return false; 
        }   
              
            if (intval($value) < 0 || intval($value)  > 1){
                $option->error(\XF::phrase('please_enter_valid_values'));
                return false;            
        }
        $prev_active = $cron->active;
        $cron->active = $value;
        $cron->save();
       
        return true;

     }
}