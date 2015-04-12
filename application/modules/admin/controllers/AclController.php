<?php

class Admin_AclController extends Plugin_Inject
{

    public function init()
    {
        parent::init();
        $this->sessionNameSpace  = $this->view->sessionNameSpace= Zend_Registry::get('SESS_admin');
        if (!isset($this->sessionNameSpace->user))
        {
            $this->sessionNameSpace->user_redirect = $this->getRequest()->getRequestUri();

            $this->gotoPage('index', 'login');
        }
    }

    /************* ROLE ADD/EDIT ******************/
    public function roleAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }
        $rm = new Application_Model_RoleMapper();
        $this->view->roles = $rm->fetchAllWithStatus(array("A", "I"));
       
    }

    public function roleaddAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }

        $rr = new Application_Model_ResourcegroupMapper();
        $this->view->res = $rr->fetchAllWithStatus(array("A", "I"));
        $this->view->roleResArray = array();
    }

    public function rolesaveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();
        if ($this->request->isPost())
        {
            $msg = array();
            if ($request["submit"])
            {
                if ($request["rname"] == "")
                {
                    $msg[] = "Please enter Role Name.";
                }
                if (count($msg) == 0)
                {

                    $rname = $request["rname"];
                    $id = $request["id"];
                    $lastRoleId = 0;
                    if ($id > 0)
                    {
                       $r = new Application_Model_Role($id);
                    } else
                    {
                       $r = new Application_Model_Role();
                       $r->setId(NULL);
                    }
                    $r->setName($rname);
                    $r->setDefault(1);
                    $res = $r->save();
                    if($id>0)
                    {
                        $lastRoleId = $id;
                    }else
                    {
                        $lastRoleId = $res;
                    }
                    
                    $savedRole = new Application_Model_Role($lastRoleId);
                    $savedRole->deleteResourceGroups();
                    
                    $rsMap = new Application_Model_RoleResourceGroupMapper();
                    
                    if(count($request['resources'])>0)
                    {
                        
                        foreach ($request['resources'] as $key => $resourceid)
                        {
                            $reso = new Application_Model_Resourcegroup($resourceid);
                            $rsMap->save($savedRole, $reso);
                        }
                    }
                    
                    $Succmsg[] = "Role \"$rname\" has been saved successfully.";
                    $this->_helper->flashMessenger->addMessage(array("success"=>$Succmsg));
                    $this->gotoPage('role', 'acl');
                }
                if (count($msg) > 0)
                {
                    $this->_helper->flashMessenger->addMessage(array("error"=>$msg));
                }
                $this->gotoPage('roleadd', 'acl');
            }
        }
    }

    public function roleeditAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }

        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_Role($id);
            $this->view->id = $r->getId();
            $this->view->roleName = $r->getName();
            
            
            $rr = new Application_Model_ResourcegroupMapper();
            $this->view->res = $rr->fetchAllWithStatus(array("A", "I"));

            $roleRess = $r->getResourceGroups();
            $roleResArray = array();
            if ($roleRess)
            {
                foreach ($roleRess as $roleRes)
                {
                    $roleResArray[] = $roleRes->getId();
                }
            }
            $this->view->roleResArray = $roleResArray;
            $this->render('roleadd');
        } else
        {
            throw new Exception("Invalid request");
        }
    }

    public function roledeleteAction()
    {
        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_Role($id);
            $rname = $r->getName();
            $r->deleteResourceGroups();
            $r->delete();

            $Succmsg[] = "Role \"$rname\" delete successfully.";
            $this->_helper->flashMessenger->addMessage(array("success"=>$Succmsg));
            $this->gotoPage('role', 'acl');
        } 
        else
        {
            throw new Exception("Invalid request");
        }
    }
    
    
    
    
    /************* ROLE ADD/EDIT ******************/
    public function resourcegroupAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }
        
        $rm = new Application_Model_ResourcegroupMapper();
        $this->view->resgrp = $rm->fetchAllWithStatus(array("A", "I"));
       
    }

    public function resourcegroupaddAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }

        $rr = new Application_Model_ResourceMapper();
        $this->view->res = $rr->fetchAll();
        $this->view->reGrpResArray = array();
    }

    public function resourcegroupsaveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();
        if ($this->request->isPost())
        {
            $msg = array();
            if ($request["submit"])
            {
                if ($request["rname"] == "")
                {
                    $msg[] = "Please enter Role Name.";
                }
                if (count($msg) == 0)
                {

                    $rname = $request["rname"];
                    $id = $request["id"];
                    $ResGrpId = 0;
                    
                    
                    if ($id > 0)
                    {
                       $r = new Application_Model_Resourcegroup($id);
                    } else
                    {
                       $r = new Application_Model_Resourcegroup();
                       $r->setId(NULL);
                    }
                    
                    $r->setName($rname);                    
                    $res = $r->save();
                    
                    if($id > 0)
                    {
                        $ResGrpId = $id;
                    }
                    else
                    {
                        $ResGrpId = $res;
                    }
                    
                    $savedResGrp = new Application_Model_Resourcegroup($ResGrpId);
                    $savedResGrp->deleteResource();
                    
                    $rsGrpresMap = new Application_Model_ResGroupResourceMapper();
                    
                    if(count($request['resources']) > 0)
                    {
                        
                        foreach ($request['resources'] as $resourceid)
                        {
                            $reso = new Application_Model_Resource($resourceid);
                            $rsGrpresMap->save($savedResGrp, $reso);
                        }
                    }
                    
                    $Succmsg[] = "Resource Group \"$rname\" has been saved successfully.";
                    $this->_helper->flashMessenger->addMessage(array("success"=>$Succmsg));
                    $this->gotoPage('resourcegroup', 'acl');
                }
                
                if (count($msg) > 0)
                {
                    $this->_helper->flashMessenger->addMessage(array("error"=>$msg));
                }
                
                $this->gotoPage('resourcegroupadd', 'acl');
            }
        }
    }

    public function resourcegroupeditAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }

        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_Resourcegroup($id);
            $this->view->id = $r->getId();
            $this->view->roleName = $r->getName();
            
            
            $rr = new Application_Model_ResourceMapper();
            $this->view->res = $rr->fetchAll();

            $resGrpRess = $r->getResources();
            $reGrpResArray = array();
            if ($resGrpRess)
            {
                foreach ($resGrpRess as $resGrpRes)
                {
                    $reGrpResArray[] = $resGrpRes->getId();
                }
            }
            
            $this->view->reGrpResArray = $reGrpResArray;
            $this->render('resourcegroupadd');
            
        } 
        else
        {
            throw new Exception("Invalid request");
        }
    }

    public function resourcegroupdeleteAction()
    {
        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_Resourcegroup($id);
            $rname = $r->getName();
            $r->deleteResource();
            $r->delete();

            $Succmsg[] = "Resource Group \"$rname\" delete successfully.";
            $this->_helper->flashMessenger->addMessage(array("success"=>$Succmsg));
            $this->gotoPage('resourcegroup', 'acl');
        } else
        {
            throw new Exception("Invalid request");
        }
    }
    
     /************* user ADD/EDIT ******************/
    public function userAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }
        $em = new Application_Model_UserMapper();
        $this->view->users = $em->fetchAll();
       
    }

    public function useraddAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }

        $r= new Application_Model_RoleMapper();
        $this->view->roles = $r->fetchAllWithStatus('A');
    }

    public function usersaveAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $request = $this->request->getParams();
        if ($this->request->isPost())
        {
            $msg = array();
            if ($request["submit"])
            {
                $id = $request["id"];
                $fname = $request["fname"];
                $lname = $request["lname"];
                $email = $request["email"];
                $password = $request["password"];
                $roleId = $request["roleId"];
                if ($fname == "")
                {
                    $msg[] = "Please enter First Name.";
                }
                if ($lname == "")
                {
                    $msg[] = "Please enter Last Name.";
                }
                if ($email == "")
                {
                    $msg[] = "Please enter Email.";
                }
                if ($id == 0 && $password == "")
                {
                    $msg[] = "Please enter Password.";
                }
                if ($roleId == "" || $roleId == 0)
                {
                    $msg[] = "Please select role.";
                }
                
                if (count($msg) == 0)
                {
                    
                    if ($id > 0)
                    {
                       $r = new Application_Model_User($id);
                    } else
                    {
                       $r = new Application_Model_User();
                       $r->setId(NULL);
                    }
                    $r->setFirstName($fname);
                    $r->setLastName($lname);
                    $r->setEmail($email);
                    if ($id == 0)
                    {
                        $r->setPassword(Application_Model_Helpers_Common::encryptPassword($password));
                    }
                    $r->setRoleID($roleId);
                    $r->save();
                   
                    $Succmsg[] = "User \"$fname $lname\" has been saved successfully.";
                    $this->_helper->flashMessenger->addMessage(array("success"=>$Succmsg));
                    $this->gotoPage('user', 'acl');
                }
                if (count($msg) > 0)
                {
                    $this->_helper->flashMessenger->addMessage(array("error"=>$msg));
                }
                $this->gotoPage('user', 'acl');
            }
        }
    }

    public function usereditAction()
    {
        if ($this->_helper->flashMessenger->hasMessages())
        {
            $msgArr = $this->_helper->flashMessenger->getMessages();
            if (count($msgArr[0]) > 0)
            {
                $this->view->errors = $msgArr[0];
            }
        }

        $r= new Application_Model_RoleMapper();
        $this->view->roles = $r->fetchAllWithStatus('A');
        
        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $r = new Application_Model_User($id);
            $this->view->id = $r->getId();
            $this->view->fname = $r->getFirstName();
            $this->view->lname = $r->getLastName();
            $this->view->email = $r->getEmail();
            $this->view->roleId = $r->getRoleID();;

            $this->render('useradd');
        } else
        {
            throw new Exception("Invalid request");
        }
    }

    public function userdeleteAction()
    {
        $id = $this->request->getParam('id');
        if ($id > 0)
        {
            $e = new Application_Model_User($id);
            $rname = $e->getFullName();
            $e->delete();

            $Succmsg[] = "User \"$rname\" delete successfully.";
            $this->_helper->flashMessenger->addMessage(array("success"=>$Succmsg));
           
            $this->gotoPage('user', 'acl');
        } else
        {
            throw new Exception("Invalid request");
        }
    }

}
