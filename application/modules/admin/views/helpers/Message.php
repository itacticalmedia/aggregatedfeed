<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_Views_Helpers_Message extends Zend_View_Helper_Abstract
{
    public function message()
    {    
        $str = '';
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        if ($messages->hasMessages())
        {
            $msgArr =$messages->getMessages(); 
            
            if(isset( $msgArr[0]['error']))
            {
                $str = '';
                $str = '  <div  class="ui-widget">
                            <div style="padding: 0 .7em;" class="ui-state-error ui-corner-all">';
                            foreach ($msgArr[0]['error'] as $error)
                            {
                                 $str.= ' <p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-alert"></span>'.$error.'</p>';
                            }
                $str.='                        
                            </div>
                        </div>';
                
            }else if($msgArr[0]['success'])
            {
                $str = '';
                $str = '  <div  class="ui-widget">
                            <div style="padding: 0 .7em;" class="ui-state-highlight ui-corner-all">';
                            foreach ($msgArr[0]['success'] as $secc)
                            {
                                 $str.= ' <p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-alert"></span>'.$secc.'</p>';
                            }
                $str.='                        
                            </div>
                        </div>';
            }
        }
        
        return $str;
    }
}