<?php

class Application_Model_RoleResourceGroupMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string 
     */
    protected $_name = 'RoleResGroup';
    
    /**
     * 
     * @param Application_Model_Role $rl
     * @return boolean|Application_Model_Resourcegroup[]
     */
    public function loadResourceGroupsByRole(Application_Model_Role $rl)
    {
        $sel = $this->getDbTable()->select()
                ->where("roleId = ? ",$rl->getId());
        
       
        $rows = $this->getDbTable()->fetchAll($sel);
        
        if (0 == count($rows))
        {
            return FALSE;
        }
        
        foreach ($rows as $r)
        {
            $rmg = new Application_Model_Resourcegroup($r['resGroupId'], $this);            
            $rta[] = $rmg;
        }
        
        return $rta;
    }
    
    public function save(Application_Model_Role $r, Application_Model_Resourcegroup $re)
    {
        $data = array(
            'roleId' => $r->getId()
            ,'resGroupId' => $re->getId()
            ,'created' => new Zend_Db_Expr('NOW()')
        );

        if ($r->getId() > 0 && $re->getId()>0)
        {
           
            $this->getDbTable()->insert($data);
        }
    }
    
    public function deleteByRole(Application_Model_Role $r)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('roleId = ?', $r->getId());
        return $table->delete($where);

    }
    
}
