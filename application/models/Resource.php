<?php

class Application_Model_Resource extends Application_Model_Base
{

    const ALW_ADD_ORD_ALL_FEE_AD= 'alw_add_ord_all_fee_ad';
    const ALW_ORD_ALL_FEE_AD_ED= 'alw_ord_all_fee_ad_ed';
    const ALW_DIV_SIG_FEE_AD_ED= 'alw_div_sig_fee_ad_ed';
    const ALW_DIV_ALL_FEE_AD_ED= 'alw_div_all_fee_ad_ed';
    const ALW_ORD_ACC_ED= 'alw_ord_acc_up';

    public function setId($a)
    {
        $this->_id = $a;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setModule($a)
    {
        $this->_module = $a;
        return $this;
    }

    public function getModule()
    {
        return $this->_module;
    }

    public function setController($a)
    {
        $this->_controller = $a;
        return $this;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function setAction($a)
    {
        $this->_action = $a;
        return $this;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setName($a)
    {
        $this->_name = $a;
        return $this;
    }

    public function getName()
    {
        if($this->_name == '')
        {
            $this->setName($this->getDefaultName());
        }
        return $this->_name;
    }

    private function getDefaultName()
    {
        return $this->getModule()."::".$this->getController()."::".$this->getAction();
    }
    public function setRouteName($a)
    {
        $this->_routeName = $a;
        return $this;
    }

    public function getRouteName()
    {
        return $this->_routeName;
    }

    public function setModified($a)
    {
        $this->_modified = $a;
        return $this;
    }

    public function getModified()
    {
        return $this->_modified;
    }

    public function setCreated($a)
    {
        $this->_created = $a;
        return $this;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    function save()
    {
        $mp = new Application_Model_ResourceMapper();
        $mp->save($this);
    }

    /**
     * 
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return int
     * @throws Exception
     */
    public function createResource($module = null, $controller = null, $action = null)
    {
        if (!$module || !$controller || !$action)
        {
            throw new Exception("Error resourceExists(), the module/controller/action is empty");
        }

        $this->setModule($module);
        $this->setController($controller);
        $this->setAction($action);

        return $this->save();
    }

    /**
     * 
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return bool
     * @throws Exception
     */
    public function resourceExists($module = null, $controller = null, $action = null)
    {
        if (!$module || !$controller || !$action)
        {
            throw new Exception("Error resourceExists(), the controller/action is empty");
        }

        $mp = new Application_Model_ResourceMapper();
        return $mp->resourceExists($module, $controller, $action);
    }

    /**
     * 
     * @return boolean|array
     */
    public function getAllResourcesArray()
    {
        $mp = new Application_Model_ResourceMapper();
        return $mp->getAllResourcesArray();
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     * @return boolean
     */
    public static function resourceValid(Zend_Controller_Request_Abstract $request)
    {
        // Check if controller exists and is valid
        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();
        if (!$dispatcher->isDispatchable($request))
        {
            return false;
        }
        // Check if action exist and is valid
        $front = Zend_Controller_Front::getInstance();
        $dispatcher = $front->getDispatcher();
        $controllerClass = $dispatcher->getControllerClass($request);
        $controllerclassName = $dispatcher->loadClass($controllerClass);
        $actionName = $dispatcher->getActionMethod($request);
        $controllerObject = new ReflectionClass($controllerclassName);
        
        if (!$controllerObject->hasMethod($actionName))
        {
            return false;
        }
        return true;
    }

}