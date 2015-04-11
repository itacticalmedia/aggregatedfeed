<?php

class Application_Model_RoleMapper extends Application_Model_MapperBase
{

    /**
     * Set table Name
     * @var string 
     */
    protected $_name = 'Roles';

    /**
     * 
     * @param Application_Model_Role $roles
     * @return boolean|int
     */
    public function save(Application_Model_Role $roles)
    {
        $data = array(
            'name' => $roles->getName(),
            'default' => $roles->getDefault()
        );

        if (null === ($id = $roles->getId()))
        {
            $data['created']= new Zend_Db_Expr('NOW()'); 
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        }
        else
        {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return TRUE;
        }
    }
    public function delete(Application_Model_Role $roles)
    {
        $data = array(
            'status' => 'D'
        );

        if (null === ($id = $roles->getId()) || $id == 0)
        {
            throw new Exception("Invalid Id");
        }
        else
        {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return TRUE;
        }
    }

}
