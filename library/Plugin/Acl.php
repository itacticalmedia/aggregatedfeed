<?php

/**
 * @author Arup Maji
 */
class Plugin_Acl extends Zend_Controller_Plugin_Abstract
{

    const ADMIN_ROLEID = 2;

    private $implementedModules = array('admin');

    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     * @return type
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

        $request = $this->getRequest();


        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        
        if (in_array($module, $this->implementedModules) && $controller != 'login' && $controller != 'error')
        {
       
            $aclResource = new Application_Model_Resource();

            if (!$aclResource->resourceValid($request))
            {
                //BLOCK , otherwise the error will not show on error page
                //$request->setModuleName('default');
                //$request->setControllerName('error');
                //$request->setActionName('error');
                return;
            }


            //Check if the requested resource exists in database. If not it will add it
            if (!$aclResource->resourceExists($module, $controller, $action))
            {
                $aclResource->createResource($module, $controller, $action);
            }

            $sessionNameSpace = Zend_Registry::get('SESS_admin');
          
            if (isset($sessionNameSpace->user) && ($sessionNameSpace->user instanceof Application_Model_User))
            {

                $emp = $sessionNameSpace->user;
                //Get role
                $roleObj = $emp->getRole();
                
                
                if(!($roleObj instanceof Application_Model_Role))
                {
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('accessdenied');
                    return;                     
                }
                
                $role = $roleObj->getName();
                
                

                // setup acl
                $acl = new Zend_Acl();

                // add the role
                $acl->addRole(new Zend_Acl_Role($role));

                if ($roleObj->getId() == self::ADMIN_ROLEID)
                {
                    //If role_id=3 "Admin" don't need to create the resources
                    $acl->add(new Zend_Acl_Resource($module . "." . $controller));
                    $acl->allow($role);
                } 
                else
                {
                    //Create all the existing resources
                    $resources = $aclResource->getAllResourcesArray();

                    // Add the existing resources to ACL
                    foreach ($resources as $resource)
                    {
                        $acl->add(new Zend_Acl_Resource($resource['module'] . "." . $resource['controller']));
                    }

                    //Create user AllowedResources
                    $userAllowedResourcesArr = array();

                    $userAllowedResourcesObj = $roleObj->getResources();
                    
                    if($userAllowedResourcesObj)
                    {
                        foreach ($userAllowedResourcesObj as $userAllowedResourceObj)
                        {
                            $res = $userAllowedResourceObj->getModule() . "." . $userAllowedResourceObj->getController();
                            $userAllowedResourcesArr[$res][] = $userAllowedResourceObj->getAction();
                        }
                    }

                    // Add the user permissions to ACL
                    $regNav = Zend_Registry::get('regNav');
                    foreach ($userAllowedResourcesArr as $resName => $allowedActions)
                    {
                        $arrayAllowedActions = array();

                        foreach ($allowedActions as $allowedAction)
                        {
                            $arrayAllowedActions[] = $allowedAction;
                        }

                        $acl->allow($role, $resName, $arrayAllowedActions);
                        
                    }
                        
                    $regNav->setAcl ( $acl ) -> setRole ( $role);
                   
                }
               
                Zend_Registry::set('siteAcl', $acl);
                
         
         
                
                //Check if user is allowed to acces the url and redirect if needed
                if (!$acl->isAllowed($role, $module . "." . $controller, $action))
                {
                    $request->setModuleName('default');
                    $request->setControllerName('error');
                    $request->setActionName('accessdenied');
                    return;
                }
                
            }
        }
    }

}