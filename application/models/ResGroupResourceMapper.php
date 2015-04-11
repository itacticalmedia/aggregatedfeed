<?php

class Application_Model_ResGroupResourceMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string 
     */
    protected $_name = 'ResGroupResources';
    
    /**
     * 
     * @param Application_Model_Role $rl
     * @return boolean|\Application_Model_Resource[]
     */
    public function loadResourcesByResGroup(Application_Model_Resourcegroup $rl)
    {
        $sel = $this->getDbTable()->select()
                ->where("resGroupId = ? ",$rl->getId());
        
       
        $rows = $this->getDbTable()->fetchAll($sel);
        
        if (0 == count($rows))
        {
            return FALSE;
        }
        
        foreach ($rows as $r)
        {
            $rmg = new Application_Model_Resource($r['resourceId'], $this);            
            $rta[] = $rmg;
        }
        
        return $rta;
    }
    
    public function save(Application_Model_Resourcegroup $r, Application_Model_Resource $re)
    {
        $data = array(
            'resGroupId' => $r->getId()
            ,'resourceId' => $re->getId()
            ,'createdOn' => new Zend_Db_Expr('NOW()')
        );

        if ($r->getId() > 0 && $re->getId()>0)
        {
           
            $this->getDbTable()->insert($data);
        }
    }
    
    public function deleteByResGroup(Application_Model_Resourcegroup $r)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('resGroupId = ?', $r->getId());
        return $table->delete($where);

    }
    
}
