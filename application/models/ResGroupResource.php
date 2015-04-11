<?php

class Application_Model_ResGroupResource extends Application_Model_Base
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

    public function setResGroupId($a)
    {
        $this->_resGroupId = $a;
        return $this;
    }

    public function getResGroupId()
    {
        return $this->__resGroupId;
    }

    public function setResourceId($a)
    {
        $this->_resourceId = $a;
        return $this;
    }

    public function getResourceId()
    {
        return $this->_resourceId;
    }

    public function setModifiedOn($a)
    {
        $this->_modifiedOn = $a;
        return $this;
    }    
    public function getModifiedOn()
    {
       return $this->_modified;
    }
    
    public function setCreatedOn($a)
    {
        $this->_createdOn = $a;
        return $this;
    }
    public function getCreatedOn()
    {
       return $this->_createdOn;
    }
    
    public function getResource()
    {
        if($this->getResourceId() > 0)
        {
            return new Application_Model_Resource($this->getResourceId());
        }
        
        return false;
    }
}