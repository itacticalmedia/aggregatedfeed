<?php
/**
 * Inject class in the one which should be extended 
 * by all controllers in pacdoc
 */
class Plugin_Inject extends Zend_Controller_Action
{
    /**
     * holds the action name
     * @var string 
     */
    public $actionName;
    
     /**
     * holds the controller name
     * @var string 
     */
    public $controllerName;
    
     /**
     * holds the module name
     * @var string 
     */
    public $moduleName;


    function init()
    {
       
        $req = $this->getRequest();
        
        $this->request =$this->view->request= $req;
        $this->actionName = $req->getActionName();        
        $this->controllerName = $req->getControllerName();        
        $this->moduleName = $req->getModuleName();
        
        $this->view->actionName = $this->actionName;
        $this->view->moduleName = $this->moduleName;
        $this->view->controllerName = $this->controllerName;
        
    }
    
    /**
     * This function redirect to the particular page
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     */
    public function gotoPage($action, $controller = null, $module = null, array $params = array())
    {        
        $this->_helper->redirector->gotoSimple($action, $controller, $module, $params);
    }
}