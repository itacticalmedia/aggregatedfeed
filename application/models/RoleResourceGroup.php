<?php

class Application_Model_RoleResourceGroup extends Application_Model_Base
{
    public function setId($a)
    {
        $this->_id = $a;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setRoleId($a)
    {
        $this->_roleId = $a;
        return $this;
    }

    public function getRoleId()
    {
        return $this->_roleId;
    }

    public function setResourceGroupId($a)
    {
        $this->_resGroupId = $a;
        return $this;
    }

    public function getResourceGroupId()
    {
        return $this->_resGroupId;
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
    
    public function getResourcegroup()
    {
        if($this->getResourceGroupId() > 0)
        {
            return new Application_Model_Resourcegroup($this->getResourceGroupId());
        }
        return false;
    }
}