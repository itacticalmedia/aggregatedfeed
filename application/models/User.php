<?php

class Application_Model_User extends Application_Model_Base
{

    public $obj_rolevar;
    public $resourcesArrayObj;

    public function setId($a)
    {
        $this->_id = $a;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setFirstName($a)
    {
        $this->_firstName = $a;
        return $this;
    }

    public function getFirstName()
    {
        return Application_Model_Helpers_Common::uppercaseFirst($this->_firstName);
    }

    public function setLastName($a)
    {
        $this->_lastName = $a;
        return $this;
    }

    public function getLastName()
    {
        return Application_Model_Helpers_Common::uppercaseFirst($this->_lastName);
    }

    public function getFullName()
    {
        return $this->getFirstName()." ".$this->getLastName();
    }
    
    public function setEmail($a)
    {
        $this->_email = $a;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }
    
    public function setPassword($a)
    {
        $this->_password = $a;
        return $this;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setRoleID($a)
    {
        $this->_roleID = $a;
        return $this;
    }

    public function getRoleID()
    {
        return $this->_roleID;
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

    /**
     * Get User role object
     * @return Application_Model_Role | false
     */
    public function getRole()
    {

        if (isset($this->obj_rolevar))
        {
            return $this->obj_rolevar;
        }


        if ($this->getRoleID() > 0)
        {
            return $this->obj_rolevar = new Application_Model_Role($this->getRoleID(),$this);
        }
        else
        {
            return $this->obj_rolevar = FALSE;
        }
    }

    /**
     * Get all Resource of that user
     * @return FALSE|array array of Application_Model_Resource object
     */
    public function getResources()
    {
        if(isset($this->resourcesArrayObj))
        {
            return $this->resourcesArrayObj;
        }
        
        if($this->getRole())
        {
            return $this->resourcesArrayObj = $this->getRole()->getResources();
        }
        else
        {
            return $this->resourcesArrayObj = FALSE;
        }
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return boolean | Application_Model_User
     * 
     */
    public function authenticate($username, $password)
    {
        $empmp = new Application_Model_UserMapper();
        return $empmp->authentication($username, $password);      
    }
    
    
    public function save()
    {
        $m = new Application_Model_UserMapper();
        return $m->save($this);
    }
    public function delete()
    {
        $m = new Application_Model_UserMapper();
        return $m->delete($this);
    }
}
