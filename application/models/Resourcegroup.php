<?php

class Application_Model_Resourcegroup extends Application_Model_Base
{
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
    public function setName($a)
    {
        $this->_name = $a;
        return $this;
    }
    public function getName()
    {
       return $this->_name;
    }
   
    public function setModifiedOn($a)
    {
        $this->_modifiedOn = $a;
        return $this;
    } 
    
    public function getModifiedOn()
    {
       return $this->_modifiedOn;
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
    public function setStatus($a)
    {
        $this->_status = $a;
        return $this;
    }
    public function getStatus()
    {
       return $this->_status;
    }
    
  
    /**
     * Get all Resource of that role
     * @return boolean|\Application_Model_Resource[]
     */
    public function getResources()
    {
        if(isset($this->resourcesArrayObj))
        {
            return $this->resourcesArrayObj;
        }
        
        $m = new Application_Model_ResGroupResourceMapper();
        return $this->resourcesArrayObj = $m->loadResourcesByResGroup($this);
    }
    
    /**
     * 
     * @return boolean|int
     */
    public function save()
    {
        $m = new Application_Model_ResourcegroupMapper();
        return $m->save($this);
    }
    
    public function delete()
    {
        $m = new Application_Model_ResourcegroupMapper();
        return $m->delete($this);
    }
    
    public function deleteResource()
    {
        $m = new Application_Model_ResGroupResourceMapper();
        return $m->deleteByResGroup($this);
    }
}