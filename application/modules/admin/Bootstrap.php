<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{    
    protected function _initSession()
    {
        $sessionNameSpace = new Zend_Session_Namespace('SESS_admin');
        Zend_Registry::set('SESS_admin', $sessionNameSpace);
    }
    
    protected function _initViewRenderer()
    {
        $bootstrap = $this->getApplication(); 
        $layout = $bootstrap->getResource('layout');
        $view = $layout->getView();
        $view->addHelperPath(dirname(__FILE__).'/views/helpers/', 'Admin_Views_Helpers');
 
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }   
}


