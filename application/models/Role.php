<?php

class Application_Model_Role extends Application_Model_Base
{
    public $resourcesArrayObj;
    
    public $resourceGroupsArrayObj;
    
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
    public function setDefault($a)
    {
        $this->_default = $a;
        return $this;
    }
    public function getDefault()
    {
       return $this->_default;
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
        
        $resArry = array();
        $resGrps = $this->getResourceGroups();        
        
        if($resGrps)
        {
            foreach ($resGrps  as $resGrp)
            {
                $mp = new Application_Model_ResGroupResourceMapper();
                $resourcesArr = $mp->loadResourcesByResGroup($resGrp);
                
                if($resourcesArr)
                {
                    foreach($resourcesArr as $resourceArr)
                    {
                        array_push($resArry, $resourceArr);
                    }
                }
                
            }
        }
      
        return $this->resourcesArrayObj = (count($resArry) > 0)? $resArry : FALSE;        
    }
    
    
    /**
     * Get all Resource of that role
     * @return boolean|Application_Model_Resourcegroup[]
     */
    public function getResourceGroups()
    {
        if(isset($this->resourceGroupsArrayObj))
        {
            return $this->resourceGroupsArrayObj;
        }
        
        $m = new Application_Model_RoleResourceGroupMapper();
        return $this->resourceGroupsArrayObj = $m->loadResourceGroupsByRole($this);
    }
    /**
     * 
     * @return boolean|int
     */
    public function save()
    {
        $m = new Application_Model_RoleMapper();
        return $m->save($this);
    }
    public function delete()
    {
        $m = new Application_Model_RoleMapper();
        return $m->delete($this);
    }
    
    public function deleteResourceGroups()
    {
        $m = new Application_Model_RoleResourceGroupMapper();
        return $m->deleteByRole($this);
    }
}