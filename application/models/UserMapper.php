<?php

class Application_Model_UserMapper extends Application_Model_MapperBase
{
    /**
     * Set table Name
     * @var string
     * 
     */
    protected $_name = 'users';
    
    public function loadAll($page=FALSE, $limit = Application_Model_Helpers_Common::MAX_RECORDS_PER_PAGE, $orderBy =array(),$search=array())
    {
        $select = $this->getDbTable()->select();
        
        if(count($search)>0)
        {
            foreach ($search as $searchAr)
            {
                $op = ' = ?';
                if(isset($searchAr['op']) && $searchAr['op']!='')
                {
                    $op = $searchAr['op'];
                }
                $select->orWhere($searchAr['col'].' ' .$op.' ', $searchAr['value']);
            }
            
        }
        if(count($orderBy)>0)
        {
            $select->order($orderBy['col']." ".$orderBy['type']);
        }
        $res = $this->paginator($select, $page, $limit);
        return $res;
    }
    
    public function authentication($username, $password)
    {       
        $table = $this->getDbTable();
        $q = $table->select()
                        ->where('email = ?', $username)
                        ->where('password = ?', $password);
        $row = $table->fetchRow($q);

        if (!$row)
        {
            return FALSE;
        }
        $rmg = new Application_Model_User(0);
        $this->initObject($row, $rmg,$this);
        return $rmg;
    }
    
    public function save(Application_Model_User $emp)
    {
        $data = array(
            'firstName' => $emp->getFirstName(),
            'lastName' => $emp->getLastName(),
            'email' => $emp->getEmail(),
            'password' => $emp->getPassword(),
            'roleId' => $emp->getRoleID()
        );

        if (null === ($id = $emp->getId()))
        {
            $data['created']= new Zend_Db_Expr('NOW()'); 
            $data['modified']= new Zend_Db_Expr('NOW()');
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
            $data['modified']= new Zend_Db_Expr('NOW()');
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return TRUE;
        }
    }
    public function delete(Application_Model_User $emp)
    {
        $table = $this->getDbTable();
        $where = $table->getAdapter()->quoteInto('id = ?', $emp->getId());
        return $table->delete($where);

    }
    
    public function getUserById($id)
    {
        $table = $this->getDbTable();
        $row = $table->fetchRow(
                $table->select()
                        ->where('id = ?', $id)
        );

        if (!$row)
        {
            return FALSE;
        }
        $rmg = new Application_Model_User(0);
        $this->initObject($row, $rmg);
        return $rmg;
    }
    
   
}
