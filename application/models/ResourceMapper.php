<?php

class Application_Model_ResourceMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string 
     */
    protected $_name = 'Resources';
    
    /**
     * 
     * @param Application_Model_Resource $rs
     * @return int
     */
    function save(Application_Model_Resource $rs)
    {
        $module = $rs->getModule();
        $cont = $rs->getController();
        $act = $rs->getAction();
        
        $data = array(
          'module' => $module,
          'controller' => $cont,
          'action'  => $act,
          'modified' => new Zend_Db_Expr('now()'),
          'created' => new Zend_Db_Expr('now()')
        );
        
        return $this->getDbTable()->insert($data);
    }
    
    /**
     * 
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function resourceExists($module = null, $controller = null, $action = null)
    {
        $table = $this->getDbTable();
       
        $sel = $table->select()
                        ->from($table, array("count(1) as cnt"))
                        ->where("module = ?", $module)
                        ->where("controller = ?", $controller)
                        ->where("action = ?", $action);
        
        $res = $table->fetchRow($sel);
        
        if($res['cnt'] > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    
    /**
     * 
     * @return boolean|array
     */
    public function getAllResourcesArray()
    {       
        $table = $this->getDbTable();
        
        $rows =  $table->fetchAll( $table->select()
                 ->distinct()
                 ->from($table, array("module", "controller"))
                 );
        
        
        if(!$rows)
        {
            return FALSE;
        }
        
        return $rows;
    }
    
    
    
}
