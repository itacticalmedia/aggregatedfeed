<?php

class Plugin_ModuleLayout extends Zend_Layout_Controller_Plugin_Layout
{
    private $_moduleName;
   
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_moduleName = $request->getModuleName(); 
        $this->_change();
    }
 
    private function _change()
    {
        $this->getLayout()->setLayoutPath(APPLICATION_PATH.'/modules/'. $this->_moduleName . '/layouts/scripts');  
        
        /*if(in_array($this->_moduleName, $this->navEnabledModules))
        {
            $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', $this->_moduleName);
            $nav = new Zend_Navigation($config);
            $regNav = $this->getLayout()->getView()->navigation($nav);
            Zend_Registry::set('regNav', $regNav);
        }*/
    }
}