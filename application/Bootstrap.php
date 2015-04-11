<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $request;
    protected $moduleName;
    protected $controllerName;
    protected $actionName;

    /**
     * initialize html doc type
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        $view->headTitle()->setSeparator(' - ')->append('Aggregated Feed');
    }

    protected function _initSetMVCName()
    {
        $this->bootstrap("frontController");
        $this->request = $request = new Zend_Controller_Request_Http();
        $router = new Zend_Controller_Router_Rewrite();
        $router->route($request);


        $this->moduleName = $request->getModuleName();
        $this->controllerName = $request->getControllerName();
        $this->actionName = $request->getActionName();
    }

    protected function _initSetConstants()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $baseUrl = $config->baseHttp;
        define('BASE_URL', $baseUrl);
        $basePath = $config->basePath;
        define('BASE_URL_ADMIN', $baseUrl."/admin");
        define('BASE_PATH', $basePath);
        define('FILE_PATH', $basePath."/files/");
        
        $storeName = $config->storeName;
        define('STORE_NAME', $storeName);

        $emails = $config->emaildata->toArray();
        
        foreach ($emails as $emailskey => $emailsvalue)
        {
           Zend_Registry::set('email_' . $emailskey, $emailsvalue);
        }
        
    }
    
}
